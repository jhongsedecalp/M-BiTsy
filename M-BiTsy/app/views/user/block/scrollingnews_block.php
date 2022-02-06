<?php
if ($_SESSION['loggedin'] == true) {
    if (Config::get('NEWSON')) { //check news is turned on first
		Style::block_begin(Lang::T("LATEST_NEWS"));
        $res = DB::raw('news', '*', '', 'ORDER BY added DESC LIMIT 10'); ?>
	    <style type="text/css">
		#marqueecontainer{
			position: relative;
			height: 70px; /*marquee height */
			background-color: transparent;
			overflow: hidden;
			padding: 2px;
			padding-left: 4px;
	    }
		</style>
	
	    <script type="text/javascript">
		var delayb4scroll=2000 //Specify initial delay before marquee starts to scroll on page (2000=2 seconds)
		var marqueespeed=1 //Specify marquee scroll speed (larger is faster 1-10)
		var pauseit=1 //Pause marquee onMousever (0=no. 1=yes)?
		////NO NEED TO EDIT BELOW THIS LINE////////////
		var copyspeed=marqueespeed
		var pausespeed=(pauseit==0)? copyspeed: 0
		var actualheight=''
		function scrollmarquee(){
			if (parseInt(cross_marquee.style.top)>(actualheight*(-1)+8))
			cross_marquee.style.top=parseInt(cross_marquee.style.top)-copyspeed+"px"
			else
			cross_marquee.style.top=parseInt(marqueeheight)+8+"px"
		}
		function initializemarquee(){
			cross_marquee=document.getElementById("vmarquee")
			cross_marquee.style.top=0
			marqueeheight=document.getElementById("marqueecontainer").offsetHeight
			actualheight=cross_marquee.offsetHeight
			if (window.opera || navigator.userAgent.indexOf("Netscape/7")!=-1){ //if Opera or Netscape 7x, add scrollbars to scroll and exit
	        cross_marquee.style.height=marqueeheight+"px"
	        cross_marquee.style.overflow="scroll"
	        return
	        }
	    setTimeout('lefttime=setInterval("scrollmarquee()",30)', delayb4scroll)
	    }
	    <?php
	    if ($res->rowCount() > 3) { ?>
	        if (window.addEventListener)
	            window.addEventListener("load", initializemarquee, false)
	        else if (window.attachEvent)
	            window.attachEvent("onload", initializemarquee)
	        else if (document.getElementById)
	           window.onload=initializemarquee
               <?php
	    } ?>
	    </script>

	<div id="marqueecontainer" onmouseover="copyspeed=pausespeed" onmouseout="copyspeed=marqueespeed">
	<div id="vmarquee">
	<!--YOUR SCROLL CONTENT HERE-->
	<?php
    if ($res->rowCount()) { ?>
     	<dl>
    	<?php while ($array = $res->fetch(PDO::FETCH_ASSOC)) {?>
			<dt><a href='<?php echo URLROOT; ?>/comment?type=news&amp;id=<?php echo $array['id']; ?>'><strong><?php echo $array['title']; ?></strong></a></dt><dd><strong><?php echo Lang::T("POSTED"); ?>:</strong> <?php echo gmdate("d-M-y", TimeDate::utc_to_tz_time($array["added"])); ?><dd>
		<?php }?>
	    </dl><?php
	} else { ?>
	    <p class="text-center"><?php echo Lang::T("NO_NEWS"); ?></p>
	    <?php
	} ?>
    </div>
    </div>
	<?php
    Style::block_end();
    }
}