<?php
	//echo phpinfo();
	//ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
	if(! ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7)) {echo "échec";}
?>