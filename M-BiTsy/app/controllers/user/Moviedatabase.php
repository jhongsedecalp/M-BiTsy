<?php
require_once APPROOT . "/libraries/TMDB.php";

class Moviedatabase
{

    public function __construct()
    {
        Auth::user(0, 2);
    }

    public function index()
    {
        $data = [
            'title' => 'Search TMDB Movie',
        ];
        View::render('moviedatabase/index', $data, 'user');
    }

    public function submit()
    {
        $name = $_POST['inputsearch'];
        $type = $_POST['input'];

        if (!$name || !$type) {
            Redirect::to(URLROOT);
        }

        $tmdb = new TMDB();
        $data = [
            'title' => $type,
            'name' => $name,
            'tmdb' => $tmdb,
        ];
        
        if ($type == 'person') {
            View::render('moviedatabase/person', $data, 'user');
        } elseif ($type == 'show') {
            View::render('moviedatabase/show', $data, 'user');
        } else {
            View::render('moviedatabase/movie', $data, 'user');
        }
    }

    

    // Search Person
    public function person()
    {
        $id = (int) Input::get('id');
        $tmdb = new TMDB();
        $data = [
            'id' => $id,
            'title' => 'Person',
            'tmdb' => $tmdb,
        ];
        View::render('moviedatabase/persondetails', $data, 'user');
    }

    // Search Shows
    public function shows()
    {
        $id = (int) Input::get('id');
        $tmdb = new TMDB();
        $data = [
            'id' => $id,
            'title' => 'Show',
            'tmdb' => $tmdb,
        ];
        View::render('moviedatabase/showdetails', $data, 'user');
    }

    // Search Movies
    public function movies()
    {
        $id = (int) Input::get('id');
        $tmdb = new TMDB();
        $data = [
            'id' => $id,
            'title' => 'Movie',
            'tmdb' => $tmdb,
        ];
        View::render('moviedatabase/moviedetails', $data, 'user');
    }
}