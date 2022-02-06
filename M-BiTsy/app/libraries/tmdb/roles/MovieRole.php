<?php

class MovieRole extends Role
{
    private $_data;

    // 	Construct Class
    public function __construct($data, $idPerson) {
        $this->_data = $data;

        parent::__construct($data, $idPerson);
    }

    //  Get the Movie's title of the role
    public function getMovieTitle() {
        return $this->_data['title'];
    }

    //  Get the Movie's id
    public function getMovieID() {
        return $this->_data['id'];
    }

    //  Get the Movie's original title of the role
    public function getMovieOriginalTitle() {
        return $this->_data['original_title'];
    }

    //  Get the Movie's release date of the role
    public function getMovieReleaseDate() {
        return $this->_data['release_date'];
    }

    //  Get the JSON representation of the Episode
    public function getJSON() {
        return json_encode($this->_data, JSON_PRETTY_PRINT);
    }
}