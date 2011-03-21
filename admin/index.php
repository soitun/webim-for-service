<?php

require_once( dirname( __FILE__ ) . '/' . 'admin_common.php' );

/** Check install */
#if ( ! isset( $_IMC['version'] ) ) {
if ( ! webim_is_lock() ) {
	header("Location: install.php");
	exit();
}

if ( !webim_admin_is_login() ) {
	require_once("_login.php");
	exit();
}

/** Check update */
if ( version_compare( $_IMC['version'], $im_version, "<" ) ) {
	header("Location: upgrade.php");
	exit();
}

if ( isset( $_GET['add_user'] ) ) {
	$notice = "<p id='notice'>添加用户成功。</p>";
} elseif ( isset( $_GET['update_user'] ) ) {
	$notice = "<p id='notice'>更新用户成功。</p>";
} elseif ( isset( $_GET['del_user'] ) ) {
	$notice = "<p id='notice'>删除用户成功。</p>";
} elseif ( isset( $_GET['install'] ) ) {
	$notice = "<p id='notice'>安装成功。</p>";
} else {
}

echo webim_header( '用户管理' );
echo webim_menu( 'index' );
$users = $imdb->get_results( $imdb->prepare( "SELECT * FROM $imdb->webim_users" ) );
$webim_path = dirname(webim_urlpath()) . "/";

?>
<div id="content">
	<?php echo $notice ?>
	<h3 id="header-title">用户管理</h3>
	<p><a href="user.php">添加新用户</a></p>
	<table id="users">
		<thead>
			<tr>
				<th>帐号</th>
				<th>密码</th>
				<th>昵称</th>
				<th>邮箱</th>
				<th>限制域</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $users  as $user ) { ?>
			<tr>
				<td><?php echo $user->login . "@" . $_IMC["host"] ?></td>
				<td><?php echo $user->password ?></td>
				<td><?php echo $user->nick ?></td>
				<td><?php echo $user->email ?></td>
				<td><?php echo $user->domain ?></td>
				<td><a href="user.php?id=<?php echo $user->id ?>">修改</a>&nbsp;<!--a href="user.php?id=<?php echo $user->id ?>">删除</a--></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<h3 id="header-title">使用</h3>
	<p>在安装IM服务器时，验证地址填写下面地址：</p>
	<pre>
<?php echo $webim_path . "auth.php"; ?>
	</pre>
	<p>将下面代码插入需要安装WebIM的网页<?php echo htmlspecialchars("</body>") ?>标签前即可。
	<pre>
<?php 
echo htmlspecialchars( "<script type=\"text/javascript\" src=\"${webim_path}custom.js.php\"></script>" );
?>
	</pre>
	<p>如果有域限制，采用下面代码，更改一下域名参数</p>
	<pre>
<?php 
echo htmlspecialchars( "<script type=\"text/javascript\" src=\"${webim_path}custom.js.php?domain=webim20.cn\"></script>" );
?>
	</pre>

</div>
<script type="text/javascript" src="../custom.js.php"></script>
<?php
echo webim_footer();
?>
