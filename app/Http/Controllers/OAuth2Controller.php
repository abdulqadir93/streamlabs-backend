<?php

namespace App\Http\Controllers;
use App\Services\GoogleClientService;
use Laravel\Lumen\Http\Redirector;
use Illuminate\Http\Request;

class OAuth2Controller extends Controller
{
    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(GoogleClientService $service)
    {
        $this->service = $service;
    }

    public function login(Redirector $redirect) {
        $redirect->to($this->service->getOAuthUrl())->send();
    }

    public function loginCallback(Request $request, Redirector $redirect) {
        $query = $request->query();
        $redirect->to(env('FRONTEND_URI') . '/login?code=' . $query['code'])->send();
    }

    public function getToken(Request $request) {
        return ['access_token' => $this->service->getToken($request->input('code'))];
    }
}
