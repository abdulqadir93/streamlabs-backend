<?php

namespace App\Services;

use App\Services\GoogleBaseService;
use Google_Service_Oauth2_Userinfoplus;

class GoogleUserService extends GoogleBaseService {

    public function get(string $token): array {
        $service = $this->clientService->getOAuth2Service($token);
        return $this->convertUser($service->userinfo->get());
    }

    private function convertUser(Google_Service_Oauth2_Userinfoplus $userInfo): array {
        return [
            'name' => $userInfo->getName(),
            'picture' => $userInfo->getPicture()
        ];
    }
}

?>