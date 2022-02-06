<?php
class Logout
{

    public function index()
    {
        Cookie::destroyAll();
        Redirect::to(URLROOT . "/login");
    }

}