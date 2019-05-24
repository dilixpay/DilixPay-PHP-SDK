<?php

namespace DilixPayPHPSDK\DilixPay\DilixPay;

use DilixPayPHPSDK\DilixPay\Core\HttpClient;
use DilixPayPHPSDK\DilixPay\Core\CurlRequest;
use DilixPayPHPSDK\DilixPay\Core\DilixPayConstants;
use DilixPayPHPSDK\DilixPay\Exception\SandBoxOrLiveException;


class DilixPay
{
    /**
     *@var string the mode LIVE|SANDBOX
     *
     */
    private $mode;

    /**
     *@var string the client id
     *
     */
    private $clientID;

    /**
     *@var string the client secret
     *
     */
    private $clientSecret;

    /**
     *@var string the access token send by api
     */
    private $accessToken;

    /**
     *constructor
     *
     * @param   string  $mode              the mode LIVE|SANDBOX
     *
     * @param   string  $clientID          the client id
     *
     * @param   string  $clientSecret      the client secret
     *
     */
	public function __construct($mode,$clientID,$clientSecret) 
    {
        $this->setMode($mode);
        $this->setClientID($clientID);
        $this->setClientSecret($clientSecret);
        
        
    }

    /**
     * Gets the mode
     *
     * @return  string  The current mode
     *
     */
    public function getMode(): string
    {
        return $this->mode;
    }
    
    /**
     * Set the mode
     *
     * @param   string  $mode          the mode LIVE|SANDBOX
     *
     */
    public function setMode(string $mode): self
    {
        $this->mode=$mode;
        return $this;
    }

    /**
     * Gets the clientID
     *
     * @return  string  $clientID      the client id
     *
     */
    public function getClientID(): string
    {
        return $this->clientID;
    }
    
    /**
     * Set the clientID
     *
     * @param   string  $clientID          the client id
     *
     */
    public function setClientID(string $clientID): self
    {
        $this->clientID=$clientID;
        return $this;
    }


    /**
     * Gets the clientSecret
     *
     * @return  string  $clientSecret      the client secret
     *
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }
    
    /**
     * Set the clientSecret
     *
     * @param   string  $clientSecret          the client secret
     *
     */
    public function setClientSecret(string $clientSecret): self
    {
        $this->clientSecret=$clientSecret;
        return $this;
    }

    /**
     * Gets the accessToken
     *
     * @return  string  $accessToken      the accessToken 
     *
     */
    public function getAccessToken(): string
    {
        if($this->accessToken)
        {
        return $this->accessToken;
        }else{
        return $this->generateAccessToken();
        }
    }
    
    /**
     * Set the accessToken
     *
     * @param   string  $accessToken          the access Token
     *
     */
    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken=$accessToken;
        return $this;
    }
    
    /**
     * Generate  the accessToken
     *
     * @return  string  $accessToken      the accessToken 
     *
     * @throws  SandBoxOrLiveException     when mode is different from sandbox or live
     *
     */
    public function generateAccessToken() 
    {

        if($this->getMode()=='SANDBOX' || $this->getMode()=='LIVE')
        {
                $data =array(
                    '_username' => $this->getClientID(),
                    '_password' => $this->getClientSecret(),
                    );

               $httpClient = new HttpClient();

               $response = $httpClient->post(DilixPayConstants::API_LOGIN_CHECK,$data);

               $response = json_decode($response['httpResponse']); 


                if(is_string($response->token))
                {
                        $this->setAccessToken($response->token);          

                        return $this->accessToken;

                }else{

                        throw new \Exception("Contact support");
                }               



        }else{

                throw new SandBoxOrLiveException("choose SANDBOX or LIVE");

        }

            
     }


}
