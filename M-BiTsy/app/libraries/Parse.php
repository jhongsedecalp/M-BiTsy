<?php

class Parse
{

    public function torr($filename = "")
    {
        $torrentInfo = array();
        $array = array();

        //check file type is a torrents
        $torrent = explode(".", $filename);
        // $fileend = end($torrent);
        $fileend = strtolower(end($torrent));

        if ($fileend == "torrent") {
            $parse = file_get_contents("$filename");
            // If Parse Lets Read It
            if (!isset($parse)) {
                Redirect::autolink(URLROOT, Lang::T("Error Getting Torrent file"));
            } else {
                // Decode File Array
                $array = Bencode::decode($parse);
                // If Array Lets Read It
                if ($array === false) {
                    Redirect::autolink(URLROOT, Lang::T("Unable to decode file."));
                } else {
                    if (array_key_exists("info", $array) === false) {
                        Redirect::autolink(URLROOT . "/index", Lang::T("Error opening torrent.<br>"));
                    } else {
                        //Get Announce URL
                        $torrentInfo['announce'] = $array["announce"];
                        //Get Announce List Array
                        if (isset($array["announce-list"])) {
                            $torrentInfo["announce-list"] = $array["announce-list"];
                        }
                        // Calculates date from UNIX Epoch
                        $torrentInfo["creation date"] = date('r', $array["creation date"]);
                        //Get torrents comment
                        if (isset($array['comment'])) {
                            $torrentInfo['comment'] = $array['comment'];
                        }
                        // Read info - files
                        $info = $array["info"];
                        // The name of the torrents is different to the file name
                        $torrentInfo['name'] = $info['name'];
                        // Calculate Size/Count
                        $filecount = 0;
                        $torrentsize = 0;

                        if (isset($info["file tree"])) {
                            // Calculates SHA256 Hash
                            $hash = hash('sha256', Bencode::encode($info));
                            $torrentInfo['hash'] = substr($hash, 0, 40);
                            // Return Marker To identify as v2
                            $torrentInfo['type'] = '2';
                        } else {
                            // Calculates SHA1 Hash
                            $torrentInfo['hash'] = hash('sha1', Bencode::encode($info));
                            // Return Marker To identify as v1
                            $torrentInfo['type'] = '1';
                        }
                        
                        // Get file list
                        if (isset($info["file tree"]) && is_array($info["file tree"])) {
                            // For Now Dont Allow V2
                            unlink("$filename");
                            Redirect::autolink(URLROOT, Lang::T("We Dont Except V2 Torrents Just Yet."));

                            //Get files here
                            $torrentInfo['files'] = $info["file tree"];
                            foreach ($info["file tree"] as  $key => $value) {
                                $path = array($key);
                                foreach ($value as $detail) {
                                    $filecount = ++$filecount;
                                    $torrentsize += $detail['length'];
                                    $newfiles[] = [
                                        'length' => $detail['length'],
                                        'path' => $path // key is important
                                    ];
                                }
                            }
                            // Replace File Stucture 
                            $torrentInfo['files'] = $newfiles;

                            $torrentInfo['length'] = $torrentsize;
                            $torrentInfo['ttfilecount'] = $filecount;
                            
                        } elseif (isset($info["files"]) && is_array($info["files"])) {
                            //Get files here
                            $torrentInfo['files'] = $info["files"];
                            foreach ($info["files"] as $file) {
                                $filecount = ++$filecount;
                                $torrentsize += $file['length'];
                            }
                            $torrentInfo['length'] = $torrentsize;
                            $torrentInfo['ttfilecount'] = $filecount;

                        } else {
                            // Single File Torrent
                            $torrentInfo['name'] = $info['name'];
                            $torrentInfo['length'] = $info['length'];
                            $torrentInfo['ttfilecount'] = 1;
                        }
                    }
                }
            }
        }
        return $torrentInfo;
        //var_dump($torrentInfo);
    }
}
