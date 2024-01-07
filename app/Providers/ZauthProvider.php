<?php

namespace App\Providers;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class ZauthProvider extends AbstractProvider
{

    protected $scopes = [
        'openid',
        'profile',
    ];

    public function getZauthUrl()
    {
        return config('services.zauth.base_uri');
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getZauthUrl() . '/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return $this->getZauthUrl() . '/oauth/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getZauthUrl() . '/current_user', [
            'headers' => [
                'cache-control' => 'no-cache',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'name' => $user['username'],
            'admin' => $user['admin'],
        ]);
    }
}
