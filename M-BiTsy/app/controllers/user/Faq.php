<?php
class Faq
{
    public function __construct()
    {
        Auth::user(0, 1);
    }

    public function index()
    {
        $faq_categ = Faqs::select();
        
        $data = [
            'title' => Lang::T("FAQ"),
            'faq_categ' => $faq_categ,
            ];
        View::render('faq/index', $data, 'user');
    }

}