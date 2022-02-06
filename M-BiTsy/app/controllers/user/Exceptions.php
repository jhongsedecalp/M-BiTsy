<?php
class Exceptions
{
    public function __construct()
    {
        Auth::user(0, 0);
    }

    public function index()
    {
        Redirect::autolink(URLROOT, Lang::T("OOPS_ERR"));
    }

}