/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'ko',
    './customer/address'
], function ($, ko, Address) {
    'use strict';

    var isLoggedIn = ko.observable(window.isCustomerLoggedIn);

    return {
        /**
         * @return {Array}
         */
        getAddressItems: function () {
            var items = [],
                customerData = window.customerData,
                address = [];

            if (isLoggedIn()) {
                if (Object.keys(customerData).length) {
                    if(window.checkoutConfig.companyAccountData && window.checkoutConfig.companyAccountData.length){
                        $.each(window.checkoutConfig.companyAccountData, function (key, item) {
                            // if(item.is_billing != 1)
                                address.push(item);
                        });
                    }else
                        address = customerData.addresses;
                    $.each(address, function (key, item) {
                        items.push(new Address(item));
                    });
                }
            }
            return items;
        }
    };
});
