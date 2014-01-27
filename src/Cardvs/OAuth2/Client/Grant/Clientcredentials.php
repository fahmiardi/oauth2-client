<?php
namespace Cardvs\OAuth2\Client\Grant;

use League\OAuth2\Client\Token\AccessToken as AccessToken;

class Clientcredentials implements League\OAuth2\Client\Grant\GrantInterface
{
    public function __toString()
    {
        return 'client_credentials';
    }

    public function prepRequestParams($defaultParams, $params)
    {
        return array_merge($defaultParams, $params);
    }

    public function handleResponse($response = [])
    {
        return new AccessToken($response);
    }
}
