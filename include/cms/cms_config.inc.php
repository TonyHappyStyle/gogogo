<?php
/**
 * $Id: cms_config.inc.php,v 1.20 2004/05/27 01:37:26 yangh Exp $
 * 建网通配置文件，此文件不需要加密，可根据服务器具体情况修改配置。
 * 此文件中的变量会在 Cms 对象实例初始化时进行装载，所以在别的文件中不要直接使用这些变量，而是使用 Cms 对象实例的属性值代替。
 */

//  定义 CMS 包含文件所在目录的绝对路径
if (!defined('CMS_DIR')) 	 
    define('CMS_DIR', dirname(__FILE__) . '/'); 	 
 
//  包含 cms_config2.inc.php，此文件可通过基础平台产品配置进行修改（通过 XMLRPC 方法）
require_once CMS_DIR . 'cms_config2.inc.php';

/**
 * @access public
 * @var array  $cfg_multi   建网通是否是一台服务器装多套（使用虚拟主机方式）。
 */
$cfg_multi = FALSE;

//  {{{ 没有特殊情况，请勿修改以下配置变量

/**
 * @access public
 * @var array  $cfg_modules    建网通各个模块的配置数组。
 *                             TITLE 是模块的标题。
 *                             DIR 是程序或模块所在的目录，此目录以建网通的应用程序目录（默认为 /cms/app）或者模板目录（默认为 /cms/data/templates）为根。
 */
$cfg_modules = array(
    'INDEX'=>array('TITLE'=>'首页', 'DIR'=>''),
    'CHAT'=>array('TITLE'=>'聊天室', 'DIR'=>'chat'),
    'FORUM'=>array('TITLE'=>'论坛', 'DIR'=>'forum'),
    'HOME'=>array('TITLE'=>'主页空间', 'DIR'=>'home'),
    'KICQ'=>array('TITLE'=>'寻呼', 'DIR'=>'kicq'),
    'CAT'=>array('TITLE'=>'栏目', 'DIR'=>'info/cat'),
    'DOC'=>array('TITLE'=>'内容', 'DIR'=>'info/doc'),
    'POLL'=>array('TITLE'=>'调查', 'DIR'=>'info/poll'),
    'SEARCH'=>array('TITLE'=>'搜索', 'DIR'=>'info/search'),
    'SUBSCRIBE'=>array('TITLE'=>'订阅', 'DIR'=>'info/subscribe'),
    'USER'=>array('TITLE'=>'个性化设置', 'DIR'=>'user'),
    'ADMIN'=>array('TITLE'=>'网站管理', 'DIR'=>'admin'),
    'CONTRIBUTE'=>array('TITLE'=>'投稿', 'DIR'=>'admin/contribute'),
    'JYDT'=>array('TITLE'=>'教育地图', 'DIR'=>'jydt'),
    );

/**
 * @access public
 * @var string  $cfg_user_types    用户类型数组。
 */
$cfg_user_types = array('一般注册用户', '行政人员', '教职员工', '学生', '家长');    
    
/**
 * @access public
 * @var string  $cfg_cms_url    建网通所在的目录 URL。
 */
$cfg_cms_url = '/cms';

/**
 * @access public
 * @var string  $cfg_cms_path    建网通所在的物理目录路径。
 */
$cfg_cms_path = $_SERVER['DOCUMENT_ROOT'].$cfg_cms_url;

$cfg_go_url = $cfg_cms_url.'/go.php?pAct=%s&pUrl=%%s';

/**
 * @access public
 * @var string  $cfg_login_url    用户登录页面的 URL。
 */
$cfg_login_url = sprintf($cfg_go_url, 'login');

/**
 * @access public
 * @var string  $cfg_login_do_url    用户登录后台处理的 URL。
 */
$cfg_login_do_url = sprintf($cfg_go_url, 'login_do');

/**
 * @access public
 * @var string  $cfg_register_url    用户注册页面的 URL。
 */
$cfg_register_url = sprintf($cfg_go_url, 'register');

/**
 * @access public
 * @var string  $cfg_logout_do_url    用户注销后台处理的 URL。
 */
$cfg_logout_do_url = sprintf($cfg_go_url, 'logout_do');

/**
 * @access public
 * @var string  $cfg_retrieve_url   忘记密码之后获取密码的 URL。
 */
$cfg_retrieve_url = sprintf($cfg_go_url, 'retrieve');

/**
 * @access public
 * @var string  $cfg_user_type_url    用户“个人主页面”的 URL。
 */
$cfg_user_type_url = sprintf($cfg_go_url, 'user_main');

/**
 * @access public
 * @var string  $cfg_user_mod_url   用户“个人设置”页面的 URL。
 */
$cfg_user_mod_url = sprintf($cfg_go_url, 'user_mod');

/**
 * @access public
 * @var string  $cfg_user_info_url    查看某一特定用户信息的 URL。
 */
$cfg_user_info_url = sprintf($cfg_go_url, 'user_info');

//  }}}
