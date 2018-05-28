/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* global $, $H */

define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedCustomers = config.selectedCustomers,
            customerAdmins = $H(selectedCustomers),
            gridJsObject = window[config.gridJsObjectName],
            tabIndex = 1000;

        $('in_account_customers').value = Object.toJSON(customerAdmins);

        /**
         * Register Account Customer
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerAccountCustomer(grid, element, checked) {
            if (checked) {
                if (element.adminElement) {
                    element.adminElement.disabled = false;
                    if (element.adminElement.checked) {
                        customerAdmins.set(element.value, 1);
                    } else customerAdmins.set(element.value, 0);
                }
            } else {
                if (element.adminElement) {
                    element.adminElement.disabled = true;
                }
                customerAdmins.unset(element.value);
            }
            $('in_account_customers').value = Object.toJSON(customerAdmins);
            grid.reloadParams = {
                'selected_customers[]': customerAdmins.keys()
            };
        }

        /**
         * Click on customer row
         *
         * @param {Object} grid
         * @param {String} event
         */
        function accountCustomerRowClick(grid, event) {
            var trElement = Event.findElement(event, 'tr'),
                isInput = Event.element(event).tagName === 'INPUT',
                checked = false,
                checkbox = null;

            if (trElement) {
                checkbox = Element.getElementsBySelector(trElement, 'input');

                if (checkbox[0]) {
                    checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                    gridJsObject.setCheckboxChecked(checkbox[0], checked);
                }
            }
        }

        /**
         * Initialize account customer row
         *
         * @param {Object} grid
         * @param {String} row
         */
        function accountCustomerRowInit(grid, row) {
            var checkboxes = $(row).getElementsByClassName('checkbox');
            var checkbox_customer = checkboxes[0],
                checkbox_admin = checkboxes[1];
            if (checkbox_customer && checkbox_admin) {
                checkbox_customer.adminElement = checkbox_admin;
                checkbox_admin.customerElement = checkbox_customer;
                checkbox_admin.disabled = !checkbox_customer.checked;
            }
        }

        gridJsObject.rowClickCallback = accountCustomerRowClick;
        gridJsObject.initRowCallback = accountCustomerRowInit;
        gridJsObject.checkboxCheckCallback = registerAccountCustomer;

        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                accountCustomerRowInit(gridJsObject, row);
            });
        }
    };
});
