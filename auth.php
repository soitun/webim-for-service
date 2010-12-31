<?php

include_once( 'common.php' );
if ( webim_login( webim_gp("login"), webim_gp("pw") ) ) {
	echo "ok";
} else {
	header("HTTP/1.0 401 Unauthorized");
	echo "Login or password is invalid.";
}
