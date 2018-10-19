<?php
namespace Tigren\CompanyAccount\Plugin\Customer;

class DisableRegistration
{
    /**
     * Frontend registration is completely disabled
     * @param \Magento\Customer\Model\Registration $subject
     * @param bool $result
     * @return bool
     */
    public function afterIsAllowed(\Magento\Customer\Model\Registration $subject, $_result)
    {
        return false;
    }
}