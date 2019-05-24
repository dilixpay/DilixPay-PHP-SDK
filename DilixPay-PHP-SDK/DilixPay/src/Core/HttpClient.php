<?php

namespace DilixPayPHPSDK\DilixPay\Core;

use DilixPayPHPSDK\DilixPay\Exception\DilixPayServerException;
use DilixPayPHPSDK\DilixPay\Exception\BadRequestException;
use DilixPayPHPSDK\DilixPay\Exception\UnauthorizedException;
use DilixPayPHPSDK\DilixPay\Exception\ForbiddenException;
use DilixPayPHPSDK\DilixPay\Exception\NotFoundException;
use DilixPayPHPSDK\DilixPay\Exception\NotAllowedException;
use DilixPayPHPSDK\DilixPay\Exception\HttpException;
use DilixPayPHPSDK\DilixPay\Exception\ConnectionException;
use DilixPayPHPSDK\DilixPay\Exception\UnexpectedAPIResponseException;


/**
 * 
 *
 * 
 */
class HttpClient
{

    /**
     * @var array  Default User-Agent products made to improve the User-Agent HTTP header sent for each HTTP request.
     * 
     */
    private static $defaultUserAgentProducts = array();

	/**
     * Sends a POST request to the API.
     *
     * @param   string  	   $resource       the path to the sends resource
     * @param   array          $data           Request data
     * @param   string|null    $authorization  the authorization
     *
     * @return  array   the response in a dictionary with following keys:
     * <pre>
     *  'httpStatus'    => The 2xx HTTP status code as defined at http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.2
     *  'httpResponse'  => The HTTP response
     * </pre>
     *
     * @throws  UnexpectedAPIResponseException  When the API response is not parsable in JSON.
     * @throws  HttpException                   When status code is not 2xx.
     * @throws  ConnectionException             When an error was encountered while connecting to the resource.
     */
    public function post($resource, $data = null , $authorization = null)
    {
        return $this->request('POST', $resource, $data, $authorization);
    }

    /**
     * Sends a PATCH request to the API.
     *
     * @param   string  		$resource   	the path to the PATCH resource
     * @param   array   		$data       	Request data
     * @param   string|null    	$authorization  the authorization
     *
     * @return  array   the response in a dictionary with following keys:
     * <pre>
     *  'httpStatus'    => The 2xx HTTP status code as defined at http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.2
     *  'httpResponse'  => The HTTP response
     * </pre>
     *
     * @throws  UnexpectedAPIResponseException  When the API response is not parsable in JSON.
     * @throws  HttpException                   When status code is not 2xx.
     * @throws  ConnectionException             When an error was encountered while connecting to the resource
     */
    public function patch($resource, $data = null, $authorization = null)
    {
        return $this->request('PATCH', $resource, $data, $authorization);
    }

    /**
     * Sends a DELETE request to the API.
     *
     * @param   string  		$resource   	the path to the DELETE resource
     * @param   array   		$data       	Request data
     * @param   string|null    	$authorization  the authorization
     *
     * @return  array   the response in a dictionary with following keys:
     * <pre>
     *  'httpStatus'    => The 2xx HTTP status code as defined at http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.2
     *  'httpResponse'  => The HTTP response
     * </pre>
     *
     * @throws  UnexpectedAPIResponseException  When the API response is not parsable in JSON.
     * @throws  HttpException                   When status code is not 2xx.
     * @throws  ConnectionException             When an error was encountered while connecting to the resource
     */
    public function delete($resource, $data = null, $authorization = null)
    {
        return $this->request('DELETE', $resource, $data, $authorization);
    }

    /**
     * Sends a GET request to the API.
     *
     * @param   string  		$resource   	the path to the GET resource
     * @param   array   		$data       	Request data
     * @param   string|null    	$authorization  the authorization 
     *
     * @return  array   the response in a dictionary with following keys:
     * <pre>
     *  'httpStatus'    => The 2xx HTTP status code as defined at http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.2
     *  'httpResponse'  => The HTTP response
     * </pre>
     *
     * @throws  UnexpectedAPIResponseException  When the API response is not parsable in JSON.
     * @throws  HttpException                   When status code is not 2xx.
     * @throws  ConnectionException             When an error was encountered while connecting to the resource.
     */
    public function get($resource, $data = null, $authorization = null)
    {
        return $this->request('GET', $resource, $data, $authorization);
    }
    /**
     * Adds a default product for the User-Agent HTTP header sent for each HTTP request.
     *
     * @param   string  $product   the product's name
     * @param   string  $version   the product's version
     * @param   string  $comment   a comment about the product
     *
     */
    public static function addDefaultUserAgentProduct($product, $version = null, $comment = null)
    {
        self::$defaultUserAgentProducts[] = array($product, $version, $comment);
    }

    /**
     * Formats a product for a User-Agent HTTP header.
     *
     * @param   string  $product   the product name
     * @param   string  $version   (optional) product version
     * @param   string  $comment   (optional) comment about the product.
     *
     * @return  string  a formatted User-Agent string (`PRODUCT/VERSION (COMMENT)`)
     */
    private static function formatUserAgentProduct($product, $version = null, $comment = null)
    {
        $productString = $product;
        if ($version) {
            $productString .= '/' . $version;
        }
        if ($comment) {
            $productString .= ' (' . $comment . ')';
        }
        return $productString;
    }

    /**
     * Gets the User-Agent HTTP header sent for each HTTP request.
     */
    public static function getUserAgent()
    {
        $curlVersion = curl_version(); // Do not move this inside $headers even if it is used only there.
                                       // PHP < 5.4 doesn't support call()['value'] directly.
        $userAgent = self::formatUserAgentProduct('DilixPay-PHP',
                                                  Config::LIBRARY_VERSION,
                                                  sprintf('PHP/%s; curl/%s', phpversion(), $curlVersion['version']));
        foreach (self::$defaultUserAgentProducts as $product) {
            $userAgent .= ' ' . self::formatUserAgentProduct($product[0], $product[1], $product[2]);
        }
        return $userAgent;
    }

    /**
     * Performs a request.
     *
     * @param   string  		$httpVerb       the HTTP verb (PUT, POST, GET, …)
     * @param   string  		$resource       the path to the resource queried
     * @param   array   		$data           the request content
     * @param   string|null    	$authorization  the authorization 
     *
     * @return array the response in a dictionary with following keys:
     * <pre>
     *  'httpStatus'    => The 2xx HTTP status code as defined at http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.2
     *  'httpResponse'  => The HTTP response
     * </pre>
     *
     * @throws  UnexpectedAPIResponseException  When the API response is not parsable in JSON.
     * @throws  HttpException                   When status code is not 2xx.
     * @throws  ConnectionException             When an error was encountered while connecting to the resource.
     */
    private function request($httpVerb, $resource, array $data = null, $authorization = null)
    {

        $request = new CurlRequest();

        if (!empty($authorization)) {

		    $userAgent = self::getUserAgent();

		    $headers = array(
		            'Accept: application/json',
		            'Content-Type: application/json',
		            'User-Agent: ' . $userAgent
		        );
		        
		    $headers[] = $authorization;

		    $request->setopt(CURLOPT_HTTPHEADER, $headers);


		 if (!empty($data)) {

            $string=json_encode($data);

        switch (json_last_error()) {

        case JSON_ERROR_NONE:

            $request->setopt(CURLOPT_POSTFIELDS,$string);

        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
            }

        }

        }else{

		 if (!empty($data)) {
		 	//for get Access Token
            $request->setopt(CURLOPT_POSTFIELDS,$data);
        }        	
        }

	        $request->setopt(CURLOPT_FAILONERROR, false);
	        $request->setopt(CURLOPT_SSL_VERIFYHOST, 0);
	        $request->setopt(CURLOPT_SSL_VERIFYPEER, 0);
	        $request->setopt(CURLOPT_RETURNTRANSFER,true);        
	        $request->setopt(CURLOPT_CUSTOMREQUEST, $httpVerb);
	        $request->setopt(CURLOPT_URL, $resource);
        


    	$result = array(
            'httpResponse'  => $request->exec(),
            'httpStatus'    => $request->getInfo(CURLINFO_HTTP_CODE)
        );

        // We must do this before closing curl
        $errorCode = $request->errno();
        $errorMessage = $request->error();

        $request->close();

        // We want manage errors from HTTP in standards cases
        $curlStatusNotManage = array(
            0, // CURLE_OK
            22 // CURLE_HTTP_NOT_FOUND or CURLE_HTTP_RETURNED_ERROR
        );


        // If there was an HTTP error
        if (in_array($errorCode, $curlStatusNotManage) && substr($result['httpStatus'], 0, 1) !== '2') {
            $this->throwRequestException($result['httpResponse'], $result['httpStatus']);
        // Unreachable bracket marked as executable by old versions of XDebug
        } // If there was an error with curl
        elseif ($result['httpResponse'] === false || $errorCode) {
            $this->throwConnectionException($result['httpStatus'], $errorMessage);
        // Unreachable bracket marked as executable by old versions of XDebug
        }

        $response = $result['httpResponse'];


        $response = json_decode($response, true);


        if ($response === null) {

            throw new UnexpectedAPIResponseException('API response is not valid JSON.',$response);
        }

        return $result;
    }


      /**
     * Throws an exception from a given curl error.
     *
     * @param   int     $errorCode      the curl error code
     * @param   string  $errorMessage   the error message
     *
     * @throws  ConnectionException
     */
    private function throwConnectionException($errorCode, $errorMessage)
    {
        throw new ConnectionException(
            'Connection to the API failed with the following message: ' . $errorMessage, $errorCode
        );
    }

    /**
     * Throws an exception from a given HTTP response and status.
     *
     * @param   string  $httpResponse   the HTTP response
     * @param   int     $httpStatus     the HTTP status
     *
     * @throws  HttpException   the generated exception from the request
     */
    private function throwRequestException($httpResponse, $httpStatus)
    {
        $exception = null;

        // Error 5XX
        if (substr($httpStatus, 0, 1) === '5') {
       
            throw new DilixPayServerException('Unexpected server error during the request.',
                $httpResponse, $httpStatus
            );
        }

        switch ($httpStatus) {
            case 400:
                throw new BadRequestException('Bad request.', $httpResponse, $httpStatus);
                break;
            case 401:
                throw new UnauthorizedException('Unauthorized. Please check your credentials.',
                    $httpResponse, $httpStatus);
                break;
            case 403:
                throw new ForbiddenException('Forbidden error. You are not allowed to access this resource.',
                    $httpResponse, $httpStatus);
                break;
            case 404:
                throw new NotFoundException('The resource you requested could not be found.',
                    $httpResponse, $httpStatus);
                break;
            case 405:
                throw new NotAllowedException('The requested method is not supported by this resource.',
                    $httpResponse, $httpStatus);
                break;
        }

        throw new HttpException('Unhandled HTTP error.', $httpResponse, $httpStatus);
    }
}	
