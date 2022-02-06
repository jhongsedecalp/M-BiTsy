<a href='<?php echo URLROOT ?>/request'>
<button  class='btn btn-sm ttbtn'>All Request</button></a>&nbsp;
<a href='<?php echo URLROOT ?>/request?requestorid=<?php echo Users::get('id') ?>'>
<button  class='btn btn-sm ttbtn'>View my requests</button></a><br><br>

<p class='text-center'><b>If this is abused, it will be for VIP only!</b></p>

<b>* Before posting a request, please make sure to search the site first to make sure it's not already posted.</b><br>
<b>* 1 request per day per member. Any more than that will be deleted by a moderator.</b><br>
<b>* When possible, please provide a full scene release name.</b><br>

<div class="ttform">
<form method=post action='<?php echo URLROOT ?>/request/confirmreq'><a name=add id=add></a>

<div class="text-center">
    <b><?php echo Lang::T('MAKE_REQUEST') ?></b></a>
</div>

<div class="col">
    <b>Title: </b><input type=text  class="form-group" name=requesttitle>
</div><br>
<div class="col">
    <b>Type: </b>
    <select name="cat">
    <option value="0"><?php echo "(" . Lang::T("ALL") . " " . Lang::T("TYPES") . ")"; ?></option>
    <?php
    $cats = Catagories::genrelist();
    $catdropdown = "";
    foreach ($cats as $cat) {
        $catdropdown .= "<option value=\"" . $cat["id"] . "\"";
            if ($cat["id"] == $_GET["cat"]) {
                $catdropdown .= " selected=\"selected\"";
            }
            $catdropdown .= ">" . htmlspecialchars($cat["parent_cat"]) . ": " . htmlspecialchars($cat["name"]) . "</option>\n";
    }
    echo $catdropdown?>
    </select>
</div>
<div class="text-center">
    Additional Information <b>(Optional - but be generous!</b>)<br>
    <textarea class="form-control" id="descr" name="descr" rows="7"></textarea>
</div>

<div class="text-center">
    <button  class='btn btn-sm ttbtn'><?php echo Lang::T('SUBMIT') ?></button>
</div>

</form>
</div>