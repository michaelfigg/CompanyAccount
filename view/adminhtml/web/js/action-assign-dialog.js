define([
    'jquery',
    'uiRegistry',
    'jquery/ui',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'mage/backend/tree-suggest',
    'mage/backend/validation'
], function ($, registry) {
    'use strict';

    $.widget('mage.customerAssignActionDialog', {
        _create: function () {
            var widget = this;
            var assignCustomerForm = $('#new-assign-customer-form-validate');
            this.element.modal({
                type: 'slide',
                modalClass: 'mage-assign-customer-dialog form-inline',
                title: $.mage.__('Select Customers'),
                opened: function () {

                },
                buttons: [{
                    text: $.mage.__('Assign Customer'),
                    class: 'action-primary',
                    click: function (e) {
                        var thisButton = $(e.currentTarget);
                        thisButton.prop('disabled', true);

                        $.ajax({
                            type: 'GET',
                            url: widget.options.assignCustomerModalUrl,
                            data: assignCustomerForm.serializeArray(),
                            dataType: 'json',
                            context: $('body'),
                            beforeSend: function () {
                                var body = $('body').loader();
                                body.loader('show');
                            }
                        }).success(function (data) {
                            $(widget.element).modal('closeModal');
                            if(data.status == 1) {
                                $('#companyaccount_account_edit_tabs_customers_content').html(data.html);
                                account_customer_gridJsObject.resetFilter();
                            }
                        }).complete(
                            function () {
                                thisButton.prop('disabled', false);
                                var body = $('body').loader();
                                body.loader('hide');
                                $(widget.element).modal('closeModal');
                            }
                        );
                    }
                }]
            });
        }
    });

    return $.mage.customerAssignActionDialog;
});
