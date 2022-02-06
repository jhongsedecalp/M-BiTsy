<?php

class TVShowRole extends Role
{
    private $_data;

    // 	Construct Class
    public function __construct($data, $idPerson) {
        $this->_data = $data;

        parent::__construct($data, $idPerson);
    }

    //  Get the TVShow's title of the role
    public function getTVShowName() {
        return $this->_data['name'];
    }

    //  Get the TVShow's id
    public function getTVShowID() {
        return $this->_data['id'];
    }

    //  Get the TVShow's original title of the role
    public function getTVShowOriginalTitle() {
        return $this->_data['original_name'];
    }

    //  Get the TVShow's release date of the role
    public function getTVShowFirstAirDate() {
        return $this->_data['first_air_date'];
    }

    //  Get the JSON representation of the Episode
    public function getJSON() {
        return json_encode($this->_data, JSON_PRETTY_PRINT);
    }
}