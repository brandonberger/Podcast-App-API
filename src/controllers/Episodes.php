<?php
namespace Controllers;

class Episodes {

    public function getPlaylistEpisodes($playlistObj, $limit = null)
    {
        $episodes = $playlistObj->getEpisodes(\Models\Episodes\EpisodesQuery::create()->limit(4));
        return $episodes;
    }

    public function saveEpisode($userId, $episodeId, $podcastId)
    {
        // Check if in episodes table
            // If true, get ID
            // If false, add to table and get ID    
        $episodes = \Models\Episodes\EpisodesQuery::create()
            ->filterByEpisodeId($episodeId)
            ->findOne();
        if (!isset($episodes)) {

            // Check if podcast in podcasts table
            $podcasts = \Models\Podcasts\PodcastsQuery::create()
                ->filterByPodcastId($podcastId)
                ->findOne();
            if (!isset($podcasts)) {
                $podcasts = new \Models\Podcasts\Podcasts;
                $podcasts->setPodcastId($podcastId);
                $podcasts->save();
            }
            $podcastRowId = $podcasts->getId();

            $episodes = new \Models\Episodes\Episodes;
            $episodes->setEpisodeId($episodeId);
            $episodes->setPodcastId($podcastRowId);
            $episodes->save();
        }
        $episodeRowId = $episodes->getId();
                    
        // Check if in user episodes table
        $userEpisodes = \Models\Episodes\UserEpisodesQuery::create()
            ->filterByUserId($userId)
            ->filterByEpisodeId($episodeRowId)
            ->findOne();
        
        if (isset($userEpisodes)) {
            // If true, check if saved
            if ($userEpisodes->getSaved()) {
                // If true, error
                echo json_encode('Error');
            } else {
                // If false, mark as saved
                $userEpisodes->setSaved(1);
                $userEpisodes->save();
            }
        } else {
            //  If false, add to table
            $userEpisodes = new \Models\Episodes\UserEpisodes;
            $userEpisodes->setUserId($userId);
            $userEpisodes->setEpisodeId($episodeRowId);
            $userEpisodes->setSaved(1);
            $userEpisodes->save();
        }

        // Add Podcast to UserPodcasts
        $podcastsController = new Podcasts;
        $podcastsController->addUserPodcast($userId, $podcastRowId);

        echo json_encode('Episode Saved.');
    }


    public function is_saved($userId, $episodeId)
    {
        $episodes = \Models\Episodes\EpisodesQuery::create()
            ->filterByEpisodeId($episodeId)
            ->findOne();
        if (isset($episodes)) {
            $episodeRowId = $episodes->getId();
            $userEpisodes = \Models\Episodes\UserEpisodesQuery::create()
            ->filterByUserId($userId)
            ->filterByEpisodeId($episodeRowId)
            ->findOne();
            if (isset($userEpisodes)) {
                if ($userEpisodes->getSaved()) {
                    return true;
                }
            }
        }
        return false;
    }
}