<?php

namespace Mazharul\Lastfm;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Lastfm API wrapper
 *
 * API documentation : http://www.last.fm/api/intro
 *
 * @author Maz <me@mazharulanwar.com>
 * @since 2016.11.20
 * @package Mazharul\Lastfm
 * @version 0.1.0
 */
class Lastfm
{

    /**
     * Top artists based on country method name
     */
    const GEO_TOP_ARTISTS_METHOD = 'geo.getTopArtists';

    /**
     * To tracks of an artists method name
     */
    const ARTISTS_TOP_TRACKS_METHOD = 'artist.getTopTracks';


    /**
     * Resquest GET
     */
    const HTTP_REQUEST_GET = 'GET';

    /**
     * API Base url
     *
     * @param string
     */
    private $_api_base_url = "http://ws.audioscrobbler.com/2.0/";

    /**
     * Format for the API result
     *
     * @var string
     */
    private $_format = "json";

    /**
     * API key
     *
     * @var string
     */
    private $_api_key = "";

    /**
     * Guzzle client object
     *
     * @var class instance
     */
    private $_guzzle;

    /**
     * HTTP call guzzle timeout
     *
     * @var integer | float
     */
    private $_guzzle_timeout;

    /**
     * Default Query param with each request
     *
     * @var array
     */
    private $_default_query_params;

    /**
     * Limit of the request
     *
     * @var int
     */
    private $_limit = 5;

    /**
     * Page no
     *
     * @var integer
     */
    private $_page;


    public function __construct($config)
    {
        if (is_array($config)) {
            if (array_key_exists('api_key', $config)) {
                $this->setApiKey($config['api_key']);
            }

            if (array_key_exists('format', $config)) {
                $this->setFormat($config['format']);
            }

            if (array_key_exists('base_url', $config)) {
                $this->setApiBaseUrl($config['base_url']);
            }
        } elseif (is_string($config)) {
            $this->setApiKey($config);
        } else {
            throw new LastfmException('Error: please construct the class properly!');
        }

        $this->_initGuzzleClient();
        $this->_buildDefaultQueryParam();
    }

    /**
     * API key setter
     *
     * @param $api_key
     */
    public function setApiKey($api_key)
    {
        $this->_api_key = $api_key;
    }

    /**
     * Format setter  : json
     *
     * @param $format
     */
    public function setFormat($format)
    {
        $this->_format = $format;
    }

    /**
     * API base url setter
     *
     * @param $base_url
     */
    public function setApiBaseUrl($base_url)
    {
        $this->_api_base_url = $base_url;
    }

    /**
     * Initialisation of guzzle client
     */
    private function _initGuzzleClient()
    {
        $this->_guzzle = new Client([
            'base_uri' => $this->_api_base_url,
            'timeout'  => $this->_guzzle_timeout,
        ]);
    }

    private function _buildDefaultQueryParam()
    {
        $this->_default_query_params = [
            'query' => [
                'api_key' => $this->_api_key,
                'limit' => $this->_limit,
                'page' => $this->_page,
                'method' => '',
                'format' => $this->_format
            ]
        ];
    }

    /**
     * @param $method
     * @param $extra
     * @return mixed
     */
    private function _call($method, $extra)
    {
        try {
            $response = $this->_guzzle->request($method, $this->_api_base_url, $extra);
            $body = $response->getBody()->getContents();
            return $body;
        } catch (RequestException $e) {
            // TODO handle all the bad requests;
        } catch (\Exception $e) {
            // TODO all fails; handle unexpected errors
        }
    }

    /**
     * Getting top artists based on geo location (country)
     *
     * @param $country
     * @param int $page
     * @return mixed
     */
    public function getGeoTopArtists($country, $page = 1)
    {
        // TODO : validate the $country user input
        $country_param = [
            'query' => [
                'country' => (string) $country,
                'method' => self::GEO_TOP_ARTISTS_METHOD,
                'page' => $page,
            ]
        ];

        $query_params = array_replace_recursive($this->_default_query_params, $country_param);

        $res = $this->_call(self::HTTP_REQUEST_GET, $query_params);

        return $res;

    }

    public function getArtistsTopTracks($mbid = '', $name = '', $page = 1)
    {
        if ($mbid == '' && $name == '') {
            throw new LastfmException('You have to give either mbid or an artist name!');
        }

        $top_tracks_param = [
            'query' => [
                'method' => self::ARTISTS_TOP_TRACKS_METHOD,
                'page' => $page,
            ]
        ];

        if (!empty($name)) {
            $top_tracks_param['query']['name'] = $name;
        }

        if (!empty($mbid)) {
            $top_tracks_param['query']['mbid'] = $mbid;
        }

        $query_params = array_replace_recursive($this->_default_query_params, $top_tracks_param);

        $res = $this->_call(self::HTTP_REQUEST_GET, $query_params);

        return $res;

    }


}
