<?php
namespace Tigren\CompanyAccount\Model;

use \Tigren\CompanyAccount\Helper\OAuth2\Client;
use \Tigren\CompanyAccount\Model\Config\ApiScopeInterface;
class ApiConnection
{
    protected $_helperApi;
    protected $_helper;
    protected $collectionFactory;
    protected $accountFactory;

    private $apiClient;

    public function __construct(
        \Tigren\CompanyAccount\Helper\DataApi $helperApi,
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Framework\Data\CollectionFactory $collectionFactory,
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory
    ){
        $this->_helperApi = $helperApi;
        $this->_helper = $helper;
        $this->collectionFactory = $collectionFactory;
        $this->accountFactory = $accountFactory;
    }


    public function getAccessToken()
    {
        if(!$this->_helperApi->isAllow()){
            return false;
        }

        if(empty($this->apiClient)){
            $client = new Client(
                $this->_helperApi->getClientId(),
                $this->_helperApi->getClientSecret()
            );
            $token = $client->getAccessToken(
                ApiScopeInterface::API_TOKEN_ENDPOINT,
                ApiScopeInterface::API_GRANT_TYPE,
                ['scope' => 'all']
            );

            if(!isset($token['result']['access_token'])){
                return false;
            }

            $client->setAccessTokenType(Client::ACCESS_TOKEN_BEARER);
            $client->setAccessToken($token['result']['access_token']);
            $this->apiClient = $client;
        }
        
        return $this->apiClient;
    }


    /**
     * Badly named, more like an account sync (updating the managers) as far as I can tell from optimising the code
     * @return bool
     */
    public function getAccountDetail()
    {
        if(!$this->_helper->isLoggedIn() || !$this->_helperApi->isAllow()){
            return false;
        }

        $customerId = $this->_helper->getCustomerId();
        $accountId = $this->_helper->isInAvailableAccount($customerId);

        if(!$accountId){
            return false;
        }

        $client = $this->getAccessToken();
        $response = $client->fetch(
            ApiScopeInterface::API_URL_ACCOUNT . $accountId,
            [],
            Client::HTTP_METHOD_GET,
            ['Accept' => ApiScopeInterface::API_ACCEPT_HEADER]
        );

        if($response['code'] != 200 || empty($response['result']) || !is_set($response['result']['PrimaryManager']) || !is_set($response['result']['SecondaryManager'])){
            return [];
        }

        $data = $this->accountFactory->create();
        $data->load($accountId);
        $data->setPrimaryManager(implode('&', $response['result']['PrimaryManager']));
        $data->setSecondaryManager(implode('&', $response['result']['SecondaryManager']));
        $data->save();

        return true;
    }

    public function getListOrder($skip)
    {
        if(!$this->_helper->isLoggedIn() || !$this->_helper->isAllow()){
            return [];
        }

        $client = $this->getAccessToken();
        $response = $client->fetch(
            ApiScopeInterface::API_URL_SALE_ORDERS . '?&$orderby=Date%20desc&$skip='. $skip,
            [],
            Client::HTTP_METHOD_GET,
            ['Accept' => ApiScopeInterface::API_ACCEPT_HEADER]
        );

        if($response['code'] != 200 || empty($response['result'])){
            return [];
        }
        return $response['result'];
    }

    public function getOrderDetail($orderId)
    {
        if(!$this->_helper->isLoggedIn() || !$this->_helper->isAllow()){
            return [];
        }

        $client = $this->getAccessToken();
        $response = $client->fetch(
            ApiScopeInterface::API_URL_SALE_ORDERS . '/'. $orderId,
            [],
            Client::HTTP_METHOD_GET,
            ['Accept' => ApiScopeInterface::API_ACCEPT_HEADER]
        );

        if($response['code'] != 200 || empty($response['result'])){
            return [];
        }
        return $response['result'];
    }
}