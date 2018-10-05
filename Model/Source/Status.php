<?php

namespace Tigren\CompanyAccount\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{

    protected $account;

    public function __construct(\Tigren\CompanyAccount\Model\Account $account)
    {
        $this->account = $account;
    }

    /**
     * Get status options
     */
    public function toOptionArray()
    {
        $availableOptions = $this->account->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}