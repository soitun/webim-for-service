<?php

/** 
 * Custom interface 
 *
 * Provide 
 *
 * define WEBIM_PRODUCT_NAME
 * array $_IMC
 * boolean $im_is_login
 * object $imuser require when $im_is_login
 * function webim_get_buddies()
 * function webim_login()
 *
 */

define( 'WEBIM_PRODUCT_NAME', 'service' );

@include_once( 'config.php' );

/**
 *
 * Provide the webim database config.
 *
 * $_IMC['dbuser'] MySQL database user
 * $_IMC['dbpassword'] MySQL database password
 * $_IMC['dbname'] MySQL database name
 * $_IMC['dbhost'] MySQL database host
 * $_IMC['dbtable_prefix'] MySQL database table prefix
 * $_IMC['dbcharset'] MySQL database charset
 *
 */

if( empty($_IMC['host']) ) {
	$_IMC['host'] = $_SERVER['SERVER_NAME'];
}

$_IMC['dbcharset'] = "utf8";


/**
 * Init im user.
 * 	-uid:
 * 	-id:
 * 	-nick:
 * 	-pic_url:
 * 	-show:
 *
 */
$im_is_login = true;
webim_set_user();

function profile_url( $email ) {
	return "http://www.gravatar.com/" . ( empty( $email ) ? "" :  md5( strtolower( trim( $email ) ) ) );
}

function gravatar( $email ) {
	return "http://www.gravatar.com/avatar/" . ( empty( $email ) ? "" :  md5( strtolower( trim( $email ) ) ) ) . "?s=50";
}

function webim_set_user() {
	global $_SGLOBAL, $imuser;
	if ( isset($_COOKIE['webim_visitor_id']) ) {
		$id = $_COOKIE['webim_visitor_id'];
	} else {
		$id = uniqid();
		setcookie('webim_visitor_id', $id, time() + 3600 * 24, "/", "");
	}
	if ( isset($_COOKIE['webim_visitor_nick']) ) {
		$nick = $_COOKIE['webim_visitor_nick'];
	} else {
		$nick = 'guest' . rand(1000, 9999);
		setcookie('webim_visitor_nick', $nick, time() + 3600 * 24, "/", "");
	}
	$imuser->id = $id;
	$imuser->nick = $nick;
	$imuser->pic_url = gravatar("");
	$imuser->default_pic_url = gravatar("");
	$imuser->show = webim_gp('show') ? webim_gp('show') : "available";
	$imuser->url = profile_url("");
}

/*im服务器登录验证用户*/
function webim_login( $username, $password ) {
	global $imdb;
	if( empty($username) || empty($password) ) {
		return false;
	}
	$v = $imdb->get_var( $imdb->prepare( "SELECT id FROM $imdb->webim_users WHERE login=%s and password=%s", $username, $password ) );
	return !empty($v);
}

/**
 * Online buddy list.
 *
 */
function webim_get_online_buddies() {
	global $imuser, $imdb;
	$list = array();
	$query = $imdb->prepare( "SELECT * FROM $imdb->webim_users" );
	foreach( $imdb->get_results( $query ) as $value ) {
		$list[] = (object)array(
			"id" => $value->login,
			"nick" => $value->nick,
			"group" => "friend",
			"url" => profile_url($value->email),
			"pic_url" => gravatar($value->email)
		);
	}
	return $list;
}

/**
 * Get buddy list from given ids
 * $ids:
 *
 * Example:
 * 	buddy('admin,webim,test');
 *
 */

function webim_get_buddies( $names, $uids = null ) {
	global $_SGLOBAL, $imuser, $imdb;
	$where_name = "";
	$where_uid = "";
	if(!$names and !$uids)return array();
	if($names){
		$names = "'".implode("','", explode(",", $names))."'";
		$where_name = "login IN ($names)";
	}
	if($uids){
		$where_uid = "id IN ($uids)";
	}
	$where_sql = $where_name && $where_uid ? "($where_name OR $where_uid)" : ($where_name ? $where_name : $where_uid);
	$list = array();
	$query = $imdb->prepare( "SELECT * FROM $imdb->webim_users WHERE $where_sql" );
	foreach( $imdb->get_results( $query ) as $value ) {
		$list[] = (object)array(
			"id" => $value->login,
			"nick" => $value->nick,
			"group" => "friend",
			"url" => profile_url($value->email),
			"pic_url" => gravatar($value->email)
		);
	}
	return $list;
}

/** Don't delete this.*/
function webim_get_rooms(){
	return array();
}
