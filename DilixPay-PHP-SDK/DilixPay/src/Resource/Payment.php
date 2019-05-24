<?php

namespace DilixPayPHPSDK\DilixPay\Resource;

use DilixPayPHPSDK\DilixPay\Core\HttpClient;
use DilixPayPHPSDK\DilixPay\Core\DilixPayConstants;
use DilixPayPHPSDK\DilixPay\DilixPay\DilixPay;


/**
 * A Payment
 */
 class Payment
 {

    /**
     * Creates a Payment.
     *
     * @param   array               $data       API data for payment creation
     *
     * @param   DilixPay    		$dilixPay   
     *
     * @return  null|Payment the created payment instance
     *
     */	
    public static function create(array $data, DilixPay $dilixPay)
    {
	    	$authorization = "Authorization: Bearer ".$dilixPay->getAccessToken();

	    	$httpClient = new HttpClient();

        if($dilixPay->getMode()=='SANDBOX'){
			
			$response = $httpClient->post(DilixPayConstants::PAYMENT_SANDBOX_RESOURCE,$data,$authorization); 

        }else if($dilixPay->getMode()=='LIVE'){

			$response = $httpClient->post(DilixPayConstants::PAYMENT_LIVE_RESOURCE,$data,$authorization); 

        }else{

            throw new SandBoxOrLiveException("choose SANDBOX or LIVE");
        }

        	return json_decode($response['httpResponse']);   	
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
     */
    public static function execute(string $success, string $msg, $response, DilixPay $dilixPay)
    {
       echo json_encode(["success" => $success, "msg" => $msg,"mode"=>$dilixPay->getMode(), "response" => $response]); 
    }

        /**
     * execute a Payment.
     *
     * @param   0|1               $$success           
     * @param   string            $msg            message
     * 
     *
     */
    public static function approved(string $success, string $msg)
    {
        echo json_encode(["success" => $success, "msg" => $msg]);
    }

 }