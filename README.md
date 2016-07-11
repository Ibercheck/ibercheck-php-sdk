# Ibercheck API SDK for PHP

[Ibercheck page](https://www.ibercheck.com) |
[API Documentation page](https://www.ibercheck.com/docs/api/) |
[API Playground page](https://www.ibercheck.com/docs/api/playground)

This SDK provides a friendly interface for performing requests and decode responses.


## Installation

You can use [Composer](https://getcomposer.org):

```bash
composer require ibercheck/ibercheck-api-sdk
```


## Usage

### Create the sale hash

```php
$hash = Ibercheck\Api\Sale::createSaleHash(
    's3cr3t', // Affiliate Secret
    'ABCD_123456', // Order number
    'autocheck' // Product mnemonic
);
```


### Authenticate webhook request

```php
if(!Ibercheck\Api\Webhook::isAuthentic(
        's3cr3t', // Affiliate Secret
        '596f6838cb5b....ae812fb62f91c6', // Value of `X-Ibercheck-Signature` header
        '{....}' // Webhook payload
    )
) {
    throw new Exception('Fraudulent webhook received'); 
}
```


### API Calls

This SDK assist you for craft a well formatted API request and decode the response back to PHP.

```php

// Exchange affiliate credentials with a private access token.
$oAuthRequest = new Ibercheck\Api\ApiRequest('POST', 'http://api_dev.ibercheck.net/oauth');
$oAuthRequest = $oAuthRequest->withJsonSerializableData(
    [
        'grant_type' => 'client_credentials',
        'client_id' => 'ACME_SL', // Affiliate Name
        'client_secret' => 's3cr3t', // Affiliate Secret
    ]
);
$oAuthPayload = sendRequestToApi($oAuthResponse);

// Use the new private access token for authenticate your API requests.
$meRequest = new Ibercheck\Api\ApiRequest('GET', 'http://api_dev.ibercheck.net/me');
$meRequest = $meRequest->withAuthentication($oAuthResponse->access_token);
$mePayload = sendRequestToApi($meRequest);
print_r(sendRequestToApi($mePayload);

function sendRequestToApi(Psr\Http\Message\RequestInterface $request) {
    $ibercheckApiClient = new Ibercheck\Api\Client();

    try {
        $response = $ibercheckApiClient->sendRequest($request);
        $payload = $ibercheckApiClient->decodeResponseBody($response->getBody());
    } catch (Ibercheck\Api\ApiCommunicationException $apiCommunicationException) {
        // A network error has occurred while sending the request or receiving the response.
    
        // Retry
    } catch (Ibercheck\Api\DeserializeException $deserializationException) {
        // Nobody knows when this happen, may an HTTP Proxy on our side or on your side started to return HTML responses with errors.
    
        // Retry
    } catch (Ibercheck\Api\ApiServerException $apiServerException) {
        // Our server has crashed. We promise to fix it ASAP.
    
        echo 'Error code', $apiClientException->getStatus(), PHP_EOL;
        echo 'Error type', $apiClientException->getType(), PHP_EOL;
        echo 'Error message', $apiClientException->getMessage(), PHP_EOL;
        echo 'Error detail', var_export($apiClientException->getDetail(), true), PHP_EOL;
    } catch (Ibercheck\Api\ApiClientException $apiClientException) {
        // Your client has sent an invalid request. Please check your code.
    
        echo 'Error code', $apiClientException->getStatus(), PHP_EOL;
        echo 'Error type', $apiClientException->getType(), PHP_EOL;
        echo 'Error message', $apiClientException->getMessage(), PHP_EOL;
        echo 'Error detail', var_export($apiClientException->getDetail(), true), PHP_EOL;
    }

    return $payload;
}
```


## License

Distributed under the [MIT license](LICENSE)
