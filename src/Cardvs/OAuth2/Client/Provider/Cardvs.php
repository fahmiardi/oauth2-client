<?php
namespace Cardvs\OAuth2\Client\Provider;

use Cardvs\OAuth2\Client\Provider\IdentityProvider;

/**
*
*/
class Cardvs extends IdentityProvider
{

    public function urlAuthorize()
    {
        return null;
    }

    public function urlAccessToken()
    {
        return 'http://apis.cardvs.com/oauth2/token';
    }

    public function urlUserDetails(League\OAuth2\Client\Token\AccessToken $token)
    {
        return 'http://apis.cardvs.com/shop/people/me?access_token='.$token;
    }

    public function userDetails($response, League\OAuth2\Client\Token\AccessToken $token)
    {
        $response = (array) $response;
        $user = new Cardvs\OAuth2\Client\Provider\User;
        $user->uid = $response['userId'];
        $user->name = $response['fullname'];
        $user->email = $response['email'];
        $user->gender = $response['gender'];
        $user->birthday = $response['birthday'];
        $user->languageId = $response['languageId'];
        $user->isLoggedUser = $response['isLoggedUser'];
        $user->connect = isset($response['connect']) ? $response['connect'] : null;

        return $user;
    }
}