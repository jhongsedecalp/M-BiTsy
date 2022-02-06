<?php

class Search
{

    public function __construct()
    {
        Auth::user(0, 1);
    }

    public function check()
    {
        if (Config::get('MEMBERSONLY')) {
            if (Users::get("view_torrents") == "no") {
                Redirect::autolink(URLROOT, Lang::T("NO_TORRENT_VIEW"));
            }
        }
    }

    public function index()
    {
        $this->check();

        $search = search(); // torrent function to help search

        $count = DB::run("SELECT COUNT(*) FROM torrents " . $search['where'], $search['params'])->fetchcolumn();
        if ($count) {
            list($pagerbuttons, $limit) = Pagination::pager(25, $count, URLROOT . "/search?$search[url]$search[pagerlink]");
            $res = Torrents::search($search['where'], $search['orderby'], $limit, $search['params']);

            if (!$search['keyword'] == '') {
                $title = Lang::T("SEARCH_RESULTS_FOR") . " \"" . htmlspecialchars($search['keyword']) . "\"";
            } else {
                $title = Lang::T("SEARCH");
            }

            $data = [
                'title' => $title,
                'res' => $res,
                'pagerbuttons' => $pagerbuttons,
                'keyword' => $search['keyword'],
                'url' => $search['url'],
            ];
            View::render('search/search', $data, 'user');
        } else {
            Redirect::autolink(URLROOT."/search", "Nothing Found Try Again");
        }
    }



    public function needseed()
    {
        $this->check();

        $res = DB::run("SELECT torrents.id, torrents.name, torrents.owner, torrents.external, torrents.size, torrents.seeders, torrents.leechers, torrents.times_completed, torrents.added, users.username FROM torrents LEFT JOIN users ON torrents.owner = users.id WHERE torrents.banned = 'no' AND torrents.leechers > 0 AND torrents.seeders <= 1 ORDER BY torrents.seeders");
        if ($res->rowCount() == 0) {
            Redirect::autolink(URLROOT, Lang::T("NO_TORRENT_NEED_SEED"));
        }
        
        $data = [
            'title' => Lang::T("TORRENT_NEED_SEED"),
            'res' => $res,
        ];
        View::render('search/needseed', $data, 'user');
    }

    public function today()
    {
        $this->check();

        $date_time = TimeDate::get_date_time(TimeDate::gmtime() - (3600 * 24)); // the 24 is the hours you want listed
        $catresult = DB::raw('categories', 'id, name', '', 'ORDER BY sort_index');

        $data = [
            'title' => Lang::T("TODAYS_TORRENTS"),
            'date_time' => $date_time,
            'catresult' => $catresult,
        ];
        View::render('search/today', $data, 'user');
    }

    public function browse()
    {
        $this->check();

        $search = search();

        // Get Total For Pager
        $count = DB::run("SELECT COUNT(*) FROM torrents LEFT JOIN categories ON category = categories.id $search[where]", $search['params'])->fetchColumn();
        $catsquery = Torrents::getCatByParent();
        if ($count) {
            list($pagerbuttons, $limit) = Pagination::pager(25, $count, URLROOT."/search/browse" . $search['url'].$search['pagerlink']);
            $res = DB::run("SELECT torrents.id, torrents.anon, torrents.announce, torrents.category, torrents.sticky, 
                                    torrents.leechers, torrents.nfo, torrents.seeders, torrents.name, torrents.times_completed, 
                                    torrents.tube, torrents.tmdb, torrents.size, torrents.added, torrents.comments, torrents.numfiles, 
                                    torrents.filename, torrents.owner, torrents.external, torrents.freeleech, 
                                    categories.name AS cat_name, categories.parent_cat AS cat_parent, 
                                    categories.image AS cat_pic, users.username, users.privacy, 
                                    IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating 
                                    FROM torrents 
                                    LEFT JOIN categories 
                                    ON category = categories.id 
                                    LEFT JOIN users 
                                    ON torrents.owner = users.id $search[where] $search[orderby] $limit", $search['params']);
        } else {
            unset($res);
        }
        $cats = DB::raw('categories', '*', '', 'ORDER BY parent_cat, name');

        $data = [
            'title' => Lang::T("BROWSE_TORRENTS"),
            'res' => $res,
            'pagerbuttons' => $pagerbuttons,
            'catsquery' => $catsquery,
            'url' => $search['url'],
            'parent_cat' => $search['parent_cat'],
            'count' => $count,
            'wherecatina' => $search['wherecatina'],
            'cats' => $cats
        ];
        View::render('search/browse', $data, 'user');
    }

}