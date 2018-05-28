<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */
namespace Tigren\CompanyAccount\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class AccountActions extends Column
{

    /** Url path */
    const GRID_URL_PATH_EDIT = 'companyaccount/account/edit';
    const GRID_URL_PATH_DELETE = 'companyaccount/account/delete';

    protected $urlBuilder;

    public function __construct(
        ContextInterface $context, 
        UiComponentFactory $uiComponentFactory, 
        UrlInterface $urlBuilder, 
        array $components = [], 
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['account_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(self::GRID_URL_PATH_EDIT, ['account_id' => $item['account_id']]),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::GRID_URL_PATH_DELETE, ['account_id' => $item['account_id']]),
                        'label' => __('Delete')
                    ];
                }
            }
        }
        return $dataSource;
    }

}
