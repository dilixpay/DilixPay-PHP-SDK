<?php

namespace DilixPayPHPSDK\DilixPay\DilixPay;

use DilixPayPHPSDK\DilixPay\DilixPay\DilixPay;
use DilixPayPHPSDK\DilixPay\Resource\Payment;

/**
 * give access to simplify methods of payment
 */
 class Payments
 {


 	/**
     * Creates a Payment.
     *
     * @param   array               $data           API data for payment creation
     * @param   DilixPay    		$dilixPay 
     *
     * @return  null|Resource\Payment the created payment instance
     *
     */
    public static function create(array $data, DilixPay $dilixPay)
    {
    	return Payment::create($data, $dilixPay);
    }

    /**
     * execute a Payment.
     *
     * @param   0|1                 $$success           
     * @param   string              $msg              message     
     * @param   Payment               $response           API data for payment execute
     * @param   DilixPay            $dilixPay 
     *
     *   
     *
     */
    public static function execute(string $success, string $msg, $response, DilixPay $dilixPay)
    {
         Payment::execute($success, $msg, $response, $dilixPay);
    }

    /**
     * execute a Payment.
     *
     * @param   0|1               $$success           
     * @param   string            $msg            message
     *
     *   
     *
     */
    public static function approved(string $success, string $msg)
    {
         Payment::approved($success, $msg);
    }    


 }	