<?php
if ($_SESSION['loggedin'] == true) {
	Style::block_begin(Lang::T("THEME") . " / " . Lang::T("LANGUAGE"));
    $stylesheets = '';
    $languages = '';
    $ss_r = DB::raw('stylesheets', '*', '');
    $ss_sa = array();
    while ($ss_a = $ss_r->fetch(PDO::FETCH_ASSOC)) {
        $ss_id = $ss_a["uri"];
        $ss_name = $ss_a["name"];
        $ss_sa[$ss_name] = $ss_id;
    }
    ksort($ss_sa);
    reset($ss_sa);
    //while (list($ss_name, $ss_id) = thisEach($ss_sa)) {
    foreach($ss_sa as $ss_name => $ss_id) {
        if ($ss_id == Users::get("stylesheet")) {
            $ss = " selected='selected'";
        } else {
            $ss = "";
        }
        $stylesheets .= "<option value='$ss_id'$ss>$ss_name</option>\n";
    }

    $lang_r = DB::raw('languages', '*', '');
    $lang_sa = array();
    while ($lang_a = $lang_r->fetch(PDO::FETCH_ASSOC)) {
        $lang_id = $lang_a["name"];
        $lang_name = $lang_a["name"];
        $lang_sa[$lang_name] = $lang_id;
    }
    ksort($lang_sa);
    reset($lang_sa);
    //while (list($lang_name, $lang_id) = thisEach($lang_sa)) {
    foreach($lang_sa as $lang_name => $lang_id) {
        if ($lang_id == Users::get("language")) {
            $lang = " selected='selected'";
        } else {
            $lang = "";
        }
        $languages .= "<option value='$lang_id'$lang>$lang_name</option>\n";
    }
    ?>

    <form method="post" action="<?php echo URLROOT; ?>/stylesheet" class="form-horizontal">
 	    <div class="form-group">
		    <center><label><?php echo Lang::T("THEME"); ?></label></center>
		    <select name="stylesheet" style="width: 95%" ><?php echo $stylesheets; ?></select>
  	    </div>
	    <div class="form-group">
            <center><label><?php echo Lang::T("LANGUAGE"); ?></label></center>
	    	<select name="language" style="width: 95%" ><?php echo $languages; ?></select>
  	    </div>
        <center><button type="submit" class="btn ttbtn center-block" value="" /><?php echo Lang::T("APPLY"); ?></button></center>
    </form>
    <?php
    Style::block_end();
}