<?php
class Admincensor
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        if (Config::get('OLD_CENSOR')) {
            if ($_POST['submit'] == 'Add Censor') {
                DB::insert('censor', ['word'=>$_POST['word'], 'censor'=>$_POST['censor']]);
            }

            if ($_POST['submit'] == 'Delete Censor') {
                DB::delete('censor', ['word'=>$_POST['censor']], 1);
            }

            $sres = DB::select('censor', 'word', '', 'ORDER BY word');

            $data = [
              'title' => Lang::T("Censor"),
              'sres' => $sres,
            ];
            View::render('censor/oldcensor', $data, 'admin');
        } else {
            $to = isset($_GET["to"]) ? htmlentities($_GET["to"]) : $to = '';

            switch ($to) {
                case 'write':
                    if (isset($_POST["badwords"])) {
                        $f = fopen(LOGGER . "/censor.txt", "w+");
                        @fwrite($f, $_POST["badwords"]);
                        fclose($f);
                    }
                    Redirect::autolink(URLROOT . "/admincensor", Lang::T("SUCCESS"), "Censor Updated!");
                    break;
                    
                case '':
                case 'read':
                default:
                    $f = @fopen(LOGGER . "/censor.txt", "r");
                    $badwords = @fread($f, filesize(LOGGER . "/censor.txt"));
                    @fclose($f);
                    $data = [
                      'title' => Lang::T("Censor"),
                      'badwords' => $badwords,
                    ];
                    View::render('censor/newcensor', $data, 'admin');
                    break;
            }
        }
    }
    
}