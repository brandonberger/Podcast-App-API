<?php
namespace Controllers;

class ListenNotes {

    const LISTEN_NOTES_API_KEY = '---';
    const LISTEN_NOTES_API_URL = 'https://listen-api.listennotes.com/api/v2/';


    public function searchEpisodes($searchQuery)
    {
        $response = \Unirest\Request::get(self::LISTEN_NOTES_API_URL."search?q=".$searchQuery."&type=episode",
            array( "X-ListenAPI-Key" => self::LISTEN_NOTES_API_KEY )
        );
        echo json_encode($response->raw_body);
    }

    public function searchPodcasts($searchQuery)
    {
        $response = \Unirest\Request::get(self::LISTEN_NOTES_API_URL."search?q=".$searchQuery."&type=podcast",
            array("X-ListenAPI-Key" => self::LISTEN_NOTES_API_KEY)
        );

        $podcastSearchResultsArr = array();
        $podcastSearchResultsArr['results'] = array();
        $key = 0;

        $podcastSearchResponse = $response->body->results;

        foreach ($podcastSearchResponse as $podcast) {
            $podcastSearchData['key'] = $key;
            $podcastSearchData['id'] = $podcast->id;
            $podcastSearchData['image'] = $podcast->image;
            $podcastSearchData['title'] = $podcast->title_original;
            array_push($podcastSearchResultsArr['results'], $podcastSearchData);
            $key++;
        }

        echo json_encode($podcastSearchResultsArr);

    }

    // Get Single Podcast
    public function getPodcast($podcastId, $userId = null)
    {
        $response = \Unirest\Request::get(self::LISTEN_NOTES_API_URL."/podcasts/".$podcastId."?sort=recent_first",
            array("X-ListenAPI-Key" => self::LISTEN_NOTES_API_KEY)
        );

        $podcastArr = array();
        $podcastArr['podcast'] = array();
        $podcast = $response->body;
        $podcastData['id'] = $podcast->id;
        $podcastData['description'] = $podcast->description;
        $podcastData['title'] = $podcast->title;
        $podcastData['publisher'] = $podcast->publisher;
        $podcastData['image'] = $podcast->image;
        $podcastData['total_episodes'] = $podcast->total_episodes;

        $podcastsController = new Podcasts;
        $podcastData['is_following'] = $podcastsController->isFollowing($userId, null, $podcastId);

        array_push($podcastArr['podcast'], $podcastData);
        echo json_encode($podcastArr);
    }

    public function getPodcastEpisodes($podcastId, $userId = null)
    {
        $episodesArr = array();
        $episodesArr['episodes'] = array();
        $next_episode_pub_date = null;
        $key = 0;

        for ($i = 1; $i <= 2; $i++) {
            $response = \Unirest\Request::get(self::LISTEN_NOTES_API_URL."/podcasts/".$podcastId."?next_episode_pub_date=".$next_episode_pub_date."&sort=recent_first",
                array("X-ListenAPI-Key" => self::LISTEN_NOTES_API_KEY)
            );

            $episodesResult = $response->body->episodes;

            foreach ($episodesResult as $episode) {
                $episodeData['key'] = $key;
                $episodeData['podcastId'] = $podcastId;
                $episodeData['id'] = $episode->id;
                $episodeData['audio'] = $episode->audio;
                $episodeData['image'] = $episode->image;
                $episodeData['title'] = $episode->title;
                $episodeData['thumbnail'] = $episode->thumbnail;
                $episodeData['description'] = $episode->description;
                $episodeData['audio_length_sec'] = $episode->audio_length_sec;

                // Check if episode saved.
                $episodesController = new Episodes;
                $episodeData['is_saved'] = $episodesController->is_saved($userId, $episode->id);

                array_push($episodesArr['episodes'], $episodeData);
                $key++;
            }

            $next_episode_pub_date = $response->body->next_episode_pub_date;
        }
        echo json_encode($episodesArr);
    }

    // Get Multiple Podcasts
    public function getPodcasts($podcastIds)
    {
        $response = \Unirest\Request::post(self::LISTEN_NOTES_API_URL."/podcasts",
            array(
                "X-ListenAPI-Key" => self::LISTEN_NOTES_API_KEY,
                "Content-Type" => "application/x-www-form-urlencoded"    
            ),
            \Unirest\Request\Body::form(array(
                "show_latest_episodes" => "1",
                "ids" => $podcastIds
            ))
        );

        return $response->body->podcasts;  
    }

    // Get First 4 Podcast Images
    // TODO
    public function getPlaylistCoverImages(array $episodeIds)
    {
        // Stringify episodeIds
        $episodeIds = implode(',', $episodeIds);

        $response = \Unirest\Request::post(self::LISTEN_NOTES_API_URL."/episodes",
            array(
                "X-ListenAPI-Key" => self::LISTEN_NOTES_API_KEY,
                "Content-Type" => "application/x-www-form-urlencoded"
            ),
            \Unirest\Request\Body::form(array(
                "ids" => $episodeIds,
            ))
        );

        $episodes = $response->body->episodes;

        $thumbnails = array();
        foreach ($episodes as $episode) {
            $thumbnails[] = $episode->thumbnail;
        }

        return $thumbnails;
    }

}

