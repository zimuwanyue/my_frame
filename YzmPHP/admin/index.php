<?php

header("Content-type:text/html;charset=utf-8");//设置框架编码
ini_set("date.timezone", "Asia/Shanghai");//设置时间区域

define('APP_PATH', __DIR__.'/');//定义我们的项目路径常量
define('Lib','../YzmPHP');//定义我们的框架目录常量
define('Resource', APP_PATH.'Resource');//定义我们的项目资源目录常量
define('APP_DEBUG', true);
ini_set("display_errors",true);//是否抛出错误

$_REQUEST['mod'] = $GLOBALS["argv"]["1"];
$_REQUEST['action'] = $GLOBALS["argv"]["2"];
var_dump($_REQUEST['mod']);

require Lib.'/YzmPHP.php';
$app = new YzmPHP();
$app->run();
$app = null;