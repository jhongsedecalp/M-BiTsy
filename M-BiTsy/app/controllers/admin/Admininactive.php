<?php
class Admininactive
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
         // Number of days of inactivite
        $cday = empty($_POST['cday']) ? 30 : $_POST['cday'];
        $dt = sqlesc(TimeDate::get_date_time(TimeDate::gmtime() - ($cday * 86400)));
        // Get Users
        $res = DB::run("SELECT id,username,class,email,uploaded,downloaded,last_access,ip,added FROM users WHERE last_access<$dt AND status='confirmed' AND enabled='yes' ORDER BY last_access DESC ");
        $count = $res->rowCount();

        if ($count > 0) {
            Style::header(Lang::T("USURSINACTIVE"));
            Style::begin(Lang::T("USURSINACTIVE"));
            print("<form action='" . URLROOT . "/admininactive' method='post'>");
            print("<br><table class=table_table align=center border=1 cellspacing=0 cellpadding=5><tr>\n");
            print("<td class=table_head>" . Lang::T("NUMBER0FDAYS") . "</td><td class=table_head><input type='text' name='cday' size='10' value='" . ($cday > 30 ? $cday : 30) . "' maxlength='3' /></td>");
            print("<td class='table_head'><input type='submit' value='Change' /><input type='hidden' name='action' value='cday' />");
            print("</td></tr></table></form><br/>");

            print("<h2 align=center>" . $count . " accounts inactive for longer than " . $cday . " days.</h2>");
            print("<form action='" . URLROOT . "/admininactive/submit' method='post'>");
            print("<table class=table_table align=center width=800 border=1 cellspacing=0 cellpadding=5><tr>\n");
            print("<td class=table_head>" . Lang::T("USERNAME") . "</td>");
            print("<td class=table_head>" . Lang::T("CLASS") . "</td>");
            print("<td class=table_head>" . Lang::T("IP") . "</td>");
            print("<td class=table_head>" . Lang::T("RATIO") . "</td>");
            print("<td class=table_head>" . Lang::T("JOINDATE") . "</td>");
            print("<td class=table_head>" . Lang::T("LAST_VISIT") . "</td>");
            print("<td class=table_head align='center'><input type='checkbox' name='checkall' onclick='checkAll(this.form.id);' /></td>");

            while ($arr = $res->fetch(PDO::FETCH_ASSOC)) {
                $ratio = ($arr["downloaded"] > 0 ? number_format($arr["uploaded"] / $arr["downloaded"], 3) : ($arr["uploaded"] > 0 ? "Inf." : "---"));
                $ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
                $downloaded = mksize($arr["downloaded"]);
                $uploaded = mksize($arr["uploaded"]);
                $last_seen = (($arr["last_access"] == null) ? "never" : "" . TimeDate::get_elapsed_time(TimeDate::sql_timestamp_to_unix_timestamp($arr["last_access"])) . "&nbsp;ago");
                $class = Groups::get_user_class_name($arr["class"]);
                $joindate = substr($arr['added'], 0, strpos($arr['added'], " "));
                print("<tr>");
                print("<td><a href='" . URLROOT . "/profile?id=" . $arr["id"] . "'>" . Users::coloredname($arr["id"]) . "</a></td>"); //=== Use this line if you did not have the function class_user ===//
                print("<td>" . $class . "</td>");
                print("<td>" . ($arr["ip"] == "" ? "----" : $arr["ip"]) . "</td>");
                print("<td><b>" . $ratio . "</b><font class='small'> | Dl:<font color=red><b>" . $downloaded . "</b></font> | Up:<font color=lime><b>" . $uploaded . "</b></font></font></td>");
                print("<td>" . $joindate . "</td>");
                print("<td>" . $last_seen . "</td>");
                print("<td><input type='checkbox' name='userid[]' value='$arr[id]' /></td>");
                print("</tr>");
            }
            print("<tr><td colspan=7 class='table_head' align='center'>
	                   <select name='action'>
	                   <option value=mail>" . Lang::T("SENDMAIL") . "</option>
	                   <option value='deluser'>" . Lang::T("DELETEUSERS") . "</option>
                       <option value='disable'>" . Lang::T("DISABLED_ACCOUNTS") . "</option>
	                   </select>&nbsp;&nbsp;<input type='submit' name='submit' value='Apply Changes'/>&nbsp;&nbsp;<input type='button' value='Check all' onClick='this.value=check(form)' /></td></tr>");
                       $record_mail = true; // Set this true or false . If you set this true every time whene you send a mail the time , userid , and the number of mail sent will be recorded
        


            print("</table></form>");
            Style::end();
            Style::footer();
        } else {
            Redirect::autolink(URLROOT . '/admincp', Lang::T("NOACOUNTINATIVE") . " " . $cday . " " . Lang::T("DAYS") . "");
        }
    }

    public function submit()
    {
        //var_dump($_POST);
        $sitename = Config::get('SITENAME'); // Sitename
        $siteurl = Config::get('SITEURL'); // Default site url
        $replyto = Config::get('SITEEMAIL'); // The Reply-to email
        // End config
        $record_mail = true; // Set this true or false . If you set this true every time whene you send a mail the time , userid , and the number of mail sent will be recorded

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $action = $_POST["action"];
            $cday = 0 + $_POST["cday"];
            $days = 30; // Number of days of inactivite

            if (!is_numeric($cday)) {
                Redirect::autolink(URLROOT . '/admininactive', "Int Expected !");
            }
            if (empty($_POST["userid"]) && (($action == "deluser") || ($action == "mail") || ($action == "disable"))) {
                Redirect::autolink(URLROOT . '/admininactive', "For this to work you must select at least a user !");
            }
            if ($action == "deluser" && (!empty($_POST["userid"]))) {
                $ids = implode(", ", $_POST['userid']);
                DB::deleteByIds('users', 'id', $ids);
                Redirect::autolink(URLROOT . '/admininactive', "You have successfully deleted the selected accounts! <a href=" . URLROOT . "/admininactive>Go back</a>");
            }

            if ($action == "cday" && ($cday > $days)) {
                $days = $cday;
            }

            if ($action == "disable" && (!empty($_POST["userid"]))) {
                $res = DB::run("SELECT id, modcomment FROM users WHERE id IN (" . implode(", ", $_POST['userid']) . ") ORDER BY id DESC ");
                while ($arr = $res->fetch(PDO::FETCH_ASSOC)) {
                    $id = 0 + $arr["id"];
                    $cname = Users::get("username");
                    $modcomment = $arr["modcomment"];
                    $modcomment = gmdate("Y-m-d") . " - Disabled for inactivity by $cname.n" . $modcomment;
                    DB::update('users', ['modcomment' =>$modcomment, 'enabled' =>'no'], ['id' => $id]);
                }
                Redirect::autolink(URLROOT . '/admininactive', "You have successfully disabled the selected accounts! <a href=" . URLROOT . "/inactiveusers.php>Go back</a>");
            }

            if ($action == "mail" && (!empty($_POST["userid"]))) {
                $res = DB::run("SELECT id, email , username, added, last_access FROM users WHERE id IN (" . implode(", ", $_POST['userid']) . ") ORDER BY last_access DESC ");
                $count = $res->rowCount();
                while ($arr = $res->fetch(PDO::FETCH_ASSOC)) {
                    $id = $arr["id"];
                    $username = htmlspecialchars($arr["username"]);
                    $email = htmlspecialchars($arr["email"]);
                    $added = $arr["added"];
                    $last_access = $arr["last_access"];

                    $subject = "Warning for inactive account on $sitename";
                    $message = "
Your account on $sitename was marked as inactive and will be deleted within a few days.
If you want to remain member at $sitename you must just login and download something nice!

Your username it's:      $username
Account created on:      $added
Last visit on site:                      $last_access

You can login here:      $siteurl/login
Recovery password:       $siteurl/recover
";
                    $headers = 'From: no-reply@' . $sitename . "rn" . 'Reply-To:' . $replyto . "rn" . 'X-Mailer: PHP/' . phpversion();

                    $TTMail = new TTMail();
                    $mail = $TTMail->Send($email, $subject, $message, $headers);
                }

                if ($mail) {
                    Redirect::autolink(URLROOT . '/admininactive', "Messages sent.");
                } else {
                    Redirect::autolink(URLROOT . '/admininactive',  "Try again.");
                }

            }
        }

    }

}