<?php
class Rss
{

    public function __construct()
    {
        Auth::user(0, 1);
    }

    public function index()
    {
        $cat = $_GET["cat"];
        $dllink = (int) $_GET["dllink"];
        $passkey = $_GET["passkey"];
        if (!get_row_count("users", "WHERE passkey=" . sqlesc($passkey))) {
            $passkey = "";
        }
        $where = "";
        $wherea = array();
        if (!$incldead) {
            $wherea[] = "visible='yes'";
        }
        if ($cat) {
            $cats = implode(", ", array_unique(array_map("intval", explode(",", (string) $cat))));
            $wherea[] = "category in ($cats)";
        }
        if (Validate::Id($_GET["user"])) {
            $wherea[] = "owner=$_GET[user]";
        }
        if ($wherea) {
            $where = "WHERE " . implode(" AND ", $wherea);
        }
        $limit = "LIMIT 50";
        // start the RSS feed output
        header("Content-Type: application/xhtml+xml; charset=".CHARSET."");
        echo ("<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>");
        echo ("<rss version=\"2.0\"><channel><generator>" . htmlspecialchars(Config::get('SITENAME')) . " RSS 2.0</generator><language>en</language>" .
            "<title>" . Config::get('SITENAME') . "</title><description>" . htmlspecialchars(Config::get('SITENAME')) . " RSS Feed</description><link>" . URLROOT . "</link><copyright>Copyright " . htmlspecialchars(Config::get('SITENAME')) . "</copyright><pubDate>" . date("r") . "</pubDate>");
        $res = DB::run("SELECT torrents.id, torrents.name, torrents.size, torrents.category, torrents.added, torrents.leechers, torrents.seeders, categories.parent_cat as cat_parent, categories.name AS cat_name FROM torrents LEFT JOIN categories ON category = categories.id $where ORDER BY added DESC $limit");
        while ($row = $res->fetch(PDO::FETCH_LAZY)) {
            list($id, $name, $size, $category, $added, $leechers, $seeders, $catname) = $row;
            if ($dllink) {
                if ($passkey) {
                    $link = "".URLROOT."/download?id=$id&amp;passkey=$passkey";
                } else {
                    $link = "".URLROOT."/download?id=$id";
                }
            } else {
                $link = URLROOT . "/torrent?id=$id&amp;hit=1";
            }
            $pubdate = date("r", TimeDate::sql_timestamp_to_unix_timestamp($added));
            echo ("<item><title>" . htmlspecialchars($name) . "</title><guid>" . $link . "</guid><link>" . $link . "</link><pubDate>" . $pubdate . "</pubDate>	<category> " . $row["cat_parent"] . ": " . $row["cat_name"] . "</category><description>Category: " . $row["cat_parent"] . ": " . $row["cat_name"] . "  Size: " . mksize($size) . " Added: " . $added . " Seeders: " . $seeders . " Leechers: " . $leechers . "</description></item>");
        }
        echo ("</channel></rss>");
    }

    public function custom()
    {
        $resqn = DB::raw('categories', 'id, name, parent_cat', '', 'ORDER BY parent_cat ASC, sort_index ASC');
        $data = [
            'title' => Lang::T("CUSTOM_RSS_XML_FEED"),
            'resqn' => $resqn
        ];
        View::render('rss/custom', $data, 'user');
    }

    
    public function submit()
    {
        if ($_POST) {
            $params = array();
            if ($cats = $_POST["cats"]) {
                $catlist = array();
                foreach ($cats as $cat) {
                    if (is_numeric($cat)) {
                        $catlist[] = $cat;
                    }
                }
                if ($catlist) {
                    $params[] = "cat=" . implode(",", $catlist);
                }
            }
            if ($_POST["incldead"]) {
                $params[] = "incldead=1";
            }
            if ($_POST["dllink"]) {
                $params[] = "dllink=1";
            }
            if (!$_POST["cookies"] && $_SESSION['loggedin'] == true) {
                $params[] = "passkey=".Users::get('passkey')."";
            }
            if ($params) {
                $param = "?" . implode("&amp;", $params);
            } else {
                $param = "";
            }
            $mss = "Your RSS link is: <a href=\"".URLROOT."/rss$param\">".URLROOT."/rss$param</a><br/><br/>";
            Redirect::autolink(URLROOT, $mss);
        }
    }
}