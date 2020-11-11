<?php
namespace Controllers;

class Playlists {
    
    public function getPlaylists()
    {
        $playlists = \Models\Playlists\PlaylistsQuery::create()->find();
        $playlistArr = array();
        $playlistArr['playlists'] = array();
        foreach ($playlists as $playlist) {
            $playlistData['name'] = $playlist->getName();
            $playlistData['favorites'] = $playlist->getFavorites();
            $playlistData['tag_generated'] = $playlist->getTagGenerated();

            array_push($playlistArr['playlists'], $playlistData);
        }
        return json_encode($playlistArr);
    }


    public function getUserPlaylists($userId)
    {
        // Get User Row
        $user = \Models\Users\UsersQuery::create()->filterById($userId)->findOne();
        // Get Playlists
        $playlists = $user->getPlaylistss();

        $playlistsArr = array();
        $playlistsArr['playlists'] = array();

        foreach ($playlists as $playlist) {
            // If Parent get that first, then find children
            if ($playlist->getIsParent()) {
                // Set the parents name
                $playlistData['id'] = $playlist->getId();
                $playlistData['name'] = $playlist->getName();

                // Get the parents tags
                $tags = $playlist->getUserPlaylistTagss();

                $playlistData['tags'] = array();
                foreach ($tags as $tag) {
                    $playlistData['tags'][] = $tag->getPlaylistTag()->getName();
                }

                // Get playlist cover
                $playlistData['thumbnails']  = array();
                foreach ($this->getPlaylistCoverImage($playlist) as $thumbnail) {
                    $playlistData['thumbnails'][] = $thumbnail;
                }

                // Get Children related to parent
                $children = $playlist->getPlaylistChildrensRelatedByParentId();

                // Find Children
                $playlistData['children'] = array();
                $x = 0;
                foreach ($children as $child) {
                    $childPlaylists = \Models\Playlists\PlaylistsQuery::create()->findPK($child->getChildId());

                    $playlistData['children'][$x]['id'] =  $child->getChildId();
                    $playlistData['children'][$x]['name'] = $childPlaylists->getName();
                    
                    // Get Child Tags
                    $tags = $childPlaylists->getUserPlaylistTagss();
                    foreach ($tags as $tag) {
                        $playlistData['children'][$x]['tags'][] = $tag->getPlaylistTag()->getName();
                    }
                    $x++;
                }
                if (empty($playlistData['children'])) {
                    $playlistData['children'] = (Object) $playlistData['children'];
                }
                array_push($playlistsArr['playlists'], $playlistData);
            
            } else { // Not Parent Playlist

                // $playlistData['id'] = $playlist->getId();
                // $playlistData['name'] =$playlist->getName();

                // // Get the parents tags
                // $tags = $playlist->getUserPlaylistTagss();

                // $playlistData['tags'] = array();
                // foreach ($tags as $tag) {
                //     $playlistData['tags'][] = $tag->getPlaylistTag()->getName();
                // }
                // array_push($playlistsArr['playlists'], $playlistData);
            }
        }
        return json_encode($playlistsArr, JSON_PRETTY_PRINT);
    }

    public function getPlaylistCoverImage($playlistObj)
    {
        $episodes = new Episodes;
        $episodes = $episodes->getPlaylistEpisodes($playlistObj, 4);

        $episodeIds = array();
        foreach ($episodes as $episode) {
            $episodeIds[] = $episode->getEpisodeId();
        }

        $episodeImages = new ListenNotes();
        $episodeImages = $episodeImages->getPlaylistCoverImages($episodeIds);


        return $episodeImages;
    }

}