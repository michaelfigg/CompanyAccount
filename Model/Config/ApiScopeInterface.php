<?php
namespace Tigren\CompanyAccount\Model\Config;

interface ApiScopeInterface
{
    const API_TOKEN_ENDPOINT    = 'https://www.stockinthechannel.co.uk/auth/connect/token';
    const API_URL_QUOTE         = 'https://api.stockinthechannel.co.uk/Quotes';
    const API_URL_ACCOUNT       = 'https://api.stockinthechannel.co.uk/Accounts/';
    const API_URL_PRODUCT       = 'https://api.stockinthechannel.co.uk/Products/';
    const API_ACCEPT_HEADER     = 'application/json;api-version=2';
    const API_GRANT_TYPE        = 'client_credentials';
    const API_URL_SALE_ORDERS = 'https://api.stockinthechannel.co.uk/SalesOrders';
}