<?php
require_once "../../config/config.php";

// 消息推送
define('NEWSPUSHURL','http://192.168.2.18:9090/services/Generic?wsdl');

// 推送来源
define('SOURCE', 0);

// 推送类型
define('TYPE', 126);

// 数据库主机
define("dbHOST" , $CONFIG['dbhost']);

// 数据库名称
define("dbName" , $CONFIG['dbname']);

// 数据库用户
define("dbUser", $CONFIG['dbuser']);

// 数据库密码
define("dbPwd" , $CONFIG['dbpassword']);

require_once "../lib/nusoap.php";

