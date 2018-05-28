<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Block\Html;

use Magento\Framework\View\Element\Template;

/**
 * Html page title block
 *
 * @method $this setTitleId($titleId)
 * @method $this setTitleClass($titleClass)
 * @method string getTitleId()
 * @method string getTitleClass()
 * @api
 * @since 100.0.2
 */
class Title extends \Magento\Theme\Block\Html\Title
{
    /**
     * Own page title to display on the page
     *
     * @var string
     */
    protected $pageTitle;

    /**
     * Provide own page title or pick it from Head Block
     *
     * @return string
     */
    public function getPageTitle()
    {
        if (!empty($this->pageTitle)) {
            return $this->pageTitle;
        }
        return __($this->pageConfig->getTitle()->getShort());
    }

    /**
     * Provide own page content heading
     *
     * @return string
     */
    public function getPageHeading()
    {
        if($this->getCurrentPage() == 'sales_order_view' || $this->getCurrentPage() == 'companyaccount_order_view' || $this->getCurrentPage() == 'returns_rma_order'){
            if($this->getCurrentPage() == 'sales_order_view' || $this->getCurrentPage() == 'returns_rma_order'){
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $escaper = $objectManager->create('Magento\Framework\Escaper')->escapeHtml($this->pageConfig->getTitle()->getShortHeading());
                if (strlen(strstr($escaper, 'Order #')) > 0) {
                    $incrementId = substr($escaper, 8, 100);
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $order = $objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($incrementId);
                    return 'Order #'.' '.$order->getExtOrderId();
                }
            }
            if($this->getCurrentPage() == 'companyaccount_order_view'){
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $escaper = $objectManager->create('Magento\Framework\Escaper')->escapeHtml($this->pageConfig->getTitle()->getShortHeading());
                if (strlen(strstr($escaper, 'Order #')) > 0) {
                    $incrementId = substr($escaper, 7, 100);
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $order = $objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($incrementId);
                    return 'Order #'.' '.$order->getExtOrderId();
                }
            }
        }
        if (!empty($this->pageTitle)) {
            return __($this->pageTitle);
        }
        return __($this->pageConfig->getTitle()->getShortHeading());
    }

    public function getCurrentPage(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $requestInterface = $objectManager->get('Magento\Framework\App\RequestInterface');
        $moduleName     = $requestInterface->getModuleName();
        $controllerName = $requestInterface->getControllerName();
        $actionName     = $requestInterface->getActionName();
        $current_page = $moduleName.'_'.$controllerName.'_'.$actionName;
        return $current_page;
    }
    /**
     * Set own page title
     *
     * @param string $pageTitle
     * @return void
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }
}
