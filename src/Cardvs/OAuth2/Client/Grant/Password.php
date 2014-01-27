<?php
namespace Cardvs\OAuth2\Client\Grant;

use OAuth2\Client\Token\AccessToken as LeagueAccessToken;
use OAuth2\Client\Grant\GrantInterface as LeagueGrantInterface;

class Password implements LeagueGrantInterface
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
        return new LeagueAccessToken($response);
    }
}
