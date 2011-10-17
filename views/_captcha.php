<div id="captcha">
Please enter the word below to return to the main site, Otherwise you will be banned and thus, stuck here!<div id="casetxt">(word is not case sensitive)</div>
<form id="form" action="" method="post">
<p>
<img id="siimage" style="border: 1px solid #000; margin-right: 15px" src="securimage/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" align="left">
<object type="application/x-shockwave-flash" data="securimage/securimage_play.swf?audio_file=securimage/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" height="32" width="32">
<param name="movie" value="securimage/securimage_play.swf?audio_file=securimage/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000">
</object>
&nbsp;
<a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'securimage/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="securimage/images/refresh.png" alt="Reload Image" onclick="this.blur()" align="bottom" border="0"></a><br />
<strong>Enter Code*:</strong><br />
<input type="text" name="ct_captcha" size="12" maxlength="8" />
</p>
<button id="Next" value="Next"type="submit" href="">I'm not a Bot</button> 
</form>
</div>
