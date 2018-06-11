<?php

namespace App\Services;

use App\Services\GoogleBaseService;
use App\Models\ChatMessage;
use App\Models\LiveChat;
use Google_Service_YouTube_LiveChatMessageListResponse;
use Google_Service_YouTube_LiveChatMessage;
use Google_Service_YouTube_LiveChatMessageSnippet;
use Google_Service_YouTube_LiveChatMessageAuthorDetails;
use Carbon\Carbon;

class GoogleLiveChatService extends GoogleBaseService {

    public function get(string $token, string $chatId, $filters = []) {
        $queryFilters = [];
        if (isset($filters['nextPageToken'])) {
            $queryFilters['pageToken'] = $filters['nextPageToken'];
        }

        $service = $this->clientService->getYoutubeService($token);
        $result = $service->liveChatMessages->listLiveChatMessages(
            $chatId,
            'id,snippet,authorDetails',
            $queryFilters
        );

        return [
            'nextPageToken' => $result->getNextPageToken(),
            'pollingIntervalMillis' => $result->getPollingIntervalMillis(),
            'items' => $this->getMessagesFromResult($result)
        ];
    }

    // TODO: Investigate if the writes are synchronous!
    public function storeMessages(array $messages) {
        return ChatMessage::raw( function ( $collection ) use ($messages) {
            $operations = [];
            foreach($messages as $message) {
                $operations[] = [
                    'updateOne' => [
                        [ 'id' => $message['id'] ],
                        [ '$set' => $message ],
                        [ 'upsert' => true ]
                    ]
                ];
            }
            return $collection->bulkWrite($operations);
        });
    }

    public function storeFetchedTimeForChat(string $id) {
        return LiveChat::where('id', $id)->update(
            ['id' => $id, 'lastFetchedAt' => Carbon::now()->format('Y-m-d H:i:s')],
            ['upsert' => true]
        );
    }

    public function areStoredMessagesOld(string $chatId): bool {
        $liveChat = LiveChat::where('id', $chatId)->get()->first();
        if (is_null($liveChat)) {
            return false;
        }
        return Carbon::now()->diffInSeconds($liveChat->lastFetchedAt) > 60;
    }

    public function removeStoredMessagesForChat(string $chatId) {
        return ChatMessage::where('chatId', $chatId)->delete();
    }

    public function getStoredMessages(array $filters) {
        $model = (new ChatMessage)->newQuery();
        foreach($filters as $key => $value) {
            $model->where($key, $value);
        }
        return $model->orderBy('publishedAt', 'desc')->take(500)->get()->toArray();
    }

    private function getMessagesFromResult(Google_Service_YouTube_LiveChatMessageListResponse $result) {
        $messages = [];
        foreach($result->getItems() as $message) {
            $messages[] = $this->convertMessage($message);
        }
        return $messages;
    }

    private function convertMessage(Google_Service_YouTube_LiveChatMessage $message) {
        return array_merge(
            [
                'id' => $message->getId(),
                'author' => $this->convertAuthor($message->getAuthorDetails())
            ],
            $this->convertSnippet($message->getSnippet())
        );
    }

    private function convertSnippet(Google_Service_YouTube_LiveChatMessageSnippet $snippet) {
        return [
            'publishedAt' => $snippet->getPublishedAt(),
            'hasDisplayContent' => $snippet->getHasDisplayContent(),
            'displayMessage' => $snippet->getDisplayMessage(),
            'chatId' => $snippet->getLiveChatId()
        ];
    }

    private function convertAuthor(Google_Service_YouTube_LiveChatMessageAuthorDetails $author) {
        return [
            'id' => $author->getChannelId(),
            'displayName' => $author->getDisplayName(),
            'profileImageUrl' => $author->getProfileImageUrl()
        ];
    }
}

?>