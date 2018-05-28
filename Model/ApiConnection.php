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
        $client = '';
        if($this->_helperApi->isAllow()){
            $params = array('scope' => 'all');
            $client = new Client($this->_helperApi->getClientId(), $this->_helperApi->getClientSecret());
            $token = $client->getAccessToken(ApiScopeInterface::API_TOKEN_ENDPOINT, ApiScopeInterface::API_GRANT_TYPE, $params);
            if(isset($token['result']['access_token'])) {
                $client->setAccessTokenType(Client::ACCESS_TOKEN_BEARER);
                $client->setAccessToken($token['result']['access_token']);
            } else {
                return false;
            }
        }
        return $client;
    }

    public function getAccountDetail()
    {
        $customerId = $this->_helper->getCustomerId();
        $accountId = $this->_helper->isInAvailableAccount($customerId);
        //$accountId = 536887;
        if($accountId && $this->getAccessToken()){
            $requestUrl     = ApiScopeInterface::API_URL_ACCOUNT . $accountId;
            $parameters     = array();
            $http_method    = Client::HTTP_METHOD_GET;
            $http_headers   = array('Accept' => ApiScopeInterface::API_ACCEPT_HEADER);
            $request = $this->getAccessToken();
            $response = $request->fetch($requestUrl, $parameters, $http_method, $http_headers);
            $response = $response['result'];

            if(!empty($response)){
                $data = $this->accountFactory->create();
                $data->load($accountId);
                $data->setPrimaryManager(implode('&',$response['PrimaryManager']));
                $data->setSecondaryManager(implode('&',$response['SecondaryManager']));
                $data->save();
            }
        }

        return true;
    }
    public function getListOrder($skip)
    {
        $response = '';
        if($this->getAccessToken()){
            $requestUrl     = ApiScopeInterface::API_URL_SALE_ORDERS . '?&$orderby=Date%20desc&$skip='. $skip;
            $parameters     = array();
            $http_method    = Client::HTTP_METHOD_GET;
            $http_headers   = array('Accept' => ApiScopeInterface::API_ACCEPT_HEADER);
            $request = $this->getAccessToken();
            $response = $request->fetch($requestUrl, $parameters, $http_method, $http_headers);

            if(!empty($response)){
                $response = $response['result'];
            }
        }

        return $response;
    }

    public function getOrderDetail($orderId)
    {
        $response = '';
        if($this->getAccessToken()){
            $requestUrl     = ApiScopeInterface::API_URL_SALE_ORDERS . '/'. $orderId;
            $parameters     = array();
            $http_method    = Client::HTTP_METHOD_GET;
            $http_headers   = array('Accept' => ApiScopeInterface::API_ACCEPT_HEADER);
            $request = $this->getAccessToken();
            $response = $request->fetch($requestUrl, $parameters, $http_method, $http_headers);
            $response = $response['result'];
        }

        return $response;
    }

}