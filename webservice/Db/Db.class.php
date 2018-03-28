<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 17-4-26
 * Time: 下午4:02
 */

require_once "../config/config.php";

class Db {
    static private $_instance;
    static private $_connectSource;
    private $_dbConfig = array(
        'host' => 'mysql:host='.dbHOST.';dbname='.dbName.'',
        'user' => dbUser,
        'password' => dbPwd,
    );

    private function __construct() {
    }

    static public function getInstance() {
        if(!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function connect() {
        if(!self::$_connectSource) {

            self::$_connectSource = new PDO($this->_dbConfig['host'], $this->_dbConfig['user'], $this->_dbConfig['password']);

            self::$_connectSource->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$_connectSource->exec('set names utf8');
        }
        return self::$_connectSource;
    }
}




