<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Model\Following;
use Instagram\Utils\InstagramHelper;

class JsonFollowingDataFeed extends AbstractDataFeed
{
    /**
     * @param integer $id
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(int $id): \StdClass
    {
        $variables = [
            'id'            => $id,
            'include_reel'  => true,
            'fetch_mutual'  => false,
            'first'         => InstagramHelper::PAGINATION_DEFAULT_FIRST_FOLLOW,
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . InstagramHelper::QUERY_HASH_FOLLOWINGS . '&variables=' . json_encode($variables);

        $data = $this->fetchJsonDataFeed($endpoint);

        if (!$data->data->user) {
            throw new InstagramFetchException('Instagram id ' . $id . ' does not exist.');
        }

        return $data->data->user;
    }

    /**
     * @param integer $id
     * @param string $endCursor
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchMoreData(int $id, string $endCursor): \StdClass
    {
        $variables = [
            'id'            => $id,
            'include_reel'  => true,
            'fetch_mutual'  => false,
            'first'         => InstagramHelper::PAGINATION_DEFAULT,
            'after'         => $endCursor
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . InstagramHelper::QUERY_HASH_FOLLOWINGS . '&variables=' . json_encode($variables);

        $data = $this->fetchJsonDataFeed($endpoint);

        if (!$data->data->user) {
            throw new InstagramFetchException('Instagram id ' . $id . ' does not exist.');
        }

        return $data->data->user;
    }
}
