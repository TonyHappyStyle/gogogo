<?php
$cfg_db_type = 'mysql';
$cfg_db_vars = array (
  'oracle' => 
  array (
    'dbtype' => 'oci8po',
    'dbhost' => '192.168.212.67',
    'dbuser' => 'k12jwt',
    'dbpass' => 'k12jwt',
    'dbname' => 'cohere.k12',
  ),
  'mysql' => 
  array (
    'dbtype' => 'mysql',
    'dbhost' => 'localhost',
    'dbuser' => 'root',
    'dbpass' => '',
    'dbname' => 'xh_2011_web',
  ),
);
$cfg_platform_xmlrpcs = array (
  'host' => 'xh-platform.yz',
  'port' => '80',
  'path' => '/platform/rpc/index.k12.php',
  'username' => 'K12RPC',
  'password' => 'K12RPCPwd',
);
?>