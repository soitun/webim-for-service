<?php

require_once( dirname( __FILE__ ) . '/' . 'admin_common.php' );

#webim_only_for_admin();

webim_update_config( false );

$unwritable_paths = webim_select_unwritable_path( true );
$msg = "";
$success = false;

if( webim_is_lock() ) {
	$msg = '<div class="box"><div class="box-c">请先删除 install.lock 再重新安装。</div></div>';
	include_once( '_error.php' );
	exit();
}
elseif ( !empty($unwritable_paths ) ){

	$msg = webim_unwritable_log( $unwritable_paths );
	include_once( '_error.php' );
	exit();

} 
/*elseif ( !$imdb->ready ) {

	$msg = '<div class="box"><div class="box-c">不能连接数据库。</div></div>';
	include_once( '_error.php' );
	exit();

}
 */
$valid = false;
if( isset( $_POST['host'] ) && isset( $_POST['domain'] ) && isset( $_POST['apikey'] ) ){
	$errors = array();
	$ar = array( array("dbname", "数据库名"), array("dbhost", "数据库服务器"), array("dbtable_prefix", "数据库表前缀"), array("admin_login", "管理员帐号"), array("admin_password", "管理员密码"), array("admin_email", "管理员邮箱") );
	foreach( $ar as $val ) {
		if(empty($_POST[$val[0]])){
			$errors[] = $val[1] . "不能为空";
		}
	}
	if ( !preg_match( '/^[\da-zA-Z_]+$/', $_POST['admin_login'] ) ) {
		$errors[] = "管理员帐号无效";
	}
	if( empty($errors) ) {
		webim_update_config();
		$dbname = $_IMC["dbname"];
		//Create db.
		@mysql_query("create database if not exists $dbname", mysql_connect( $_IMC["dbhost"], $_IMC["dbuser"], $_IMC["dbpassword"] ));
		webim_initdb();
		if ( !$imdb->ready ) {
			$errors[] = "数据库配置错误，请检查数据库用户名密码";
		} else {
			webim_update_db();

		}
		$valid = empty($errors);
	}
}

if( $valid ){
	#webim_insert_template();
	#webim_clean_cache();
	webim_lock();
	webim_admin_login($_IMC["admin_login"], $_IMC["admin_password"]);
	header("Location: index.php?install");
}else{
	if ( isset( $_GET['success'] ) ) {
		$success = true;
		$msg = <<<EOF
	<div class="box">
		<h3>WebIM安装完成</h3>
		<div class="box-c">
			<p class="box-desc">你可以</p>
			<p>
				<a href="index.php">进入WebIM管理首页</a>
			</p>
		</div>
	</div>
EOF;
	} else {
		$err = "";
		$err_c = "";
		if(!empty($errors)){
			$err_c = " box-error";
			$err = "<ul class=\"error\"><li>".implode($errors, "</li><li>")."</li></ul>";
		}
		$form = implode("", array( 
			webim_text_tag( "host", $_IMC['host'], "im服务器地址：", "im服务器域名或IP" ), 
			webim_text_tag( "domain", $_IMC['domain'], "im注册域名：", "网站注册域名" ), 
			webim_text_tag( "apikey", $_IMC['apikey'], "im注册apikey：", "网站注册域名" ), 
			webim_text_tag( "dbhost", $_IMC['dbhost'], "数据库服务器：", "" ), 
			webim_text_tag( "dbname", $_IMC['dbname'], "数据库名：", "" ), 
			webim_text_tag( "dbuser", $_IMC['dbuser'], "数据库用户名：", "" ), 
			webim_text_tag( "dbpassword", $_IMC['dbpassword'], "数据库密码：", "" ), 
			webim_text_tag( "dbtable_prefix", $_IMC['dbtable_prefix'], "数据库表前缀：", "区分一个数据库中的多个im" ), 
			webim_text_tag( "admin_login", $_IMC['admin_login'], "管理员帐号：", "只允许字母，数字和_" ), 
			webim_text_tag( "admin_password", $_IMC['admin_password'], "管理员密码：", "" ), 
			webim_text_tag( "admin_email", $_IMC['admin_email'], "管理员邮箱：", "" ), 
		));

		$msg = <<<EOF
		<div class="box$err_c">
		<h3>设置安装信息</h3>
		<div class="box-c">
				<p class="box-desc">请先安装im服务器获得注册域名和apikey</p>
			$err
			<form action="" method="post" class="form">
$form
				<p class="actions clearfix"><input type="submit" class="submit" value="提交" /></p>
			</form>
		</div>
	</div>
EOF;
	}
}

echo webim_header( '安装' );

echo $success ? webim_menu( '' ) . "<div id=content>$msg</div>" : $msg;

echo webim_footer();

?>
