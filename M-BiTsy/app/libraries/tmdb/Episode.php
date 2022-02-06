<?php

class Episode
{
    private $_data;

    // 	Construct Class
    public function __construct($data, $idTVShow) {
        $this->_data = $data;
        $this->_data['tvshow_id'] = $idTVShow;
    }

    // 	Get the episode's id
    public function getID() {
        return $this->_data['id'];
    }

    // 	Get the Episode's name
    public function getName() {
        return $this->_data['name'];
    }

    //  Get the Season's TVShow id
    public function getTVShowID() {
        return $this->_data['tvshow_id'];
    }

    //  Get the Season's number
    public function getSeasonNumber() {
        return $this->_data['season_number'];
    }

    // 	Get the Episode's number
    public function getEpisodeNumber() {
        return $this->_data['episode_number'];
    }

    //  Get the Episode's Overview
    public function getOverview() {
        return $this->_data['overview'];
    }

    //	Get the Seasons's Still
    public function getStill() {
        return $this->_data['still_path'];
    }

    // 	Get the Season's AirDate
    public function getAirDate() {
        return $this->_data['air_date'];
    }

    // 	Get the Episode's vote average
    public function getVoteAverage() {
        return $this->_data['vote_average'];
    }

    // 	Get the Episode's vote count
    public function getVoteCount() {
        return $this->_data['vote_count'];
    }

    //  Get Generic.<br> - Get a item of the array, you should not get used to use this, better use specific get's.
    public function get($item = ''){
        return (empty($item)) ? $this->_data : $this->_data[$item];
    }

    //  Reload the content of this class.<br> - Could be used to update or complete the information.
    public function reload($tmdb) {
        $tmdb->getEpisode($this->getTVShowID(), $this->getSeasonNumber(), $this->getEpisodeNumber);
    }

    // 	Get the JSON representation of the Episode
    public function getJSON() {
        return json_encode($this->_data, JSON_PRETTY_PRINT);
    }
}