<?php

namespace App\Services;

use App\Services\GoogleBaseService;
use Google_Collection;
use Google_Service_YouTube_SearchResult;
use Google_Service_YouTube_Video;
use Google_Service_YouTube_VideoSnippet;
use Google_Service_YouTube_VideoLiveStreamingDetails;
use Google_Service_YouTube_ThumbnailDetails;

class GoogleVideoService extends GoogleBaseService {

    public function search(string $token, array $queryParams) {
        $query = array(
            'type' => 'video',
            'eventType' => 'live'
        );

        if (isset($queryParams['nextPageToken'])) {
            $query['pageToken'] = $queryParams['nextPageToken'];
        }
        else if (isset($queryParams['q'])) {
            $query['q'] = $queryParams['q'];
        }

        $service = $this->clientService->getYoutubeService($token);
        $result = $service->search->listSearch('id,snippet', $query);

        return [
            'nextPageToken' => $result->getNextPageToken(),
            'items' => $this->getVideosArrayFromResult($result, [$this, 'convertVideoFromSearchResult'])
        ];
    }

    public function get(string $token, string $id) {
        $service = $this->clientService->getYoutubeService($token);
        $filters = ['id' => $id];
        $result = $service->videos->listVideos('snippet,liveStreamingDetails', $filters);
        $videoArr = $this->getVideosArrayFromResult($result, [$this, 'convertVideoFromVideoObject']);
        return array_pop($videoArr);
    }

    private function getVideosArrayFromResult(Google_Collection $result, callable $callback) {
        $videos = [];
        foreach ($result->getItems() as $video) {
            $videos[] = $callback($video);
        }
        return $videos;
    }

    private function convertVideoFromSearchResult(Google_Service_YouTube_SearchResult $model) {
        return array_merge(
            ['id' => $model->getId()->getVideoId()],
            $this->convertSnippet($model->getSnippet()));
    }

    private function convertVideoFromVideoObject(Google_Service_YouTube_Video $model) {
        return array_merge(
            ['id' => $model->getId()],
            $this->convertSnippet($model->getSnippet()),
            $this->convertLiveStreamingDetails($model->getLiveStreamingDetails())
        );
    }

    /**
     * @param Google_Service_YouTube_VideoSnippet|Google_Service_YouTube_SearchResultSnippet $snippet
     */
    private function convertSnippet($snippet) {
        return [
            'title' => $snippet->getTitle(),
            'description' => $snippet->getDescription(),
            'publishedAt' => $snippet->getPublishedAt(),
            'thumbnail' => $this->convertThumbnail($snippet->getThumbnails())
        ];
    }

    private function convertThumbnail(Google_Service_YouTube_ThumbnailDetails $thumbnailDetails) {
        $thumbnail = $thumbnailDetails->getDefault();
        return [
            'width' => $thumbnail->getWidth(),
            'height' => $thumbnail->getHeight(),
            'url' => $thumbnail->getUrl()
        ];
    }

    private function convertLiveStreamingDetails(Google_Service_YouTube_VideoLiveStreamingDetails $liveStreamingDetails) {
        return [
            'chatId' => $liveStreamingDetails->getActiveLiveChatId()
        ];
    }
}

?>