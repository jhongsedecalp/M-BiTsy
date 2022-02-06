<?php

class Role
{
    private $_data;

    // 	Construct Clas
    protected function __construct($data, $ipPerson) {
        $this->_data = $data;
        $this->_data['person_id'] = $ipPerson;
    }

    //  Get the Role's character
    public function getCharacter() {
        return $this->_data['character'];
    }

    //  Get the Movie's poster
    public function getPoster() {
        return $this->_data['poster_path'];
    }

    //  Get Generic.<br>
    public function get($item = ''){
        return (empty($item)) ? $this->_data : $this->_data[$item];
    }
}