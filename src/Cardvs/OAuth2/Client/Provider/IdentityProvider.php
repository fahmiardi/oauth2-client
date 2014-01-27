<?php
namespace Cardvs\OAuth2\Client\Provider;

use OAuth2\Client\Provider\IdentityProvider as LeagueIdentityProvider;
use OAuth2\Client\Grant\Authorizationcode as LeagueAuthorizationcode;
use OAuth2\Client\Grant\GrantInterface as LeagueGrantInterface;

/**
*
*/
class IdentityProvider extends LeagueIdentityProvider
{
    public function __construct($options = array())
    {
        foreach ($options as $option => $value) {
            if (isset($this->{$option})) {
                $this->{$option} = $value;
            }
        }
    }

    public function getAccessToken(LeagueAuthorizationcode $grant, $params = array())
    {
        if (is_string($grant)) {
            if ( ! class_exists($grant)) {
                throw new \InvalidArgumentException('Unknown grant "'.$grant.'"');
            }
            $grant = new $grant;
        } elseif ( ! $grant instanceof LeagueGrantInterface) {
            throw new \InvalidArgumentException($grant.' is not an instance of OAuth2\Client\Grant\GrantInterface');
        }

        $defaultParams = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => $grant,
        ];

        $requestParams = $grant->prepRequestParams($defaultParams, $params);

        try {
            switch ($this->method) {
                case 'get':
                    $client = new GuzzleClient($this->urlAccessToken() . '?' . http_build_query($requestParams));
                    $request = $client->get()->send();
                    $response = $request->getBody();
                    break;
                case 'post':
                    $client = new GuzzleClient($this->urlAccessToken());
                    $request = $client->post(null, null, $requestParams)->send();
                    $response = $request->getBody();
                    break;
                case 'put':
                case 'delete':
                    $client = new GuzzleClient($this->urlAccessToken());
                    $requestParamsOriginal = $requestParams;
                    unset($requestParams['authorization']);
                    $request = $client->post(null, isset($requestParamsOriginal['authorization']) ? $requestParamsOriginal['authorization'] : null, $requestParams)->send();
                    $response = $request->getBody();
                    break;
            }
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            $raw_response = explode("\n", $e->getResponse());
            $response = end($raw_response);
        }

        switch ($this->responseType) {
            case 'json':
                $result = json_decode($response, true);
                break;
            case 'string':
                parse_str($response, $result);
                break;
        }

        if (isset($result['error']) && ! empty($result['error'])) {
            throw new IDPException($result);
        }

        return $grant->handleResponse($result);
    }
}