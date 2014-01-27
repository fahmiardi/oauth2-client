<?php
namespace Cardvs\OAuth2\Client\Provider;

use OAuth2\Client\Provider\User as LeagueUser;

/**
*
*/
class User extends LeagueUser
{

    public $gender = null;
    public $birthday = null;
    public $languageId = null;
    public $isLoggedUser = null;
    public $connect = null;
}