/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
        'ko',
        'underscore',
        'Magento_Ui/js/form/form',
        'Magento_Customer/js/model/customer',
        'Tigren_CompanyAccount/customer/js/model/address-list',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/create-billing-address',
        'Magento_Checkout/js/action/select-billing-address',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/checkout-data-resolver',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/action/set-billing-address',
        'Magento_Ui/js/model/messageList',
        'mage/translate',
        'Tigren_CompanyAccount/customer/js/model/customer/address',
        'jquery'
    ],
    function (
        ko,
        _,
        Component,
        customer,
        addressList,
        quote,
        createBillingAddress,
        selectBillingAddress,
        checkoutData,
        checkoutDataResolver,
        customerData,
        setBillingAddressAction,
        globalMessageList,
        $t,
        Address,
        $
    ) {
        'use strict';

        var lastSelectedBillingAddress = null,
            newAddressOption = {
                /**
                 * Get new address label
                 * @returns {String}
                 */
                getAddressInline: function () {
                    return $t('New Address');
                },
                customerAddressId: null
            },
            countryData = customerData.get('directory-data');
        var addressOptions = [];
        var companyAddress = [];
        var hasCompanyAddress = (window.checkoutConfig.companyAccountData && window.checkoutConfig.companyAccountData.length);
        if(hasCompanyAddress){
            //custom by Son
            //add billing address company account to select box
            $.each(window.checkoutConfig.companyAccountData, function (key, item) {
                if(item.is_billing == 1)
                    companyAddress.push(new Address(item));
            });
            //change customerAddressId to null
            $.each(companyAddress, function (key, item) {
                item.customerAddressId = null;
            });
            //filter current address of company account
            addressOptions = companyAddress.filter(function (address) {
                return address.getType() == 'customer-address';
            });
            quote.billingAddress(addressOptions[0]);
        }else{
            //default magento 2
            addressOptions = addressList().filter(function (address) {
                return address.getType() == 'customer-address'; //eslint-disable-line eqeqeq
            });
        }

        addressOptions.push(newAddressOption);

        return Component.extend({
            defaults: {
                template: 'Magento_Checkout/billing-address'
            },
            currentBillingAddress: quote.billingAddress,
            addressOptions: addressOptions,
            customerHasAddresses: addressOptions.length > 1,

            /**
             * Init component
             */
            initialize: function () {
                this._super();
                quote.paymentMethod.subscribe(function () {
                    checkoutDataResolver.resolveBillingAddress();
                }, this);
            },

            /**
             * @return {exports.initObservable}
             */
            initObservable: function () {
                this._super()
                    .observe({
                        selectedAddress: null,
                        isAddressDetailsVisible: quote.billingAddress() != null,
                        isAddressFormVisible: !customer.isLoggedIn() || addressOptions.length === 1,
                        isAddressSameAsShipping: (hasCompanyAddress) ? true : false,
                        saveInAddressBook: 1
                    });

                if(!hasCompanyAddress){
                    quote.billingAddress.subscribe(function (newAddress) {
                        if (quote.isVirtual()) {
                            this.isAddressSameAsShipping(false);
                        } else {
                            this.isAddressSameAsShipping(
                                newAddress != null &&
                                newAddress.getCacheKey() == quote.shippingAddress().getCacheKey() //eslint-disable-line eqeqeq
                            );
                        }

                        if (newAddress != null && newAddress.saveInAddressBook !== undefined) {
                            this.saveInAddressBook(newAddress.saveInAddressBook);
                        } else {
                            this.saveInAddressBook(1);
                        }
                        this.isAddressDetailsVisible(true);
                    }, this);
                }else{
                    this.isAddressDetailsVisible(true);
                }

                return this;
            },

            canUseShippingAddress: ko.computed(function () {
                if(hasCompanyAddress)
                    return false;
                else
                    return !quote.isVirtual() && quote.shippingAddress() && quote.shippingAddress().canUseForBilling();
            }),

            /**
             * @param {Object} address
             * @return {*}
             */
            addressOptionsText: function (address) {
                return address.getAddressInline();
            },

            /**
             * @return {Boolean}
             */
            useShippingAddress: function () {
                if(!hasCompanyAddress){
                    if (this.isAddressSameAsShipping()) {
                        selectBillingAddress(quote.shippingAddress());

                        this.updateAddresses();
                        this.isAddressDetailsVisible(true);
                    } else {
                        lastSelectedBillingAddress = quote.billingAddress();
                        quote.billingAddress(null);
                        this.isAddressDetailsVisible(false);
                    }
                    checkoutData.setSelectedBillingAddress(null);
                }else{
                    this.isAddressDetailsVisible(true);
                }

                return true;
            },

            /**
             * Update address action
             */
            updateAddress: function () {
                var addressData, newBillingAddress;

                if (this.selectedAddress() && this.selectedAddress() != newAddressOption) { //eslint-disable-line eqeqeq
                    selectBillingAddress(this.selectedAddress());
                    checkoutData.setSelectedBillingAddress(this.selectedAddress().getKey());
                } else {
                    this.source.set('params.invalid', false);
                    this.source.trigger(this.dataScopePrefix + '.data.validate');

                    if (this.source.get(this.dataScopePrefix + '.custom_attributes')) {
                        this.source.trigger(this.dataScopePrefix + '.custom_attributes.data.validate');
                    }

                    if (!this.source.get('params.invalid')) {
                        addressData = this.source.get(this.dataScopePrefix);

                        if (customer.isLoggedIn() && !this.customerHasAddresses) { //eslint-disable-line max-depth
                            this.saveInAddressBook(1);
                        }
                        addressData['save_in_address_book'] = this.saveInAddressBook() ? 1 : 0;
                        newBillingAddress = createBillingAddress(addressData);

                        // New address must be selected as a billing address
                        selectBillingAddress(newBillingAddress);
                        checkoutData.setSelectedBillingAddress(newBillingAddress.getKey());
                        checkoutData.setNewCustomerBillingAddress(addressData);
                    }
                }
                this.updateAddresses();
            },

            /**
             * Edit address action
             */
            editAddress: function () {
                lastSelectedBillingAddress = quote.billingAddress();
                quote.billingAddress(null);
                this.isAddressDetailsVisible(false);
            },

            /**
             * Cancel address edit action
             */
            cancelAddressEdit: function () {
                this.restoreBillingAddress();

                if (quote.billingAddress()) {
                    // restore 'Same As Shipping' checkbox state
                    this.isAddressSameAsShipping(
                        quote.billingAddress() != null &&
                        quote.billingAddress().getCacheKey() == quote.shippingAddress().getCacheKey() && //eslint-disable-line
                        !quote.isVirtual()
                    );
                    this.isAddressDetailsVisible(true);
                }
            },

            /**
             * Restore billing address
             */
            restoreBillingAddress: function () {
                if (lastSelectedBillingAddress != null) {
                    selectBillingAddress(lastSelectedBillingAddress);
                }
            },

            /**
             * @param {Object} address
             */
            onAddressChange: function (address) {
                this.isAddressFormVisible(address == newAddressOption); //eslint-disable-line eqeqeq
            },

            /**
             * @param {Number} countryId
             * @return {*}
             */
            getCountryName: function (countryId) {
                return countryData()[countryId] != undefined ? countryData()[countryId].name : ''; //eslint-disable-line
            },

            /**
             * Trigger action to update shipping and billing addresses
             */
            updateAddresses: function () {
                if (window.checkoutConfig.reloadOnBillingAddress ||
                    !window.checkoutConfig.displayBillingOnPaymentMethod
                ) {
                    setBillingAddressAction(globalMessageList);
                }
            },

            /**
             * Get code
             * @param {Object} parent
             * @returns {String}
             */
            getCode: function (parent) {
                return _.isFunction(parent.getCode) ? parent.getCode() : 'shared';
            }
        });
    });
