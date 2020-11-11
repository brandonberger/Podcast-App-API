<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
$loader = require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
$config = new \Config\BuildConfig($loader);
require_once $_SERVER['DOCUMENT_ROOT'].'/generated-conf/config.php';
require_once 'vendor/autoload.php';



$klein = new \Klein\Klein();
define('USER_ID', 1);
define('API_KEY', '---');
define('CLIENT_ID', '---');

$klein->respond('GET', '/', function($request, $response, $service, $app) {
    echo '<link rel="stylesheet" href="public/css/api.css" />';
    $service->render('templates/layouts/dashboard.php');
    echo '<script src="public/js/api.js"></script>';
});


$klein->with('/api', function() use ($klein) {
    ///////////
    // Users //
    ///////////

    $klein->respond('POST', '/authenticateSession', function($request, $response, $service, $app) {
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, TRUE);

        if (isset($data['userSessionId'])) {
            // Decode Hash
            $hash = substr($data['userSessionId'], 0, -23);
            $salt = substr($data['userSessionId'], strlen($hash)+14, -1);
        
            $session = \Models\Users\UserSessionsQuery::create()->filterByHash($hash)->filterBySalt($salt)->findOne();
            
            if (isset($session)) {
                return true;
            } else {
                return false;
            }
        }
    });

    // Login
    $klein->respond('POST', '/authenticateUser', function($request, $response, $service, $app) {
        $client = new Google_Client(['client_id' => CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, TRUE);
        
        $googlePayload = $client->verifyIdToken($data['idtoken']);

        if ($googlePayload) {
            $userid = $googlePayload['sub'];
            $userEmail = $googlePayload['email'];
            $user = \Models\Users\UsersQuery::create()->filterByEmail($userEmail)->findOne();

            if (isset($user)) {
                // Login Session
                $session = new \Models\Users\UserSessions();

                $hash = hash('SHA3-512', $user->getEmail().$user->getId());
                $salt = substr(md5(uniqid(rand(), true)), 0, 9);

                $session->setUserId($user->getId());
                $session->setExpDate('2020-02-05');
                $session->setHash(substr($hash, 0, -14));
                $session->setSalt(substr($salt, 0, -1));
                $session->save();

                $returnData['sessionId'] = $hash.$salt;
                $returnData['userId'] = $user->getId();

                return json_encode($returnData);
            } else {
                $users = new \Controllers\Users();
                $users = $users->addUser($googlePayload['email'], $googlePayload['given_name'], $googlePayload['family_name'], $googlePayload['sub'], $googlePayload['picture']);
                if ($users) {
                    $_SESSION['token'] = $user->getGoogleId();
                    return 'success';
                }
            }
        } else {
            return 'Failed';
        }
    });

    // // Logout
    // $klein->respond('POST', '/authenticateUser', function($request, $response, $service, $app) {


    // });


    // Add new user
    // Needs validation, of course.
    $klein->respond('POST', '/addUser', function($request, $response, $service, $app) {
        $users = new \Controllers\Users();
        $users = $users->addUser($_POST['email'], $_POST['first_name'], $_POST['last_name']);
        return $users;
    });

    // Deactivate User
    $klein->respond('POST', '/deactivateUser', function($request, $response, $service, $app) {
        $users = new \Controllers\Users();
        $users = $users->deactivateUser($_POST['email'], $_POST['user_id']);
        return $users;
    });

    // Activate user
    $klein->respond('POST', '/activateUser', function($request, $response, $service, $app) {
        $users = new \Controllers\Users();
        $users = $users->activateUser($_POST['email'], $_POST['user_id']);
        return $users;
    });

    // Get all users, maybe needed who knows.
    $klein->respond('GET', '/getUsers', function($request, $response, $service) {
       $users = new \Controllers\Users();
       $users = $users->getUsers();
       return $users;
    });

    // Get active users
    $klein->respond('GET', '/getActiveUsers', function($request, $response, $service) {
        $users = new \Controllers\Users();
        $users = $users->getActiveUsers();
        return $users;
    });

    // Get all users, maybe needed who knows.
    $klein->respond('POST', '/getUser/[:userId]', function($request, $response, $service) {
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, TRUE);

        $session = new \Controllers\UserSessions();
        if ($session->authenticateUser($request->userId, $data['userSessionId'])) {
            if ($request->headers()['Api-Key'] != API_KEY) {
                return json_encode("WAT ARE YOU DOING?");
            }
    
           $users = new \Controllers\Users();
           $users = $users->getUser($request->userId);
           return $users;
        } else {
            return json_encode('Bad actor');
        }
    });

    // Count All Users
    $klein->respond('GET', '/countUsers/[:activeOnly]?', function($request, $response, $service) {
        $users = new \Controllers\Users();
        $users = $users->countUsers($request->activeOnly);
        return $users;
    });

    // Search Users
    $klein->respond('POST', '/searchUsers', function($request, $response, $service, $app) {
        $users = new \Controllers\Users();
        $users = $users->searchUsers($_POST['query']);
        return  $users;
    });

    // Get User Settings
    $klein->respond('GET', '/getUserSettings/[:userId]', function($request, $response, $service) {
        $users = new \Controllers\Users();
        $users = $users->getUserSettings($request->userId);
        return $users;
    });

    // Update User Settings
    $klein->respond('POST', '/updateUserSettings/[:userId]', function($request, $response, $service) {
        $users = new \Controllers\Users();
        $users = $users->updateUserSettings($request->userId, $_POST);
        return $users;
    });

    // Get User Plan
    $klein->respond('GET', '/getUserPlan/[:userId]', function($request, $response, $service) {
        $users = new \Controllers\Users();
        $users = $users->getUserPlan($request->userId);
        return $users;
    });

    // Get User Audio Player Settings
    $klein->respond('GET', '/getUserAudioPlayerSettings/[:userId]', function($request, $response, $service) {
        $users = new \Controllers\Users();
        $users = $users->getUserAudioPlayerSettings($request->userId);
        return $users;
    });

    // Update User Audio Player Settings
    $klein->respond('POST', '/updateUserAudioPlayerSettings/[:userId]', function($request, $response, $service) {
        $users = new \Controllers\Users();
        $users = $users->updateUserAudioPlayerSettings($request->userId, $_POST);
        return $users;
    });      

    // followUser
    $klein->respond('POST', '/followUser', function($request, $response, $service) {
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, TRUE);

        $users = new \Controllers\Users();
        $users = $users->followUser($data['followerId'], $data['followingId']);
        return $users;
    });    

    //unfollowUser
    $klein->respond('POST', '/unfollowUser', function($request, $response, $service) {
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, TRUE);

        $users = new \Controllers\Users();
        $users = $users->unfollowUser($data['followerId'], $data['followingId']);
        return $users;
    });  

    // Get followers
    $klein->respond('GET', '/getFollowers/[:userId]', function($request, $response, $service) {
        $users = new \Controllers\Users();
        $users = $users->getFollowers($request->userId);
        return $users;
    });  

    // Get following
    $klein->respond('GET', '/getFollowing/[:userId]', function($request, $response, $service) {
        $users = new \Controllers\Users();
        $users = $users->getFollowing($request->userId);
        return $users;
    });  

    // Count Followers
    $klein->respond('GET', '/countFollowers/[:userId]', function($request, $response, $service) {
        $users = new \Controllers\Users();
        $users = $users->countFollowers($request->userId);
        return $users;
    });  

    // Count Followings
    $klein->respond('GET', '/countFollowings/[:userId]', function($request, $response, $service) {
        $users = new \Controllers\Users();
        $users = $users->countFollowings($request->userId);
        return $users;
    });  

    

    

    //////////
    // Tags //
    //////////

    // Get All Tags
    $klein->respond('GET', '/getTags', function($request, $response, $service) {
        $tags = new \Controllers\Tags();
        $tags = $tags->getTags();
        return $tags;
    });

    // Add Tag - Will probably never be used anywhere
    $klein->respond('POST', '/addTag', function($request, $response, $service) {
        $tags = new \Controllers\Tags();
        $tags = $tags->addTag($_POST['tag']);
        return $tags;
    });

    // Add Tag to Playlist
    $klein->respond('POST', '/addPlaylistTag', function($request, $response, $service) {
        $tags = new \Controllers\Tags();
        $tags = $tags->addPlaylistTag($_POST['playlistId'], $_POST['tag']);
        return $tags;
    });

    // Add tag to Episode 
    $klein->respond('POST', '/addEpisodeTag', function($request, $response, $service) {
        $tags = new \Controllers\Tags();
        $tags = $tags->addEpisodeTag($_POST['episodeId'], $_POST['tag']);
        return $tags;
    });

    // Remove Tag from Playlist
    $klein->respond('POST', '/removePlaylistTag', function($request, $response, $service) {
        $tags = new \Controllers\Tags();
        $tags = $tags->removePlaylistTag($_POST['playlistId'], $_POST['tagId']);
        return $tags;
    });

    // Remove Tag from Episode
    $klein->respond('POST', '/removeEpisodeTag', function($request, $response, $service) {
        $tags = new \Controllers\Tags();
        $tags = $tags->removeEpisodeTag($_POST['episodeId'], $_POST['tagId']);
        return $tags;
    });



    ///////////////
    // Playlists //
    ///////////////

    // Get User Playlists
    $klein->respond('POST', '/getUserPlaylists/[:userId]', function($request, $response, $service) {

        $payload = file_get_contents('php://input');
        $data = json_decode($payload, TRUE);

        $session = new \Controllers\UserSessions();
        if ($session->authenticateUser($request->userId, $data['userSessionId'])) {
            $playlists = new \Controllers\Playlists();
            $playlists = $playlists->getUserPlaylists($request->userId);
            return $playlists;
        } else {
            return json_encode('Bad actor');
        }
    });

    // Get All Playlists
    $klein->respond('GET','/getPlaylists', function ($request, $response, $service) {
        $playlists = new \Controllers\Playlists();
        $playlists = $playlists->getPlaylists();
        return $playlists;
    });

    
    ///////////////
    // Podcasts ///
    ///////////////

    // Get Saved Podcasts
    $klein->respond('GET','/getUserPodcasts/[:userId]', function ($request, $response, $service) {
        $podcasts = new \Controllers\Podcasts();
        $podcasts = $podcasts->getUserPodcasts($request->userId);
        return $podcasts;
    });

    // Follow Podcast
    $klein->respond('POST','/followPodcast', function ($request, $response, $service, $app) {
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, TRUE);

        $podcasts = new \Controllers\Podcasts();
        $podcasts = $podcasts->followPodcast($data['user_id'], $data['podcast_id']);
        return $podcasts;
    });


    // unfollow Podcast
    $klein->respond('POST','/unfollowPodcast', function ($request, $response, $service, $app) {
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, TRUE);

        $podcasts = new \Controllers\Podcasts();
        $podcasts = $podcasts->unfollowPodcast($data['user_id'], $data['podcast_row_id']);
        return $podcasts;
    });



    // Only pulls 10 at max, need to make multiple loops for 10+
    $klein->respond('GET', '/getPlaylistEpisodes/[:playlistId]', function ($request, $response, $service) {
        $episodes = \Models\SavedEpisodes\SavedEpisodesQuery::create()->filterByPlaylistId($request->param('playlistId'))->find();

        foreach ($episodes as $episode) {
            $episodeIds .= $episode->getEpisodeId().',';
        }

        $episodeIds = substr($episodeIds, 0, -1);

        $uniResponse = Unirest\Request::post("https://listen-api.listennotes.com/api/v2/episodes/", 
            array(
                "X-ListenAPI-Key" => "---",
                "Content-Type" => "application/x-www-form-urlencoded"
            ),
            Unirest\Request\Body::form(array(
                "ids" => $episodeIds
            ))
        );

        echo $uniResponse->raw_body;
        
    });

    // Search Episodes
    $klein->respond('GET', '/searchEpisodes/[:query]', function ($request, $response, $service) {
        $listenNotes = new \Controllers\ListenNotes();
        $episodes = $listenNotes->searchEpisodes($request->query);
        return $episodes;
    });

    // Search Podcasts
    $klein->respond('GET', '/searchPodcasts/[:query]', function ($request, $response, $service) {
        $listenNotes = new \Controllers\ListenNotes();
        $podcasts = $listenNotes->searchPodcasts($request->query);
        return $podcasts;
    });

    // Get Podcast
    $klein->respond('GET', '/getPodcast/[:podcastId]/[:userId]?', function ($request, $response, $service) {
        $listenNotes = new \Controllers\ListenNotes();
        $podcast = $listenNotes->getPodcast($request->podcastId, $request->param('userId', null));
        return $podcast;
    });

    // Get Podcast Episodes
    $klein->respond('GET', '/getPodcastEpisodes/[:podcastId]/[:userId]?', function ($request, $response, $service) {
        $listenNotes = new \Controllers\ListenNotes();
        $episodes = $listenNotes->getPodcastEpisodes($request->podcastId, $request->param('userId', null));
        return $episodes;
    });



    
    ///////////////
    // Episodes ///
    ///////////////

    // Save Episode
    $klein->respond('POST','/saveEpisode', function ($request, $response, $service, $app) {
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, TRUE);

        $episodes = new \Controllers\Episodes();
        $episodes = $episodes->saveEpisode($data['user_id'], $data['episode_id'], $data['podcast_id']);
        return $episodes;
    });
});


$klein->dispatch();


