<?php
namespace Cardvs\OAuth2\Client\Grant;

use OAuth2\Client\Token\AccessToken as AccessToken;

class Password implements OAuth2\Client\Grant\GrantInterface
{
    public function __toString()
    {
        return 'password';
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
