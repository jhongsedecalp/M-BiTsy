<?php
class Adminblock
{

    public function __construct()
    {
        Auth::user(_ADMINISTRATOR, 2);
    }

    public function index()
    {
        $enabled = DB::raw('blocks', 'named, name, description, position, sort', ['enabled'=>1], 'ORDER BY position, sort');
        $disabled = DB::raw('blocks', 'named, name, description, position, sort', ['enabled'=>0], 'ORDER BY position, sort');
        $data = [
            'title' => Lang::T("_BLC_MAN_"),
            'enabled' => $enabled,
            'disabled' => $disabled,
        ];
        View::render('block/index', $data, 'admin');
    }

    public function edit()
    {
        if ($_REQUEST["edit"] == "true") {
            # Prune Block Cache.
            $TTCache = new Cache();
            $TTCache->Delete("blocks_left");
            $TTCache->Delete("blocks_middle");
            $TTCache->Delete("blocks_right");

            $nextleft = Blocks::getposition('left');
            $nextmiddle = Blocks::getposition('middle');
            $nextright = Blocks::getposition('right');

            // Delete
            if ($_POST["delete"]) {
            foreach ($_POST["delete"] as $delthis) {
                DB::delete('blocks', ['id'=>$delthis]);
            }
            // Move Blocks
            Blocks::resort('left');
            Blocks::resort('middle');
            Blocks::resort('right');
            }
            
            // Move to left
            if (Validate::Id($_GET["left"])) {
                DB::update('blocks', ['position' =>'left', 'sort' =>$nextleft], ['id'=>$_GET["left"]]);
                Blocks::resort('middle');
                Blocks::resort('right');
            }
            // Move to center
            if (Validate::Id($_GET["middle"])) {
                DB::update('blocks', ['position' =>'middle', 'sort' =>$nextmiddle], ['id'=>$_GET["middle"]]);
                Blocks::resort('left');
                Blocks::resort('right');
            }
            // Move to right
            if (Validate::Id($_GET["right"])) {
                DB::update('blocks', ['position' =>'right', 'sort' =>$nextright], ['id'=>$_GET["right"]]);
                Blocks::resort('left');
                Blocks::resort('middle');
            }
            // Move upper
            if (Validate::Id($_GET["up"])) {
                $sort = (int) $_GET["sort"];
                DB::run("UPDATE blocks SET sort = ? WHERE sort = ? AND id != ? AND position = ?", [$sort, $sort - 1, $_GET["up"], $_GET["position"]]);
                DB::run("UPDATE blocks SET sort = ? WHERE id = ?", [($sort - 1), $_GET["up"]]);
            }
            // Move lower
            if (Validate::Id($_GET["down"])) {
                $sort = (int) $_GET["sort"];
                DB::run("UPDATE blocks SET sort = ? WHERE id = ?", [$sort + 1, $_GET["down"]]);
                DB::run("UPDATE blocks SET sort = ? WHERE sort = ? AND id != ? AND position = ?", [$sort, $sort + 1, $_GET["down"], $_GET["position"]]);
            }
            // Update
            $res = DB::raw('blocks', '*', '', 'ORDER BY id');
            if (!$_GET["up"] && !$_GET["down"] && !$_GET["right"] && !$_GET["left"] && !$_GET["middle"]) {
                $update = array();
                while ($upd = $res->fetch(PDO::FETCH_ASSOC)) {
                    $id = $upd["id"];
                    $update[] = "enabled = " . $_POST["enable_" . $upd["id"]];
                    $update[] = "named = '" . $_POST["named_" . $upd["id"]] . "'";
                    $update[] = "description = '" . $_POST["description_" . $upd["id"]] . "'";
                    if (($upd["enabled"] == 0) && ($upd["position"] == "left") && ($_POST["enable_" . $upd["id"]] == 1)) {
                        $update[] = "sort = " . $nextleft;
                    } elseif (($upd["enabled"] == 0) && ($upd["position"] == "middle") && ($_POST["enable_" . $upd["id"]] == 1)) {
                        $update[] = "sort = " . $nextmiddle;
                    } elseif (($upd["enabled"] == 0) && ($upd["position"] == "right") && ($_POST["enable_" . $upd["id"]] == 1)) {
                        $update[] = "sort = " . $nextright;
                    } elseif (($upd["enabled"] == 1) && ($upd["position"] == "left") && ($_POST["enable_" . $upd["id"]] == 0)) {
                        $update[] = "sort = 0";
                    } elseif (($upd["enabled"] == 1) && ($upd["position"] == "middle") && ($_POST["enable_" . $upd["id"]] == 0)) {
                        $update[] = "sort = 0";
                    } elseif (($upd["enabled"] == 1) && ($upd["position"] == "right") && ($_POST["enable_" . $upd["id"]] == 0)) {
                        $update[] = "sort = 0";
                    } else {
                        $update[] = "sort = " . $upd["sort"];
                    }
                    DB::run("UPDATE blocks SET " . implode(", ", $update) . " WHERE id=$id");
                }
            }
            Blocks::resort('left');
            Blocks::resort('middle');
            Blocks::resort('right');
        }

        $res = DB::raw('blocks', '*', '', 'ORDER BY enabled DESC, position, sort');
        $data = [
            'title' => Lang::T("_BLC_MAN_"),
            'res' => $res,
        ];
        View::render('block/edit', $data, 'admin');
    }

    public function preview()
    {
        $name = Validate::cleanstr($_GET["name"]);
        if (!file_exists(APPROOT . "/views/user/block/{$name}_block.php")) {
                Redirect::autolink(URLROOT, "Possible XSS attempt.");
        }
        $data = [
            'title' => Lang::T("_BLC_PREVIEW_"),
            'name' => $name,
        ];
        View::render('block/preview', $data, 'admin');
        die();
    }

    public function upload()
    {
        $exist = DB::select('blocks', 'name', '');
        while ($fileexist = $exist->fetch(PDO::FETCH_ASSOC)) {
            $indb[] = $fileexist["name"] . "_block.php";
        }
        if ($folder = opendir(APPROOT . '/views/user/blocks')) {
            while (false !== ($file = readdir($folder))) {
                if ($file != "." && $file != ".." && !in_array($file, $indb)) {
                    if (preg_match("/_block.php/i", $file)) {
                        $infolder[] = $file;
                    }

                }
            }
            closedir($folder);
        }

        if ($infolder) {
            $data = [
                'title' => Lang::T("_BLC_MAN_"),
                'indb' => $indb,
                'infolder' => $infolder,
            ];
            View::render('block/added', $data, 'admin');
        } else {
            Redirect::autolink(URLROOT . "/adminblock", "Please upload block to app/views/blocks");
        }

    }

    public function submit()
    {
        if ($_POST["deletepermanent"]) {
            foreach ($_POST["deletepermanent"] as $delpthis) {
                unlink(APPROOT."/views/user/block/" . $delpthis);
                if (file_exists(APPROOT . "/views/user/block/" . $delpthis)) {
                        Redirect::autolink(URLROOT . '/adminblock', Lang::T("_FAIL_DEL_"));
                } else {
                        Redirect::autolink(URLROOT . '/adminblock', Lang::T("_SUCCESS_DEL_"));
                }
            }
        }

        if ($_POST["addnew"]) {
            foreach ($_POST["addnew"] as $addthis) {
                $i = $addthis;
                $addblock = $_POST["addblock_" . $i];
                $wantedname = $_POST["wantedname_" . $i];
                $name = str_replace("_block.php", "", Validate::cleanstr($addblock));
                $description = $_POST["wanteddescription_" . $i];
                $bins = DB::insert('blocks', ['named'=>$wantedname,'name'=>$name,'description'=>$description,'position'=>'left','enabled'=>0,'sort'=>0]);
                if ($bins) {
                        Redirect::autolink(URLROOT . '/adminblock', Lang::T("_SUCCESS_ADD_"));
                } else {
                        Redirect::autolink(URLROOT . '/adminblock', Lang::T("_FAIL_ADD_"));
                }
            }
        }

    }

}