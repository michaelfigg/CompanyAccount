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
    private $escaper;
    private $orderFactory;
    private $requestInterface;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Framework\App\RequestInterface $requestInterface
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Escaper $escaper,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\App\RequestInterface $requestInterface,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->escaper = $escaper;
        $this->orderFactory = $orderFactory;
        $this->requestInterface = $requestInterface;
    }

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
        $curPage = $this->getCurrentPage();
        if($curPage == 'sales_order_view' || $curPage == 'returns_rma_order' || $curPage == 'companyaccount_order_view'){
            $escaped = $this->escaper->escapeHtml($this->pageConfig->getTitle()->getShortHeading());
            $offset = $curPage == 'companyaccount_order_view' ? 7 : 8; //Why these numbers?

            if (strlen(strstr($escaped, 'Order #')) > 0) {
                $incrementId = substr($escaped, $offset, 100);
                $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
                if (!empty($order->getExtOrderId())) {
                    return 'Order #' . $order->getExtOrderId();
                }
                return "Order #{$incrementId}";
            }
        }

        if (!empty($this->pageTitle)) {
            return __($this->pageTitle);
        }
        return __($this->pageConfig->getTitle()->getShortHeading());
    }

    public function getCurrentPage()
    {
        $moduleName = $this->requestInterface->getModuleName();
        $controllerName = $this->requestInterface->getControllerName();
        $actionName = $this->requestInterface->getActionName();
        return $moduleName . '_' . $controllerName . '_' . $actionName;
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
