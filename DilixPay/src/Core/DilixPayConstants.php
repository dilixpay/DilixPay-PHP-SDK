<?php
namespace DilixPayPHPSDK\DilixPay\Core;

/**
 *
 * DilixPay constants
 */
class DilixPayConstants
{
	/**
     * The library name
     */
    const LIBRARY_NAME = 'DilixPay_PHP_SDK';


    /**
     * The library version
     */
    const LIBRARY_VERSION = '1.1.0';

    /**
     * api base url
     */
    const API_BASE_URL="https://www.dilixpay.com/public";

    /**
     * api payment sandbox resource
     */
    const PAYMENT_SANDBOX_RESOURCE = DilixPayConstants::API_BASE_URL.'/api/v1/payments/sandboxPayment';

    /**
     * api payment live resource
     */
     const PAYMENT_LIVE_RESOURCE =DilixPayConstants::API_BASE_URL.'/api/v1/payments/payment';

    /**
     * api loin check
     */
     const API_LOGIN_CHECK = DilixPayConstants::API_BASE_URL.'/api/login_check';

}



