<?php

namespace App\Http\Controllers;

use App\Services\GoogleUserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(GoogleUserService $service)
    {
        $this->service = $service;
    }

    public function get(Request $request) {
        return $this->service->get($this->getToken($request));
    }
}
