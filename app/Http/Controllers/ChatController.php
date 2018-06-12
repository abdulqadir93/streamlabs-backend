<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleLiveChatService;

class ChatController extends Controller
{
    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(GoogleLiveChatService $service)
    {
        $this->service = $service;
    }

    public function get(Request $request, string $id) {
        $response = $this->service->get(
            $this->getToken($request),
            $id,
            $request->query()
        );

        if ($this->service->areStoredMessagesOld($id)) {
            $this->service->removeStoredMessagesForChat($id);
        }

        $this->service->storeFetchedTimeForChat($id);
        if (isset($response['items']) && count($response['items']) > 0) {
            $this->service->storeMessages($response['items']);
        }

        return $response;
    }

    public function getByAuthor(Request $request, string $authorId, string $chatId) {
        $messages = $this->service->getStoredMessages([
            'chatId' => $chatId,
            'author.id' => $authorId
        ]);
        return array_reverse($messages);
    }

    public function insert(Request $request, string $chatId) {
        return $this->service->insertMessage(
            $this->getToken($request),
            $chatId,
            $request->input('text')
        );
    }
}
