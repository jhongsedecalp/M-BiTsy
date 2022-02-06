<?php
if ($_SESSION['loggedin'] == true) {
    Style::block_begin(Lang::T("POLL"));
    if (!function_exists("srt")) {
        function srt($a, $b)
        {
            if ($a[0] > $b[0]) {
                return -1;
            }
            if ($a[0] < $b[0]) {
                return 1;
            }
            return 0;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['loggedin'] && $_POST["act"] == "takepoll") {
        $choice = $_POST["choice"];
        if ($choice != "" && $choice < 256 && $choice == floor($choice)) {
            $res = DB::raw('polls', '*', '', 'ORDER BY added DESC LIMIT 1');
            $arr = $res->fetch(PDO::FETCH_ASSOC) or print("No Poll");

            $pollid = $arr["id"];
            $userid = Users::get("id");

            $res = DB::raw('pollanswers', '*', ['pollid' =>$pollid, 'userid' =>$userid]);
            $arr = $res->fetch(PDO::FETCH_ASSOC);

            if ($arr) {
                print("You have already voted!");
            } else {
                $ins = DB::run("INSERT INTO pollanswers VALUES(?, ?, ?, ?)", [0, $pollid, $userid, $choice]);
                if (!$ins) {
                    print("An error occured. Your vote has not been counted.");
                }

            }
        } else {
            print("Please select an option.");
        }
    }

    // Get current poll
    if ($_SESSION['loggedin']) {
        $res = DB::raw('polls', '*', '', 'ORDER BY added DESC LIMIT 1');

        if ($pollok = ($res->rowCount())) {
            $arr = $res->fetch(PDO::FETCH_ASSOC);
            $pollid = $arr["id"];
            $userid = Users::get("id");
            $question = $arr["question"];

            $o = array($arr["option0"], $arr["option1"], $arr["option2"], $arr["option3"], $arr["option4"],
                $arr["option5"], $arr["option6"], $arr["option7"], $arr["option8"], $arr["option9"],
                $arr["option10"], $arr["option11"], $arr["option12"], $arr["option13"], $arr["option14"],
                $arr["option15"], $arr["option16"], $arr["option17"], $arr["option18"], $arr["option19"]);

            // Check if user has already voted
            $res =  DB::raw('pollanswers', '*', ['pollid'=>$pollid, 'userid'=>$userid]);
            $arr2 = $res->fetch(PDO::FETCH_ASSOC);
        }

        //Display Current Poll
        if ($pollok) {?>
          <p class="text-center"><strong><?php echo $question; ?></strong></p>
          <?php
          $voted = $arr2;
          // If member has voted already show results
          if ($voted) {
              if ($arr2["selection"]) {
                  $uservote = $arr2["selection"];
              } else {
                  $uservote = -1;
              }
              // we reserve 255 for blank vote.
              $res = DB::run("SELECT selection FROM pollanswers WHERE pollid=$pollid AND selection < 20");
              $tvotes = $res->rowCount();
              $vs = array(); // array of
              $os = array();
              // Count votes
              while ($arr2 = $res->fetch(PDO::FETCH_LAZY)) {
                  $vs[$arr2[0]] += 1;
              }
              reset($o);
              for ($i = 0; $i < count($o); ++$i) {
                  if ($o[$i]) {
                      $os[$i] = array($vs[$i], $o[$i]);
                  }
              }
              // now os is an array like this: array(array(123, "Option 1"), array(45, "Option 2"))
              if ($arr["sort"] == "yes") {
                  usort($os, 'srt');
              }
                $i = 0;

              while ($a = $os[$i]) {
                  if ($i == $uservote) {
                      $a[1] .= "";
                  }
                  if ($tvotes == 0) {
                      $p = 0;
                   } else {
                      $p = round($a[0] / $tvotes * 100);
                  }
                  if ($i % 2) {
                      $c = "";
                  } else {
                      $c = "";
                  }?>
                  <div class="row">
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-6">
                       <strong><?php echo format_comment($a[1]); ?></strong>
                      </div>
                      <div class="col-lg-6">
                      <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $p; ?>" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: <?php echo $p; ?>%;">
                          <?php echo $p; ?>%
                        </div>
                      </div>
                      </div>
                    </div>
                  </div>
                  </div><?php
                   ++$i;
              }
              $tvotes = number_format($tvotes);?>
    	        <div class="text-center"><b><span class="label label-success"><?php echo Lang::T("VOTES") . ": " . $tvotes; ?></span></b></div>
              <?php
        } else { //User has not voted, show options?>
          <form method='post' action='<?php echo encodehtml($_SERVER["REQUEST_URI"]); ?>'>
          <input type='hidden' name='act' value='takepoll' />
          <?php $i = 0;
          while ($a = $o[$i]) {?>
            <div class="radio">
            <label>
      	    <input type='radio' name='choice' value='<?php echo $i; ?>' /><?php echo format_comment($a); ?>
            </label>
            </div>
            <?php	++$i;
          } ?>
          <div class="radio">
            <label>
    	        <input type='radio' name='choice' value='255' /><?php echo Lang::T("BLANK_VOTE"); ?>
            </label>
          </div>
          <center>
        	<button type='submit' class="btn ttbtn center-block" /><?php echo Lang::T("VOTE"); ?></button>
	      	</center>
         </form><?php
        }

    } else { ?>
  		<p class="text-center">No Active Polls</p>
	    <?php
      }
    } else { ?>
      <p class="text-center"><?php echo Lang::T("POLL_MUST_LOGIN"); ?></p>
      <?php
    }
    Style::block_end();
}