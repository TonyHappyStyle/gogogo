<?php
include_once dirname(__FILE__).'/Cache.class.php';

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

/**
 * 公告栏目ID。
 */
define('ANNOUNCE_CAT_ID', 4569);

/**
 * 包含 CMS_Common 类（通用函数类）文件。
 */

require_once 'cms/cms_common.class.php';

/**
 * 建网通基础类。
 * 此类主要用在建网通的公共包含文件 cms_system.inc.php 中，只需创建一个实例 $cms，整个系统均使用此实例。
 *
 * @copyright   K12 Studio
 * @package     K12CMS
 * @access      public
 */
class CMS
{
    //  {{{ private properties

    /**
     * CMS 类文件的 Id，用于 CVS 版本追踪。
     * @var string
     * @access  private
     */
    public $_id = '$Id: cms.class.php,v 1.62 2004/07/20 07:30:20 yangh Exp $';

    /**
     * 建网通所用到的数据库的完全配置数组。包含了数据库类型，连接的主机、用户名、密码等信息，此变量信息来自配置文件。
     * @var     array
     * @access private
     */
    public $_db_vars = array();

    //  }}}

    //  {{{ public properties

    /**
     * 建网通全称。
     * @var string
     * @access  public
     */
    public $cms_name = 'K12网站信息发布系统';

    /**
     * 建网通版本号。
     * @var string
     * @access  public
     */
    public $cms_version = '1.00';

    /**
     * 建网通是否为多套版（使用虚拟主机方式）。
     * @var string
     * @access  public
     */
    public $multi = FALSE;

    /**
     * 访问基础平台 XMLRPC 的参数
     * @var array
     * @access  public
     */
    public $platform_xmlrpcs = array();

    /**
     * 建网通所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $cms_path = '';

    /**
     * 建网通所在的目录 URL。
     * @var string
     * @access  public
     */
    public $cms_url = '';

    /**
     * 主页空间所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $home_path = '';

    /**
     * 建网通应用程序所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $app_path = '';

    /**
     * 建网通应用程序所在目录的 URL。
     * @var string
     * @access  public
     */
    public $app_url = '';

    /**
     * 建网通数据所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $data_path = '';

    /**
     * 建网通数据所在目录的 URL。
     * @var string
     * @access  public
     */
    public $data_url = '';

    /**
     * 建网通模板文件所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $tpl_path = '';

    /**
     * 建网通模板文件所在目录的 URL。
     * @var string
     * @access  public
     */
    public $tpl_url = '';

    /**
     * 建网通信息发布的主题目录所在的物理路径。
     * @var string
     * @access  public
     */
    public $theme_path = '';

    /**
     * 建网通信息发布的主题目录的 URL。
     * @var string
     * @access  public
     */
    public $theme_url = '';

    /**
     * 建网通当前信息发布的主题文件所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $cur_theme_path = '';

    /**
     * 建网通当前信息发布的主题文件所在目录的 URL。
     * @var string
     * @access  public
     */
    public $cur_theme_url = '';


    /**
     * 建网通 LOGO 所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $logo_path = '';

    /**
     * 建网通 LOGO 所在目录的 URL。
     * @var string
     * @access  public
     */
    public $logo_url = '';

    /**
     * 建网通 BANNER 所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $banner_path = '';

    /**
     * 建网通 BANNER 所在目录的 URL。
     * @var string
     * @access  public
     */
    public $banner_url = '';

    /**
     * 建网通用户头像所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $face_path = '';

    /**
     * 建网通用户头像所在目录的 URL。
     * @var string
     * @access  public
     */
    public $face_url = '';

    /**
     * 建网通用户上传头像文件的宽度和高度的最大值，单位为像素（pixel）。
     * @var integer
     * @access  public
     */


	 /**
     * 建网通浮动新闻所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $pic_path = '';

    /**
     * 建网通浮动图片所在目录的 URL。
     * @var string
     * @access  public
     */
    public $pic_url = '';

    public $face_max_width = 150;

    /**
     * 建网通用户上传头像文件大小的最大值。
     * @var integer
     * @access  public
     */
    public $face_max_filesize = 20000;

    /**
     * 建网通用户头像文件类型。
     * @var string
     * @access  public
     */
    public $face_type = 'gif|jpg|jpeg|png|bmp';

    /**
     * 建网通栏目页面 PHP 文件的 URL，默认值为“/cms/app/info/cat/index.php?pCatId=%d”，使用 sprintf() 函数将 %d 替换成相应的栏目 ID 号。
     * @var string
     * @access  public
     */
    public $cat_index_url = '';

    /**
     * 建网通文章内容页面 PHP 文件的 URL，默认值为“/cms/app/info/doc/index.php?pCatBodyId=%d”，使用 sprintf() 函数将 %d 替换成相应的文章内容 ID 号。。
     * @var string
     * @access  public
     */
    public $doc_index_url = '';

    /**
     * 建网通用户在“网站管理”中设置的配置文件所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $config_path = '';

    /**
     * 建网通所在的物理目录路径。
     * @var string
     * @access  public
     */
    public $tmp_path = '';

    /**
     * 建网通 ADOdb Cache 所在的物理目录路径数组。
     * @var string
     * @access  public
     */
    public $adodb_cache_paths = array();

    /**
     * 建网通使用的 ADOdb 数据库连接对象。
     * @var object ADOConnection
     * @access  public
     */
    public $conn = NULL;

    /**
     * 建网通使用的 Smarty 对象实例。
     * @var object Smarty
     * @access  public
     */
    public $smarty = NULL;

    /**
     * 建网通信息发布类 CMS_Info 的对象实例。
     * @var object CMS_Info
     * @access  public
     */
    public $cms_info = NULL;
	 /**
     * 建网通人才交流类 CMS_Rcjl 的对象实例。
     * @var object CMS_Rcjl
     * @access  public
     */
    public $cms_rcjl=NULL;

    /**
     * 建网通主页空间类 CMS_Home 的对象实例。
     * @var object CMS_Home
     * @access  public
     */
    public $cms_home = NULL;

	/**
     * 建网通网络寻呼类 CMS_Kicq 的对象实例。
     * @var object CMS_Kicq
     * @access  public
     */
	public $cms_kicq = NULL;

	/**
     * 建网通论坛类 CMS_Forum 的对象实例。
     * @var object CMS_Forum
     * @access  public
     */
	public $cms_forum = NULL;

	/**
     * 建网通聊天类 CMS_Chat 的对象实例。
     * @var object CMS_Chat
     * @access  public
     */
	public $cms_chat = NULL;

    /**
     * 当前所用的操作系统，其值可为 win（Windows 操作系统）、lin（Linux 操作系统）、sun（solaris 操作系统）等。
     * @var string
     * @access  public
     */
    public $os = '';

    /**
     * 建网通使用的主要数据库类型，其值可为 mysql、oracle。
     * @var string
     * @access public
     */
	public $db_type = '';

    /**
     * 建网通当前正在使用的数据库类型，其值可为 mysql、oracle。
     * @var string
     * @access public
     */
    public $db_cur_type = '';

    /**
     * 建网通各个模块的配置数组。
     * @var array
     * @access public
     */
    public $modules = array();

    /**
     * 建网通当前模块名，来自 $modules 的键值。
     * @var string
     * @access public
     */
    public $module = '';

    /**
     * 建网通综合参数配置数组。
     * @var array
     * @access public
     */
    public $arguments = array();

    /**
     * 建网通栏目相关配置的数组变量。
     * @var array
     * @access public
     */
    public $cats = array();

    /**
     * 建网通最上层栏目 ID 号集合的数组变量。
     * @var array
     * @access public
     */
    public $cat_tops = array();


    /**
     * 建网通频道相关配置的数组变量。
     * @var array
     * @access public
     */
    var $channels = array();

    /**
     * 建网通频道相关配置的数组变量。
     * @var array
     * @access public
     */
    var $channel_orders = array();


    /**
     * 用户 ID 使用的 SESSION 名，调用此值的时候，使用 $_SESSION[$this->sess_user_id]。
     * @var string
     * @access  public
     */
    public $sess_user_id   = 'CMS_USER_ID';

    /**
     * 用户 SID （自动递增值）使用的 SESSION 名。
     * @var string
     * @access  public
     */
    public $sess_user_sid   = 'CMS_USER_SID';

    /**
     * 用户真实姓名所使用的 SESSION 名。
     * @var string
     * @access  public
     */
    public $sess_true_name = 'CMS_USER_TRUE_NAME';

    /**
     * 用户昵称所使用的 SESSION 名。
     * @var string
     * @access  public
     */
    public $sess_nick_name = 'CMS_USER_NICK_NAME';

    /**
     * 用户头像所使用的 SESSION 名。
     * @var string
     * @access  public
     */
    public $sess_user_face = 'CMS_USER_FACE';

    /**
     * 用户类型所使用的 SESSION 名。
     * @var string
     * @access  public
     */
	public $sess_user_type = 'CMS_USER_TYPE';

    /**
     * 用户是否有“网站管理”权限所使用的 SESSION 名。
     * @var string
     * @access  public
     */
    public $sess_user_perm = 'CMS_USER_PERM';

    /**
     * 用户在基础平台的 SESSION ID 使用的 SESSION 名，调用此值的时候，使用 $_SESSION[$this->sess_platform_sessid]。
     * @var string
     * @access  public
     */
    public $sess_platform_sessid   = 'CMS_PF_ID';

    /**
     * 基础平台的 URL。
     * @var string
     * @access  public
     */
    public $sess_platform_url = 'CMS_PF_URL';

    /**
     * 用户登录页面的 URL。
     * @var string
     * @access  public
     */
    public $login_url = '';

    /**
     * 用户登录后台处理的 URL。
     * @var string
     * @access  public
     */
    public $login_do_url = '';

    /**
     * 用户注册页面的 URL。
     * @var string
     * @access  public
     */
    public $register_url = '';

    /**
     * 用户注销后台处理的 URL。
     * @var string
     * @access  public
     */
    public $logout_do_url = '';

    /**
     * 用户忘记密码后获取密码页面的 URL。
     * @var string
     * @access  public
     */
    public $retrieve_url = '';

    /**
     * 用户“个人主页面”的 URL。
     * @var string
     * @access  public
     */
    public $user_type_url = '';

    /**
     * 用户“个人设置”页面的 URL。
     * @var string
     * @access  public
     */
    public $user_mod_url = '';

    /**
     * 用户信息的 URL。
     * @var string
     * @access  public
     */
    public $user_info_url = '';

    /**
     * 用户域数组。
     * @var array
     * @access  public
     */
    //var $domains = array();

    /**
     * 用户类型数组。
     * @var array
     * @access  public
     */
    public $user_types = array();

	/**
     * 网络寻呼的 URL。
     * @var string
     * @access  public
     */
    public $kicq_url = '';

    /**
     * 建网通的错误触发开关
     * @var boolean
     * @access  public
     */
    public $trigger = TRUE;

    /**
     * 所有子栏目的数组
     * @var array
     * @access  public
     */
    public $children = array();

    /**
     * 子栏目的数组的键值
     * @var array
     * @access  public
     */
    public $children_key = 1;


    /**
     * 建网通的最后一次错误信息
     * @var string
     * @access  public
     */
    public $errmsg = '';

    //  }}}

    //  {{{ constructor

    /**
     * CMS 的类构造函数。在此构造函数中将对建网通所用到的目录路径进行初始化。
     *
     * @access  public
     * @global  array   $_SERVER    PHP 服务器环境变量数组
     * @return  void
     */
    public function CMS()
    {
        //  OS.
        $this->os = strtolower(substr(PHP_OS, 0, 3));
        //  Load CMS config and argument file, then assign properties value.
        require_once 'cms/cms_config.inc.php';
        if (!isset($cfg_multi)) $cfg_multi = FALSE; //兼容单套版
        $this->multi        = &$cfg_multi;
        $this->db_type      = &$cfg_db_type;
        if ($this->multi) { //多套版，overload $cfg_db_vars
            $this->cms_version .= ' 多套版';
            require_once 'cms/virtual/'.$_SERVER['SERVER_NAME'].'.php';
        }   //end if
        $this->platform_xmlrpcs = &$cfg_platform_xmlrpcs;   //基础平台 XMLRPC 参数
        $this->_db_vars     = &$cfg_db_vars;
        $this->cms_path     = &$cfg_cms_path;
        $this->cms_url      = &$cfg_cms_url;
        $this->home_path    = &$cfg_home_path;
        $this->modules      = &$cfg_modules;
        $this->user_types   = &$cfg_user_types;

        $this->login_url    = &$cfg_login_url;
        $this->login_do_url = &$cfg_login_do_url;
        $this->register_url = &$cfg_register_url;
        $this->logout_do_url= &$cfg_logout_do_url;
        $this->retrieve_url = &$cfg_retrieve_url;
        $this->user_type_url= &$cfg_user_type_url;
        $this->user_mod_url = &$cfg_user_mod_url;
        $this->user_info_url= &$cfg_user_info_url;

        /*if (file_exists($cfg_domain_path)) {
            require $cfg_domain_path;
            $this->domains  = &$sDomain;
        } else {
            $this->domains = array('k12.com.cn');
        }   //end if*/

        $this->app_path     = $this->cms_path.'/app';
        $this->app_url      = $this->cms_url.'/app';

        $this->data_path    = $this->cms_path.'/data';
        $this->data_url     = $this->cms_url.'/data';
        $this->config_path  = $this->data_path.'/configs';
        $this->tpl_path     = $this->data_path.'/templates';
        $this->tpl_url      = $this->data_url.'/templates';
        $this->theme_path   = $this->tpl_path.'/themes';
        $this->theme_url    = $this->tpl_url.'/themes';
        $this->logo_url     = $this->tpl_url.'/images/logos';
        $this->logo_path    = $this->tpl_path.'/images/logos';
        $this->banner_url   = $this->tpl_url.'/images/banners';
        $this->banner_path  = $this->tpl_path.'/images/banners';
        $this->face_url     = $this->tpl_url.'/images/faces';
        $this->face_path    = $this->tpl_path.'/images/faces';
	$this->pic_url     = $this->tpl_url.'/images/pics';
        $this->pic_path    = $this->tpl_path.'/images/pics';

        $this->cat_index_url      = $this->app_url.'/'.$this->modules['CAT']['DIR']."/index.php/%d";
        $this->doc_index_url      = $this->app_url.'/'.$this->modules['DOC']['DIR']."/index.php/%d";

        $this->tmp_path     = $this->cms_path.'/tmp';

        $this->adodb_cache_paths = array(
            'Forum' => $this->tmp_path.'/adodb_cache/forum',
            'Info' => $this->tmp_path.'/adodb_cache/info',
            'Misc' => $this->tmp_path.'/adodb_cache/misc'
        );

        switch ($this->os) {
            case 'win':  //Windows
                $this->home_path = $this->cms_path.'/home';
                break;
            case 'sun':  //Solaris
                $this->home_path = '/export/home/home';
                break;
            default:    //Linux
                $this->home_path = '/home/home';
                break;
        }   //end switch

        //  Load user setting files.
        require_once $this->config_path.'/cms_argument.inc.php';
        $this->arguments    = &$cfg_arguments;
        $this->cur_theme_path   = $this->theme_path.'/'.$this->arguments['theme'];
        $this->cur_theme_url    = $this->theme_url.'/'.$this->arguments['theme'];
		$this->kicq_url.'/'.$this->cms_path.'/kicq';
        setlocale(LC_TIME, 'zh_CN.GB18030');    //设置时区
     }   //end function

    //  }}}

    //  {{{ set_platform_url()

    /**
     * 设置基础平台 URL。
     *
     * @return  void
     * @access  public
     */
	/*
    function set_platform_url()
    {
        global $_SESSION;

        $this->login_url    = $_SESSION[$this->sess_platform_url].$this->login_url;
        $this->login_do_url = $_SESSION[$this->sess_platform_url].$this->login_do_url;
        $this->register_url = $_SESSION[$this->sess_platform_url].$this->register_url;
        $this->logout_do_url= $_SESSION[$this->sess_platform_url].$this->logout_do_url;
        $this->retrieve_url = $_SESSION[$this->sess_platform_url].$this->retrieve_url;
        $this->user_type_url= $_SESSION[$this->sess_platform_url].$this->user_type_url;
        $this->user_mod_url = $_SESSION[$this->sess_platform_url].$this->user_mod_url;
        $this->user_info_url= $_SESSION[$this->sess_platform_url].$this->user_info_url;
    }   //end function
    */
    //  }}}

    //  {{{ _assign_global()

    /**
     * 用于给全局的模板匹配符赋值。
     *
     * @global  array $_SESSION PHP session 的全局数组变量
     * @global  array $_SERVER  PHP 服务器参数的全局数组变量
     * @access  private
     * @return  void
     */
    function _assign_global()
    {

        $request_uri = $_SERVER["REQUEST_URI"];
        if (function_exists('cms_filter')) {
            $request_uri = cms_filter($request_uri);
        }
        $cur_url = urlencode('http://'.$_SERVER['SERVER_NAME'].preg_replace('/(\?|&)sessid=[^&]*/', '', $request_uri));
        $this->smarty->assign_by_ref('s_site_name', $this->arguments['site_name']);
        $this->smarty->assign_by_ref('s_master_mail', $this->arguments['master_mail']);
        $this->smarty->assign_by_ref('s_page_width', $this->arguments['page_width']);
        $this->smarty->assign_by_ref('s_module_name', $this->modules[$this->module]['TITLE']);
        $this->smarty->assign_by_ref('s_site_url', $this->cms_url);
        $this->smarty->assign_by_ref('s_app_url', $this->app_url);
        $this->smarty->assign_by_ref('s_template_url', $this->tpl_url);
        $this->smarty->assign_by_ref('s_theme_url', $this->cur_theme_url);
        $this->smarty->assign('s_login_url', sprintf($this->login_url, $cur_url));
        $tmp_s_login_do_url = sprintf($this->login_do_url, $cur_url);
        $this->smarty->assign_by_ref('s_login_do_url', $tmp_s_login_do_url);
        $this->smarty->assign('s_register_url', sprintf($this->register_url, $cur_url));
        $this->smarty->assign('s_logout_do_url', sprintf($this->logout_do_url, urlencode('http://'.$_SERVER['SERVER_NAME'].$this->cms_url.'/?logout=1')));
        $this->smarty->assign_by_ref('s_retrieve_url', $this->retrieve_url);

        $this->smarty->assign('s_js_menu_url', $this->app_url.'/js/menu.js.php?pPath='.urlencode($this->app_url.'/js/menuContainer.html'));
        $this->smarty->assign('s_poll_do_url', $this->app_url.'/'.$this->modules['POLL']['DIR'].'/do.php?token='.cms_form_token('poll'));
        $this->smarty->assign('s_poll_url', $this->app_url.'/'.$this->modules['POLL']['DIR'].'/index.php');
        $tmp_search_do_url = $this->app_url.'/'.$this->modules['SEARCH']['DIR'].'/index.php';
        if (function_exists('cms_form_token')) {
            $tmp_search_do_url .= '?token='.cms_form_token('search');
        }
        $this->smarty->assign('s_search_do_url', $tmp_search_do_url);

        $this->smarty->assign_by_ref('s_user_id', $_SESSION[$this->sess_user_id]);
        $this->smarty->assign_by_ref('s_true_name', $_SESSION[$this->sess_true_name]);
        $this->smarty->assign_by_ref('s_nick_name', $_SESSION[$this->sess_nick_name]);
        $this->smarty->assign_by_ref('s_user_face', $_SESSION[$this->sess_user_face]);
        $this->smarty->assign_by_ref('s_user_perm', $_SESSION[$this->sess_user_perm]);
	$this->smarty->assign_by_ref('s_user_type', $_SESSION[$this->sess_user_type]);
	$sw_teacher_flag = substr($_SESSION[$this->sess_user_type],2,1);
	$this->smarty->assign_by_ref('sw_teacher_flag', $sw_teacher_flag);
	$tmp_s_now_url = urlencode('http://'.$_SERVER['SERVER_NAME'].$this->cms_url);
	$this->smarty->assign_by_ref('s_now_url', $tmp_s_now_url);
    }   //end function

    //  }}}

    //  {{{ _get_format_date()

    /**
     * 获取格式化后的时间字符串。
     *
     * @access  public
     * @param   string  $format     设置时间格式的字符串，可用的转换符（%a、%A 等等）和 PHP strftime() 函数的一样，默认值为“%Y-%m-%d”。
     * @param   integer $timestamp  需要格式化的时间戳。
     * @return  string
     */
    function _get_format_date($format, $timestamp = 0)
    {
        if (empty($format))
            $format = "%Y-%m-%d";
        if (empty($timestamp))
            $timestamp = time();
        return strftime($format, $timestamp);
    }   //end function

    //  }}}

    //  {{{ set_trigger_error()

    /**
     * 设置建网通错误触发器开关
     *
     * @param   boolean $flag   开关标记，TRUE 表示开，FALSE 表示关。
     * @access  public
     * @return  void
     */
    function set_trigger_error($flag = TRUE)
    {
        $this->trigger = ($flag) ? TRUE : FALSE;
    }   //end function

    //  }}}

    //  {{{ trigger_error()

    /**
     * 建网通错误触发器
     *
     * @param   string  $error_msg  用于输出的错误信息。
     * @param   integer $error_type 错误类型，默认值为 E_USER_WARNING，即为警告错误。
     *                              取值范围：
     *                                  E_USER_ERROR   -> 错误级别错误
     *                                  E_USER_WARNING -> 警告级别错误
     *                                  E_USER_NOTICE  -> 注意级别错误
     * @access  public
     * @return  void
     */
    function trigger_error($error_msg, $error_type = E_USER_WARNING)
    {
        $this->errmsg = $error_msg;
        if ($this->trigger)
            trigger_error('CMS 错误：'.$error_msg, $error_type);
    }   //end function

    //  }}}

        //  {{{ get_adodb_conn()

    /**
     * 用于获取建网通所用的 ADOdb 数据库连接对象。
     *
     * @global  string  $ADODB_FETCH_MODE   ADOdb 的数据集返回模式。
     * @param   string  $db_type    数据库连接所用的数据库类型。
     * @param   string  $cache_key  数组“$this->adodb_cache_paths”的键值，当前可用值为：
     *                                  'Info'  -> 信息发布的 ADOdb cache 目录。
     *                                  'Forum' -> 论坛的 ADOdb cache 目录。
     *                                  'Misc'  -> 其它杂项的 ADOdb cache 目录。
     * @access  public
     * @return  object ADOConnection
     */
    function &get_adodb_conn($db_type = '', $cache_key = 'Misc')
    {
        global $ADODB_FETCH_MODE;

        //包含 ADOdb 类文件。
        require_once 'adodb/adodb.inc.php';

        //  设置 ADOdb
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;    //设置 ADOdb 返回值模式为“数字下标”
        $this->set_adodb_cache_dir($cache_key); //设置 ADOdb Cache 目录

        if (NULL == $this->conn || $this->db_cur_type != $db_type) {
            $db_array = ('' != $db_type)
                ? $this->_db_vars[$db_type]
                : $this->_db_vars[$this->db_type];
            $this->conn = &ADONewConnection($db_array['dbtype']);
            $this->conn->NConnect($db_array['dbhost'],
                                $db_array['dbuser'],
                                $db_array['dbpass'],
                                $db_array['dbname']);
            $this->db_cur_type = $db_type;
        }   //end if
        $this->conn->cacheSecs = 86400; //3600 * 24, cache 24 hours.
        $this->conn->execute('set names utf8;');
		return $this->conn;
    }   //end function

    //  }}}

    //  {{{ get_smarty()

    /**
     * 用于获取建网通所用的 Smarty 对象。
     *
     * @access  public
     * @return  object Smarty
     */
    function &get_smarty()
    {
        //包含 Smarty 类文件。
        require_once 'smarty/Smarty.class.php';

        if ('smarty' != get_class($this->smarty)) {
            $this->smarty = new Smarty;
            $this->smarty->template_dir = $this->tpl_path.'/';
            $this->smarty->compile_dir = $this->tmp_path.'/templates_c/';
            $this->smarty->config_dir = $this->config_path.'/';
            $this->smarty->cache_dir = $this->tmp_path.'/smarty_cache/';
            $this->smarty->caching = FALSE;
            $this->_assign_global();
            // 设置语言输出过滤器（Nio, 2013-10-11, #7004）
            $this->smarty->register_outputfilter(array($this, 'lang_trans_smarty'));
        }
        return $this->smarty;
    }   //end function

    //  }}}

    //  {{{ get_cms_info()

    /**
     * 用于获取建网通所用的信息发布类 CMS_Info 的对象实例。
     *
     * @access  public
     * @return  object CMS_Info
     */
    function &get_cms_info()
    {
        //包含 CMS_Info 类（信息发布类）文件。
        require_once 'cms/cms_info.class.php';

        if ('cms_info' != get_class($this->cms_info)) {
            $this->cms_info = new CMS_Info($this);
        }
        return $this->cms_info;
    }   //end function

    //  }}}
	 //  {{{ get_cms_rcjl()

    /**
     * 用于获取建网通所用的人才交流类 CMS_Rcjl 的对象实例。
     *
     * @access  public
     * @return  object CMS_Rcjl
     */
    function &get_cms_rcjl()
    {
        //包含 CMS_Info 类（信息发布类）文件。
        require_once 'cms/cms_rcjl.class.php';

        if ('cms_rcjl' != get_class($this->cms_rcjl)) {
            $this->cms_rcjl = new CMS_Rcjl($this);
        }
        return $this->cms_rcjl;
    }   //end function

    //  }}}

    //  {{{ get_cms_home()

    /**
     * 用于获取建网通主页空间类 CMS_Home 的对象实例。
     *
     * @access  public
     * @return  object CMS_Home
     */
    function &get_cms_home()
    {
        //包含 CMS_Home 类（主页空间类）文件。
        require_once 'cms/cms_home.class.php';

        if ('cms_home' != get_class($this->cms_info)) {
            $this->cms_home = new CMS_Home($this);
        }
        return $this->cms_home;
    }   //end function

    //  }}}
	 //  {{{ echo_cat_tree_top()

    /**
     * 输出栏目页面左边的树状栏目列表的 HTML 代码（包括 JavaScript 代码）。
     *
     * @access  public
     * @global  array   $_REQUEST   PHP 的请求变量数组，包括 POST 和 GET 方法的请求变量。
     * @param   array   $params 参数数组，格式
     *                          array (
     *                              'cat_id' => 当前栏目 ID 号，若不设置此参数，则使用 $_REQUEST['pCatId'] 作为栏目 ID 号，若 $_REQUEST['pCatId'] 的值也为空，则显示所有的栏目树。
     *                              'expand' => 设置树的展开方式取值可为 none（不展开任何树，搜索页面的默认值） 和 all（展开所有的树，栏目页面的默认值）
     *                          )
     * @return  void
     */
    function echo_cat_tree_top($cat_id)
    {


        $this->require_cat_array();
      //  extract($params);   //分解参数，有两个参数：$cat_id 和 $expand
        if (!isset($cat_id))
            $cat_id = $_REQUEST['pCatId'];
        if (!isset($expand))
            $expand = 'none';
        if ('all' != $expand && 'none' != $expand) {
            $this->trigger_error('CMS 类的 echo_cat_tree() 方法操作失败，参数 expand 的取值只能是 none 或 all，而不能是其它值。');
            return NULL;
        }   //end if

        $code = '
            <script language="JavaScript">
                var sImagePath = "'.$this->tpl_url.'/images/cat_tree/";
                webFXTreeConfig = {
                    rootIcon        : sImagePath + "root.gif",
                    openRootIcon    : sImagePath + "openroot.gif",
                    folderIcon      : sImagePath + "foldericon.gif",
                    openFolderIcon  : sImagePath + "openfoldericon.gif",
                    fileIcon        : sImagePath + "file.gif",
                    iIcon           : sImagePath + "I.gif",
                    lIcon           : sImagePath + "L.gif",
                    lMinusIcon      : sImagePath + "L.gif",
                    lPlusIcon       : sImagePath + "L.gif",
                    tIcon           : sImagePath + "T.gif",
                    tMinusIcon      : sImagePath + "T.gif",
                    tPlusIcon       : sImagePath + "T.gif",
                    blankIcon       : sImagePath + "blank.gif",
                    defaultText     : "",
                    defaultAction   : "javascript:void(0);",
                    defaultBehavior : "classic"
                };'.CRLF;

        if ($cat_id) {
            $code .= $this->get_cat_tree_js($cat_id,$expand);
        } else {
            foreach ($this->cat_tops as $cat_top_id)
                $code .= $this->get_cat_tree_js($cat_top_id,$expand);
        }   //end if
        $code .= '
            </script>'.CRLF;
        echo $code;
    }   //end function

    //  }}}

        //  }}}
    function get_cat_tree_js_paiming($cat_id)
    {

        $code = '';
        $path="content_cat_author_top.php?catid=".$cat_id;
  // echo "path=".$this->cat_index_url_path;
        $cur_name = 'oT'.$cat_id;   //当前节点名
        $parent = $this->cats[$cat_id]['PARENT'];   //上层节点名
        if (!$parent) { //最上层
            $parent_name = '';
            $code .= 'var '.$cur_name.' = new WebFXTree("'.addslashes($this->cats[$cat_id]['NAME']).'", "'.sprintf($path, $cat_id).'");'.CRLF;

        } else {    //非最上层
            $parent_name = 'oT'.$parent;
        }   //end if


        $children = $this->cats[$cat_id]['CHILD'];
        if (count($children) > 0) { //有子节点

            if ($parent_name) {

                $code .= 'var '.$cur_name.' = new WebFXTreeItem("'.addslashes($this->cats[$cat_id]['NAME']).'", "'.sprintf($path, $cat_id).'");'.CRLF;
                $code .= $parent_name.'.add('.$cur_name.');'.CRLF;

            }   //end if
            ksort($children);
            foreach($children as $next_cat_id) //递归处理后续字节点
                $code .= $this->get_cat_tree_js_paiming($next_cat_id);
        } else if ($parent_name) {
            $code .= 'var '.$cur_name.' = new WebFXTreeItem("'.addslashes($this->cats[$cat_id]['NAME']).'", "'.sprintf($path, $cat_id).'");'.CRLF;
            $code .= $parent_name.'.add('.$cur_name.');'.CRLF;


        }   //end if
        if (!$parent_name) {    //最上层，操作结束
            $code .= 'document.write('.$cur_name.');'.CRLF;
            if ('all' == $expand)
                $code .= $cur_name.'.expandAll();'.CRLF;
            else if ('none' == $expand)
                $code .= $cur_name.'.collapseAll();'.CRLF;
        }   //end if
        return $code;
    }   //end function

        /**
     * 输出栏目当前位置的 HTML 代码。
     *
     * @access  public
     * @global  array   $_REQUEST   PHP 的请求变量数组，包括 POST 和 GET 方法的请求变量。
     * 此方法经过修改，传过来的参数经过处理后进行数据分解
     * @param   array   $params 参数数组，格式
     *                          array (
     *                              'cat_id' => 当前栏目的 ID 号，若没有给出此参数，则使用 $_REQUEST['pCatId'] 作为栏目 ID 号,
     *                              'symbol' => 导航栏中用于分隔栏目的符号，默认值为“>”
     *                          )
     * @return  array
     */
 function echo_top_count_sort($start_time,$end_time)
    {

	$conn =& $this->get_adodb_conn();
        $sql = "SELECT cat_body_title,cat_body_click,cat_body_type ,cat_body_indate ,cat_body_id  FROM cat_body   where cat_body_confirm=1 and cat_body_indate>='".$start_time."' and cat_body_indate<='".$end_time."'  order by cat_body_click desc ";
          //echo "((".$sql."))<br>";
           $rs = $conn->SelectLimit($sql, 50, 0);

	   $i=1;
	   $ret="";
           while (!$rs->EOF)
            {
            	$name=$rs->fields[1];



		   if($rs->fields[2]=='A'){
		   $m_type="文章";
		 }
		   elseif($rs->fields[2]=='I'){
		   $m_type="图片";

		 }
		   else{
		   $m_type="链接";


		    }


		  $m_item['title']=$rs->fields[0];
		  $m_item['click']=$rs->fields[1];
		  $m_item['m_type']=$m_type;
		  $m_item['m_time']=$rs->fields[3];
		  $m_item['id']=$rs->fields[4];
		  $ret[$i]=$m_item;
            	  $i++;
            	  $rs->MoveNext();
            }


	     return $ret;
    }   //end function

    //  }}}


         /**
     * 输出栏目当前位置的 HTML 代码。
     *
     * @access  public
     * @global  array   $_REQUEST   PHP 的请求变量数组，包括 POST 和 GET 方法的请求变量。
     * 此方法经过修改，传过来的参数经过处理后进行数据分解
     * @param   array   $params 参数数组，格式
     *                          array (
     *                              'cat_id' => 当前栏目的 ID 号，若没有给出此参数，则使用 $_REQUEST['pCatId'] 作为栏目 ID 号,
     *                              'symbol' => 导航栏中用于分隔栏目的符号，默认值为“>”
     *                          )
     * @return  void
     */
 function echo_cat_location_count($params)
    {

	$conn =& $this->get_adodb_conn();

	    $cat_id = $_REQUEST['catid'];
	if($cat_id=="")
{


	   $sql = "SELECT cat_body_title,cat_body_click,cat_body_type ,cat_body_indate  FROM cat_body   order by cat_body_click desc ";
        //  echo "((".$sql."))<br>";
           $rs = $conn->SelectLimit($sql, 20, 0);
           echo "<p>";
           echo "<table width='100%' class='common' cellspacing='1' >";

           echo "<tr>";
           echo "<td><div align='center'>排名</div></td>";
           echo "<td><div align='center'>新闻标题</div></td>";
	   echo "<td><div align='center'>点击次数</div></td>";
	   echo "<td><div align='center'>新闻类型</div></td>";
	   echo "<td><div align='center'>日期</div></td>";
	   echo "</tr>";
	   $i=1;
           while (!$rs->EOF)
            {
            	$name=$rs->fields[1];
		  $j = $i/2;
		  $langth = strlen($j);
		  if($langth!=3)
		  echo "<tr class=tr1>";
		  else
		  echo "<tr class=tr2>";
		   echo "<td>".$i."</td>";
		  echo "<td>".$rs->fields[0]."</td>";
		  echo "<td align=center>".$rs->fields[1]."</td>";
		   echo "<td align=center>";
		   if($rs->fields[2]=='A')
		   echo "文章</td>";
		   elseif($rs->fields[2]=='I')
		   echo "图片</td>";
		   else
		   echo "链接</td>";

		    echo "<td align=center>".$rs->fields[3]."</td>";
		  echo "</tr>";
            	  $i++;
            	  $rs->MoveNext();
            }
            echo "</table>";

	}
else
{
        $this->require_cat_array();
        extract($params);   //分解参数，有两个参数：$cat_id 和 $symbol
        if (empty($cat_id))
            $cat_id = $_REQUEST['pCatId'];
            $catid=$cat_id ;
        if (empty($cat_id) || !is_array($this->cats[$cat_id])) {
            $this->trigger_error('CMS 类的 echo_cat_location() 方法操作失败，请检查栏目 ID 号（'.$cat_id.'）是否正确。');
            return NULL;
        }   //end if



      $cms_info =& $this->get_cms_info();
      $idsd = array();
      $cms_info->get_cat_child_ids($catid,$idsd);
      $str = implode(",", $idsd);
      if($str=='')$str=$catid;
	//echo $str;


          $sql = "SELECT cat_body_title,cat_body_click,cat_body_type ,cat_body_indate   FROM cat_body WHERE cat_id IN ($str) order by cat_body_click desc ";
       //  echo "((".$sql."))<br>";
         //$conn->debug=true;
         $rs = $conn->SelectLimit($sql, 20, 0);



	   echo "<p>";
	   echo "<table width='100%' class='common' cellspacing='1'  >";

           echo "<tr>";
           echo "<td><div align='center'>排名</div></td>";
           echo "<td><div align='center'>新闻标题</div></td>";
	   echo "<td><div align='center'>点击次数</div></td>";
	   echo "<td><div align='center'>新闻类型</div></td>";
	   echo "<td><div align='center'>日期</div></td>";
	   echo "</tr>";
	   $i=1;
           while (!$rs->EOF)
            {
            	$name=$rs->fields[1];
		   $j = $i/2;
		  $langth = strlen($j);
		  if($langth!=3)
		  echo "<tr class=tr1>";
		  else
		  echo "<tr class=tr2>";
		   echo "<td align='center'>".$i."</td>";
		  echo "<td>".$rs->fields[0]."</td>";
		  echo "<td align=center>".$rs->fields[1]."</td>";
		   echo "<td align=center>";
		   if($rs->fields[2]=='A')
		   echo "文章</td>";
		   elseif($rs->fields[2]=='I')
		   echo "图片</td>";
		   else
		   echo "链接</td>";

		    echo "<td align=center>".$rs->fields[3]."</td>";
		  echo "</tr>";
            	  $i++;
            	  $rs->MoveNext();
            }
            echo "</table>";
	}
    }   //end function

    //  }}}
     //  {{{ echo_cat_location_paiming()



    /**
     * 输出栏目当前位置的 HTML 代码。
     *
     * @access  public
     * @global  array   $_REQUEST   PHP 的请求变量数组，包括 POST 和 GET 方法的请求变量。
     * 此方法经过修改，传过来的参数经过处理后进行数据分解
     * @param   array   $params 参数数组，格式
     *                          array (
     *                              'cat_id' => 当前栏目的 ID 号，若没有给出此参数，则使用 $_REQUEST['pCatId'] 作为栏目 ID 号,
     *                              'symbol' => 导航栏中用于分隔栏目的符号，默认值为“>”
     *                          )
     * @return  void
     */



 function echo_cat_location_paiming($params)
    {


	$conn =& $this->get_adodb_conn();
	$cat_id = $_REQUEST['catid'];
	//echo "##".$cat_id."##";


	if($cat_id=="")
{

	   echo "根";
	    echo "<br> <br><b><div font color=#000000>**按统计本目录下新闻作者发贴数量</div></b>";
	   $sql = "SELECT count(*)  ddd,cat_body_author   FROM cat_body where   cat_body_author IS NOT NULL  group by cat_body_author order by ddd desc";
         // echo "((".$sql."))<br>";
         // $conn->debug=true;

         $rs = $conn->SelectLimit($sql, 20, 0);

           echo "<table class='common' cellspacing='1'  width='70%'>";
           echo "<tr>";
              echo "<td><div align='center'>排名</div></td>";
           echo "<td><div align='center'>新闻来源</div></td>";
	   echo "<td><div align='center'>发新闻数</div></td>";

	   echo "</tr>";
	   $i=0;
           while (!$rs->EOF)
            {
            	$name=$rs->fields[1];
            	$name=strtr($name,' ','@');
            	//echo "((".$name."))";
            	$r=$i+1;
		   $j = $i/2;
		  $langth = strlen($j);
		  if($langth==1)
		  echo "<tr class=tr2>";
		  else
		  echo "<tr class=tr1>";
		  echo "<td align=center>".$r."</td>";
		  echo "<td><a href=javascript:open_author('".$name."')>".$rs->fields[1]."</a></td>";
		  echo "<td align=center>".$rs->fields[0]."</td></tr>";
		  $i++;
            	  $rs->MoveNext();
            }
            echo "</table>";

	}
else
{
        $this->require_cat_array();
        extract($params);   //分解参数，有两个参数：$cat_id 和 $symbol
        if (empty($cat_id))
            $cat_id = $_REQUEST['pCatId'];
            $catid=$cat_id ;
        if (empty($cat_id) || !is_array($this->cats[$cat_id])) {
            $this->trigger_error('CMS 类的 echo_cat_location() 方法操作失败，请检查栏目 ID 号（'.$cat_id.'）是否正确。');
            return NULL;
        }   //end if
        $symbol = isset($symbol) ? htmlspecialchars($symbol) : '&gt;';
        $code = '<a href="'.sprintf($this->cat_index_url, $cat_id).'">'.$this->cats[$cat_id]['NAME'].'</a>';
        while ($parent = $this->cats[$cat_id]['PARENT']) {
            $cat_id = $parent;
            $code = '<a href="'.sprintf($this->cat_index_url, $cat_id).'">'.$this->cats[$cat_id]['NAME'].'</a> '.$symbol.' '.$code;
        }   //end while
        echo '根 '.$symbol.' '.$code;
       echo " <br><br><b><div font color=#000000>**按统计本目录下新闻作者发贴数量</div></b>";


      $cms_info =& $this->get_cms_info();
      $idsd = array();
      $cms_info->get_cat_child_ids($catid,$idsd);
      $str = implode(",", $idsd);
      if($str=='')$str=$catid;

          $sql = "SELECT count(*)  ddd,cat_body_author   FROM cat_body where cat_body_author IS NOT NULL and cat_id in ($str) group by cat_body_author order by ddd desc ";
       //  echo "((".$sql."))<br>";
          //$conn->debug=true;
          $rs = $conn->SelectLimit($sql, 20, 0);




	           echo "<table class='common' cellspacing='1'  width='70%' >";
	           echo "<tr>";
	             echo "<td><div align='center'>排名</div></td>";
	           echo "<td ><div align='center'>新闻来源</div></td>";
		   echo "<td><div align='center'>发新闻数</div></td>";

		   echo "</tr>";
	 if(count($rs)=='' or count($rs)==0)
          {
	          echo "<tr><td  colspan=2 align=center>没有数据！</td></tr>";

         }
         else
         {
         	$i=0;

         	 while (!$rs->EOF)
	            {
	            $r=$i+1;
	            $name=$rs->fields[1];
	            $name=strtr($name,' ','@');
            //	echo "((".$name."))";
	             $j = $i/2;
		  $langth = strlen($j);
		  if($langth==1)
		  echo "<tr class=tr2>";
		  else
		  echo "<tr class=tr1>";
		   	  echo "<td align=center>".$r."</td>";
			  echo "<td> <a href=javascript:open_author('".$name."')>".$rs->fields[1]."</a></td>";
			  echo "<td align=center>".$rs->fields[0]."</td></tr>";
			  $i++;
	            	  $rs->MoveNext();
	            }
         	}

            echo "</table>";
	}
    }   //end function

	//  {{{ get_cms_kicq()

    /**
     * 用于获取建网通网络寻呼类 CMS_Kicq 的对象实例。
     *
     * @access  public
     * @return  object CMS_Kicq
     * @see     CMS_Kicq :: CMS_Kicq()
     */
    function &get_cms_kicq()
    {
        //包含 CMS_Kicq 类（网络寻呼类）文件。
        require_once 'cms/cms_kicq.class.php';

        if ('cms_kicq' != get_class($this->cms_info)) {
            $this->cms_kicq = new CMS_Kicq($this);
        }
        return $this->cms_kicq;
    }   //end function

    //  }}}

    //  {{{ get_cms_forum()

    /**
     * 用于获取建网通论坛类 CMS_Forum 的对象实例。
     *
     * @access  public
     * @return  object CMS_Forum
     */
    function &get_cms_forum()
    {
        //包含 CMS_Kicq 类（论坛类）文件。
        require_once 'cms/cms_forum.class.php';

        if ('cms_forum' != get_class($this->cms_forum)) {
            $this->cms_forum = new CMS_Forum($this);
        }
        return $this->cms_forum;
    }   //end function

    //  }}}

    //  {{{ get_cms_chat()

    /**
     * 用于获取建网通聊天类 CMS_Chat 的对象实例。
     *
     * @access  public
     * @return  object CMS_Forum
     */
    function &get_cms_chat()
    {
        //包含 CMS_Chat 类（聊天类）文件。
        require_once 'cms/cms_chat.class.php';

        if ('cms_chat' != get_class($this->cms_chat)) {
            $this->cms_chat = new CMS_Chat($this);
        }
        return $this->cms_chat;
    }   //end function

    //  }}}

    //  {{{ get_image_html()

    /**
     * 用于获取显示图片或 FLASH 的 HTML 代码。
     *
     * @access  public
     * @param   string  $src    图片或 FLASH 文件所在的 URL。
     * @param   string  $width  图片或 FLASH 的宽度，可使用百分比，如：50%。
     * @param   string  $height 图片或 FLASH 的高度，可使用百分比，如：50%。
     * @param   string  $url    图片链接的目标 URL，此参数对 FALSH 文件无效。
     * @param   string  $alt    图片的替代文字，此参数对 FALSH 文件无效。
     * @return  string  返回用于显示的 HTML 代码。
     */
    function get_image_html($src, $width, $height, $url='', $alt='')
    {
        if ('swf' == strtolower(substr(strrchr($src, '.'), 1))) {  //Flash ...
            $html_code = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="'.$width.'" height="'.$height.'">
					<param name="movie" value="'.$src.'" />
				    <param name="quality" value="high" />
				 	<embed src="'.$src.'" quality=high type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" width="'.$width.'" height="'.$height.'"></embed>
				</object>';
        } else { //Image ...
            $html_code = '<img src="'.$src.'" width="'.$width.'" height="'.$height.'" border="0" alt="'.$alt.'" />';
            if ($url)
                $html_code = '<a href="'.$url.'">'.$html_code.'</a>';
        }   //end if

        return $html_code;
    }   //end function

    //  }}}

    //  {{{ get_cat_list_code()

    /**
     * 根据传递的参数，获取栏目的内容标题列表的 HTML 代码。
     *
     * @access  public
     * @global  array   $_REQUEST   PHP 的请求变量数组，包括 POST 和 GET 方法的请求变量。
     * @param   integer $cat_id         栏目 ID 号。
     * @param   integer $title_length   标题长度，默认值为“30”（15个汉字）。
     * @param   string  $append_text    发生截取时在标题后边补齐的字符串。
     * @param   integer $rs_start       记录开始下标。
     * @param   integer $rs_rows        记录数。
     * @param   integer $is_pager       是否显示分页，默认值为 0，不显示；1 则为显示，同时参数 $rs_rows 将失效。
     * @param   string  $date_format    设置输出时间格式的字符串，可用的转换符（%a、%A 等等）和 PHP strftime() 函数的一样，若该值为空，则不显示日期。
     * @return void
     */
    function get_cat_list_code($cat_id, $title_length = 30, $append_text = '…', $rs_start = 0, $rs_rows = 15, $is_pager = 0, $date_format = '%m-%d', $cat_name = 0)
    {


        if (!is_object($this->conn)) {
            $this->trigger_error('CMS 类的 echo_cat_list() 方法操作失败，没有检测到数据库连接对象 ADOConnection。');
            return FALSE;
        }   //end if

        if (!is_object($this->cms_info)) {
            $this->trigger_error('CMS 类的 echo_cat_list() 方法操作失败，没有检测到 CMS_Info 对象实例。');
            return FALSE;
        }   //end if
/*
        $cat_ids = array();
        $this->get_cat_bottom_ids($cat_id, $cat_ids);
        if (count($cat_ids) <= 0) {
            $this->trigger_error('CMS 类的 echo_cat_list() 方法操作失败，栏目 ID 错误。');
            return FALSE;
        }   //end if
*/
        if ($cat_id == -1) {
			 $where = "(cat_body_outdate IS NULL OR cat_body_outdate='' OR cat_body_outdate>'".$today."') AND cat_body_confirm=1";
		} else {
			 $catids = explode(',',$cat_id);
			 $cat_ids = array();
			 foreach ($catids As $key => $cat_id)
				{
					$this->get_cat_bottom_ids($cat_id, $cat_ids);
					if (count($cat_ids) <= 0) {
						$this->trigger_error('CMS .. echo_cat_list() ......... ID ...');
						return FALSE;
					}   //end if
				} // end foreach

			$id_str = implode(',', $cat_ids);
			$today = date('Y-m-d');
			$where = "cat_id IN (".$id_str.") AND (cat_body_outdate IS NULL OR cat_body_outdate='' OR cat_body_outdate>'".$today."') AND cat_body_confirm=1";
		}  //end if


        if ($is_pager) {
            $page = $_REQUEST['pPage'] ? $_REQUEST['pPage'] : 1;
            $rs_start = ($page - 1) * $rs_rows;
        }   //end if

        $id_str = implode(',', $cat_ids);
        $today = date('Y-m-d');
        $where = "cat_id IN (".$id_str.") AND (cat_body_outdate IS NULL OR cat_body_outdate='' OR cat_body_outdate>'".$today."') AND cat_body_confirm=1";
        $order = "cat_body_recommend DESC, cat_body_indate DESC, cat_body_id DESC";
        $code = '';
        $rs = &$this->cms_info->get_cat_body_rs($this->conn, $where, $order, TRUE, TRUE, $rs_rows, $rs_start);
        while ($rs && !$rs->EOF) {
            /* cat_body_id, cat_id, cat_body_title, cat_body_author, cat_body_type, cat_body_url, cat_body_keyword, cat_body_indate, cat_body_outdate, cat_body_click, cat_body_recommend, cat_body_confirm, cat_body_icon */
            $cat_body_id = $rs->fields[0];
            $cat_body_cat_id = $rs->fields[1];
            $a_title = str_replace(' ', '&nbsp;', htmlspecialchars($rs->fields[2]));
            $title_tmp = CMS_Common :: substr($rs->fields[2], $title_length, $append_text);
            $pad_length = $title_length - strlen(iconv('UTF-8', 'GBK', $title_tmp));
            $pad_length = $pad_length>=0?$pad_length:0;
            $cat_body_title = str_replace(' ', '&nbsp;', htmlspecialchars($title_tmp));
            $cat_body_title = str_replace($append_text, '<span style="font-size: 6px;">&middot;&middot;&middot;&middot;</span>', $cat_body_title);
            $cat_body_indate = $rs->fields[7];
            $cat_body_icon = $rs->fields[12];
            if ($cat_name) {
                $tmpstr = '[<a href="'.sprintf($this->cat_index_url, $cat_body_cat_id).'" target="_blank">'.$this->cats[$cat_body_cat_id]['NAME'].'</a>] ';
            }
            $code .= '<li><nobr>'.$tmpstr.'<a href="'.sprintf($this->doc_index_url, $cat_body_id).'" title="'.$a_title.'" target="_blank">'.$cat_body_title.'</a><span style="font-size: 6px;">'.str_repeat('&middot;', $pad_length).'</span>';
            if ($date_format) {
                $code .= '<small>('.$this->_get_format_date($date_format, strtotime($cat_body_indate)).')</small>';
            }
            if ($cat_body_icon)
                $code .= '<img src="'.$this->tpl_url.'/images/doc/'.$cat_body_icon.'" border="0" />';
            $code .= '</nobr></li>'.CRLF;
            $rs->MoveNext();
        }   //end while
        if ($is_pager) {
            $rs = &$this->cms_info->get_cat_body_rs($this->conn, $where, '', TRUE, TRUE, 'count');
            $total = $rs->fields[0];
            $pages = ($total % $rs_rows) ? (int)($total / $rs_rows + 1) : $total / $rs_rows;
            $code .= '<div align="right" class="pager">共&nbsp;'.$total.'&nbsp;条记录&nbsp;|&nbsp;第&nbsp;'.$page.'&nbsp;页/共&nbsp;'.$pages.'&nbsp;页&nbsp;|&nbsp;';
			if($page==1) $code .='上一页&nbsp;|&nbsp;';
			else $code .='<a href="'.sprintf($this->cat_index_url, $_REQUEST['pCatId']).'/'.($page-1).'">上一页</a>&nbsp;|&nbsp;';
			if($page==$pages) $code .='下一页&nbsp;|&nbsp;'; else $code .='<a href="'.sprintf($this->cat_index_url, $_REQUEST['pCatId']).'/'.($page+1).'">下一页</a>&nbsp;|&nbsp;';
			$code .='选择跳到第
                <select name="selGoTo'.$cat_id.'" onchange="document.location.href=\''.sprintf($this->cat_index_url, $_REQUEST['pCatId']).'/\'+this.options[this.selectedIndex].value">';
            $inc = 5;
            $page_start = max($page - $inc, 1);
            $page_end = min($page_start + $inc * 2, $pages);
            for ($i = $page_start; $i <= $page_end; $i++) {
                $code .= '<option value="'.$i.'"';
                if ($i == $page)    $code .= ' selected="selected"';
                $code .= '>';
                if ($i == $page_start && 1 != $page_start)
                    $code .= '&lt;&lt;';
                else if ($i == $page_end && $pages != $page_end)
                    $code .= '&gt;&gt;';
                else
                    $code .= $i;
                $code .= '</option>'.CRLF;
            }   //end for
            $code .= '</select>页</div>'.CRLF;
        }   //end if

        return $code;
    }   //end function
    //////
    function get_cat_list_code_top_10($cat_id, $title_length = 30, $append_text = '…', $rs_start = 0, $rs_rows = 15, $is_pager = 0, $date_format = '%m-%d', $cat_name = 0)
    {


        if (!is_object($this->conn)) {
            $this->trigger_error('CMS 类的 echo_cat_list() 方法操作失败，没有检测到数据库连接对象 ADOConnection。');
            return FALSE;
        }   //end if

        if (!is_object($this->cms_info)) {
            $this->trigger_error('CMS 类的 echo_cat_list() 方法操作失败，没有检测到 CMS_Info 对象实例。');
            return FALSE;
        }   //end if
/*
        $cat_ids = array();
        $this->get_cat_bottom_ids($cat_id, $cat_ids);
        if (count($cat_ids) <= 0) {
            $this->trigger_error('CMS 类的 echo_cat_list() 方法操作失败，栏目 ID 错误。');
            return FALSE;
        }   //end if
*/
        if ($cat_id == -1) {
			 $where = "(cat_body_outdate IS NULL OR cat_body_outdate='' OR cat_body_outdate>'".$today."') AND cat_body_confirm=1";
		} else {
			 $catids = explode(',',$cat_id);
			 $cat_ids = array();
			 foreach ($catids As $key => $cat_id)
				{
					$this->get_cat_bottom_ids($cat_id, $cat_ids);
					if (count($cat_ids) <= 0) {
						$this->trigger_error('CMS .. echo_cat_list() ......... ID ...');
						return FALSE;
					}   //end if
				} // end foreach

			$id_str = implode(',', $cat_ids);
			$today = date('Y-m-d');
			$where = "cat_id IN (".$id_str.") AND (cat_body_outdate IS NULL OR cat_body_outdate='' OR cat_body_outdate>'".$today."') AND cat_body_confirm=1";
		}  //end if


        if ($is_pager) {
            $page = $_REQUEST['pPage'] ? $_REQUEST['pPage'] : 1;
            $rs_start = ($page - 1) * $rs_rows;
        }   //end if

        $id_str = implode(',', $cat_ids);
        $today = date('Y-m-d');
        $where = "cat_id IN (".$id_str.") AND (cat_body_outdate IS NULL OR cat_body_outdate='' OR cat_body_outdate>'".$today."') AND cat_body_confirm=1";
        $order = "cat_body_recommend DESC, cat_body_id DESC";
        $code = '';
        $rs = &$this->cms_info->get_cat_body_rs($this->conn, $where, $order, TRUE, TRUE, $rs_rows, $rs_start);
        while ($rs && !$rs->EOF) {
            /* cat_body_id, cat_id, cat_body_title, cat_body_author, cat_body_type, cat_body_url, cat_body_keyword, cat_body_indate, cat_body_outdate, cat_body_click, cat_body_recommend, cat_body_confirm, cat_body_icon */
            $cat_body_id = $rs->fields[0];
            $cat_body_cat_id = $rs->fields[1];
            $a_title = $rs->fields[2];
            $title_tmp = CMS_Common :: substr($rs->fields[2], $title_length, $append_text);
            // 忽略传过来的append_text，强制使用6个middot
            //$title_tmp = CMS_Common :: substr($rs->fields[2], $title_length, '&middot;&middot;&middot;&middot;&middot;&middot;');
            $pad_length = $title_length - strlen(iconv('UTF-8', 'GBK', $title_tmp));
            //$title_tmp = str_pad($title_tmp, $title_length);
            $cat_body_title = str_replace(' ', '&nbsp;', htmlspecialchars($title_tmp));
            $cat_body_title = str_replace($append_text, '<span style="font-size: 6px;">&middot;&middot;&middot;&middot;</span>', $cat_body_title);
            //$cat_body_title = str_replace('……', '<span style="font-weight: 100;">……</span>', $cat_body_title);
            $cat_body_indate = $rs->fields[7];
            $cat_body_icon = $rs->fields[12];
            if ($cat_name) {
                $tmpstr = '[<a href="'.sprintf($this->cat_index_url, $cat_body_cat_id).'" target="_blank">'.$this->cats[$cat_body_cat_id]['NAME'].'</a>] ';
            }
            $code .= '<li><nobr>'.$tmpstr.'<a href="'.sprintf($this->doc_index_url, $cat_body_id).'" title="'.$a_title.'" target="_blank">'.$cat_body_title.'</a>';
            if ($date_format) {
                $code .= '<small>('.$this->_get_format_date($date_format, strtotime($cat_body_indate)).')</small>';
            }
            if ($cat_body_icon)
                $code .= '<img src="'.$this->tpl_url.'/images/doc/'.$cat_body_icon.'" border="0" />';
            $code .= '</nobr></li>'.CRLF;
            $rs->MoveNext();
        }   //end while

        if ($is_pager) {
            $rs = &$this->cms_info->get_cat_body_rs($this->conn, $where, '', TRUE, TRUE, 'count');
            $total = $rs->fields[0];
            $pages = ($total % $rs_rows) ? (int)($total / $rs_rows + 1) : $total / $rs_rows;
            $code .= '<div align="right" class="pager">共&nbsp;'.$total.'&nbsp;条记录&nbsp;|&nbsp;第&nbsp;'.$page.'&nbsp;页/共&nbsp;'.$pages.'&nbsp;页&nbsp;|&nbsp;选择跳到第
                <select name="selGoTo'.$cat_id.'" onchange="document.location.href=\''.sprintf($this->cat_index_url, $_REQUEST['pCatId']).'/\'+this.options[this.selectedIndex].value">';
            $inc = 5;
            $page_start = max($page - $inc, 1);
            $page_end = min($page_start + $inc * 2, $pages);
            for ($i = $page_start; $i <= $page_end; $i++) {
                $code .= '<option value="'.$i.'"';
                if ($i == $page)    $code .= ' selected="selected"';
                $code .= '>';
                if ($i == $page_start && 1 != $page_start)
                    $code .= '&lt;&lt;';
                else if ($i == $page_end && $pages != $page_end)
                    $code .= '&gt;&gt;';
                else
                    $code .= $i;
                $code .= '</option>'.CRLF;
            }   //end for
            $code .= '</select>页</div>'.CRLF;
        }   //end if

        return $code;
    }   //end function
    //  }}}

    //  {{{ get_cat_tpl_code()

    /**
     * 获取通过 Smarty 解析后的首页或栏目页面的栏目框 HTML 代码。
     *
     * @access  public
     * @param   integer $cat_id     栏目 ID 号。
     * @param   boolean $parse_cat  是否解析栏目内容列表的代码，默认值为 TRUE。
     * @param   boolean $is_cat     是否是栏目页面的栏目框，默认值为 TRUE；TRUE 则使用 blocks/0.tpl，FALSE 则使用 blocks/1.tpl。
     * @return  string  通过 Smarty 解析后的栏目框 HTML 代码。
     */
    function get_cat_tpl_code($cat_id, $parse_cat = TRUE, $is_cat = TRUE)
    {
        $this->require_cat_array();
        $this->smarty->assign('cat_name', $this->cats[$cat_id]['NAME']);
        $this->smarty->assign('cat_code', ($parse_cat) ? $this->get_cat_list_code($cat_id, 100, '…', 0, 5) : '{cms->echo_cat_list cat_id="'.$cat_id.'" title_length="100" append_text="……" rs_start="0" rs_rows="5" is_pager="0" date_format="%m-%d"}');
        $this->smarty->assign('cat_url', sprintf($this->cat_index_url, $cat_id));
        return $this->smarty->fetch('themes/'.$this->arguments['theme'].'/blocks/'.(($is_cat) ? '0' : '1').'.tpl');
    }  //end function

    //  }}}

    //  {{{ get_cat_bottom_ids()

    /**
     * 获取某个栏目的所有最底层子栏目的 ID 号。
     * 根据参数 $cat_id 指定栏目的 ID 号，然后获取该栏目的所有最底层子栏目 ID 号组成的数组，此数组即是参数变量 &$cat_ids。
     *
     * @access  public
     * @param   integer $cat_id     想要操作的栏目的 ID 号。
     * @param   integer &$cat_ids   最终获取的最底层子栏目的 ID 号所存放的数组变量的引用。
     * @return  void
     */
    function get_cat_bottom_ids($cat_id, &$cat_ids)
    {
        $this->require_cat_array();
        $cat = $this->cats[$cat_id]['CHILD'];
        if (count($cat) > 0) {
            ksort($cat);
            foreach ($cat as $sub_cat_id) {
                $this->get_cat_bottom_ids($sub_cat_id, $cat_ids);
            }
        } else {
            $cat_ids[] = $cat_id;
        }   //end if
    }   //end function

    //  }}}

    //  {{{ get_cat_all_ids()

    /**
     * 获取某个栏目的所有子栏目的 ID 号。
     * 根据参数 $cat_id 指定栏目的 ID 号，然后获取该栏目的所有子栏目 ID 号组成的数组，此数组即是参数变量 &$cat_ids。键值是从1开始。
     *
     * @access  public
     * @param   integer $cat_id     想要操作的栏目的 ID 号。
     * @param   integer &$cat_ids   最终获取的最底层子栏目的 ID 号所存放的数组变量的引用。
     * @return  void
     */
    function get_cat_all_ids($cat_id, &$cat_ids)
    {
        $this->require_cat_array();

        $cats = $this->cats[$cat_id]['CHILD'];


        if (count($cats) > 0) {
            foreach ($cats as $cat) {
                $key = $this->children_key;
                $this->children_key = $key + 1;
                $this->children[$key] = $cat;
                $this->get_cat_all_ids($cat, $cat_ids);
            }
        }
    }   //end function

    //  }}}

    //  {{{ get_cat_tree_js_code()

    /**
     * 通过自身递归调用，获取栏目页面左边的树状栏目列表的 JavaScript 代码。
     *
     * @access  public
     * @param   integer $cat_id 栏目 ID 号。
     * @param   integer $expand 设置树的展开方式取值可为 none（不展开任何树，搜索页面的默认值） 和 all（展开所有的树，栏目页面的默认值）。
     * @return  string  JavaScript 代码。
     */
    function get_cat_tree_js($cat_id, $expand)
    {
        if($this->cats[$cat_id]['FORBIDDEN'] == 0){

        $code = '';
        $cur_name = 'oT'.$cat_id;   //当前节点名
        $parent = $this->cats[$cat_id]['PARENT'];   //上层节点名
        if (!$parent) { //最上层
            $parent_name = '';
            $code .= 'var '.$cur_name.' = new WebFXTree("'.addslashes($this->cats[$cat_id]['NAME']).'", "'.sprintf($this->cat_index_url, $cat_id).'");'.CRLF;
        } else {    //非最上层
            $parent_name = 'oT'.$parent;
        }   //end if
        $children = $this->cats[$cat_id]['CHILD'];
        if (count($children) > 0) { //有子节点
            if ($parent_name) {
                $code .= 'var '.$cur_name.' = new WebFXTreeItem("'.addslashes($this->cats[$cat_id]['NAME']).'", "'.sprintf($this->cat_index_url, $cat_id).'");'.CRLF;
                $code .= $parent_name.'.add('.$cur_name.');'.CRLF;
            }   //end if
            ksort($children);
            foreach ($children as $next_cat_id) //递归处理后续字节点
                $code .= $this->get_cat_tree_js($next_cat_id, $expand);
        } else if ($parent_name) {
            $code .= 'var '.$cur_name.' = new WebFXTreeItem("'.addslashes($this->cats[$cat_id]['NAME']).'", "'.sprintf($this->cat_index_url, $cat_id).'");'.CRLF;
            $code .= $parent_name.'.add('.$cur_name.');'.CRLF;
        }   //end if

        if (!$parent_name) {    //最上层，操作结束
            $code .= 'document.write('.$cur_name.');'.CRLF;
            if ('all' == $expand)
                $code .= $cur_name.'.expandAll();'.CRLF;
            else if ('none' == $expand)
                $code .= $cur_name.'.collapseAll();'.CRLF;
        }   //end if

        return $code;
        }
    }   //end function

    //  }}}

    //  {{{ set_module()

    /**
     * 设置建网通当前模块名。设置模块名之后，输出头/尾文件以及其它方法将会自动使用此模块名。
     *
     * @access  public
     * @param   string  $module    模块名，即 CMS 类属性 $modules 中的键值。
     * @return  void
     * @see     $modules, $module, echo_header(), echo_footer()
     */
    function set_module($module)
    {
        $this->module = (isset($this->modules[$module])) ? $module : '';
        if ('smarty' == get_class($this->smarty))
            $this->smarty->assign_by_ref('s_module_name', $this->modules[$this->module]['TITLE']);
    }   //end function

    //  }}}

    //  {{{ set_adodb_cache_dir()

    /**
     * 设置建网通 ADOdb cache 所在的目录，只要用到 ADOdb cache 的地方，都要先行调用此方法。
     *
     * @access  public
     * @global  string  $ADODB_CACHE_DIR    ADOdb 的 Cache 存放目录。
     * @param   string  $cache_key  数组“$this->adodb_cache_paths”的键值，当前可用值为：
     *                                  'Info'  -> 信息发布的 ADOdb cache 目录。
     *                                  'Forum' -> 论坛的 ADOdb cache 目录。
     *                                  'Misc'  -> 其它杂项的 ADOdb cache 目录。
     * @return  boolean
     */
    function set_adodb_cache_dir($cache_key = 'Misc')
    {
        global $ADODB_CACHE_DIR;

        if (!isset($this->adodb_cache_paths[$cache_key]))
            return FALSE;
        $ADODB_CACHE_DIR  = $this->adodb_cache_paths[$cache_key];
        return TRUE;
    }   //end function

    //  }}}

    //  {{{ echo_domain()

    /**
     * 用于输出建网通用户域的 HTML 代码。
     *
     * @access  public
     * @return  void
     */
    function echo_domain()
    {
        /*if (1 == count($this->domains)) {
            echo '<input type="hidden" name="DoMainId" value="'.$this->domains[0].'" />'.CRLF;
        } else if (1 < count($this->domains)) {
            $code .= '<select name="DoMainId">'.CRLF;
            foreach ($this->domains as $domain) {
                $code .= '<option value="'.$domain.'">'.$domain.'</option>'.CRLF;
            };
            $code .= '</select>'.CRLF;
            echo $code;
        }   //end if*/
        if (function_exists('cms_form_token')) {
            echo '<input type="hidden" name="token" value="'.cms_form_token('login_do').'"/>';
        }
    }   //end function

    //  }}}

    //  {{{ echo_logo()

    /**
     * 用于输出建网通的 LOGO 的 HTML 代码。
     *
     * @access  public
     * @global  array   $cfg_logos   LOGO 配置数组。
     * @return  void
     */
    function echo_logo()
    {
        global $cfg_logos;

        //Load variable $cfg_logos.
        require_once $this->config_path.'/cms_logo.inc.php';

        echo $this->get_image_html($this->logo_url.'/'.$cfg_logos['SRC'],
                        $cfg_logos['WIDTH'],
                        $cfg_logos['HEIGHT'],
                        $this->cms_url.'/',
                        $cfg_logos['ALT']);
    }   //end function

    //  }}}


	 //  {{{ echo_pic()

    /**
     * 用于输出建网通的 图片新闻 的 HTML 代码。
     *
     * @access  public
     * @global  array   $cfg_pics   图片新闻 配置数组。
     * @return  void
     */
    function echo_pic()
    {
        global $cfg_pics;
		require_once $this->config_path.'/cms_pcat.inc.php';
		$pic_news .= '<SCRIPT LANGUAGE="JavaScript">var isNS = ((navigator.appName == "Netscape") && (parseInt(navigator.appVersion) >= 4));
		var _all = "";
		var _style = "";
		var wwidth, wheight;
		var ydir = "++";
		var xdir = "++";
		var id1, id2, id3;
		var x = 1;
		var y = 1;
		var x1, y1;
		if(!isNS) {
		_all="all.";
		_style=".style";
		}
		function getwindowsize() {
		clearTimeout(id1);
		clearTimeout(id2);
		clearTimeout(id3);
		if (isNS) {
		wwidth = window.innerWidth - 55;
		wheight = window.innerHeight - 50;
		} else {
		wwidth = document.body.clientWidth - 55;
		wheight = document.body.clientHeight - 50;
		}
		id3 = setTimeout("randomdir()", 20000);
		animate();
		}
		function randomdir() {
		if (Math.floor(Math.random()*2)) {
		(Math.floor(Math.random()*2)) ? xdir="--": xdir="++";
		} else {
		(Math.floor(Math.random()*2)) ? ydir="--": ydir="++";
		}
		id2 = setTimeout("randomdir()", 20000);
		}
		function animate() {
		eval("x"+xdir);
		eval("y"+ydir);
		if (isNS) {
		pic1.moveTo((x+pageXOffset),(y+pageYOffset))
		} else {
		pic1.pixelLeft = x+document.body.scrollLeft;
		pic1.pixelTop = y+document.body.scrollTop;
		}
		if (isNS) {
		if (pic1.top <= 5+pageYOffset) ydir = "++";
		if (pic1.top >= wheight+pageYOffset) ydir = "--";
		if (pic1.left >= wwidth+pageXOffset) xdir = "--";
		if (pic1.left <= 5+pageXOffset) xdir = "++";
		} else {
		if (pic1.pixelTop <= 5+document.body.scrollTop) ydir = "++";
		if (pic1.pixelTop >= wheight+document.body.scrollTop) ydir = "--";
		if (pic1.pixelLeft >= wwidth+document.body.scrollLeft) xdir = "--";
		if (pic1.pixelLeft <= 5+document.body.scrollLeft) xdir = "++";
		}
		id1 = setTimeout("animate()", 30);
		}

		function setSize(sDrection)
		{
			var sWid ='.$cfg_pics['WIDTH'].';
			var sHeit = '.$cfg_pics['HEIGHT'].';
			if (sDrection == "in")
			{
				document.getElementById("img").style.pixelWidth = sWid*'.$cfg_pics['ZOOM'].';
				document.getElementById("img").style.pixelHeight = sHeit*'.$cfg_pics['ZOOM'].';
			} else {
				document.getElementById("img").style.width = '.$cfg_pics['WIDTH'].';
				document.getElementById("img").style.height = '.$cfg_pics['HEIGHT'].';
			}
		}</script>';
		$pic_news .= '<div id="pic1" style="position:absolute; visibility:visible; left:50px; top:200px; z-index:0"><br />';
		if ($cfg_pics['LINK'])
		{
			$pic_news .= '<a href="'.$cfg_pics['LINK'].'" onmouseover="setSize(\'in\');" onmouseout="setSize(\'out\');" target="_blank">';
		} else {
			$pic_news .= '<a href="javascript:void(0);" onmouseover="setSize(\'in\');" onmouseout="setSize(\'out\');" target="_top">';
		}

		$pic_news .= '<img src="'.$this->pic_url.'/'.$cfg_pics['SRC'].'" id="img" alt="'.$cfg_pics['ALT'].'" style="border:0; height:'.$cfg_pics['HEIGHT'].'; width:'. $cfg_pics['WIDTH'].';"></a></div>';
		$pic_news .= '<script language="javascript">';
		$pic_news .= 'var pic1=eval("document."+_all+"pic1"+_style);';
		$pic_news .= 'getwindowsize();';
		$pic_news .= '</script>';
		echo $pic_news;
			}   //end function

    //  }}}


	 //  {{{ echo_linktype()

	/**
     * 输出友情链接。
     *
     * @access  public
     * @global  array   $_REQUEST   PHP 的请求变量数组，包括 POST 和 GET 方法的请求变量。
     * @param   array   $params 参数数组，格式
     *                          array (
     *                              'linktype_id' => 友情链接类别的 ID 号,
     *                              'width'       => 显示宽度
	 *                          )
     * @return  void
     */
    function echo_linktype($params)
    {

        extract($params);   //分解参数，只有一个参数：$linktype_id
        if (empty($linktype_id))
			$this->trigger_error('匹配符 {cms->echo_linktype linktype_id="value"} 必须设置 linktype_id 的值，指定友情链接类别的 ID 号。');
		if (empty($width))
		    $width = 100;

		$link_path = $this->data_path.'/html/link';
        if (!file_exists($link_path.'/'.$linktype_id.'.html')) {
            $this->trigger_error('没找到该类友情链接，请检查  linktype_id 所指的友情链接类别 ID 号是否正确。');
            return NULL;
        } else {
			$url = $link_path.'/'.$linktype_id.'.html';
			$content = Cache::cache_fetch($url);
			$content = str_replace('###LINK_TYPE_WIDTH###', $width, $content);
			echo $content;
		}
    }   //end function

    /**
     * 输出友情链接_1。
     *
     * @access  public
     * @global  array   $_REQUEST   PHP 的请求变量数组，包括 POST 和 GET 方法的请求变量。
     * @param   array   $params 参数数组，格式
     *                          array (
     *                              'linktype_id' => 友情链接类别的 ID 号,
     *                              'width'       => 显示宽度
	 *                          )
     * @return  void
     */
    function echo_linktype_a($params)
    {

        extract($params);   //分解参数，只有一个参数：$linktype_id
        if (empty($linktype_id))
			$this->trigger_error('匹配符 {cms->echo_linktype linktype_id="value"} 必须设置 linktype_id 的值，指定友情链接类别的 ID 号。');
		if (empty($width))
		    $width = 100;

		$link_path = $this->data_path.'/html/link';
        if (!file_exists($link_path.'/a_'.$linktype_id.'.html')) {
            $this->trigger_error('没找到该类友情链接，请检查  linktype_id 所指的友情链接类别 ID 号是否正确。');
            return NULL;
        } else {
			$url = $link_path.'/a_'.$linktype_id.'.html';
			$content = Cache::cache_fetch($url);
			//$content = str_replace('###LINK_TYPE_WIDTH###', $width, $content);
			echo $content;
		}
    }   //end function

    //  {{{ echo_banner()

    /**
     * 用于输出建网通的 BANNER 的 HTML 代码。
     *
     * @access  public
     * @global  array   $cfg_banners    BANNER 的配置数组。
     * @param   array   $params 参数数组，格式：
     *                          array (
     *                              'banner_id' => 是要求显示的 BANNER 的 ID 号。默认值为 0，表示随即显示；若指定此参数，则显示相应的 BANNER
     *                          )
     * @return  void
     */
    function echo_banner($params)
    {
        global $cfg_banners;

        //Load variable $cfg_banners.
        require_once $this->config_path.'/cms_banner.inc.php';

        extract($params);   //分解参数数组
        if (isset($banner_id)) {
            if (!isset($cfg_banners[$banner_id])) {
                $this->trigger_error('匹配符 {cms->echo_banner banner_id="'.$banner_id.'"} 没找到 ID 号为 '.$banner_id.' 的 BANNER，该 BANNER 不存在或未被激活。');
                return NULL;
            }   //end if
            $banner = $cfg_banners[$banner_id];
        } else {
            $size = count($cfg_banners);
            if ($size <= 0) {
                $this->trigger_error('没有任何激活的 BANNER。', E_USER_NOTICE);
                return NULL;
            }   //end if
            srand((float)microtime()*10000000);
            $banner = $cfg_banners[array_rand($cfg_banners)];
        }   //end if

        echo $this->get_image_html($this->banner_url.'/'.$banner['SRC'],
                        $banner['WIDTH'],
                        $banner['HEIGHT'],
                        $banner['URL'],
                        $banner['ALT']);
    }   //end function

    //  }}}

    //  {{{ echo_menu()

    /**
     * 输出下拉菜单的 HTML 代码。
     *
     * @access  public
     * @return  void
     */
    function echo_menu()
    {
        $file = $this->data_path.'/html/menu/menu.html';
        echo Cache::cache_fetch($file);
    }   //end function

    //  }}}

    //  {{{ echo_nav()

    /**
     * 用于输出建网通的导航栏的 HTML 代码。
     *
     * @access  public
     * @global array $_SESSION PHP session 的全局数组变量
     * @param   array   $params 参数数组，格式：
     *                          array (
     *                              'cat_id_str'    => 导航栏中显示的栏目的 ID 号所组成的字符串，ID 号之间使用“,”分隔。默认值为空，显示最上层栏目；若指定此参数，则按照 ID 号从左到右出现的顺序显示栏目导航链接,
     *                              'symbol'        => 导航栏中用于分隔栏目的符号，默认值为“|”
     *                          )
     * @return  void
     */
    function echo_nav($params)
    {

        $this->require_cat_array();

        extract($params);   //分解参数数组，共有两个参数：$cat_id_str 和 $symbol
        ksort($this->cat_tops);
        $cat_ids = ($cat_id_str) ? explode(',', $cat_id_str) : $this->cat_tops;
        $symbol = isset($symbol) ? htmlspecialchars($symbol) : '|';
        $nav = '';
        foreach ($cat_ids as $cat_id) {
            if ($nav)
                $nav .= '&nbsp;'.$symbol.'&nbsp;';
            $nav .= '<a href="'.sprintf($this->cat_index_url, $cat_id).'">'.$this->cats[$cat_id]['NAME'].'</a>';
        }   //end foreach
        if ($_SESSION[$this->sess_user_perm] == 1)  //显示“网站管理”链接
            $nav .= '&nbsp;'.$symbol.'&nbsp;<a href="'.$this->app_url.'/'.$this->modules['ADMIN']['DIR'].'/">网站管理</a>';
        echo $nav;
    }   //end function

    //  }}}

    //  {{{ echo_date()

    /**
     * 用于输出当前时间，包括年月日、时分秒以及星期，输出格式可自定义。
     *
     * @access  public
     * @param   array   $params 参数数组，格式：
     *                          array (
     *                              'format' => 设置输出时间格式的字符串，可用的转换符（%a、%A 等等）和 PHP strftime() 函数的一样，默认值为“%Y-%m-%d”
     *                          )
     * @return  void
     */
    function echo_date($params)
    {
        extract($params);   //分解参数，只有一个参数：$format
        echo $this->_get_format_date($format);
    }   //end function

    //  }}}

    //  {{{ echo_announce()

    /**
     * 通过指定公告 ID 号输出滚动或弹出公告 HTML 代码。
     *
     * @access  public
     * @param   array   $params 参数数组，格式：
     *                          array (
     *                              'announce_id' => 设置使用的公告的 ID 号，此参数必须设置
     *                          )
     * @return  void
     */
    function echo_announce($params)
    {
        extract($params);   //分解参数，只有一个参数：$announce_id
        if (empty($announce_id)) {  //缺少参数！
            $this->trigger_error('匹配符 {cms->echo_announce announce_id="value"} 必须设置 announce_id 的值，指定公告的 ID 号。');
            return NULL;
        }   //end if
        $anno_path = $this->data_path.'/html/announce';
        $anno_url  = $this->data_url.'/html/announce';
        if (!file_exists($anno_path.'/'.$announce_id.'.html')) {
            $this->trigger_error('没找到公告，请检查 announce_id 所指的公告 ID 号是否正确。');
            return NULL;
        }   //end if

        $lines = Cache::cache_fetch($anno_path.'/'.$announce_id.'.html');
        if ('JUMP' == trim($lines[1])) {  //弹出公告，输出弹出公告代码
            $feature = trim($lines[2]);
            echo '
                <script language="JavaScript">
                    window.open("'.$anno_url.'/'.$announce_id.'.html", "popup'.$announce_id.'", "'.$feature.'");
                </script>
            ';
        } else {    //滚动公告，输出滚动公告代码
        	$url = '';
            if(preg_match_all("|&lt;a.*href=\"(.*)\".*&gt;.*&lt;\/a&gt;|isU", $lines, $result, PREG_PATTERN_ORDER) == 1){
                $url = $result[1][0];
            }elseif(preg_match_all("|<a.*href=\"(.*)\".*>.*<\/a>|isU", $lines, $result, PREG_PATTERN_ORDER) == 1){
                $url = $result[1][0];
            }
            if(!empty($url)){
                if(preg_match_all("|http://.*/cms/data/html/doc/.*/.*/(.*)/index.html|isU", $url, $rs, PREG_PATTERN_ORDER)){
                    $cat_body_id = $rs[1][0];
                }elseif(strstr($url, "cms/app/info/doc/index.php")){
                    $cat_body_id = substr($url, strrpos($url, '/')+1);
                }
                if(isset($cat_body_id)){
                    $conn =& $this->get_adodb_conn();
                    $sql = "SELECT CAT_BODY_OUTDATE FROM cat_body where cat_body_confirm=1 and cat_body_id = " . $cat_body_id;
                    $rs = $conn->SelectLimit($sql, 1, 0);
                    if(!empty($rs->fields[0]) && date("Y-m-d")>=$rs->fields[0]){
                        $lines = '';
                    }
                }
            }
            echo $lines;
        }   //end if
    }   //end function

    //  }}}

	function echo_new_announce(){
		$today = date('Y-m-d');
		$where = "cat_id = ".ANNOUNCE_CAT_ID." AND (cat_body_outdate IS NULL OR cat_body_outdate='' OR cat_body_outdate>'".$today."') AND cat_body_confirm=1";
		$order = "cat_body_recommend DESC, cat_body_indate DESC, cat_body_id DESC";
		$rs = &$this->cms_info->get_cat_body_rs($this->conn, $where, $order, TRUE, TRUE, 10, 0);
		$html = '<marquee onmouseover="this.stop()" onmouseout="this.start()" scrollAmount="1" scrollDelay="20" direction="left" width="400" height="25"><p style="line-height:0.5em;_line-height:2.5em;">';
		while ($rs && !$rs->EOF) {
			$html .= '&nbsp;<a href="'.sprintf($this->doc_index_url, $rs->fields[0]).'"><font color="white">'.str_replace(' ', '&nbsp;', htmlspecialchars($rs->fields[2])).'</font></a>';
			$rs->MoveNext();
		}
		$html .= '</p></marquee>';
		echo $html;
	}

    //  {{{ echo_poll()

    /**
     * 通过指定调查 ID 号输出调查的 HTML 代码。
     *
     * @access  public
     * @param   array   $params 参数数组，格式：
     *                          array (
     *                              'poll_id' => 设置使用的调查的 ID 号，此参数必须设置
     *                          )
     * @return  void
     */
    function echo_poll($params)
    {
        extract($params);   //分解参数，只有一个参数：$poll_id
        if (empty($poll_id)) {  //缺少参数！
            $this->trigger_error('匹配符 {cms->echo_poll poll_id="value"} 必须设置 poll_id 的值，指定调查的 ID 号。');
            return NULL;
        }   //end if
        $poll_path = $this->data_path.'/html/poll';
        $poll_url  = $this->data_url.'/html/poll';
        if (!file_exists($poll_path.'/'.$poll_id.'.html')) {
            $this->trigger_error('没找到调查，请检查 poll_id 所指的调查 ID 号是否正确。');
            $content = '';
        } else {
            $content = Cache::cache_fetch($poll_path.'/'.$poll_id.'.html');
        }   //end if
        if (function_exists('cms_form_token')) {
            $content .= '<input type="hidden" name="token" value="'.cms_form_token('poll').'"/>';
        }
        echo $content;
    }   //end function

    //  }}}

    //  {{{ echo_cat_name()

    /**
     * 输出栏目的名字。
     *
     * @access  public
     * @global  array   $_REQUEST   PHP 的请求变量数组，包括 POST 和 GET 方法的请求变量。
     * @param   array   $params 参数数组，格式
     *                          array (
     *                              'cat_id' => 当前栏目的 ID 号，若没有给出此参数，则使用 $_REQUEST['pCatId'] 作为栏目 ID 号
     *                          )
     * @return  void
     */
    function echo_cat_name($params)
    {


        $this->require_cat_array();
        extract($params);   //分解参数，只有一个参数：$cat_id
        if (empty($cat_id))
            $cat_id = $_REQUEST['pCatId'];
        echo $this->cats[$cat_id]['NAME'];
    }   //end function

    //  }}}

    //  {{{ echo_cat_list()

    /**
     * 根据传递的参数，显示栏目的内容标题列表的 HTML 代码。
     *
     * @access  public
     * @param   array   $params 参数数组，格式：
     *                          array (
     *                              'cat_id'        => 栏目 ID 号，必填参数,
     *                              'title_length'  => 标题长度，默认值为“30”（15个汉字）,
     *                              'append_text'   => 发生截取时在标题后边补齐的字符串，默认值为“…”,
     *                              'rs_start'      => 记录开始下标，默认值为“0”,
     *                              'rs_rows'       => 记录数，默认值“15”,
     *                              'is_pager'      => 是否显示分页，默认值为 0，不显示；1 则为显示，同时参数 $rs_rows 将失效,
     *                              'date_format'   => 设置输出时间格式的字符串，可用的转换符（%a、%A 等等）和 PHP strftime() 函数的一样，若该值为空，则不显示日期；若不设置该值，则使用默认方式显示“%m-%d”（月-日）。
     *                          )
     * @return void
     */

         function echo_cat_list_top_10($params)
        {
        if (!is_object($this->conn)) {
            $this->trigger_error('CMS 类的 echo_cat_list() 方法操作失败，没有检测到数据库连接对象 ADOConnection。');
            return FALSE;
        }   //end if

        extract($params);
        if (!isset($cat_id)) {
            $this->trigger_error('CMS 类的 echo_cat_list() 方法操作失败，匹配符 {cms->echo_cat_list} 必须设置 cat_id 参数。');
            return FALSE;
        }   //end if

        if (!isset($title_length))
            $title_length = 30;
        if (!isset($append_text))
           $append_text = '…';
        if (!isset($rs_start))
            $rs_start = 0;
        if (!isset($rs_rows))
            $rs_rows = 15;
        if (!isset($is_pager))
            $is_pager = 0;
        if (!isset($date_format))
            $date_format = '%m-%d';
        echo $this->get_cat_list_code_top_10($cat_id, $title_length, $append_text, $rs_start, $rs_rows, $is_pager, $date_format, $cat_name);
    }   //end function
    function echo_cat_list($params)
    {
        if (!is_object($this->conn)) {
            $this->trigger_error('CMS 类的 echo_cat_list() 方法操作失败，没有检测到数据库连接对象 ADOConnection。');
            return FALSE;
        }   //end if

        extract($params);
        if (!isset($cat_id)) {
            $this->trigger_error('CMS 类的 echo_cat_list() 方法操作失败，匹配符 {cms->echo_cat_list} 必须设置 cat_id 参数。');
            return FALSE;
        }   //end if

        if (!isset($title_length))
            $title_length = 30;
        if (!isset($append_text))
           $append_text = '…';
        if (!isset($rs_start))
            $rs_start = 0;
        if (!isset($rs_rows))
            $rs_rows = 15;
        if (!isset($is_pager))
            $is_pager = 0;
        if (!isset($date_format))
            $date_format = '%m-%d';
        echo $this->get_cat_list_code($cat_id, $title_length, $append_text, $rs_start, $rs_rows, $is_pager, $date_format, $cat_name);
    }   //end function

    //  }}}

    //  {{{ echo_cat_location()

    /**
     * 输出栏目当前位置的 HTML 代码。
     *
     * @access  public
     * @global  array   $_REQUEST   PHP 的请求变量数组，包括 POST 和 GET 方法的请求变量。
     * @param   array   $params 参数数组，格式
     *                          array (
     *                              'cat_id' => 当前栏目的 ID 号，若没有给出此参数，则使用 $_REQUEST['pCatId'] 作为栏目 ID 号,
     *                              'symbol' => 导航栏中用于分隔栏目的符号，默认值为“>”
     *                          )
     * @return  void
     */
    function echo_cat_location($params)
    {


        $this->require_cat_array();
        extract($params);   //分解参数，有两个参数：$cat_id 和 $symbol
        if (empty($cat_id))
            $cat_id = $_REQUEST['pCatId'];
        if (empty($cat_id) || !is_array($this->cats[$cat_id])) {
            $this->trigger_error('CMS 类的 echo_cat_location() 方法操作失败，请检查栏目 ID 号（'.$cat_id.'）是否正确。');
            return NULL;
        }   //end if
        $symbol = isset($symbol) ? htmlspecialchars($symbol) : '&gt;';
        $code = '<a href="'.sprintf($this->cat_index_url, $cat_id).'">'.$this->cats[$cat_id]['NAME'].'</a>';
        while ($parent = $this->cats[$cat_id]['PARENT']) {
            $cat_id = $parent;
            $code = '<a href="'.sprintf($this->cat_index_url, $cat_id).'">'.$this->cats[$cat_id]['NAME'].'</a> '.$symbol.' '.$code;
        }   //end while

	 $location='<a href="'.$this->cms_url.'/" target="_blank">首页</a> '.$symbol.' '.$code;
	 $location_echo='<a href="'.$this->cms_url.'/">首页</a> '.$symbol.' '.$code;
	 if($return=="on")
		return $location;
	 else
		echo $location_echo;
    }   //end function

    //  }}}

    //  {{{ echo_cat_tree()

    /**
     * 输出栏目页面左边的树状栏目列表的 HTML 代码（包括 JavaScript 代码）。
     *
     * @access  public
     * @global  array   $_REQUEST   PHP 的请求变量数组，包括 POST 和 GET 方法的请求变量。
     * @param   array   $params 参数数组，格式
     *                          array (
     *                              'cat_id' => 当前栏目 ID 号，若不设置此参数，则使用 $_REQUEST['pCatId'] 作为栏目 ID 号，若 $_REQUEST['pCatId'] 的值也为空，则显示所有的栏目树。
     *                              'expand' => 设置树的展开方式取值可为 none（不展开任何树，搜索页面的默认值） 和 all（展开所有的树，栏目页面的默认值）
     *                          )
     * @return  void
     */
    function echo_cat_tree($params)
    {


        $this->require_cat_array();
        extract($params);   //分解参数，有两个参数：$cat_id 和 $expand
        if (!isset($cat_id))
            $cat_id = $_REQUEST['pCatId'];
        $cat_id = $this->cats[$cat_id]['THREAD'];
        if (!isset($expand))
            $expand = ('CAT' == $this->module) ? 'all' : 'none';
        if ('all' != $expand && 'none' != $expand) {
            $this->trigger_error('CMS 类的 echo_cat_tree() 方法操作失败，参数 expand 的取值只能是 none 或 all，而不能是其它值。');
            return NULL;
        }   //end if
        $code = '
            <script language="JavaScript">
                var sImagePath = "'.$this->tpl_url.'/images/cat_tree/";
                webFXTreeConfig = {
                    rootIcon        : sImagePath + "root.gif",
                    openRootIcon    : sImagePath + "openroot.gif",
                    folderIcon      : sImagePath + "foldericon.gif",
                    openFolderIcon  : sImagePath + "openfoldericon.gif",
                    fileIcon        : sImagePath + "file.gif",
                    iIcon           : sImagePath + "I.gif",
                    lIcon           : sImagePath + "L.gif",
                    lMinusIcon      : sImagePath + "L.gif",
                    lPlusIcon       : sImagePath + "L.gif",
                    tIcon           : sImagePath + "T.gif",
                    tMinusIcon      : sImagePath + "T.gif",
                    tPlusIcon       : sImagePath + "T.gif",
                    blankIcon       : sImagePath + "blank.gif",
                    defaultText     : "",
                    defaultAction   : "javascript:void(0);",
                    defaultBehavior : "classic"
                };'.CRLF;
        if ($cat_id) {
            $code .= $this->get_cat_tree_js($cat_id, $expand);
        } else {
            foreach ($this->cat_tops as $cat_top_id)
                $code .= $this->get_cat_tree_js($cat_top_id, $expand);
        }   //end if
        $code .= '
            </script>'.CRLF;
        echo $code;
    }   //end function

    //  }}}

    //  {{{ echo_counter()

    /**
     * 输出网站计数器的 HTML 代码，实际使用的是 administrator 用户的计数器。
     *
     * @access  public
     * @param   array   $params 参数数组，格式
     *                          array (
     *                              'dd' => 计数器显示图案的类型
     *                          )
     * @return  void
     */
    function echo_counter($params)
    {
        extract($params);   //分解参数，有一个参数： $dd
        if (!isset($dd))
            $dd = 'A0';
        echo '<img src="'.$this->app_url.'/'.$this->modules['HOME']['DIR'].'/counter.php?df=administrator&dd='.$dd.'" />';
    }   //end function

    //  }}}

    //  {{{ echo_home_sort()

    /**
     * 输出主页排行列表的 HTML 代码。
     *
     * @access  public
     * @param   array   $params 参数数组，格式
     *                          array (
     *                              'type' => 排行类型，取值可为“total”（按访问总数排）或“aver”（按日均访问数排），默认值为“total”,
     *                              'numrows' => 记录数，默认值为 10
     *                          )
     * @return  void
     */
    function echo_home_sort($params)
    {
        extract($params);
        $order_by = (empty($type) || 'total' == $type) ? 'total' : 'aver';
        if (empty($numrows))
            $numrows = 10;
        $conn =& $this->get_adodb_conn('mysql');
        $cms_home =& $this->get_cms_home();
        $rs =& $cms_home->get_sort_rs($conn, '', $order_by, $numrows);
        $code = '';
        while (!$rs->EOF) {
            //s.user_id, s.counter_sort_name, s.counter_sort_url, s.counter_sort_aver as aver, floor(m.counter_number) as total, t.counter_type_name
            $code .= '<li><a href="'.$rs->fields[2].'" title="'.(($order_by!='total') ? '日均访问数：'.$rs->fields[3] : '总访问数：'.$rs->fields[4]).'">'.$rs->fields[1].'</a></li>'.CRLF;
            $rs->MoveNext();
        }   //end while
        echo $code;
    }   //end function

    //  }}}

    //  {{{ echo_header()

    /**
     * 输出头文件，根据当前模块名（使用 set_module() 设置），显示相应模块的头文件，若该模块的头文件模板不存在，则显示首页头文件。
     *
     * @access  public
     * @param   boolean $show_content   是否显示头文件中 body 中的内容，默认为 TRUE，即显示，FALSE 则是不显示。
     * @param   string  $append_title   在头文件中增加显示的标题字符串，默认为空。
     * @return  void
     * @see set_module()
     */
    function echo_header($show_content = TRUE, $append_title = '')
    {
        global $gbook_user_id,$admin_menu_tops;
        if (TRUE === $this->arguments['enable_ob'])
            ob_start();

        $title = $this->modules[$this->module]['TITLE'];
        if ('' != $append_title)
            $title = $append_title.' - '.$title;
        $header_tpl = $this->modules[$this->module]['DIR'].'/header.tpl';
       // echo  "dsfdsfdafdsa====".$header_tpl;
        if (!file_exists($this->tpl_path.'/'.$header_tpl)) {
            if (TRUE === $show_content)
                $header_tpl = 'index_header.tpl';
            else
                $header_tpl = 'index_header_none.tpl';
        }   //end if
        $this->smarty->assign('s_title', $title);
        if ('administrator' == $gbook_user_id) {
            $this->smarty->assign('s_title', '网上留言');
        }

        $menu_code = '';
//$admin_menu_tops 1 2 3 4
        foreach ($admin_menu_tops as $menu_top_id) {   //根据权限整理菜单数组
            arrange_menu_tree($menu_top_id);
//    $menu_code .= get_menu_tree($menu_top_id);
            $menu_code .= get_parent_tree($menu_top_id);
        }   //end foreach

        $this->smarty->assign_by_ref('menu_code', $menu_code);
        $this->smarty->display($header_tpl);
    }   //end function

    //  }}}

    function echo_header_user($show_content = TRUE, $append_title = '')
    {

        if($show_content==1)
            $show_content=true;
        else
            if($show_content==0)
                $show_content=false;

        $title = $this->modules[$this->module]['TITLE'];
        if ('' != $append_title)
            $title = $append_title.' - '.$title;

        if (TRUE == $show_content)
            $header_tpl = "user/index_header.tpl";
        else
            $header_tpl = 'index_header_none.tpl';

        $this->smarty->assign('s_title', $title);

        $this->smarty->display($header_tpl);
    }

	function  echo_header_rcjl($show_content = TRUE, $append_title = '') {

               if($show_content==1)
    	        $show_content=true;
    	     else{
    	         if($show_content==0)
    	              $show_content=false;
    	     }
    	     $this->echo_header($show_content,$append_title);

    	     /*
			   if (TRUE == $show_content)
                $header_tpl = "rcjl/index_header.tpl";
            else
                $header_tpl = 'rcjl/index_header_none.tpl';


            $this->smarty->display($header_tpl);
			*/
       }

     function  echo_footer_rcjl($show_content = TRUE, $append_title = '') {

               if($show_content==1)
    	        $show_content=true;
    	     else{
    	         if($show_content==0)
    	              $show_content=false;
    	     }
              $this->echo_footer($show_content,$append_title);

           // $footer_tpl="rcjl/index_footer.tpl";
           // $this->smarty->display($footer_tpl);
       }

    //  {{{ echo_footer()

    /**
     * 输出尾文件，根据当前模块名（使用 set_module() 设置），显示相应模块的尾文件，若该模块的尾文件模板不存在，则显示首页尾文件。
     *
     * @access  public
     * @param   boolean $show_content   是否显示尾文件中 body 中的内容，默认为 TRUE，即显示，FALSE 则是不显示。
     * @global  float   $cfg_start_time 当前 PHP 开始运行时间。
     * @return  void
     * @see set_module()
     */
    function echo_footer($show_content = TRUE)
    {
        global $cfg_start_time;

        $footer_tpl = $this->modules[$this->module]['DIR'].'/footer.tpl';
        if (!file_exists($this->tpl_path.'/'.$footer_tpl)) {
            if (TRUE === $show_content)
                $footer_tpl = 'index_footer.tpl';
            else
                $footer_tpl = 'index_footer_none.tpl';
        }   //end if
        $this->smarty->display($footer_tpl);
        echo CRLF.'<!-- '.((CMS_Common :: get_micro_time()) - $cfg_start_time).' -->';
        if (TRUE === $this->arguments['enable_ob'])
            ob_end_flush();
    }   //end function

    //  }}}

    //  {{{ require_cat_array()

    /**
     * 包含栏目相关的数组。
     * 调用此方法之后 CMS 类有两个（数组）属性可用：$this->cats（栏目的相关设置） 和 $this->cat_tops（最上层栏目 ID 号的集合）。
     *
     * @access public
     * @return void
     */
    function require_cat_array()
    {
        if (count($this->cats) <= 0) {
            //  Load varible $cfg_cats & $cfg_cat_tops
            require_once $this->config_path.'/cms_cat.inc.php';

            $this->cats = &$cfg_cats;
            $this->cat_tops = &$cfg_cat_tops;
        }   //end if
    }   //end function

    //  }}}

    //  {{{ require_chn_array()

    /**
     * 包含频道相关的数组。
     * 调用此方法之后 CMS 类有一个（数组）属性可用：$this->channels（频道的相关设置）。
     *
     * @access public
     * @return void
     */
    function require_chn_array()
    {
        if (count($this->channels) <= 0) {
            //  Load varible $cfg_channels & $cfg_channel_orders
            if (file_exists($this->config_path.'/cms_chn.inc.php')) {
                require_once($this->config_path.'/cms_chn.inc.php');
                $this->channels = &$cfg_channels;
                $this->channel_orders = &$cfg_channel_orders;
            }   //end if
        }   //end if
    }   //end function

    //  }}}


    //  {{{ fetch_cat_rss()

    /**
     * 获取位于 $rss_host_url 的 rss, 分析并返回结果
     *
     * @param   int     $chn_id
     * @param   string  $rss_host_url
     * @access  public
     * @return  object
     */
    function &fetch_cat_rss($chn_id, $rss_host_url)
    {
        require_once 'rss/rss_fetch.inc';

        $this->require_chn_array();
        $chn_rss_lifetime = intval($this->channels[$chn_id]['RSS_LIFETIME']) * 60;

        if (!defined('MAGPIE_OUTPUT_ENCODING')) //输出rss的字符编码, 默认值: ISO-8859-1
            define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
        if (!defined('MAGPIE_FETCH_TIME_OUT'))  //与远端连接的time out时间, 默认值: 5
            define('MAGPIE_FETCH_TIME_OUT', 30);
        if (0 == $chn_rss_lifetime) {
            if (!defined('MAGPIE_CACHE_ON'))    //是否启用cache, 默认值: true
                define('MAGPIE_CACHE_ON', 0);
        } else if (0 < $chn_rss_lifetime) {
            if (!defined('MAGPIE_CACHE_ON'))    //是否启用cache, 默认值: true
                define('MAGPIE_CACHE_ON', 1);
            if (!defined('MAGPIE_CACHE_DIR'))   //cache文件存放路径, 绝对路径, 默认值: ./cache
                define('MAGPIE_CACHE_DIR', $this->tmp_path.'/rss_cache');
            if (!defined('MAGPIE_CACHE_AGE'))   //缓存时间, 单位为秒, 默认值: 60 * 60
                define('MAGPIE_CACHE_AGE', $chn_rss_lifetime);
        }

        return fetch_rss($rss_host_url);
    }

    //  }}}

    //  {{{ get_chn_cat_info()

    /**
     * 取得频道 ID 为 $chn_id 的
     *
     * @param   integer  $chn_id        频道 ID 号
     * @param   integer  $cat_id        栏目 ID 号
     * @param   integer  $title_length  每条目的长度，截取后显示
     * @param   string   $append_text   超过 $title_length 被截取后，在后面追加显示的字符
     * @param   integer  $num_rows      该栏目显示的条目数
     * @param   string   $date_format   日期格式
     * @access  public
     * @return  array
     */
    function &get_chn_cat_info($chn_id, $cat_id, $title_length = 30, $append_text = '…', $title_icon = '', $num_rows = 15, $date_format = '%m-%d')
    {
        $this->require_chn_array();

        $rss_icon = empty($title_icon) ? $this->channels[$chn_id]['RSS_ICON'] : $title_icon;
        $code_icon = empty($rss_icon) ? '' : ' style="list-style-type: none; list-style-image: url('.$rss_icon.');"';

        preg_match('/http\:\/\/[0-9a-zA-z|\.]*(\:[0-9]{1,})?/', $this->channels[$chn_id]['LINK'], $host_detail);
        $rss_host_url = $host_detail[0].'/cms/app/info/rss/index.php/%d/%d';
        $rss_host_url = sprintf($rss_host_url, $cat_id, $num_rows);
        $rss =& $this->fetch_cat_rss($chn_id, $rss_host_url);
        $code = '';
        foreach ($rss->items as $item) {
            $link    = $item['link'];
            $title   = iconv('UTF-8', 'GB2312', $item['title']);
            $a_title = (strlen($title) > $title_length) ? ' title="'.$title.'"' : '';
            $title   = str_replace(' ', '&nbsp;', CMS_Common :: substr($title, $title_length, $append_text));
            $pubdate = $this->_get_format_date($date_format, strtotime($item['pubdate']));
            $code .= '<li'.$code_icon.'><a href="'.$link.'" target="_blank"'.$a_title.'>'.$title.'</a>';
            if ($date_format) {
                $code .= '<small>('.$pubdate.')</small>';
            }
            $code .= '</li>'.CRLF;
        }
        $chn_cat_info['title'] = iconv('UTF-8', 'GB2312', $rss->channel['title']);
        $chn_cat_info['link']  = $rss->channel['link'];
        $chn_cat_info['code']  = $code;
        return $chn_cat_info;
    }

    //  }}}

    //  {{{ get_chn_cat_tpl()

    /**
     * 获取通过 Smarty 解析后的首页或栏目页面的栏目框 HTML 代码。
     *
     * @access  public
     * @param   integer $chn_id      频道 ID 号。
     * @param   integer $cat_id      栏目 ID 号。
     * @param   boolean $parse_cat   是否解析栏目内容列表的代码，默认值为 TRUE。
     * @param   boolean $typesetting 排版方式，默认值为 1；
     *                               0 为竖排，使用 blocks/0.tpl，
     *                               1 为横排，每排两个，则使用 blocks/1.tpl。
     * @access  public
     * @return  string  通过 Smarty 解析后的栏目框 HTML 代码。
     */
    function get_chn_cat_tpl($chn_id, $cat_id, $parse_cat = TRUE, $typesetting = 1)
    {
        $chn_cat_info =& $this->get_chn_cat_info($chn_id, $cat_id, 46, '…', NULL, 10);
        $this->smarty->assign('cat_name', $chn_cat_info['title']);
        $this->smarty->assign('cat_code', ($parse_cat) ? $chn_cat_info['code'] : '{cms->echo_chn_cat_list chn_id="'.$chn_id.'" cat_id="'.$cat_id.'" title_length="50" append_text="…" num_rows="10" date_format="%m-%d"}');
        $this->smarty->assign('cat_url', $chn_cat_info['link']);
        return $this->smarty->fetch('themes/'.$this->arguments['theme'].'/blocks/'.$typesetting.'.tpl');
    }  //end function

    //  }}}


    //  {{{ need_login()

    /**
     * 用于需要用户登录之后才能使用的模块或页面，若用户没有登录，则自动跳转到登录页面，登录之后再跳会原有页面。
     *
     * @global array $_SESSION PHP session 的全局数组变量
     * @access public
     * @return void
     */
    function need_login()
    {

        if (empty($_SESSION[$this->sess_user_id]) && empty($_SESSION['CMS_ZZXX_USER_ID'])) {
            echo '
            <script language="JavaScript">
                func'.'tion urlConvert(url)
                {
                    url = url + "";
                    var furl = url;
                    var foutstring = "";
                    var x1 = 0;
                    for(x1 = 0 ; x1 < (furl.length) ; x1++) {
                        chr = furl.substr(x1,1);
                        switch(chr) {
                            case "&":
                                foutstring += "%26";
                                break;
                            case "?":
                                foutstring += "%3F";
                                break;
                            case ":":
                                foutstring += "%3A";
                                break;
                            default:
                                foutstring += chr;
                                break;
                        }   //end switch
                    }   //end for
					return foutstring;
                }   //end function

                func'.'tion login()
                {
                    top.document.location = "'.sprintf($this->login_url, '').'" + urlConvert(top.window.location.href.replace(/(\?|&)sessid=[^&]*/, ""));
                }   //end function

                window.onload = login;
            </script>';
            exit;
        }   //end if
    }   //end function

    //  }}}

    //  {{{ do_after_register()

    /**
     * 用于注册之后对建网通数据库及相关目录文件的操作（用于基础平台的接口）。
     *  1、更新建网通用户表记录；
     *  2、更新建网通主页空间记录，调用 make_home_dir() 方法创建主页空间目录，同时复制默认页面；
     *
     * @param   array                   $user_infos 注册用户的相关信息。
     *                                              array (
     *                                                  'user_id'   => '用户 ID（用户帐号）',
     *                                                  'password'  => '用户密码',
     *                                                  'nickname' => '用户昵称',
     *                                                  'user_face' => '用户头像',
     *                                                  'signature' => '用户签名',
     *                                                  'homepage'  => '用户主页地址',
     *                                                  'email'     => '用户邮件地址'
     *                                              )
     * @access  public
     * @return  boolean
     * @see     make_home_dir()
     */
    function do_after_register($user_infos)
    {
        $conn =& $this->get_adodb_conn('mysql');

        if (!is_array($user_infos)) {
            $this->trigger_error('类 CMS 的方法 do_after_register() 的参数错误。');
            return FALSE;
        }   //end if

        extract($user_infos);   //分解用户信息数组的参数，生成变量：$user_id, $password, $nickname, $user_face, $signature, $homepage
        if (!isset($create_date))
            $today = date('Y-m-d');
        else
            $today = $create_date;
        if (!isset($homepage))
            $homepage = '/~'.$user_id.'/';
        if (empty($user_face))
            $user_face = 'sys/1-1.gif';
        if (!isset($signature))
            $signature = '';

        //  表 user_main
        $sql = "SELECT COUNT(user_id) FROM user_main WHERE user_id=".$conn->qstr($user_id);
        $rs = $conn->Execute($sql);
        if ($rs->fields[0] > 0) {  //用户记录已经存在，修改之
            $sql = "UPDATE user_main SET
                        nickname=".$conn->qstr($nickname).",
                        indate=".$conn->qstr($today).",
                        user_face=".$conn->qstr($user_face).",
                        signature=".$conn->qstr($signature).",
                        homepage=".$conn->qstr($homepage).",
                        email=".$conn->qstr($email)."
                    WHERE user_id=".$conn->qstr($user_id);
        } else {    //用户不存在，插入记录
            $sql = "INSERT INTO user_main
                        (user_id, nickname, indate, user_face, signature, homepage, email)
                    VALUES
                        (".$conn->qstr($user_id).", ".$conn->qstr($nickname).", ".$conn->qstr($today).", ".$conn->qstr($user_face).", ".$conn->qstr($signature).", ".$conn->qstr($homepage).", ".$conn->qstr($email).")";
        }  //end if
        $conn->Execute($sql);

        if ($this->multi) { //多套版
            return TRUE;
        }   //end if

        //  主页空间和邮件空间处理
        $home_dir = $this->home_path.'/'.$user_id[0].'/'.$user_id[1].'/'.$user_id;

        //  获取主页空间和邮件空间的大小
        $sql = "SELECT HOME_SIZE FROM user_size WHERE SIZE_ID=1";
        $rs = $conn->Execute($sql);
        if (-1 == $rs->fields[0]) {	//主页禁用
            $home_size = 1;
            $active_home = 'N';
        } else {    //主页可用
            $home_size = $rs->fields[0];
            $active_home = 'Y';
        }	//end if

        //  主页操作
        $this->make_home_dir($user_id);
        if (!is_dir($home_dir)) {
            $this->trigger_error('主页空间创建失败，请检查服务器主页空间目录的权限是否正确。');
            return FALSE;
        }   //end if

        //  表 ftp_users
        $sql = "SELECT COUNT(username) FROM ftp_users WHERE username=".$conn->qstr($user_id);
        $rs = $conn->Execute($sql);
        if ($rs->fields[0] > 0) {  //用户 FTP 记录已存在，修改之
            $sql = "UPDATE ftp_users SET
                        passowrd=".$conn->qstr($password).",
                        QuotaSize=".$conn->qstr($home_size).",
                        active=".$conn->qstr($active_home)."
                    WHERE username=".$conn->qstr($user_id);
        } else {    //用户 FTP 记录不存在，插入记录
            $sql = "INSERT INTO ftp_users
                        (ID, username, password, homedir, QuotaFiles, QuotaSize, active)
                    VALUES
                        ('', ".$conn->qstr($user_id).", ".$conn->qstr($password).", ".$conn->qstr($home_dir).", 0, ".$conn->qstr($home_size).", ".$conn->qstr($active_home).")";
        }   //end if
        $conn->Execute($sql);
        //  设置 home quota
        if ('Y' == $active_home && $home_size > 0)
            $this->set_home_quota($user_id);

        return TRUE;
    }   //end function

    //  }}}

    //  {{{ do_after_login()

    /**
     * 用于登录之后对建网通的操作（用于基础平台的接口）。
     *  1、注册用户头像和用户 SID 的 SESSION。
     *  2、注册用户管理权限的 SESSION。
     *  2、更新 user_sess 表。
     *
     * @access  public
     * @param   string  $user_id    用户帐号。
     * @param   array   &$_SESSION   PHP session 变量数组。
     * @return  void
     */
    function do_after_login($user_id)
    {
        $conn =& $this->get_adodb_conn('mysql');
        $sql = "SELECT user_face, sid, radmin FROM user_main WHERE user_id=".$conn->qstr($user_id);
        $rs = $conn->Execute($sql);
        $_SESSION[$this->sess_user_face] = $rs->fields[0];
        $_SESSION[$this->sess_user_sid] = $rs->fields[1];
        if ('Y' == $rs->fields[2]) {
            $_SESSION[$this->sess_user_perm] = 1;
        } else {
            $sql = "SELECT user_id FROM user_access WHERE user_id=".$conn->qstr($user_id);
            $rs = $conn->Execute($sql);
	        if ($rs->RecordCount() > 0)
                $_SESSION[$this->sess_user_perm] = 1;
        }   //end if
        $conn->Replace('user_sess', array('sess_id' => session_id(), 'user_id' => $user_id), 'sess_id', $autoquote = true);
    }   //end function

    //  }}}

    //  {{{ do_after_logout()

    /**
     * 用于用户注销之后对建网通的操作（用于基础平台的接口）。
     *  1、删除 user_sess 表中的记录。
     *
     * @access  public
     * @param   string  $sess_id    当前注销用户在基础平台中的 SESSION ID。
     * @return  void
     */
    function do_after_logout($sess_id)
    {
        $conn =& $this->get_adodb_conn('mysql');
        $sql = "DELETE FROM user_sess WHERE sess_id=".$conn->qstr($sess_id);
        $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ do_after_change()

    /**
     * 修改用户信息之后对建网通的操作（用于基础平台的接口）。
     *  1、修改用户信息之后，更新建网通用户表记录；
     *  2、修改密码之后，更新建网通主页空间密码和邮件空间密码；
     *  3、修改头像之后，更新用户头像的 SESSION。
     *
     * @access  public
     * @param   array   $user_infos 注册用户的相关信息，'user_id'必填，其它字段可选。
     *                              array (
     *                                  'user_id'   => '用户 ID（用户帐号）',
     *                                  'password'  => '用户密码',
     *                                  'nickname' => '用户昵称',
     *                                  'user_face' => '用户头像',
     *                                  'signature' => '用户签名',
     *                                  'homepage'  => '用户主页地址'
     *                                  'email'     => '用户邮件地址'
     *                              )
     * @param   array   &$_SESSION   PHP session 变量数组。
     * @return  void
     */
    function do_after_change($user_infos)
    {
        $conn =& $this->get_adodb_conn('mysql');
        if (!is_array($user_infos)) {
            $this->trigger_error('类 CMS 的方法 do_after_change() 的参数错误。');
            return FALSE;
        }   //end if

        extract($user_infos);
        if (!empty($password)) {    //修改了密码
            $sql = "UPDATE ftp_users SET password=".$conn->qstr($password)." WHERE username=".$conn->qstr($user_id);
            $conn->Execute($sql);
        } else if (!empty($user_face)) {    //修改了用户头像
            $sql = "UPDATE user_main SET user_face=".$conn->qstr($user_face)." WHERE user_id=".$conn->qstr($user_id);
            $conn->Execute($sql);
            $_SESSION[$this->sess_user_face] = $user_face;
        } else if (isset($signature)) { //修改论坛签名档
            $sql = "UPDATE user_main SET signature=".$conn->qstr($signature)." WHERE user_id=".$conn->qstr($user_id);
            $conn->Execute($sql);
        }
	else if (isset($nickname) || isset($email)) { //rpc
            $sql = "UPDATE user_main SET nickname=".$conn->qstr($nickname).", email=".$conn->qstr($email)." WHERE user_id=".$conn->qstr($user_id);
            $conn->Execute($sql);
            $_SESSION[$this->sess_nick_name] = $nickname;
        }    //end if
        return TRUE;
    }   //end function

    //  {{{ do_after_delete()

    /**
     * 删除用户之后对建网通的操作（用于基础平台的接口）。
     *  1、删除建网通用户表记录；
     *  2、删除建网通主页空间记录，删除用户主页空间目录；
     *  3、删除建网通邮件空间记录，删除用户邮件空间目录；
     *
     * @access  public
     * @param   string  $user_id    用户帐号。
     * @return  void
     */
    function do_after_delete($user_id)
    {
        $conn =& $this->get_adodb_conn('mysql');
        $sql = "DELETE FROM user_main WHERE user_id='$user_id'";
        $rs = $conn->Execute($sql);
        $sql = "DELETE FROM user_access WHERE user_id='$user_id'";
        $rs = $conn->Execute($sql);
        $sql = "DELETE FROM ftp_users WHERE username='$user_id'";
        $rs = $conn->Execute($sql);
        if ($user_id) {
            $home_dir = $this->home_path.'/'.$user_id[0].'/'.$user_id[1].'/'.$user_id;
            CMS_Common :: rm($home_dir);
        }   //end if
    }   //end function

    //  }}}

    //  {{{ make_home_dir()

    /**
     * 用于创建用户的主页空间目录，同时生成默认主页面 index.html。
     *
     * @param   string   $user_id    需要创建主页空间的用户的 ID 号（用户帐号）。
     * @access  public
     * @return  boolean
     */
    function make_home_dir($user_id)
    {
        //  多套版，不创建主页空间目录，直接返回
        if ($this->multi)
            return NULL;
        if (empty($user_id)) {
            $this->trigger_error('需要创建主页空间的用户帐号不能为空。');
            return FALSE;
        }   //end if
        $home_dir1 = $this->home_path.'/'.$user_id[0];
        $home_dir2 = $home_dir1.'/'.$user_id[1];
        $home_all  = $home_dir2.'/'.$user_id;

        if (!file_exists($this->home_path))
            mkdir($this->home_path, 0755);
        if (!file_exists($home_dir1))
            mkdir($home_dir1, 0755);
        if (!file_exists($home_dir2))
            mkdir($home_dir2, 0755);

        //  建立主页空间的相关目录，拷贝缺省的主页到用户目录
        if (!file_exists($home_all.'/index.html') && !file_exists($home_all.'/index.htm')) {
            mkdir($home_all, 0755);
            copy($this->app_path.'/'.$this->modules['HOME']['DIR']."/copy.html", $home_all.'/index.html');
            copy($this->app_path.'/'.$this->modules['HOME']['DIR']."/construction.gif", $home_all.'/construction.gif');
            chmod($home_all.'/index.html', 0644);
            chmod($home_all.'/construction.gif', 0644);
        }	//end if
        return TRUE;
    }   //end function

    //  }}}

    //  {{{ set_home_quota()

    /**
     * 用于设置用户主页空间限制的文件 $HOME_DIR/.ftpquota。
     *
     * @access  public
     * @param   string  $user_id    需要设置空间限制的用户的 ID（用户帐号）
     * @return  boolean 操作成功返回 TRUE，失败返回 FALSE。
     */
    function set_home_quota($user_id)
    {
        if ($this->multi)
            return NULL;
        if (empty($user_id)) {
            $this->trigger_error('需要设置主页空间限制的用户帐号不能为空。');
            return FALSE;
        }   //end if

        /**
         *  .ftpquota 格式：
         *      2 2323
         *  第一行：已上传的文件数，已上传的字节数；
         */
        $home_dir = $this->home_path.'/'.$user_id[0].'/'.$user_id[1].'/'.$user_id;
        if (!is_dir($home_dir)) {
            $this->trigger_error('用户 '.$user_id.' 的主页空间不存在，无法完成限制主页空间的操作。');
            return FALSE;
        }   //end if
        $quota_file = $home_dir.'/.ftpquota';
        $used_size = CMS_Common :: get_used_space($home_dir);
        $content = '0 '.$used_size.CRLF;
        if ($fd = @fopen($quota_file, 'w')) {
            flock($fd, 2);
            fwrite($fd, $content);
            flock($fd, 3);
            fclose($fd);
            chmod($quota_file, 0600);
            return TRUE;
        } else {
            $this->trigger_error('主页空间限制文件 '.$quota_file.' 写操作失败，请检查服务器此文件的权限是否正确。');
            return FALSE;
        }   //end if
    }   //end function

    //  }}}

    //  {{{ get_face_array()

    /**
     * 获取系统用户头像组成的数组。
     *
     * @access  public
     * @return  array   用户头像数组。
     */
    function &get_face_array()
    {
        $open_dir = $this->face_path.'/sys';
        $faces = array();
        if ($handle = opendir($open_dir)) {
            while (FALSE !== ($entry = readdir($handle))) {
                if ('.' != $entry[0] && preg_match('/\.('.$this->face_type.')$/i', $entry))
                    $faces['sys/'.$entry] = $this->face_url.'/sys/'.$entry;
            }   //end while
            closedir($handle);
        }   //end if
        return $faces;
    }   //end function

    //  }}}

    //  {{{ get_nav_array()

    /**
     * 获取建网通模块名及其 URL 组成的数组（用于基础平台的接口）。
     *
     * @access  public
     * @return  array
     */
    function &get_nav_array()
    {
        $navs = array(
            array(  'TITLE' => $this->modules['INDEX']['TITLE'],
                    'URL' => $this->cms_url.'/'),
            array(  'TITLE' => $this->modules['CHAT']['TITLE'],
                    'URL' => $this->app_url.'/'.$this->modules['CHAT']['DIR'].'/'),
            array(  'TITLE' => $this->modules['FORUM']['TITLE'],
                    'URL' => $this->app_url.'/'.$this->modules['FORUM']['DIR'].'/'),
            array(  'TITLE' => $this->modules['KICQ']['TITLE'],
                    'URL' => 'javascript:window.open(\''.$this->app_url.'/'.$this->modules['KICQ']['DIR'].'/\', \'kicq\', \'scrollbars=no,menubar=no,toolbar=no,resizable=no,alwaysRaised=no,width=100,height=200,left=20,top=20\');void(0);'));
        if (!$this->multi) {    //非多套版
            $navs[] = array('TITLE' => $this->modules['HOME']['TITLE'],
                            'URL' => $this->app_url.'/'.$this->modules['HOME']['DIR'].'/');

        }   //end if
        if ($_SESSION[$this->sess_user_perm]) {
            $navs[] = array('TITLE' => $this->modules['ADMIN']['TITLE'],
                            'URL' => $this->app_url.'/'.$this->modules['ADMIN']['DIR'].'/');
        }   //end if
        return $navs;
    }   //end function

    //  }}}

    //  {{{ get_user_info()

    /**
     * 获取建网通独有的用户字段的信息记录（用于基础平台的接口）。
     *
     * @access  public
     * @param   array   $user_id    用户帐号。
     * @return  array   array (
     *                      'indate' => '用户注册日期',
     *                      'homepage'  => '用户主页地址',
     *                      'user_face' => '用户头像',
     *                      'signature' => '用户签名档'
     *                  )
     */
    function &get_user_info_array($user_id)
    {
        $conn =& $this->get_adodb_conn('mysql');
        $sql = "SELECT indate, homepage, user_face, signature FROM user_main WHERE user_id=".$conn->qstr($user_id);
        $rs = $conn->Execute($sql);
        $user_infos = array(
            'indate'    => $rs->fields[0],
            'homepage'  => $rs->fields[1],
            'user_face' => $rs->fields[2],
            'signature' => $rs->fields[3]);
        return $user_infos;
    }   //end function

    //  }}}

    //  {{{ get_subscribe_array()

    /**
     * 获取用户订阅的栏目内容列表组成的数组（用于基础平台的接口）。
     *
     * @access  public
     * @param   string  $user_id    用户帐号
     * @return  array  数组的每个元素均为数组，元素的结构为：
     *                  array (
     *                      'URL'  => '栏目页面 URL',
     *                      'NAME' => '栏目名称',
     *                      'CODE' => '栏目内容列表的 HTML 代码'
     *                  )
     */
    function &get_subscribe_array($user_id)
    {
        $cms_info =& $this->get_cms_info();

        $subscribes = array();
        $conn =& $this->get_adodb_conn('mysql');
        $subscribe = $cms_info->get_user_subscribe($conn, $user_id);
        if (empty($subscribe))
            return $subscribes;
        $cat_ids = explode('|', $subscribe);
        $this->require_cat_array();

        $conn =& $this->get_adodb_conn($this->dbtype, 'Info');
        foreach ($cat_ids as $cat_id) {
            $subscribes[$cat_id] = array(
                'URL' => sprintf($this->cat_index_url, $cat_id),
                'NAME' => $this->cats[$cat_id]['NAME'],
                'CODE' => $this->get_cat_list_code($cat_id, 100, '…', 0, 5, 0));
        }   //end foreach
        return $subscribes;
    }   //end function

    //  }}}

    //  {{{ upload_user_face()

    /**
     * 上传用户头像（用于基础平台的接口）。
     *
     * @access  public
     * @param   string  $user_id        用户帐号。
     * @param   array   &$upload_files  从 $_FILE 获取的用户上传图片的数组引用。
     * @param   string  $old_face       从 $_SESSION[$cms->sess_user_face] 获取的用户原来的头像数据。
     * @return  array   $returns        返回数组，结果为：
     *                                  array(
     *                                      'errmsg'    => '错误信息，若无错误则为空',
     *                                      'user_face' => '用于存储到数据库中的用户头像数据',
     *                                      'ext'       => '上传的头像文件的扩展名'
     *                                  );
     */
    function upload_user_face($user_id, &$upload_files, $old_face)
    {
        if ('usr/' == substr($old_face, 0, 4)) {    //删除旧的头像文件
            CMS_Common :: rm($this->face_path.'/'.$old_face);
        }   //end if
        $dest_file = $this->face_path.'/usr/'.$user_id;
        $returns = CMS_Common :: upload_file($upload_files, $dest_file, $this->face_type, $this->face_max_filesize);
        if (empty($returns['errmsg'])) {   //上传文件成功
            $returns['user_face'] = 'usr/'.$user_id.'.'.$returns['ext'];
        } else {
            $returns['user_face'] = '';
        }  //end if
        return $returns;
    }   //end function

    //  }}}

    //  {{{ die_xmlrpc_error()

    /**
     * 访问基础平台 XMLRPC 出错时输出错误，同时结束程序运行。
     *
     * @param   string  $errmsg 错误信息
     * @return  void
     * @aceess  public
     */
    function die_xmlrpc_error($errmsg)
    {
?>
<html>
<head>
    <title>访问基础平台 XMLRPC 方法失败！</title>
    <meta charset="utf-8" />
</head>

<body>
    <div style="color:red"><?php echo nl2br($errmsg); ?></div>
</body>
</html>
<?php
        exit;
    }   //end function

    //  }}}

    //  {{{ call_xmlrpc_method

    //  {{{ die_xmlrpc_error()

    /**
     * 调用基础平台 XMLRPC 方法，出错时调用 die_xmlrpc_error 输出错误，同时结束程序运行。
     *
     * @param   string  $method XML-RPC 方法名。
     * @param   array   $params XML-RPC 传递的参数。
     * @return  object xmlrpcresp 返回 XML-RPC 相应对象的引用。
     * @aceess  public
     */
    function &call_xmlrpc_method($method, &$params)
    {
        $c = new xmlrpc_client($this->platform_xmlrpcs['path'], $this->platform_xmlrpcs['host'], $this->platform_xmlrpcs['port']);
        $c->request_charset_encoding = 'GBK'; //指定请求编码为 GBK
        //$c->debug = 9;
        $c->setCredentials($this->platform_xmlrpcs['username'], $this->platform_xmlrpcs['password']);  //Authorization
        $m = new xmlrpcmsg($method, $params);
        $r = $c->send($m);
        $errmsg = '访问基础平台（'.$this->platform_xmlrpcs['host'].'）的 XMLRPC 方法 '.$method.' 失败！原因：' . CRLF;
        if (!$r) {
            $errmsg .= '消息发送失败！' . CRLF;
        } else if ($r->faultCode()) {  //ERROR!
            $errmsg .= '错误码：' . $r->faultCode() . '；' . CRLF . '原因：' . $r->faultString();
        } else {
            $errmsg = '';
        }   //end if
        if ($errmsg)
            $this->die_xmlrpc_error($errmsg);
        else
            return $r;
    }   //end function

    //  }}}


    //显示6条留言及6条回复
    function  echo_gbook_1($params)
    {
        $cms_home = $this->get_cms_home();
        $conn =& $this->get_adodb_conn('mysql');

        extract($params);
        if (!$gbook_user_id)
            $gbook_user_id="administrator";
        if (!$numrows)
            $numrows = 6;

        $manager = 0;
        $rs =& $cms_home->get_gbook_main_rs($conn, "user_id=".$conn->qstr($gbook_user_id));
        if ($rs->RecordCount() > 0) {
            $gbook_name = $rs->fields[1];
            $gbook_font_color = $rs->fields[2];

            $where = "user_id=".$conn->qstr($gbook_user_id);
            $order = "gbook_message_id DESC";
            $rs =& $cms_home->get_gbook_message_rs($conn, $where, '', 'count');
            $total = $rs->fields[0];

            $offset = 0;
            $rs =& $cms_home->get_gbook_message_rs($conn, $where, $order, $numrows, $offset);
            $msgs = array();
            $offset = 0;
            while (!$rs->EOF) {
                //gbook_message_id, user_id, gbook_message, gbook_message_name, gbook_message_email, gbook_message_time, gbook_message_ip
                $msgs[$offset]['id'] = $rs->fields[0];
                $msgs[$offset]['content'] = nl2br($rs->fields[2]);
                $msgs[$offset]['name'] = $rs->fields[3];
                $msgs[$offset]['email'] = $rs->fields[4];
                $msgs[$offset]['time'] = $rs->fields[5];

                $offset++;
                $rs->MoveNext();
            }   //end while
            $sel_pages = array();


            $this->smarty->assign_by_ref('msgs', $msgs);


            // $smarty->assign_by_ref('manager', $manager);
            // $smarty->display($cms->modules['HOME']['DIR'].'/gbook.tpl');

            //回复

            $order = "msg_time DESC";


            $offset = 0;

            $rs =& $cms_home->get_gbook_msg_rpl_rs($conn, '', $order, $numrows, $offset);
            while (!$rs->EOF) {


                $msgs_rpl[$offset]['msg_rpl'] = $rs->fields[1];

                $offset++;
                $rs->MoveNext();
            }   //end while

            $this->smarty->assign_by_ref('msgs_rpl', $msgs_rpl);


        } else {



        }   //end if

    }//end of function echo_gbook_1


	    //显示4条留言及4条回复
    function  echo_gbook_2($params)
    {
        $cms_home = $this->get_cms_home();
        $conn =& $this->get_adodb_conn('mysql');

        extract($params);
        if (!$gbook_user_id)
            $gbook_user_id="administrator";
        if (!$numrows)
            $numrows =3;

        $manager = 0;
        $rs =& $cms_home->get_gbook_main_rs($conn, "user_id=".$conn->qstr($gbook_user_id));
        if ($rs->RecordCount() > 0) {
            $gbook_name = $rs->fields[1];
            $gbook_font_color = $rs->fields[2];

            $where = "user_id=".$conn->qstr($gbook_user_id);
            $order = "gbook_message_id DESC";
            $rs =& $cms_home->get_gbook_message_rs($conn, $where, '', 'count');
            $total = $rs->fields[0];

            $offset = 0;
            $rs =& $cms_home->get_gbook_message_rs($conn, $where, $order, $numrows, $offset);
            $msgs = array();
            $offset = 0;
            while (!$rs->EOF) {
                //gbook_message_id, user_id, gbook_message, gbook_message_name, gbook_message_email, gbook_message_time, gbook_message_ip
                $msgs[$offset]['id'] = $rs->fields[0];
                $msgs[$offset]['content'] = nl2br($rs->fields[2]);
                $msgs[$offset]['name'] = $rs->fields[3];
                $msgs[$offset]['email'] = $rs->fields[4];
                $msgs[$offset]['time'] = $rs->fields[5];

                $offset++;
                $rs->MoveNext();
            }   //end while
            $sel_pages = array();


            $this->smarty->assign_by_ref('msgs', $msgs);


            // $smarty->assign_by_ref('manager', $manager);
            // $smarty->display($cms->modules['HOME']['DIR'].'/gbook.tpl');

            //回复

            $order = "msg_time DESC";


            $offset = 0;

            $rs =& $cms_home->get_gbook_msg_rpl_rs($conn, '', $order, $numrows, $offset);
            while (!$rs->EOF) {


                $msgs_rpl[$offset]['msg_rpl'] = $rs->fields[1];

                $offset++;
                $rs->MoveNext();
            }   //end while

            $this->smarty->assign_by_ref('msgs_rpl', $msgs_rpl);


        } else {

        }   //end if

    }//end of function echo_gbook_2

    function get_zxzx_array(&$conn_m,$zx_id)
    {
        $numrows=3;
        //-------------------------------
        $sql="select id,q_title from zx_question where zx_id ='".$zx_id."' AND an_flag = 'yes' order by an_time desc ";
        // $conn_m->debug=true;
        $rs = $conn_m->SelectLimit($sql, $numrows, 0);

        $i=1;
        while (!$rs->EOF)
        {
            $zxqs[$i]['q_id'] = $rs->fields[0];

            $zxqs[$i]['q_title'] = $rs->fields[1];

            $i++;
            $rs->MoveNext();
        }   //end while
        return  $zxqs;
    }//end of function

	function get_zxzx_array_rc(&$conn_m,$zx_id)
    {
        $numrows=7;
        //-------------------------------
        $sql="select id,q_title from zx_question where zx_id ='".$zx_id."' AND an_flag = 'yes' order by an_time desc ";
        // $conn_m->debug=true;
        $rs = $conn_m->SelectLimit($sql, $numrows, 0);

        $i=1;
        while (!$rs->EOF)
        {
            $zxqs[$i]['q_id'] = $rs->fields[0];

            $zxqs[$i]['q_title'] = $rs->fields[1];

            $i++;
            $rs->MoveNext();
        }   //end while
        return  $zxqs;
    }//end of function

    function get_rs_zxzx(){
        require_once ("zxzx/zxzx_system.inc.php");
        $zxzx_app_url=$zxzx->app_url;
        //echo "ppppp===".$zxzx->web_url;
        $zx_id =1;
        $conn_m = $zxzx->get_adodb_conn();
        $rxzs=$this->get_zxzx_array($conn_m,$zx_id );//入学招生
        $zx_id=2;
        $jysf=$this->get_zxzx_array($conn_m,$zx_id );//教育收费
        $zx_id=3;
        $jxjdjy=$this->get_zxzx_array($conn_m,$zx_id );//转学借读就业
        $zx_id=4;
        $qt=$this->get_zxzx_array($conn_m,$zx_id );//其它

        $this->smarty->assign_by_ref('rxzs', $rxzs);
        $this->smarty->assign_by_ref('jysf', $jysf);
        $this->smarty->assign_by_ref('jxjdjy', $jxjdjy);
        $this->smarty->assign_by_ref('qt', $qt);
        $this->smarty->assign_by_ref('zxzx_app_url', $zxzx_app_url);
        // print_r( $rxzs);
        //------------------------------------
        // print_r($zxqs) ;


    }//end of function get_rs_zxzx

	function get_rs_zxzx_rc(){
	require_once ("zxzx/zxzx_system.inc.php");
	$zxzx_app_url=$zxzx->app_url;
	//echo "ppppp===".$zxzx->web_url;
        $zx_id =7;
        $conn_m = $zxzx->get_adodb_conn();
        $rxzs=$this->get_zxzx_array_rc($conn_m,$zx_id );//人才交流


        $this->smarty->assign_by_ref('rxzs', $rxzs);
      /*
		$this->smarty->assign_by_ref('jysf', $jysf);
        $this->smarty->assign_by_ref('jxjdjy', $jxjdjy);
        $this->smarty->assign_by_ref('qt', $qt);
         $this->smarty->assign_by_ref('zxzx_app_url', $zxzx_app_url);
      */
       // print_r( $rxzs);
  //------------------------------------
 // print_r($zxqs) ;


}//end of function get_rs_zxzx

    function echo_trans_pics ()
    {
        $pic_url = $this->tpl_url . '/images/trans/cur/';
        $pic_path = $this->tpl_path . '/images/trans/cur/';
        $images = glob("$pic_path{*.gif,*.jpg,*.png}", GLOB_BRACE);
        if (!$images) return;
        sort($images);
        $js_array = '';
        $first_img = '';
        foreach ($images as $key => $img) {
            $js_key = $key + 1;
            $img_url = $pic_url . basename($img);
        	$js_array .= "imgUrl[$js_key]=\"{$img_url}?".uniqid('fppic')."\";\n";
            if (!$first_img) $first_img = $img_url.'?'.uniqid('fppic');
        }
        $js_pic_count = count($images) - 1;
        echo <<<JS
<img width="320" height="235" border="0" name="imgInit" style="FILTER: revealTrans(duration=2,transition=6)" src="$first_img"/>
<script language=JavaScript>
var imgUrl=new Array();
var imgLink=new Array();
var adNum=0;
$js_array
var imgPre=new Array();
var j=0;
for (i=0;i<=$js_pic_count;i++) {
	if(imgLink[i]!="") {j++;}
	else {break;}
}
function playTran(){
	if (document.all)
		imgInit.filters.revealTrans.play();
}
var key=0;
function nextAd(){
	if(adNum<j)adNum++ ;
	else adNum=1;

	if( key==0 ){key=1;}
	else if (document.all){
		imgInit.filters.revealTrans.Transition=25;
		imgInit.filters.revealTrans.apply();
        playTran();

	}
	document.images.imgInit.src=imgUrl[adNum];
	jumpUrl=imgLink[adNum];
	theTimer=setTimeout("nextAd()", 5000);
}
function goUrl(){
	jumpTarget="_blank";
	if (jumpUrl != ""){
		if (jumpTarget != "") window.open(jumpUrl,jumpTarget);
		else location.href=jumpUrl;
	}
}
nextAd();
</script>
JS;
    }

    function echo_pic_list ($params){
        extract($params);
        $tag = $type?$type:'middle';
        switch($tag){
            case middle:
            $type =1;
            break;
            case primary:
            $type = 2;
            break;
            case kindergarten:
            $type = 3;
            break;
            default:
            $type=1;
            break;
        }

        $pic_url = $this->tpl_url.'/images/piclist/';
        $pic_path = $this->tpl_path.'/images/piclist/';
        $conn = self::get_adodb_conn();
        $sql = "select * from piclist where type=$type order by id desc limit 3";
        $result = $conn->Execute($sql);
        if (!$result)
                print $conn->ErrorMsg();
        else
        while (!$result->EOF) {
                $cfg_piclist[] = $result->fields;
                $result->MoveNext();
        }



        $width = $width?$width:280;
        $height = $height?$height:208;
        echo '
                <table border="0" align="center">
                  <tr align="center" valign="middle">
                  ';
                  foreach($result as $r){
                      echo '<td width="290" height="218"><div align="center"><img src="'.$pic_url.$r[2].'" width="'.$width.'" height="'.$height.'"></div></td>';
                  }
        echo '
                  </tr>
                  <tr align="center" valign="middle">
                    <td><div align="center"></div></td>
                    <td><div align="center"></div></td>
                    <td><div align="right"><a href="/cms/app/info/cat/chuangquan.php?type='.$type.'"><button>更  多</button></a></div></td>
                  </tr>
              </table>';
    }

    //创全图片滚动条
    function echo_scroll_picture(){
        $pic_url = $this->tpl_url.'/images/piclist/';

        $conn = self::get_adodb_conn();
        $sql = "select * from piclist order by id desc limit 6";
        $result = $conn->Execute($sql);
       echo' <div align="center">
          <table width="493" border="0" align="left" cellpadding="0" cellspacing="0" uetable="null">
            <tbody>
              <tr class="firstRow">
                <td height="25">
                  <table width="490" border="0" cellspacing="0" cellpadding="0" uetable="null">
                    <tbody>
                      <tr class="firstRow">
                        <td width="3"></td>
                        <td align="left" valign="top">
                          <div id="demo" style="width: 800px; height: 128px; overflow: hidden; margin-right: auto; margin-left: auto;">
                            <table align="left" border="0" cellspacing="0" cellpadding="0" uetable="null" cellspace="0">
                              <tbody>
                                <tr class="firstRow">
                                  <td width="3400" id="demo1" valign="top">
                                    <table width="845" align="left" border="0" cellspacing="0" cellpadding="0" uetable="null">
                                      <tbody>
                                        <tr align="center" class="firstRow">
                                        ';
                                          foreach($result as $r){
                                              echo '<td width="171" valign="middle" style="-ms-word-break: break-all;"> <img src="'.$pic_url.$r[2].'" width="171" height="128"> </td>';
                                          }
                                       echo '
                                        </tr>
                                      </tbody>
                                  </table></td>
                                  <td id="demo2" valign="top"></td>
                                  <td valign="top"></td>
                                  <td valign="top"> <a href="/cms/app/info/doc/index.php/300476"></a> </td>
                                </tr>
                              </tbody>
                            </table>
                        </div></td>
                      </tr>
                    </tbody>
                  </table>
                  <script>
                    demo2.innerHTML=demo1.innerHTML
                    function Marquee()
                    {
                        if(demo2.offsetWidth-demo.scrollLeft<=0)
                        {
                            demo.scrollLeft-=demo1.offsetWidth
                        }
                        else{
                            demo.scrollLeft++
                        }
                    }
                    var speed=20//速度数值越大速度越慢
                    var MyMar=setInterval(Marquee,speed)
                    demo.onmouseover=function() {clearInterval(MyMar)}

                    demo.onmouseout=function() {MyMar=setInterval(Marquee,speed)}

                    </script>
                </td>
                <td width="5" rowspan="2"></td>
              </tr>
              <tr>
                <td>&nbsp; </td>
              </tr>
              <tr>
                <td height="5" colspan="2"></td>
              </tr>
            </tbody>
          </table>
        </div>';
    }

    function echo_flash_pics ($params)
    {
        extract($params);
        $tag =(!empty($tag) && $tag !== 'home') ? $tag : '';
        $width    = empty($width)  ? 320 : intval($width);
        $height   = empty($height) ? 235 : intval($height);
        $pic_url  = $this->tpl_url . '/images/flashpic/';
        $pic_path = $this->tpl_path . '/images/flashpic/';
        $pics_str = '';
        if (!file_exists($pic_path)) return;
        $config = $pic_path.'config'.$tag.'.inc.php';
        require($config);

        foreach($cfg_flashpics as $k=>$v)
        {
            $pics_str .= $pic_url.$v['img'].'?'.uniqid('flashpic').'|';
            $texts[] = $v['text'];
            $links[] = $v['link'];
        };
//        var_dump($pics_str);

        $pics_str  = htmlspecialchars(substr($pics_str, 0, -1), ENT_NOQUOTES, 'UTF-8');
        $texts_str = htmlspecialchars(implode('|', $texts), ENT_NOQUOTES, 'UTF-8');
        $links_str = htmlspecialchars(implode('|', $links), ENT_NOQUOTES, 'UTF-8');
        echo <<<JS
<script type="text/javascript">
        var focus_width  = $width; //Flash图片宽度
        var focus_height = $height; //Flash图片高度
        var text_height  = 25; //标题文字高度
        var swf_height   = focus_height + text_height; //Flash高度=图片高度+文字高度

        var pics  = "$pics_str";
        var texts = "$texts_str";
        var links = "$links_str";

        var pvswf = '/cms/pixviewer.swf';
        document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+ focus_width +'" height="'+ swf_height +'">');
        document.write('<param name="allowScriptAccess" value="sameDomain"><param name="movie" value="'+pvswf+'"><param name="quality" value="high"><param name="bgcolor" value="#cccccc">');
        document.write('<param name="menu" value="false"><param name=wmode value="opaque">');
        document.write('<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'">');
        document.write('<embed src="'+pvswf+'" wmode="opaque" FlashVars="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'" menu="false" bgcolor="#cccccc" quality="high" width="'+ focus_width +'" height="'+ swf_height +'" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />');           document.write('</object>');
</script>
JS;
    }

    /**
     * 输出页眼（焦点区域）。
     *
     * @param array $params 参数数组。
     */
    function echo_focus ($params)
    {
		extract($params);
        $pic_fn = $this->tpl_path . '/images/focus/config.inc.php';
        if (!file_exists($pic_fn)) return;
        require_once $pic_fn;
        if ($focus_configs['active'] == 'flash') { //Flash 动态图片
            $pics  = array();
            $texts = array();
            $links = array();
            foreach ($focus_configs['flash'] as $cfg) {
                if (!$cfg['url']) continue;
                $pics[]  = $cfg['url'].'?_rnd='.uniqid();
                $texts[] = htmlspecialchars(CMS_Common::substr($cfg['text'], 38, '..'), ENT_QUOTES, 'UTF-8');
                $links[] = $cfg['link'];
            }
            if (!$pics) return;
            ?>
            <script type="text/javascript">
            var focus_width  = <?php echo $focus_configs['width']; ?>;
            var focus_height = <?php echo $focus_configs['height']; ?>;
            var text_height  = 25;
            var swf_height   = focus_height + text_height; //Flash高度=图片高度+文字高度

            var pics  = "<?php echo implode('|', $pics); ?>";
            var texts = "<?php echo implode('|', $texts); ?>";
            var links = "<?php echo implode('|', $links); ?>";

            var pvswf = '/cms/pixviewer.swf';
            document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+ focus_width +'" height="'+ swf_height +'">');
            document.write('<param name="allowScriptAccess" value="sameDomain"><param name="movie" value="'+pvswf+'"><param name="quality" value="high"><param name="bgcolor" value="#cccccc">');
            document.write('<param name="menu" value="false"><param name=wmode value="opaque">');
            document.write('<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'">');
            document.write('<embed src="'+pvswf+'" wmode="opaque" FlashVars="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'" menu="false" bgcolor="#cccccc" quality="high" width="'+ focus_width +'" height="'+ swf_height +'" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />');
            document.write('</object>');
            </script>
            <div style="text-align:center; background-color:#6d6d6d; color:#fff; font-weight:bold; padding: 4px 6px;">
                <?php echo htmlspecialchars($focus_configs['flash_title'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php
        } else { //JS 动态图片
            $pics = array();
            foreach ($focus_configs['trans'] as $cfg) {
                if (!$cfg['url']) continue;
                $pics[]  = $cfg['url'].'?_rnd='.uniqid();
            }
            if (!$pics) return;
            ?>
            <img width="<?php echo $focus_configs['width']; ?>" height="<?php echo $focus_configs['height']; ?>" border="0" name="imgInit" style="FILTER: revealTrans(duration=2,transition=6)" src="<?php echo $pics[0]; ?>"/>
            <div style="text-align:center; background-color:#6d6d6d; padding: 4px 6px;">
                <?php
                $title = '<span style="color:#fff; font-weight:bold;">'.nl2br(htmlspecialchars($focus_configs['trans_title'], ENT_QUOTES, 'UTF-8')).'</span>';
                if (empty($focus_configs['trans_url'])) {
                    echo $title;
                } else {
                    echo '<a href="'.$focus_configs['trans_url'].'" style="text-decoration:none">'.$title.'</a>';
                }
                ?>
            </div>
            <script type="text/javascript">
                var imgUrl=new Array();
                var adNum=0;

                <?php foreach ($pics as $i => $pic) {
                    echo 'imgUrl['.$i.'] = "'.$pic.'";'.CRLF;
                } ?>

                function playTran(){
                    if (document.all)
                        imgInit.filters.revealTrans.play();
                }
                var key = 0;
                function nextAd(){
                    if (adNum < imgUrl.length-1) adNum++;
                    else adNum = 0;

                    if( key==0 ){key=1;}
                    else if (document.all){
                        imgInit.filters.revealTrans.Transition=25;
                        imgInit.filters.revealTrans.apply();
                        playTran();
                    }
                    document.images.imgInit.src = imgUrl[adNum];
                    theTimer=setTimeout("nextAd()", 5000);
                }
                setTimeout("nextAd()", 5000);
            </script>
            <?php
        }


    }

    //========================= 多语言 :: START =========================

    const LANG_CHS = 'chs';
    const LANG_CHT = 'cht';
    const LANG_ENG = 'eng';

    public static $langs = array(
        self::LANG_CHS => '简体版',
        self::LANG_CHT => '繁体版',
        self::LANG_ENG => '英文版',
    );

    /**
     * 获取当前语言。
     *
     * @return string
     */
    public function get_lang()
    {
        if (isset($_COOKIE['CMS_LANG']) && isset(self::$langs[$_COOKIE['CMS_LANG']])) {
            return $_COOKIE['CMS_LANG'];
        } else {
            return self::LANG_CHS;
        }
    }

    /**
     * 设置当前语言。
     *
     * @param string $lang 设置的语言常量，参考 CMS::LANG_XXX 常量。
     * @return void
     */
    public function set_lang($lang)
    {
        if (!isset(self::$langs[$lang])) $lang = self::LANG_CHS;
        $_COOKIE['CMS_LANG'] = $lang;
        setcookie('CMS_LANG', $lang, strtotime('+365 days'));
    }

    /**
     * Smarty 的简体转繁体输出过滤函数。
     *
     * @param string $tpl_output 模板输出内容。
     * @param Smarty $smarty Smarty 对象。
     * @return string 返回转换之后的模板输出内容。
     */
    public function lang_trans_smarty($tpl_output, &$smarty)
    {
        return $this->lang_trans($tpl_output);
    }

    /**
     * 根据当前语言设置，对内容进行简繁转换。
     *
     * @param string $content 简体内容。
     * @return string 返回转换之后的内容。
     */
    public function lang_trans($content)
    {
        if ($this->get_lang() == self::LANG_CHT) { //如果当前语言为繁体，则将原有输出中的简体转换成繁体
            include dirname(__FILE__).'/chinese_conversion/convert.php';
            $content = zhconversion_tw($content);
        }
        return $content;
    }

    //========================= 多语言 :: END =========================

}
