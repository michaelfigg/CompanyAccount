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

    $.widget('mage.customerActionDialog', {
        _create: function () {
            var widget = this;
            var newCustomerForm = $('#new-customer-form-validate');
            newCustomerForm.mage('validation', {
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                }
            }).on('highlight.validate', function (e) {
                var options = $(this).validation('option');
            });
            this.element.modal({
                type: 'slide',
                modalClass: 'mage-new-customer-dialog form-inline',
                title: $.mage.__('New Customer'),
                opened: function () {
                    $('#new_firstname').val('');
                    $('#new_lastname').val('');
                    $('#new_email_address').val('');
                    $('#new_password').val('');
                    $('#new_password-confirmation').val('');
                    $('#new_is_subscribed').prop('checked', false);
                    $('#new_is-admin').prop('checked', false);
                },
                buttons: [{
                    text: $.mage.__('Add New'),
                    class: 'action-primary',
                    click: function (e) {
                        if (!newCustomerForm.valid()) {
                            return;
                        }
                        var thisButton = $(e.currentTarget);
                        thisButton.prop('disabled', true);
                        var postData = {
                            firstname: $('#new_firstname').val(),
                            lastname: $('#new_lastname').val(),
                            email: $('#new_email_address').val(),
                            password: $('#new_password').val(),
                            password_confirmation: $('#new_password-confirmation').val(),
                            is_admin: $('#new_is-admin').is(':checked') ? 1 : 0,
                            is_subscribed: $('#new_is_subscribed').is(':checked') ? 1 : 0,
                            account_id: widget.options.accountId
                        };

                        $.ajax({
                            type: 'GET',
                            url: widget.options.addNewCustomerModalUrl,
                            data: postData,
                            dataType: 'json',
                            context: $('body'),
                            beforeSend: function () {
                                var body = $('body').loader();
                                body.loader('show');
                            }
                        }).success(function (data) {
                            $('#new_firstname').val('');
                            $('#new_lastname').val('');
                            $('#new_email_address').val('');
                            $('#new_password').val('');
                            $('#new_password-confirmation').val('');
                            $('#new_is_subscribed').prop('checked', false);
                            $('#new_is-admin').prop('checked', false);
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

    return $.mage.customerActionDialog;
});
