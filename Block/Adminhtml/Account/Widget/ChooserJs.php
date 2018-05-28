<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Widget;


/**
 * Ajaxcart js block
 */
class ChooserJs extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'js/chooser.phtml';

    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        array $data = []
    )
    {
        $this->_elementFactory = $elementFactory;
        parent::__construct($context, $data);
    }

    /**
     * Return chooser HTML and init scripts
     *
     * @return string
     */
    protected function _toHtml()
    {
        $element = $this->getElement();
        $this->setElementValue($element->getValue());

        $hidden = $this->_elementFactory->create('hidden', ['data' => $element->getData()]);
        $hidden->setId("tg-customer-selected")->setForm($element->getForm());
        $hidden->addClass('required-entry');
        $hidden->setValue($element->getValue());

        $hiddenHtml = $hidden->getElementHtml();

        $html = parent::_toHtml();

        return $hiddenHtml . $html;
    }
}