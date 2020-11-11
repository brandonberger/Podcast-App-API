<?php
namespace Controllers;
use Propel\Runtime\ActiveQuery\Criteria;


class Users {
    use \Library\Logging;

    // Add User needs validation and stuff
    public function addUser($email, $first_name, $last_name, $google_id, $picture = null)
    {
        if ($this->isUser($email)) {    
           return 'An account with that email already exists.';
        }

        $user = new \Models\Users\Users();
        $user->setEmail($email);
        $user->setFirstName($first_name);
        $user->setLastName($last_name);
        $user->setGoogleId($google_id);
        $user->setImageUrl($picture ?: null);
        $user->save();

        $settings = new \Models\UserSettings\UserSettings();
        $settings->setUserId($user->getId());
        $settings->setPlanId(1);
        $settings->setAutoPlay(1);
        $settings->save();

        $audio_player_settings = new \Models\AudioPlayerSettings\AudioPlayerSettings();
        $audio_player_settings->setUserId($user->getId());
        $audio_player_settings->setShuffle(0);
        $audio_player_settings->save(); 

        return 'User successfully created.';
    }

    // Deactivate a user
    public function deactivateUser($email, $userId)
    {
        if ($this->isUser($email, $userId)) {
            if ($this->isActive($email, $userId)) {
                $user = \Models\Users\UsersQuery::create()->findPk($userId);
                $oldValues = clone $user;
                $user->setActive(0);
                $this->logUpdate($oldValues, $user, 'Users');
                $user->save();

                exit;

                return 'User successfully deactivated.';
            } else {
                return 'User already deactivated';
            }
        } else {
            return 'User does not exist.';
        }
    }

    // Activate user
    public function activateUser($email, $userId)
    {
        if ($this->isUser($email, $userId)) {
            if (!$this->isActive($email, $userId)) {
                $user = \Models\Users\UsersQuery::create()->findPk($userId);
                $user->setActive(1);
                $user->save();



                return 'User successfully activated';
            } else {
                return 'User already active';
            }
        } else {
            return 'User does not exist.';
        }
    }

    // Check if user exists
    public function isUser($email, $userId = null)
    {
        if ($userId) {
            $user = \Models\Users\UsersQuery::create()->filterByEmail($email)->filterById($userId)->findOne();
        } else {
            $user = \Models\Users\UsersQuery::create()->filterByEmail($email)->findOne();
        }

        return count($user);
    }

    // Check is user active
    public function isActive($email, $userId)
    {
        $user = \Models\Users\UsersQuery::create()->filterByEmail($email)->filterById($userId)->findOne();
        return $user->getActive();
    }

    // Get all Users
    public function getUsers()
    {
        $users = \Models\Users\UsersQuery::create()->find();
        $userArr = array();
        $userArr['users'] = array();

        foreach ($users as $user) {
            $userData['id'] = $user->getId();
            $userData['email'] = $user->getEmail();
            $userData['first_name'] = $user->getFirstName();
            $userData['last_name'] = $user->getLastName();
            $userData['image_url'] = $user->getImageUrl();
            array_push($userArr['users'], $userData);
        }

        if (empty($userArr['users'])) {
            $userArr['users'] = (object) $userArr['users'];
        }

        return json_encode($userArr);
    }


    // Get active Users
    public function getActiveUsers()
    {
        $users = \Models\Users\UsersQuery::create()->filterByActive(1)->find();
        $userArr = array();
        $userArr['users'] = array();

        foreach ($users as $user) {
            $userData['id'] = $user->getId();
            $userData['email'] = $user->getEmail();
            $userData['first_name'] = $user->getFirstName();
            $userData['last_name'] = $user->getLastName();
            $userData['image_url'] = $user->getImageUrl();
            array_push($userArr['users'], $userData);
        }
        
        if (empty($userArr['users'])) {
            $userArr['users'] = (object) $userArr['users'];
        }

        return json_encode($userArr);
    }

    // Counts how many users
    public function countUsers($activeOnly = null)
    {   
        if ($activeOnly) {
            $users = \Models\Users\UsersQuery::create()->filterByActive(1)->find();
        } else {
            $users = \Models\Users\UsersQuery::create()->find();
        }

        return count($users);
    }

    // Searches Users
    public function searchUsers($query)
    {
        $userQuery = $query . '%';
        
        $users = \Models\Users\UsersQuery::create()
            ->filterByEmail($userQuery, Criteria::LIKE)
            ->filterByActive(1)
            ->find()
            ->limit(35);
        $userArr = array();
        $userArr['users'] = array();

        foreach ($users as $user) {
            $userData['id'] = $user->getId();
            $userData['email'] = $user->getEmail();
            $userData['name'] = $user->getFirstName() . ' ' . $user->getLastName();
            array_push($userArr['users'], $userData);
        }

        if (empty($userArr['users'])) {
            $userArr['users'] = (object) $userArr['users'];
        }

        return json_encode($userArr, JSON_PRETTY_PRINT);
    }

    // Get Specific User
    public function getUser($userId)
    {
        $user = \Models\Users\UsersQuery::create()->findPK($userId);

        $userArr = array();
        $userArr['user'] = array();

        $userData['id'] = $user->getId();
        $userData['first_name'] = $user->getFirstName();
        $userData['last_name'] = $user->getLastName();
        $userData['email'] = $user->getEmail();
        $userData['active'] = $user->getActive();
        
        $userSettings = $user->getUserSettingss();
        $userData['settings']['id'] = $userSettings[0]->getId();
        $userData['settings']['auto_play'] = $userSettings[0]->getAutoPlay();
        $userData['settings']['plan_id'] = $userSettings[0]->getPlanId();

        $userPlan = \Models\Plans\PlansQuery::create()->findPK($userSettings[0]->getPlanId());
        $userData['settings']['plan']['id'] = $userPlan->getId();
        $userData['settings']['plan']['name']  = $userPlan->getName();
        $userData['settings']['plan']['sub_playlists'] = $userPlan->getSubPlaylists();
        $userData['settings']['plan']['automated_tagging'] = $userPlan->getAutomatedTagging();
        $userData['settings']['plan']['playlist_max'] = $userPlan->getPlaylistMax();

        array_push($userArr['user'], $userData);
        return json_encode($userData);
    }

    // Get User Settings
    public function getUserSettings($userId)
    {
        $userSettings = \Models\UserSettings\UserSettingsQuery::create()->filterByUserId($userId)->findOne();
        $userSettingArr = array();
        $userSettingArr['settings'] = array();
        $userData['id'] = $userSettings->getId();
        $userData['auto_play'] = $userSettings->getAutoPlay();
        array_push($userSettingArr['settings'], $userData);

        return json_encode($userSettingArr);
    }

    // Get User Plan
    public function getUserPlan($userId)
    {
        $userSettings = \Models\UserSettings\UserSettingsQuery::create()->filterByUserId($userId)->findOne();
        $userPlan = $userSettings->getPlans();
        $userPlanArr = array();
        $userPlanArr['plan'] = array();
        $planData['name'] = $userPlan->getName();
        $planData['sub_playlists'] = $userPlan->getSubPlaylists();
        $planData['automated_tagging'] = $userPlan->getAutomatedTagging();
        $planData['playlist_max'] = $userPlan->getPlaylistMax();
        array_push($userPlanArr['plan'], $planData);
        return json_encode($userPlanArr);
    }

    // Gets AudioPlayer Settings
    public function getUserAudioPlayerSettings($userId)
    {
        $audioPlayerSettings = \Models\AudioPlayerSettings\AudioPlayerSettingsQuery::create()->filterByUserId($userId)->findOne();
        $playerSettingsArr = array();
        $playerSettingsArr['audio_player_settings'] = array();
        $audioPlayerData['shuffle'] = $audioPlayerSettings->getShuffle();
        $audioPlayerData['repeat'] = $audioPlayerSettings->getRepeat();
        array_push($playerSettingsArr['audio_player_settings'], $audioPlayerData);
        return json_encode($playerSettingsArr);
    }

    // Updates User Settings
    public function updateUserSettings($userId, $updates)
    {
        $userSettings = \Models\UserSettings\UserSettingsQuery::create()->filterByUserId($userId)->findOne();
        $auto_play = $updates['auto_play'];
        $plan_id = $updates['plan_id'];
        $userSettings->setAutoPlay(
            $auto_play ?? null
        );
        $userSettings->setPlanId(
            $plan_id ?? null
        );
        $userSettings->save();
    }

    // Update audio player settings
    public function updateUserAudioPlayerSettings($userId, $updates)
    {
        $audioPlayerSettings = \Models\AudioPlayerSettings\AudioPlayerSettingsQuery::create()->filterByUserId($userId)->findOne();

        $shuffle = $updates['shuffle'];
        $repeat = $updates['repeat'];

        $audioPlayerSettings->setShuffle(
            $shuffle ?? null
        );

        $audioPlayerSettings->save();
    }

    // Follow User
    // $followerId - Current User
    // $followingId - User to follow
    public function followUser($followerId, $followingId)
    {
        $userRelations = \Models\Users\UserRelationsQuery::create()->filterByFollowerId($followerId)->filterByFollowingId($followingId)->findOne();
        if (!count($userRelations)) {
            $userRelations = new \Models\Users\UserRelations();
            $userRelations->setFollowerId($followerId);
            $userRelations->setFollowingId($followingId);
            $userRelations->save();
            return 'Success';
        } else {
            return 'Already Following User';
        }
    }


    // Unfollow User
    // $followerId - Current User
    // $followingId - User to follow
    public function unfollowUser($followerId, $followingId)
    {
        $userRelations = \Models\Users\UserRelationsQuery::create()->filterByFollowerId($followerId)->filterByFollowingId($followingId)->findOne();
        if (count($userRelations)) {
            $userRelations->delete();
            return 'Success';
        } else {
            return 'Error';
        }
    }


    // Get followers
    public function getFollowers($userId)
    {
        $followers = \Models\Users\UserRelationsQuery::create()->filterByFollowingId($userId)->find();
        $followersArr = array();
        $followersArr['followers'] = array();

        foreach ($followers as $follower) {
            $user = \Models\Users\UsersQuery::create()->filterByActive(1)->filterById($follower->getFollowerId())->findOne();

            $followerData['id'] = $user->getId();
            $followerData['email'] = $user->getEmail();
            $followerData['firstName'] = $user->getFirstName();
            $followerData['lastName'] = $user->getLastName();
            $followerData['fullName'] = $user->getFirstName() . ' ' . $user->getLastName();
            array_push($followersArr['followers'], $followerData);
        }

        if (empty($followersArr['followers'])) {
            $followersArr['followers'] = (object) $followersArr['followers'];
        }

        return json_encode($followersArr);
    }

    // Get following
    public function getFollowing($userId)
    {
        $followings = \Models\Users\UserRelationsQuery::create()->filterByFollowerId($userId)->find();
        $followingsArr = array();
        $followingsArr['followings'] = array();

        foreach ($followings as $following) {
            $user = \Models\Users\UsersQuery::create()->filterByActive(1)->filterById($following->getFollowingId())->findOne();

            $followingData['id'] = $user->getId();
            $followingData['email'] = $user->getEmail();
            $followingData['firstName'] = $user->getFirstName();
            $followingData['lastName'] = $user->getLastName();
            $followingData['fullName'] = $user->getFirstName() . ' ' . $user->getLastName();
            array_push($followingsArr['followings'], $followingData);
        }

        if (empty($followingsArr['followings'])) {
            $followingsArr['followings'] = (object) $followingsArr['followings'];
        }

        return json_encode($followingsArr);
    }

    // Count followers
    public function countFollowers($userId)
    {
        $users = \Models\Users\UsersQuery::create()->findPK($userId);
        $count = $users->countUserRelationssRelatedByFollowingId();
        
        return $count;
    }

    // Count following
    public function countFollowings($userId)
    {
        $users = \Models\Users\UsersQuery::create()->findPK($userId);
        $count = $users->countUserRelationssRelatedByFollowerId();
        return $count;
    }

}
