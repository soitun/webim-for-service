<?php

include_once('common.php');

header("Content-type: application/javascript");
/** set no cache in IE */
header("Cache-Control: no-cache");

$webim_jsonp = isset( $_GET['remote'] ) || webim_is_remote();
$webim_path = webim_urlpath();
#$webim_path = "webim/";

if ( !$im_is_login && !$_IMC['enable_login'] ) {
	exit('"Please login at first."');
}


if ( $im_is_login ) {
	#$setting = json_encode( webim_get_settings() );
	$imuser->show = 'unavailable';
	$imuser = json_encode( $imuser );
} else {
	$setting = "";
	$imuser = "";
}

?>
var _IMC = {
production_name: '<?php echo WEBIM_PRODUCT_NAME ?>',
version: '<?php echo $_IMC['version']; ?>',
path: '<?php echo $webim_path; ?>',
is_login: '<?php echo $im_is_login ? "1" : "" ?>',
user: <?php echo $imuser ? $imuser : '""'; ?>,
setting:{
		play_sound: true,
		minimize_layout: true,
		buddy_sticky: true
},
disable_chatlink: '<?php echo $_IMC['disable_chatlink'] ? "1" : "" ?>',
title: '<?php echo $_IMC['title']; ?>',
theme: '<?php echo $_IMC['theme']; ?>',
local: '<?php echo $_IMC['local']; ?>',
jsonp: '<?php echo $webim_jsonp ? "1" : "" ?>',
min: window.location.href.indexOf("webim_debug") != -1 ? "" : ".min"
};
_IMC.script = window.webim ? '' : ('<link href="' + _IMC.path + 'static/webim.' + _IMC.production_name + _IMC.min + '.css?' + _IMC.version + '" media="all" type="text/css" rel="stylesheet"/><link href="' + _IMC.path + 'static/themes/' + _IMC.theme + '/jquery.ui.theme.css?' + _IMC.version + '" media="all" type="text/css" rel="stylesheet"/><script src="' + _IMC.path + 'static/webim.' + _IMC.production_name + _IMC.min + '.js?' + _IMC.version + '" type="text/javascript"></script><script src="' + _IMC.path + 'static/i18n/webim-' + _IMC.local + '.js?' + _IMC.version + '" type="text/javascript"></script>');
_IMC.script += '<script src="' + _IMC.path + 'webim.js?' + _IMC.version + '" type="text/javascript"></script>';
document.write( _IMC.script );
