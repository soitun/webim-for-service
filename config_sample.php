<?php

$_IMC = array();
$_IMC["version"] = "@VERSION";//版本
$_IMC["enable"] = true;//开启webim
$_IMC["domain"] = "";//网站注册域名
$_IMC["apikey"] = "";//网站注册apikey
$_IMC["host"] = "";//im服务器
$_IMC["port"] = 8000;//服务端接口端口
$_IMC["theme"] = "base";//界面主题，根据webim/static/themes/目录内容选择
$_IMC["local"] = "zh-CN";//本地语言，扩展请修改webim/static/i18n/内容
$_IMC["emot"] = "default";//表情主题

//For SNS
//$_IMC["show_realname"] = false;//是否显示好友真实姓名
//$_IMC["disable_room"] = false;//禁止群组聊天
//$_IMC["disable_chatlink"] = false;//禁止页面名字旁边的聊天链接
//$_IMC["enable_shortcut"] = false;//支持工具栏快捷方式
//$_IMC['disable_menu'] = false; //隐藏工具条
//$_IMC['enable_login'] = false; //允许未登录时显示IM，并可从im登录
//$_IMC["host_from_domain"] = false; //设定im服务器为访问域名,当独立部署时,公网内网同时访问时用

//For Shop
/** db config */
$_IMC['dbuser'] = "root";
$_IMC['dbpassword'] = "";
$_IMC['dbname'] = "shopim";
$_IMC['dbhost'] = "localhost";
$_IMC['dbtable_prefix'] = "shop_"; //必填.
$_IMC['dbcharset'] = "utf8";

/** admin config*/
$_IMC['admin_login'] = "admin";
$_IMC['admin_password'] = "";
$_IMC['admin_email'] = "";

/** Site info*/
$_IMC['title'] = "在线支持";

?>
