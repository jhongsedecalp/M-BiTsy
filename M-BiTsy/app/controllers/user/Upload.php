<?php

class Upload
{
    public function __construct()
    {
        Auth::user(0, 2);
    }

    public static function checks()
    {
        if (Users::get("can_upload") == "no") {
            Redirect::autolink(URLROOT, Lang::T("UPLOAD_NO_PERMISSION"));
        }
        if (Config::get('UPLOADERSONLY') && Users::get("class") < _VIP) {
            Redirect::autolink(URLROOT, Lang::T("UPLOAD_ONLY_FOR_UPLOADERS"));
        }
    }

    public function index()
    {
        self::checks();
        $announce_urls = explode(",", ANNOUNCELIST);
        
        $data = [
            'title' => Lang::T("UPLOAD"),
            'announce_urls' => $announce_urls,
        ];
        View::render('upload/index', $data, 'user');
    }

    public function submit()
    {
        self::checks();
        if ($_POST["takeupload"] == "yes") {
            // Check form data.
            if (!isset($_POST['type'], $_POST['name'], $_FILES['torrent'])) {
                $message = Lang::T('MISSING_FORM_DATA');
            }
            $tupload = new Tupload('torrent');
            if (($num = $tupload->getError())) {
                Redirect::autolink(URLROOT . '/upload', Lang::T("UPLOAD_ERR[$num]"));
            }
            if (!($fname = $tupload->getName())) {
                $message = Lang::T("EMPTY_FILENAME");
            }
            // NFO
            $nfo = 'no';
            if ($_FILES['nfo']['size'] != 0) {
                $nfofile = $_FILES['nfo'];
                if ($nfofile['name'] == '') {
                    $message = Lang::T("NO_NFO_UPLOADED");
                }
                if (!preg_match('/^(.+)\.nfo$/si', $nfofile['name'], $fmatches)) {
                    $message = Lang::T("UPLOAD_NOT_NFO");
                }
                if ($nfofile['size'] == 0) {
                    $message = Lang::T("NO_NFO_SIZE");
                }
                if ($nfofile['size'] > 65535) {
                    $message = Lang::T("NFO_UPLOAD_SIZE");
                }
                $nfofilename = $nfofile['tmp_name'];
                if (($num = $_FILES['nfo']['error'])) {
                    $message = Lang::T("UPLOAD_ERR[$num]");
                }
                $nfo = 'yes';
            }
            // Check Post Inputs
            $descr = Input::get("descr");
            if (!$descr) {
                $descr = Lang::T("UPLOAD_NO_DESC");
            }
            $vip = Input::get("vip");
            if (!$vip) {
                $vip = 0;
            }
            $free = Input::get("free");
            if (!$free) {
                $free = 0;
            }
            $langid = (int) Input::get("lang");
            $catid = (int) Input::get("type");

            if (!Validate::Id($catid)) {
                $message = Lang::T("UPLOAD_NO_CAT");
            }
            if (!empty(Input::get('tube'))) {
                $tube = Input::get('tube');
            }
            if (!Validate::Filename($fname)) {
                $message = Lang::T("UPLOAD_INVALID_FILENAME");
            }
            if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches)) {
                $message = Lang::T("UPLOAD_INVALID_FILENAME_NOT_TORRENT");
            }

            $shortfname = $torrent = $matches[1];

            if (!empty(Input::get("name"))) {
                $name = Input::get("name");
            }
            if (!empty(Input::get('tmdb'))) {
                $tmdb = Input::get('tmdb');
            }
            // If Message Show
            if ($message) {
                Redirect::autolink(URLROOT . '/upload', $message);
            }
            if (!$message) {
                //parse torrent file
                $torrent_dir = UPLOADDIR."/torrents";
                $nfo_dir = UPLOADDIR."/nfos";
                if (!($tupload->move("$torrent_dir/$fname"))) {
                    Redirect::autolink(URLROOT . '/upload', Lang::T("ERROR") . ": " . Lang::T("UPLOAD_COULD_NOT_BE_COPIED") . " $torrent_dir - $fname");
                }
                $torInfo = new Parse();
                $tor = $torInfo->torr("$torrent_dir/$fname");

                $announce = $tor['announce'];
                $infohash = $tor['hash'];
                $creationdate = $tor["creation date"];
                $internalname = $tor['name'];
                $torrentsize = $tor['length'];
                $filecount = $tor['ttfilecount'];
                $annlist = $tor["announce-list"];
                $comment = $tor['comment'];
                $filelist = $tor['files'];

                //if externals is turned off
                //$external = $announce !== ANNOUNCELIST ? "yes" : "no";
                $announce_urls = explode(",", ANNOUNCELIST);
                if (!in_array($announce, $announce_urls)){
                    $external='yes';
                }else{
                    $external='no';
                }
                // Save annouces for scrape
                if ($external === 'yes') {
                    $multi = array_flatten($annlist);
                    $announcelist = serialize($multi);
                }

                if (!Config::get('ALLOWEXTERNAL') && $external == 'yes') {
                    $message = Lang::T("UPLOAD_NO_TRACKER_ANNOUNCE");
                }

            }
            if ($message) {
                @$tupload->remove();
                @unlink("$torrent_dir/$fname");
                Redirect::autolink(URLROOT . '/upload', $message);
            }

            //release name check and adjust
            if ($name == "") {
                $name = $internalname;
            }
            $name = str_replace(".torrent", "", $name);
            $name = str_replace("_", " ", $name);

            //upload images
            $allowed_types = ALLOWEDIMAGETYPES;
            $inames = array();
            for ($x = 0; $x < 2; $x++) {
                if (!($_FILES['image' . $x]['name'] == "")) {
                    $y = $x + 1;
                    if ($_FILES['image$x']['size'] > IMAGEMAXFILESIZE) {
                        Redirect::autolink(URLROOT . '/upload', Lang::T("INVAILD_FILE_SIZE_IMAGE"));
                    }
                    $uploaddir = UPLOADDIR . '/images/';
                    $ifile = $_FILES['image' . $x]['tmp_name'];
                    $im = getimagesize($ifile);
                    if (!$im[2]) {
                        Redirect::autolink(URLROOT . '/upload', sprintf(Lang::T("INVALID_IMAGE")));
                    }
                    if (!array_key_exists($im['mime'], $allowed_types)) {
                        Redirect::autolink(URLROOT . '/upload', Lang::T("INVALID_FILETYPE_IMAGE"));
                    }
                    $row = DB::run("SHOW TABLE STATUS LIKE 'torrents'")->fetch();
                    $next_id = $row['Auto_increment'];
                    $ifilename = $next_id . $x . $allowed_types[$im['mime']];
                    $copy = copy($ifile, $uploaddir . $ifilename);
                    if (!$copy) {
                        Redirect::autolink(URLROOT . '/upload', sprintf(Lang::T("IMAGE_UPLOAD_FAILED")));
                    }
                    $inames[] = $ifilename;
                }

            }

            //anonymous upload
            $anonyupload = Input::get("anonycheck");
            if ($anonyupload == "yes") {
                $anon = "yes";
            } else {
                $anon = "no";
            }

            $filecounts = (int) $filecount;
            // Insert Torrent
            try {
                DB::run("INSERT INTO torrents (filename, owner, name, vip, descr, image1, image2, category, tube, added, info_hash, size, numfiles, save_as, announce, external, nfo, torrentlang, anon, last_action, freeleech, tmdb, announcelist)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                        [$fname, Users::get('id'), $name, $vip, $descr, $inames[0], $inames[1], $catid, $tube, TimeDate::get_date_time(), $infohash, $torrentsize, $filecounts, $fname, $announce, $external, $nfo, $langid, $anon, TimeDate::get_date_time(), $free, $tmdb, $announcelist]);
            } catch (PDOException $e) {
                rename("$torrent_dir/$fname", "$torrent_dir/duplicate.torrent"); // todo
                Redirect::to(URLROOT . '/exceptions');
            }

            $id = DB::lastInsertId();

            if ($id == 0) {
                unlink("$torrent_dir/$fname");
                Redirect::autolink(URLROOT . '/upload', Lang::T("UPLOAD_NO_ID"));
            }
            rename("$torrent_dir/$fname", "$torrent_dir/$id.torrent");

            if (is_array($filelist)) {
                foreach ($filelist as $file) {
                    $dir = '';
                    $size = $file["length"];
                    $count = count($file["path"]);
                    for ($i = 0; $i < $count; $i++) {
                        if (($i + 1) == $count) {
                            $fname = $file["path"][$i] . $dir ;
                        } else {
                            $dir .= "/" . $file["path"][$i];
                        }

                    }
                    DB::insert('files', ['torrent'=>$id, 'path'=>$fname, 'filesize'=>$size]);
                }
            } else {
                DB::insert('files', ['torrent'=>$id, 'path'=>$internalname, 'filesize'=>$torrentsize]);
            }

            if ($nfo == 'yes') {
                move_uploaded_file($nfofilename, "$nfo_dir/$id.nfo");
            }
            // Log Upload
            Logs::write(sprintf(Lang::T("TORRENT_UPLOADED"), htmlspecialchars($name), Users::get("username")));
            // Shout new torrent
            $msg_shout = "New Torrent: [url=" . URLROOT . "/torrent?id=" . $id . "]" . $torrent . "[/url] has been uploaded " . ($anon == 'no' ? "by [url=" . URLROOT . "/profile?id=" . Users::get('id') . "]" . Users::get('username') . "[/url]" : "") . "";
            DB::insert('shoutbox', ['userid'=>0, 'date'=>TimeDate::get_date_time(), 'user'=>'System', 'message'=>$msg_shout]);
            //Uploaded ok message
            if ($external == 'no') {
                $message = sprintf(Lang::T("TORRENT_UPLOAD_LOCAL"), $name, $id, $id);
            } else {
                $message = sprintf(Lang::T("TORRENT_UPLOAD_EXTERNAL"), $name, $id);
                // scrape external
                Tscraper::ScrapeId($id, $annlist, $infohash);
            }

            Redirect::autolink(URLROOT . "/torrent?id=$id", $message);
        } else {
            Redirect::autolink(URLROOT, Lang::T("ERROR"));
        }
    }

}