<?php

namespace App\Http\Controllers;

use App\Services\GoogleVideoService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(GoogleVideoService $service)
    {
        $this->service = $service;
    }

    public function list(Request $request) {
        return $this->service->search($this->getToken($request), $request->query());
    }

    public function get(Request $request, string $id) {
        return $this->service->get($this->getToken($request), $id);
    }
}

?>