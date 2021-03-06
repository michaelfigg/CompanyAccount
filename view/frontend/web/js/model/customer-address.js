/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'Magento_Checkout/js/model/resource-url-manager',
    'Magento_Checkout/js/model/quote',
    'mage/storage',
    'Magento_Checkout/js/model/shipping-service',
    'Magento_Checkout/js/model/shipping-rate-registry',
    'Magento_Checkout/js/model/error-processor',
    'temandoCheckoutFieldsDefinition'
], function (_, resourceUrlManager, quote, storage, shippingService, rateRegistry, errorProcessor, fieldsDefinition) {
    'use strict';

    return {
        /**
         * @param {Object} address
         */
        getRates: function (address) {
            var cache,
                cacheKey;

            if (!address.extensionAttributes) {
                address.extensionAttributes = {};
            }
            if (!address.extensionAttributes.checkoutFields) {
                address.extensionAttributes.checkoutFields = {};
            }

            _.each(fieldsDefinition.getFields(), function (field) {
                address.extensionAttributes.checkoutFields[field.id] = {
                    attributeCode: field.id,
                    value: field.value
                };
            });

            cacheKey = address.getCacheKey().concat(JSON.stringify(address.extensionAttributes));

            shippingService.isLoading(true);
            cache = rateRegistry.get(cacheKey);

            if (cache) {
                shippingService.setShippingRates(cache);
                shippingService.isLoading(false);
            } else {
                //custom by Son
                var serviceUrl = null;
                var payload = null;
                if(window.checkoutConfig.companyAccountData && window.checkoutConfig.companyAccountData.length){
                    serviceUrl = resourceUrlManager.getUrlForEstimationShippingMethodsForNewAddress(quote);
                    payload = JSON.stringify({
                            address: {
                                'street': address.street,
                                'city': address.city,
                                'region_id': address.regionId,
                                'region': address.region,
                                'country_id': address.countryId,
                                'postcode': address.postcode,
                                'email': address.email,
                                'customer_id': address.customerId,
                                'firstname': address.firstname,
                                'lastname': address.lastname,
                                'middlename': address.middlename,
                                'prefix': address.prefix,
                                'suffix': address.suffix,
                                'vat_id': address.vatId,
                                'company': address.company,
                                'telephone': address.telephone,
                                'fax': address.fax,
                                'custom_attributes': address.customAttributes,
                                'extension_attributes': address.extensionAttributes || {},
                                'save_in_address_book': address.saveInAddressBook
                            }
                        }
                    );
                }else{
                    serviceUrl = resourceUrlManager.getUrlForEstimationShippingMethodsForNewAddress(quote);
                    payload = JSON.stringify({
                        addressId: address.customerAddressId,
                        extensionAttributes: address.extensionAttributes || {},
                    });
                }
                //end custom
                storage.post(
                    serviceUrl,
                    payload,
                    false
                ).done(function (result) {
                    rateRegistry.set(cacheKey, result);
                    shippingService.setShippingRates(result);
                }).fail(function (response) {
                    shippingService.setShippingRates([]);
                    errorProcessor.process(response);
                }).always(function () {
                        shippingService.isLoading(false);
                    }
                );
            }
        }
    };
});
