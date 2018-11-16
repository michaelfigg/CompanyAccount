<?php

namespace Tigren\CompanyAccount\Cron;

use Magento\Framework\ObjectManagerInterface;

/**
 * Class ReserveCart
 * @package Tigren\AdvancedProductSme\Cron
 */
class RemoveExternalToken
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $_connection;
    /**
     * @var string
     */
    protected $_externalTokenTable;
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $localeDate;
    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote\Collection
     */

    /**
     * RemoveExternalToken constructor.
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        ObjectManagerInterface $objectManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
    )
    {
        $this->_resource = $resource;
        $this->_connection = $this->_resource->getConnection('core_write');
        $this->_externalTokenTable = $this->_resource->getTableName('tigren_comaccount_customer_login_external');
        $this->objectManager = $objectManager;
        $this->localeDate = $localeDate;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $createDateFrom = $this->localeDate->date()
            ->setTime(0, 0)
            ->format('Y-m-d H:i:s');

        $sql = "DELETE FROM {$this->_externalTokenTable} WHERE created_at < '" . $createDateFrom."'";
        $this->_connection->query($sql);
    }

}