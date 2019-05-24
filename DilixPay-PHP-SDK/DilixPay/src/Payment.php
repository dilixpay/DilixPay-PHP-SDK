<?php

namespace DilixPayPHPSDK\DilixPay;

use DilixPayPHPSDK\DilixPay\DilixPay;
use DilixPayPHPSDK\DilixPay\Resource\Payment;

/**
 * give access to simplify methods of payment
 */
 class Payment
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
    	return DilixPayPHPSDK\DilixPay\Resource\Payment::create($data, $dilixPay);
    }

    /**
     * execute a Payment.
     *
     * @param   0|1                 $$success           
     * @param   string              $msg              message     
     * @param   array               $response           API data for payment execute
     * @param   DilixPay            $dilixPay 
     *
     *   
     *
     */
    public static function execute(string $success, string $msg, DilixPay $dilixPay, array $response)
    {
         return DilixPayPHPSDK\DilixPay\Resource\Payment::execute($success, $msg, $dilixPay, $response);
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
         return DilixPayPHPSDK\DilixPay\Resource\Payment::approved($success, $msg);
    }      

 }	