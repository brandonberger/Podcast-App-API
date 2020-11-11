<?php
namespace Controllers;

class Tags
{
    public function getTags()
    {
        $tags  = \Models\Tags\TagsQuery::create()->find();
        $tagArr = array();
        $tagArr['tags'] = array();

        foreach ($tags as $tag) {
            $tagData['id'] = $tag->getId();
            $tagData['name'] = $tag->getName();
            array_push($tagArr['tags'], $tagData);
        }
        if (empty($tagArr['tags'])) {
            $tagsArr['tags'] = (object) $tagsArr['tags'];
        }
        echo json_encode($tagArr, JSON_PRETTY_PRINT);
    }

    public function addTag($newTag)
    {
        $tag = \Models\Tags\TagsQuery::create()->filterByName($newTag)->findOne();
        if (!$tag) {
            $tag = new \Models\Tags\Tags();
            $tag->setName(
                $newTag ?? null
            );
            $tag->save();
        }
        return $tag;
    }

    public function addPlaylistTag($playlistId, $tag)
    {
        $tag = $this->addTag($tag);

        $userPlaylistTag = \Models\UserTags\UserPlaylistTagsQuery::create()
            ->filterByTagId($tag->getId())
            ->filterByPlaylistId($playlistId)
            ->filterByUserId(USER_ID)
            ->findOne();

        if (!$userPlaylistTag) {
            $userPlaylistTag = new \Models\UserTags\UserPlaylistTags();
            $userPlaylistTag->setTagId($tag->getId());
            $userPlaylistTag->setPlaylistId($playlistId);
            $userPlaylistTag->setUserId(USER_ID);
            $userPlaylistTag->save();
            return 'Success';
        } else {
            return 'Already exists.';
        }
    }

    public function addEpisodeTag($episodeId, $tag)
    {
        $tag = $this->addTag($tag);

        $userEpisodeTag = \Models\UserTags\UserEpisodeTagsQuery::create()
            ->filterByTagId($tag->getId())
            ->filterByEpisodeId($episodeId)
            ->filterByUserId(USER_ID)
            ->findOne();

        if (!$userEpisodeTag) {
            $userEpisodeTag = new \Models\UserTags\UserEpisodeTags();
            $userEpisodeTag->setTagId($tag->getId());
            $userEpisodeTag->setEpisodeId($episodeId);
            $userEpisodeTag->setUserId(USER_ID);
            $userEpisodeTag->save();
            return 'Success';
        } else {
            return 'Already Exists';
        }
    }

    public function removePlaylistTag($playlistId, $tagId)
    {
        $userPlaylistTag = \Models\UserTags\UserPlaylistTagsQuery::create()
            ->filterByTagId($tagId)
            ->filterByPlaylistId($playlistId)
            ->filterByUserId(USER_ID)
            ->findOne();
            
        if ($userPlaylistTag) {
            $userPlaylistTag->delete();
            return 'Success'; 
        } else {
            return 'Error';
        }
    }

    public function removeEpisodeTag($episodeId, $tagId)
    {
        $userEpisodeTag = \Models\UserTags\UserEpisodeTagsQuery::create()
            ->filterByTagId($tagId)
            ->filterByEpisodeId($episodeId)
            ->filterByUserId(USER_ID)
            ->findOne();
            
        if ($userEpisodeTag) {
            $userEpisodeTag->delete();
            return 'Success'; 
        } else {
            return 'Error';
        }
    }

}