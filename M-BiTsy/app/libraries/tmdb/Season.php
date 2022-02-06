<?php

class Season
{
    private $_data;
    private $_idTVShow;

    // 	Construct Class*/
    public function __construct($data, $idTVShow) {
        $this->_data = $data;
        $this->_data['tvshow_id'] = $idTVShow;
    }

    // 	Get the Season's id
    public function getID() {
        return $this->_data['id'];
    }

    // 	Get the Season's name
    public function getName() {
        return $this->_data['name'];
    }

    //  Get the Season's TVShow id
    public function getTVShowID() {
        return $this->_data['tvshow_id'];
    }

    // 	Get the Season's number
    public function getSeasonNumber() {
        return $this->_data['season_number'];
    }

    // 	Get the Season's number of episodes
    public function getNumEpisodes() {
        return count($this->_data['episodes']);
    }

    //  Get a Seasons's Episode
    public function getEpisode($numEpisode) {
        return new Episode($this->_data['episodes'][$numEpisode]);
    }

    //  Get the Season's Episodes
    public function getEpisodes() {
        $episodes = array();

        foreach($this->_data['episodes'] as $data){
            $episodes[] = new Episode($data, $this->getTVShowID());
        }

        return $episodes;
    }

    // 	Get the Seasons's Poster
    public function getPoster() {
        return $this->_data['poster_path'];
    }

    // 	Get the Season's AirDate
    public function getAirDate() {
        return $this->_data['air_date'];
    }

    //  Get Generic.<br>
    public function get($item = '') {
        return (empty($item)) ? $this->_data : $this->_data[$item];
    }

    //  Reload the content of this class.<br>
    public function reload($tmdb) {
        $tmdb->getSeason($this->getTVShowID(), $this->getSeasonNumber());
    }

    // 	Get the JSON representation of the Season
    public function getJSON() {
        return json_encode($this->_data, JSON_PRETTY_PRINT);
    }
}