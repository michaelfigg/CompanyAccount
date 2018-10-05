<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */
namespace Tigren\CompanyAccount\Model;

class Account extends \Magento\Framework\Model\AbstractModel 
{

    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'companyaccount_account';

    protected $_cacheTag = 'companyaccount_account';

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**
     * Prefix of model name
     *
     * @var string
     */
    protected $_accountPrefix = 'companyaccount_account';

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('Tigren\CompanyAccount\Model\ResourceModel\Account');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}
