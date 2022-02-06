<?php

class Collection
{
    private $_data;

    // 	Construct Class
    public function __construct($data) {
        $this->_data = $data;
    }

    //  Get the Collection's name
    public function getName() {
        return $this->_data['name'];
    }

    //  Get the Collection's id
    public function getID() {
        return $this->_data['id'];
    }

    //  Get the Collection's overview
    public function getOverview() {
        return $this->_data['overview'];
    }

    //  Get the Collection's poster
    public function getPoster() {
        return $this->_data['poster_path'];
    }

    //  Get the Collection's backdrop
    public function getBackdrop() {
        return $this->_data['backdrop_path'];
    }

    //  Get the Collection's Movies
    public function getMovies() {
        $movies = array();

        foreach($this->_data['parts'] as $data){
            $movies[] = new Movie($data);
        }

        return $movies;
    }

    //  Get Generic.<br> -  Get a item of the array, you should not get used to use this, better use specific get's.
    public function get($item = '') {
        return (empty($item)) ? $this->_data : $this->_data[$item];
    }
}