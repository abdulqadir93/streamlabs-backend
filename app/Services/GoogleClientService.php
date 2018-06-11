<?php

namespace App\Services;

use App\Services\TokenService;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_Oauth2;

class GoogleClientService {
    private $config;
    protected $tokenService;

    public function __construct(array $config, TokenService $tokenService) {
        $this->config = $config;
        $this->tokenService = $tokenService;
    }

    public function getOAuthUrl(): string {
        return $this->getClient()->createAuthUrl();
    }

    public function getToken(string $code): string {
        $client = $this->getClient();
        $client->authenticate($code);
        return $this->tokenService->encode($client->getAccessToken());
    }

    public function getYoutubeService(string $token): Google_Service_YouTube {
        $client = $this->getClient($token);
        return new Google_Service_YouTube($client);
    }

    public function getOAuth2Service(string $token): Google_Service_Oauth2 {
        $client = $this->getClient($token);
        return new Google_Service_Oauth2($client);
    }

    private function getClient(string $token = null): Google_Client {
        $client = new Google_Client();
        $client->setClientId($this->config['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($this->config['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri($this->config['GOOGLE_OAUTH2_CALLBACK']);
        $client->addScope('https://www.googleapis.com/auth/youtube.readonly');
        $client->addScope('https://www.googleapis.com/auth/userinfo.profile');
        if ($token != null) {
            $client->setAccessToken($this->tokenService->decode($token));
        }
        return $client;
    }
}

?>