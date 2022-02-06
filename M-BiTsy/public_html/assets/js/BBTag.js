<script type="text/javascript">
// Function To Replace BBCode Tags In Text Zones //
// BBCode write function
	function bbcomment(repdeb, repfin){
		var input = document.forms["<?php echo $form ;?>"].elements["<?php echo $name ;?>"];
		input.focus();
		if(typeof document.selection != 'undefined'){
			var range = document.selection.createRange();
			var insText = range.text;
			range.text = repdeb + insText + repfin;
			range = document.selection.createRange();
			if (insText.length == 0){	
				range.move('character', -repfin.length);
				} 
			else{
				range.moveStart('character', repdeb.length + insText.length + repfin.length);
				}
			range.select();
			}
		else if(typeof input.selectionStart != 'undefined'){
			var start = input.selectionStart;
			var end = input.selectionEnd;
			var insText = input.value.substring(start, end);
			input.value = input.value.substr(0, start) + repdeb + insText + repfin + input.value.substr(end);
			var pos;
			if (insText.length == 0){
				pos = start + repdeb.length;
				} 
			else{
				pos = start + repdeb.length + insText.length + repfin.length;
				}
			input.selectionStart = pos;
			input.selectionEnd = pos;
			}
		else{
			var pos;
			var re = new RegExp('^[0-9]{0,3}$');
			while(!re.test(pos)){
				pos = prompt("Insertion Ã  la position (0.." + input.value.length + "):", "0");
				}
			if(pos > input.value.length){
				pos = input.value.length;
				}
			var insText = prompt("Veuillez entrer le texte Ã  formater:");
			input.value = input.value.substr(0, pos) + repdeb + insText + repfin + input.value.substr(pos);
			}
		}
	// Fonction Couleur De Police
	function bbcouleur(couleur){
		bbcomment("[color="+couleur+"]", "[/color]");
		}
	// Fonction Police
	function bbfont(font){
		bbcomment("[font="+font+"]", "[/font]");
		}
	// Fonction Taille De Police
	function bbsize(taille){
		bbcomment("[size="+taille+"]", "[/size]");
		}
	// Fonctions De Remplacement De Caractères
	function deblaie(reg,t){
		texte=new String(t);
		return texte.replace(reg,'$1\n');
		}
	function remblaie(t){
		texte=new String(t);
		return texte.replace(/\n/g,'');
		}
	function remplace_tag(reg,rep,t){
		texte=new String(t);
		return texte.replace(reg,rep);
		}
	function nl2br(t){
		texte=new String(t);
		return texte.replace(/\n/g,'<br/>');
		}
	function nl2khol(t){
		texte=new String(t);
		return texte.replace(/\n/g,ptag);
		}
	function unkhol(t){
		texte=new String(t);
		return texte.replace(new RegExp(ptag,'g'),'\n');
		}	
	var timer=0;
	var ptag=String.fromCharCode(5,6,7);
	// Fonction Preview
	function  visualisation() {
		t=document.forms["<?php echo $form ;?>"].elements["<?php echo $name ;?>"].value  
		t=code_to_html(t)
		if (document.getElementById) document.getElementById("previsualisation").innerHTML=t
		if (document.formu.auto.checked) timer=setTimeout(visualisation,1000)
		}
	// Transform BBCode in HTML
	function code_to_html(t){
		t=nl2khol(t)
		// balise Center
			t=deblaie(/(\[\/center\])/g,t)
			t=remplace_tag(/\[center\](.+)\[\/center\]/g,'<center>$1</center>',t)  
			t=remblaie(t)		
		// balise Gras
			t=deblaie(/(\[\/b\])/g,t)
			t=remplace_tag(/\[b\](.+)\[\/b\]/g,'<strong>$1</strong>',t)  
			t=remblaie(t)
		// balise Italique
			t=deblaie(/(\[\/i\])/g,t)
			t=remplace_tag(/\[i\](.+)\[\/i\]/g,'<em>$1</em>',t)  
			t=remblaie(t)
		// balise SOuligné
			t=deblaie(/(\[\/u\])/g,t)
			t=remplace_tag(/\[u\](.+)\[\/u\]/g,'<u>$1</u>',t)  
			t=remblaie(t)
		// balise Barré
			t=deblaie(/(\[\/s\])/g,t)
			t=remplace_tag(/\[s\](.+)\[\/s\]/g,'<span style="text-decoration:line-through;">$1</span>',t)  
			t=remblaie(t)
		// balise Citation
			t=deblaie(/(\[\/quote\])/g,t)
			t=remplace_tag(/\[quote\](.+)\[\/quote\]/g,'<p class=sub><b> Quote : </b></p><table class=main border=1 cellspacing=0 cellpadding=10><tr><td style="border: 1px black dotted">$1</td></tr></table>',t)  
			t=remblaie(t)
			t=deblaie(/(\[\/quote\])/g,t)
			t=remplace_tag(/\[quote=([a-zA-Z]+)\]((\s|.)+?)\[\/quote\]/g,'<p class=sub><b>$1 a quote : </b></p><table class=main border=1 cellspacing=0 cellpadding=10><tr><td style="border: 1px black dotted">$2</td></tr></table>',t)  
			t=remblaie(t)
		// Balise Multi Citation Pour Message Privé
			t=deblaie(/(\[\/reponse\])/g,t)
			t=remplace_tag(/\[reponse(.*)\](.+)\[\/reponse\]/g,'<table class=main border=1 cellspacing=0 cellpadding=10><tr><td style="border: 1px black dotted">$2</td></tr></table>',t)  
			t=remblaie(t)
		// balise code	
			t=deblaie(/(\[\/code\])/g,t)
			t=remplace_tag(/\[code\](.+)\[\/code\]/g,'<p class=sub><b>Extrait De Code : </b></p><table class=main border=1 cellspacing=0 cellpadding=10><tr><td style="border: 1px black dotted">$1</td></tr></table>',t)  
			t=remblaie(t)
		// balise blink	
			t=deblaie(/(\[\/blink\])/g,t)
			t=remplace_tag(/\[blink\](.+)\[\/blink\]/g,'<div id="blink">$1</div>',t)  
			t=remblaie(t)
		// balise df	
			t=deblaie(/(\[\/df\])/g,t)
			t=remplace_tag(/\[df\](.+)\[\/df\]/g,'<marquee>$1</marquee>',t)  
			t=remblaie(t)
		// balise [audio]..[/audio]
			t=deblaie(/(\[\/audio\])/g,t)
			t=remplace_tag(/\[audio\]((www.|http:\/\/|https:\/\/)[^\s]+(\.mp3))\[\/audio\]/g,'<param name=movie value=$1/><embed width=470 height=310 src=$1></embed>',t)  
			t=remblaie(t)	
		//****************
		//* Partie Vidéo *
		//****************
		// balise [video]..[/video] pour youtube
			t=deblaie(/(\[\/video\])/g,t)
			t=remplace_tag(/\[video\][^\s'\"<>]*youtube.com.*v=([^\s'\"<>]+)\[\/video\]/img,'<object width="680" height="440"><param name="movie" value="http://www.youtube.com/v/$1"></param><embed src="http://www.youtube.com/v/$1" type="application/x-shockwave-flash" width="680" height="440"></embed></object>',t)  
			t=remblaie(t)
		// balise [video=...] pour youtube
			t=deblaie(/(\[\/video\])/g,t)
			t=remplace_tag(/\[video=[^\s'\"<>]*youtube.com.*v=([^\s'\"<>]+)\]/img,'<object width="680" height="440"><param name="movie" value="http://www.youtube.com/v/$1"></param><embed src="http://www.youtube.com/v/$1" type="application/x-shockwave-flash" width="680" height="440"></embed></object>',t)  
			t=remblaie(t)
		// balise [video]..[/video] pour mp4
			t=deblaie(/(\[\/video\])/g,t)
			t=remplace_tag(/\[video\]((www.|http:\/\/|https:\/\/)[^\s]+(\.mp4))\[\/video\]/g,'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="680" height="440" id="player1" name="player1"><param name="movie" value="$1"><param name="allowfullscreen" value="true"><param name="allowscriptaccess" value="always"><embed  src="$1" name="player1"  width="680"  height="440" allowscriptaccess="always" allowfullscreen="true"></embed></object>',t)  
			t=remblaie(t)
		// balise [video]..[/video] pour wmv
			t=deblaie(/(\[\/video\])/g,t)
			t=remplace_tag(/\[video\]((www.|http:\/\/|https:\/\/)[^\s]+(\.wmv))\[\/video\]/g,'<param name=filename value=$1/><embed width=680 height=440 src=$1></embed>',t)  
			t=remblaie(t)	
		// balise [video]..[/video] pour dailymotion
			t=deblaie(/(\[\/video\])/g,t)
			t=remplace_tag(/\[video\][^\s'\"<>]*dailymotion.com\/video\/([^\s'\"<>]+)\[\/video\]/img,'<object width="680" height="440"><param name="movie" value="http://www.dailymotion.com/swf/$1"></param><embed src="http://www.dailymotion.com/swf/$1" type="application/x-shockwave-flash" width="680" height="440"></embed></object>',t)  
			t=remblaie(t)	
		// balise [video]..[/video] pour google video
			t=deblaie(/(\[\/video\])/g,t)
			t=remplace_tag(/\[video\][^\s'\"<>]*video.google.com.*docid=(-?[0-9]+).*\[\/video\]/img,'<embed style="width:680px; height:440px;" id="VideoPlayback" align="middle" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=$1" allowScriptAccess="sameDomain" quality="best" bgcolor="#ffffff" scale="noScale" wmode="window" salign="TL"  FlashVars="playerMode=embedded"></embed>',t)  
			t=remblaie(t)	
		// balise font	
			t=deblaie(/(\[\/font\])/g,t)
			t=remplace_tag(/\[font=(#[a-fA-F0-9]{6})\](.+)\[\/font\]/g,'<font face="$1">$2</font>',t)
			t=remblaie(t)
			t=deblaie(/(\[\/font\])/g,t)
			t=remplace_tag(/\[font=([a-zA-Z].*?)\]((\s|.)+?)\[\/font\]/g,'<font face="$1">$2</font>',t)
			t=remblaie(t)
		// balise Img
			t=deblaie(/(\[\/img\])/g,t)
			t=remplace_tag(/\[img\](.+)\[\/img\]/g,'<img src="$1" />',t)
			t=remblaie(t)
		// balise URL	
			t=deblaie(/(\[\/url\])/g,t)
			t=remplace_tag(/\[url=([^\s<>]+)\](.+)\[\/url\]/g,'<a href="$1" target="_blank">$2</a>',t)
			t=remblaie(t)
			t=deblaie(/(\[\/url\])/g,t)
			t=remplace_tag(/\[url\]([^\s<>]+)\[\/url\]/g,'<a href="$1" target="_blank">$1</a>',t)
			t=remblaie(t)
			t=remplace_tag(/\[\/url\]/g,'</a>',t)
			t=remblaie(t)
		// balise Couleur	
			t=deblaie(/(\[\/color\])/g,t)
			t=remplace_tag(/\[color=(#[a-fA-F0-9]{6})\](.+)\[\/color\]/g,'<font color="$1">$2</font>',t)
			t=remblaie(t)
			t=deblaie(/(\[\/color\])/g,t)
			t=remplace_tag(/\[color=([a-zA-Z]+)\]((\s|.)+?)\[\/color\]/g,'<font color="$1">$2</font>',t)
			t=remblaie(t)
		// alignement
			t=deblaie(/(\[\/align\])/g,t)
			t=remplace_tag(/\[align=([a-zA-Z]+)\]((\s|.)+?)\[\/align\]/g,'<div style="text-align:$1">$2</div>',t)
			t=remblaie(t)
		// balise size	
			t=deblaie(/(\[\/size\])/g,t)
			t=remplace_tag(/\[size=([+-]?[0-9])\](.+)\[\/size\]/g,'<font size="$1">$2</font>',t)
			t=remblaie(t)
		// Balise HR
			t=deblaie(/(\[\/hr\])/g,t)
			t=remplace_tag(/\[hr=(#[a-fA-F0-9]{6})\]/g,'<hr color="$1" />',t)
			t=remblaie(t)
			t=deblaie(/(\[\/hr\])/g,t)
			t=remplace_tag(/\[hr=([a-zA-Z]+)\]/g,'<hr color="$1" />',t)
			t=remblaie(t)
			t=deblaie(/(\[\/hr\])/g,t)
			t=remplace_tag(/\[hr\]/g,'<hr />',t)
			t=remblaie(t)
		// smilies
			t=remplace_tag(/:sm10:/g,'<img src="/assets/images/smilies/smile.png" alt="" />',t) 
			t=remplace_tag(/:sm11:/g,'<img src="/assets/images/smilies/sad.png" alt="" />',t) 
			t=remplace_tag(/:sm12:/g,'<img src="/assets/images/smilies/wink.png" alt="" />',t) 
			t=remplace_tag(/:sm13:/g,'<img src="/assets/images/smilies/razz.png" alt="" />',t) 
			t=remplace_tag(/:sm14:/g,'<img src="/assets/images/smilies/grin.png" alt="" />',t) 
			t=remplace_tag(/:sm15:/g,'<img src="/assets/images/smilies/plain.png" alt="" />',t) 
			t=remplace_tag(/:sm16:/g,'<img src="/assets/images/smilies/suprise.png" alt="" />',t) 
			t=remplace_tag(/:sm17:/g,'<img src="/assets/images/smilies/confused.png" alt="" />',t) 
			t=remplace_tag(/:sm18:/g,'<img src="/assets/images/smilies/glasses.png" alt="" />',t)
			t=remplace_tag(/:sm19:/g,'<img src="/assets/images/smilies/eek.png" alt="" />',t) 
			t=remplace_tag(/:sm20:/g,'<img src="/assets/images/smilies/cool.png" alt="" />',t) 
			t=remplace_tag(/:sm21:/g,'<img src="/assets/images/smilies/smile-big.png" alt="" />',t)
			t=remplace_tag(/:sm22:/g,'<img src="/assets/images/smilies/crying.png" alt="" />',t) 
			t=remplace_tag(/:sm23:/g,'<img src="/assets/images/smilies/kiss.png" alt="" />',t) 
			t=remplace_tag(/O:-D/g,'<img src="/assets/images/smilies/angel.png" alt="" />',t) 	
			t=remplace_tag(/:sm25:/g,'<img src="/assets/images/smilies/devilish.png" alt="" />',t) 
			t=remplace_tag(/:sm26:/g,'<img src="/assets/images/smilies/important.png" alt="" />',t) 
			t=remplace_tag(/:sm27:/g,'<img src="/assets/images/smilies/brb.png" alt="" />',t) 
			t=remplace_tag(/:sm28:/g,'<img src="/assets/images/smilies/bomb.png" alt="" />',t) 
			t=remplace_tag(/:sm29:/g,'<img src="/assets/images/smilies/warn.png" alt="" />',t) 	
			t=remplace_tag(/:sm30:/g,'<img src="/assets/images/smilies/idea.png" alt="" />',t)
			t=remplace_tag(/:help/g,'<img src="/assets/images/smilies/help.png" alt="" />',t) 
			t=remplace_tag(/:sm32:/g,'<img src="/assets/images/smilies/love.png" alt="" />',t) 	
			t=remplace_tag(/:sm33:/g,'<img src="/assets/images/smilies/bad.png" alt="" />',t) 	
			t=remplace_tag(/:sm34:/g,'<img src="/assets/images/smilies/monkey.png" alt="" />',t) 	
			t=remblaie(t)	
		t=unkhol(t)
		t=nl2br(t)
		return t
		}
</script>