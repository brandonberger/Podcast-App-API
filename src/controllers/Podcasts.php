<?php
namespace Controllers;

class Podcasts {

    // Get users podcasts
    public function getUserPodcasts($userId)
    {
        $user = \Models\Users\UsersQuery::create()->findPK($userId);
        $podcasts = $user->getPodcastss();

        $podcastArr = array();

        foreach ($podcasts as $podcast) {
            $podcastArr[] = $podcast->getPodcastId();
        }

        $podcastIdsString = implode(",", $podcastArr);
        $listenNotes = new ListenNotes;
        $result = $listenNotes->getPodcasts($podcastIdsString);
        
        $podcastResultArr = array();
        $podcastResultArr['podcasts'] = array();

        $key = 0;
        foreach ($result as $podcast) {
            $podcastData['key'] = $key;
            $podcastTable = \Models\Podcasts\PodcastsQuery::create()->filterByPodcastId($podcast->id)->findOne();
            $podcastData['podcastRowId'] = $podcastTable->getId();
            $podcastData['podcastId'] = $podcast->id;
            $podcastData['title'] = $podcast->title;
            $podcastData['image'] = $podcast->image;
            $podcastData['thumbnail'] = $podcast->thumbnail;
            array_push($podcastResultArr['podcasts'], $podcastData);
            $key++;
        }

        echo json_encode($podcastResultArr);
    }


    // Get Podcast episodes
    public function getPodcastEpisodes($podcastId)
    {

    }

    // Follow a podcast
    public function followPodcast($userId, $podcastId)
    {
        $podcast = \Models\Podcasts\PodcastsQuery::create()->filterByPodcastId($podcastId)->findOne();
        if (!isset($podcast)) {
            $podcastRowId = $this->addPodcast($podcastId);
        } else {
            $podcastRowId = $podcast->getId();
        }

        if (!$this->isFollowing($userId, $podcastRowId)) {

            $userPodcasts = \Models\UserPodcasts\UserPodcastsQuery::create()
                ->filterByUserId($userId)
                ->filterByPodcastId($podcastRowId)
                ->findOne();

            if (isset($userPodcasts)) {
                $userPodcasts->setFollowing(1);
                $userPodcasts->save();
            } else {
                $userPodcasts = new \Models\UserPodcasts\UserPodcasts();
                $userPodcasts->setUserId($userId);
                $userPodcasts->setPodcastId($podcastRowId);
                $userPodcasts->setFollowing(1);
                $userPodcasts->save();
            }
            return 'success';
        } else {
            return 'User already following';
        }
    }

    // Unfollow Podcast
    public function unfollowPodcast($userId, $podcastRowId)
    {
        $userPodcasts = \Models\UserPodcasts\UserPodcastsQuery::create()
            ->filterByPodcastId($podcastRowId)
            ->filterByUserId($userId)
            ->findOne();
        if (isset($userPodcasts)) {
            if ($userPodcasts->getFollowing()) {
                $userPodcasts->setFollowing(0);
                $userPodcasts->save();
                return 'success';
            } else {
                return 'Error.';
            }
        } else {
            return 'Error.';
        }
    }

    // Check user podcasts for existing podcast
    public function isFollowing($userId, $podcastId = null, $LNPodcastId = null)
    {
        if ($podcastId) {
            $userPodcasts = \Models\UserPodcasts\UserPodcastsQuery::create()
                ->filterByUserId($userId)
                ->filterByPodcastId($podcastId)
                ->findOne();
        } elseif ($LNPodcastId) {
            $podcasts = \Models\Podcasts\PodcastsQuery::create()
                ->filterByPodcastId($LNPodcastId)
                ->findOne();
            if (isset($podcasts)) {
                $podcastRowId = $podcasts->getId();
                $userPodcasts = \Models\UserPodcasts\UserPodcastsQuery::create()
                    ->filterByUserId($userId)
                    ->filterByPodcastId($podcastRowId)
                    ->findOne();
            }
        }

        if ($userPodcasts->getFollowing()) {
            return true;
        } else {
            return false;
        }
    }   

    // Add podcast to db
    public function addPodcast($podcastId)
    {
        $podcast = new \Models\Podcasts\Podcasts();
        $podcast->setPodcastId($podcastId);
        $podcast->save();

        if (isset($podcast)) {
            return $podcast->getId();
        } else {
            return 'Error';
        }
    }

    // Add User Podcast
    public function addUserPodcast($userId, $podcastRowId)
    {
        $userPodcasts = \Models\UserPodcasts\UserPodcastsQuery::create()
            ->filterByPodcastId($podcastRowId)
            ->filterByUserId($userId)
            ->findOne();

        if (!isset($userPodcasts)) {
            $userPodcasts = new \Models\UserPodcasts\UserPodcasts;
            $userPodcasts->setPodcastId($podcastRowId);
            $userPodcasts->setUserId($userId);
            $userPodcasts->save();
        }
    }
}