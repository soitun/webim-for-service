<?php

require_once( dirname( __FILE__ ) . '/' . 'admin_common.php' );

webim_only_for_admin();

$user = new stdClass();
$id = webim_gp("id");
if ( !empty($id) ) {
	$user = $imdb->get_results( $imdb->prepare( "SELECT * FROM $imdb->webim_users WHERE id=%d", $id ) );
	$user = $user[0];
	if( !$user ) {
		$user = new stdClass();
	}
}

if( !empty($_POST) ) {
	$user->login = $_POST['login'];
	$user->password = $_POST['password'];
	$user->nick = $_POST['nick'];
	$user->email = $_POST['email'];
}

$type = $user->id ? "更新" : "添加";

$msg = "";
$success = false;

$valid = false;

if( isset( $_POST['_method'] ) && $_POST['_method'] == "delete" && $user->id ){
	$imdb->query( $imdb->prepare( "DELETE FROM $imdb->webim_users WHERE `id` = %d", $user->id ) );
	header("Location: index.php?del_user");
	exit();
}

if( isset( $_POST['login'] ) && isset( $_POST['password'] ) ){
	$errors = array();
	$ar = array( array("login", "用户名"), array("password", "密码"), array("nick", "昵称") );
	foreach( $ar as $val ) {
		if(empty($_POST[$val[0]])){
			$errors[] = $val[1] . "不能为空";
		}
	}
	if ( !preg_match( '/^[\da-zA-Z_]+$/', $_POST['login'] ) ) {
		$errors[] = "用户名无效";
	} else {
		$uid = $imdb->get_var( $imdb->prepare( "SELECT id FROM $imdb->webim_users WHERE login=%s", $_POST['login'] ) );
		if( $uid && $uid != $user->id ) {
			$errors[] = "此用户已存在";
		}
	}
	$valid = empty( $errors );
}

if( $valid ){
	if( $user->id ) {
		$user->updated_at = date( 'Y-m-d H:i:s' );
		$imdb->update( $imdb->webim_users, (array)$user, array("id" => $user->id) );
		header("Location: index.php?update_user");
	} else {
		$user->created_at = date( 'Y-m-d H:i:s' );
		$user->updated_at = date( 'Y-m-d H:i:s' );
		$imdb->insert( $imdb->webim_users, (array)$user );
		header("Location: index.php?add_user");
	}
}else{
	if ( isset( $_GET['success'] ) ) {
		$success = true;
		$notice = "<p id='notice'>更新成功。</p>";
	} elseif ( isset( $_GET['install'] ) ) {
		$notice = "<p id='notice'>安装成功。</p>";
	} else {
	}
}

echo webim_header( $type.'用户' );
echo webim_menu( 'index' );
$err = "";
$err_c = "";
if(!empty($errors)){
	$err_c = " box-error";
	$err = "<ul class=\"error\"><li>".implode($errors, "</li><li>")."</li></ul>";
}
$form = implode("", array( 
	webim_text_tag( "login", $user->login, "用户名：", "只允许字母，数字和_" ), 
	webim_text_tag( "password", $user->password, "密码：", "登录客户端密码" ), 
	webim_text_tag( "nick", $user->nick, "昵称：", "" ), 
	webim_text_tag( "email", $user->email, "邮箱：", "用来显示该邮箱在<a href='http://www.gravatar.com'>gravatar.com</a>上注册的头像" ), 
));
$msg = <<<EOF
		<div class="box$err_c">
		<h3>${type}用户</h3>
		<div class="box-c">
				<p class="box-desc"></p>
			$err
			<form action="" method="post" class="form">
				<input type="hidden" name="id" value="$user->id" />
$form
				<p class="actions clearfix"><input type="submit" class="submit" value="提交" /></p>
			</form>
		</div>
	</div>
EOF;

?>
<div id="content">
	<?php echo $notice ?>
	<?php echo $msg ?>
</div>
<?php
echo webim_footer();
?>
