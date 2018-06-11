<?php

namespace App\Services;

use App\Services\GoogleClientService;

class GoogleBaseService {
    protected $clientService;

    public function __construct(GoogleClientService $clientService) {
        $this->clientService = $clientService;
    }
}

?>