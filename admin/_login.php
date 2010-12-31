<?php

$errors = array();
if(!empty($_POST)){
	if( webim_admin_login($_POST["login"], $_POST["password"]) ) {
		header("Location: index.php");
		exit();
	} else {
		$errors[] = "用户名或密码错误";
	}
}

$err = "";
$err_c = "";
if(!empty($errors)){
	$err_c = " box-error";
	$err = "<ul class=\"error\"><li>".implode($errors, "</li><li>")."</li></ul>";
}

$form = implode("", array( 
	webim_text_tag( "login", $_POST["login"], "用户名：", "" ), 
	webim_text_tag( "password", $_POST["password"], "密码：", "" ) 
));

$msg = <<<EOF
		<div class="box$err_c">
		<h3>登录</h3>
		<div class="box-c">
				<p class="box-desc">忘记用户名密码可直接在config.php文件里查看admin_login, admin_password</p>
			$err
			<form action="" method="post" class="form">
$form
				<p class="actions clearfix"><input type="submit" class="submit" value="提交" /></p>
			</form>
		</div>
	</div>
EOF;

echo webim_header( '' );

echo $msg;

echo webim_footer();


