<?php
/**
 * $Id:$
 * 在线咨询配置文件，可根据服务器具体情况修改配置。
 * 此文件中的变量会在 zxzx 对象实例初始化时进行装载，所以在别的文件中不要直接使用这些变量，
 * 而是使用 zxzx 对象实例的属性值代替。
 */

/**
 * @access public
 * @var array   $cfg_db_vars    资源平台数据库配置数组，当前可用于 mysql 和 oracle 数据库。
 *                              dbtype => 该值可为“mysql”（使用 MySQL 数据库时）或“oracle”（使用 ORACLE 数据库时）。
 *                              dbhost => 连接主机 IP 地址，若 mysql 数据库安装在本机，可使用“localhost”。
 *                              dbuser => 连接数据库的用户名，注意大小写。
 *                              dbpass => 连接数据库的密码，注意大小写。
 *                              dbname => 连接的数据库名，注意大小写。
 */
 
$cfg_db_vars = array(
    'dbtype' => 'mysql',
    'dbhost' => 'localhost',
    'dbuser' => 'K12User',
    'dbpass' => 'K12ProgPWD',
    'dbname' => 'K12zxzx');

//  {{{ 没有特殊情况，请勿修改以下配置变量


/**
 * @access public
 * @var string  $cfg_web_url    资源平台所在的目录 URL。
 */
$cfg_web_url = '/zxzx';

/**
 * @access public
 * @var string  $cfg_login_do_url    用户登录后台处理的 URL。
 */
$cfg_login_do_url = '/platform/app/login_do.php?Url=%s';

/**
 * @access public
 * @var integer  $cfg_sess_expiry    资源平台 SESSION 会话最大存活时间（单位：秒），如果超过此时间，则当前客户端用户会自动被中断会话，此时需要重新登录。
 */
$cfg_sess_expiry = 1200;    //一小时

$cfg_web_path = $_SERVER['DOCUMENT_ROOT'].$cfg_web_url;


/**
 * @access public
 * @var array   $cfg_user_types 资源平台用户身份数组。 
 */
$cfg_user_types = array('系统管理员', '调度员','专家解答'); 

/**
 * @access public
 * @var string  $cfg_logout_do_url    用户注销后台处理的 URL。
 */

$cfg_logout_do_url = '/platform/app/logout_do.php?url=%s';



/**
 * @access public
 * @var array   $cfg_display_runtime    是否在每个页面的最后显示注释的运行时间。
 */    
$cfg_display_runtime = TRUE;

/**
 * @access public
 * @var array   $cfg_debug_xmlrpc    是否处于 XML-RPC 调试状态。
 */
$cfg_debug_xmlrpc = FALSE;    

/**
 * @access public
 * @var array   $cfg_debug_smarty   是否处于 Smarty 调试状态。
 */
$cfg_debug_smarty = FALSE;    


$cfg_platform_xmlrpcs = array (
  'host' => '10.96.0.36',
  'port' => '80',
  'path' => '/platform/rpc/',
  'username' => 'K12RPC',
  'password' => 'K12RPCPwd',
);

//  }}}

//===========================================================
//  DEFINE CONSTS
//===========================================================
/**
 * 回车换行。
 * @access  public
 * @const   CRLF
 */
define('CRLF', "\n");
/**
 * 制表符。
 * @access  public
 * @const   TAB
 */
 
define('TAB', "\t");
//  定义ejob平台、ADOdb、Smarty、XMLRPC 所在目录。 	 
if (!defined('ZXZX_DIR')) 	 
    define('ZXZX_DIR', dirname(__FILE__) . '/'); 	 
if (!defined('ADODB_DIR')) 	 
    define('ADODB_DIR', ZXZX_DIR . '/../adodb/'); 	 
if (!defined('SMARTY_DIR')) 	 
    define('SMARTY_DIR', ZXZX_DIR . '/../smarty/'); 	 
if (!defined('XMLRPC_DIR')) 	 
    define('XMLRPC_DIR', ZXZX_DIR . '/../xmlrpc/');
//  权限定义
define('PRI_SUPER_ADMIN', 2);    
define('PRI_ADJUST_USER', 1);
define('PRI_ANSWER_USER', 0);
define('PRI_PLATFORM_USER',3);
?>
