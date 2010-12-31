<?php

require_once( dirname( __FILE__ ) . '/' . 'admin_common.php' );

webim_only_for_admin();

$unwritable_paths = webim_select_unwritable_path( false );

$msg = "";
$success = false;

if ( !empty($unwritable_paths ) ){

	$msg = webim_unwritable_log( $unwritable_paths );
	include_once( '_error.php' );
	exit();

}

if( isset( $_POST['host'] ) && isset( $_POST['domain'] ) && isset( $_POST['apikey'] ) ){
	webim_update_config();
	header("Location: settings.php?success");
}else{
	if ( isset( $_GET['success'] ) ) {
		$success = true;
		$notice = "<p id='notice'>更新成功。</p>";
	} else {
	}
}

echo webim_header( '配置' );
echo webim_menu( 'settings' );

$form = implode("", array( 
	webim_text_tag( "title", $_IMC['title'], "标题：", "聊天窗口显示的标题" ), 
	webim_text_tag( "host", $_IMC['host'], "im服务器地址：", "im服务器域名或IP" ), 
	webim_text_tag( "port", $_IMC['port'], "im服务器度端口：", "" ), 
	webim_text_tag( "domain", $_IMC['domain'], "im注册域名：", "网站注册域名" ), 
	webim_text_tag( "apikey", $_IMC['apikey'], "im注册apikey：", "网站注册域名" ), 
	webim_text_tag( "admin_login", $_IMC['admin_login'], "管理员帐号：", "登录管理后台使用" ), 
	webim_text_tag( "admin_password", $_IMC['admin_password'], "管理员密码：", "" ), 
));


?>
<div id="content">
				<?php echo $notice ?>
				<div class="box">
				<h3>更新基本配置</h3>
				<div class="box-c">
				<p class="box-desc"></p>
					<form action="" method="post" class="form">
<?php echo $form; ?>
						<p class="clearfix"><label for="local">本地语言：</label><select class="select" id="local" name="local">
						<option value="zh-CN" <?php echo $_IMC['local'] == 'zh-CN' ? 'selected="selected"' : '' ?>>简体中文</option>
						<option value="zh-TW" <?php echo $_IMC['local'] == 'zh-TW' ? 'selected="selected"' : '' ?>>繁体中文</option>
						<option value="en" <?php echo $_IMC['local'] == 'en' ? 'selected="selected"' : '' ?>>English</option>
						</select>
						</p>
						<p class="actions clearfix"><input type="submit" class="submit" value="提交" /></p>
					</form>
				</div>
				</div>

			</div>
<script type="text/javascript" src="../custom.js.php"></script>
<?php
echo webim_footer();
?>
