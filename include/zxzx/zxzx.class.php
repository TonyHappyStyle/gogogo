<?php
//  $Id:$


/**
 * zxzx平台类。
 *
 * @package     K12zxzx
 * @copyright   K12 Studio
 * @access      public 
 */
class ZXZX
{
    //  {{{  private properties
    
    /**
     * ejob平台 ZXZX 类文件的 Id，用于 CVS 版本追踪。
     * @var     string
     * @access  private
     */
    var $_id = '$Id:$';
    /**
     * zxzx平台使用的 ADOdb 连接数据库对象。
     * @var     object ADOConnection
     * @access  private
     */
    var $_conn = NULL;
    /**
     * zxzx平台使用的 Smarty 模板引擎对象。
     * @var     object Smarty
     * @access  private
     */
    var $_smarty = NULL;
    /**
     * zxzx平台使用的 K12EJOB_User 对象。
     * @var     object K12EJOB_User
     * @access  private
     */
    var $_user = NULL;    
    /**
     * zxzx平台使用的 K12EJOB_Zonghe 对象。
     * @var     object K12EJOB_Zonghe
     * @access  private
     */
    var $zxzx平台使用的 = NULL;    
    /**
     * ejob平台使用的 K12EJOB_Log 对象。
     * @var     object K12EJOB_Log
     * @access  private
     */
    var $_log = NULL;         
    /**
     * ejob平台使用的数据库类型，当前可为“mysql”和“oracle”。
     * @var     string
     * @access  private
     */    
    var $_db_type = 'mysql';
    /**
     * ejob平台使用的数据库设置数组。
     * @var     array
     * @access  private
     */    
    var $_db_vars = array();
    
    //  }}}
    
    //  {{{ public properties

    /**
     * ejob平台的项目名称。
     * @var     string
     * @access  public
     */    
    var $prj_name = 'K12在线咨询系统';
    /**
     * zxzx 平台版本号。
     * @var     string
     * @access  public
     */    
    var $prj_version = '1.0';
    /**
     * 当前所用的操作系统，其值可为 win（Windows 操作系统）、lin（Linux 操作系统）、sun（solaris 操作系统）等。
     * @var string
     * @access  public
     */    
    var $os = '';
    /**
     * 当前所用的 PHP 版本号。
     * @var string
     * @access  public
     */    
    var $php_version = '';    
        
    /**
     * zxzx 平台 WEB 发布目录物理路径。
     * @var     string
     * @access  public
     */    

    /**
     * 访问基础平台 XMLRPC 的参数
     * @var array
     * @access  public
     */
    var $platform_xmlrpcs = array();
	
    var $web_path = '';
    /**
     * zxzx 平台 WEB 发布目录 URL。
     * @var     string
     * @access  public
     */
         
    var $web_url = '';
	/**
     * zxzx 平台应用程序所在目录物理路径。
     * @var     string
     * @access  public
     */    
    var $app_path = '';
    /**
     * zxzx 平台应用程序所在目录 URL。
     * @var     string
     * @access  public
     */        
    var $app_url = '';
    /**
     * zxzx 平台数据文件所在目录物理路径。
     * @var     string
     * @access  public
     */        
    var $data_path = '';
    /**
     * zxzx 平台数据文件所在目录 URL。
     * @var     string
     * @access  public
     */            
    var $data_template = '';
    /**
     * zxzx 平台摸版文件所在目录 URL。
     * @var     string
     * @access  public
     */            
    var $data_url = '';
    /**
     * zxzx 平台 WEB 相关配置文件所在目录物理路径。
     * @var     string
     * @access  public
     */        
    /**
     * zxzx 平台模板文件所在目录物理路径。
     * @var     string
     * @access  public
     */        
    var $tpl_path = '';
    /**
     * zxzx 平台模板文件所在目录 URL。
     * @var     string
     * @access  public
     */            
    var $tpl_url = '';
    /**
     * zxzx 平台图片所在目录物理路径。
     * @var     string
     * @access  public
     */        
    var $image_path = '';
    /**
     * ejob平台图片所在目录 URL。
     * @var     string
     * @access  public
     */            
    var $image_url = '';
 
	/**
     * zxzx 平台用户身份数组。
     * @var     array
     * @access  public
     */        
    var $user_types = array();    
    /**
     * zxzx 平台每个页面显示运行时间的开关。
     * @var     boolean
     * @access  public
     */    
    var $display_runtime = TRUE;
    /**
     * zxzx 平台用于显示 ADODB 调试信息的开关。
     * @var     boolean
     * @access  public
     */    
    var $debug_adodb = FALSE;
    /**
     * zxzx 平台用于显示 Smarty 调试信息的开关。
     * @var     boolean
     * @access  public
     */    
    var $debug_smarty = FALSE;
   
    /**
     * 用户 SESSION 最大存活时间。
     * @var     integer
     * @access  public
     */
    var $sess_expiry = 3600;
    /**
     * 用户登录帐号的 SESSION ID。
     * @var     string
     * @access  public
     */
    var $sess_user_id = 'ZXZX_USER_ID';
	/**
     * 用户真实姓名的 SESSION 名字。
     * @var     string
     * @access  public
     */
    var $sess_user_name = 'ZXZX_USER_NAME';
	/**
     * 用户真实姓名的 SESSION 名字。
     * @var     string
     * @access  public
     */
    var $sess_user_pri = 'ZXZX_USER_PRI';

	/**
     * 用户登录帐号身份标志的 SESSION 名字。
     * @var     string
     * @access  public
     */
	 var $sess_user_flag = 'ZXZX_USER_FLAG';

  /**    
     * 用户注销后台处理的 URL。
     * @var string
     * @access  public
     */            
    var $logout_do_url = '';
 /**    
     * 用户登录后台处理的 URL。
     * @var string
     * @access  public
     */            
    var $login_do_url = '';

    //  }}}
    
    
	/**
     * ZXZX 类的构建函数。
     * 初始化 ZXZX 类，设置ZXZX平台各个环境变量
     * 
     * @return  void
     * @access  public
     */
    function ZXZX()
    {
        global $cfg_db_vars,$cfg_platform_xmlrpcs,$cfg_arguments,$cfg_web_path, $cfg_web_url, $cfg_modules, $cfg_user_types, $cfg_group_flags, $cfg_debug_xmlrpc, $cfg_debug_smarty, $cfg_display_runtime, $cfg_sess_expiry,$cfg_logout_do_url,$cfg_login_do_url;
        
        //  OS & PHP version.
        require 'zxzx/zxzx_config.inc.php';
     
		$this->os = strtolower(substr(PHP_OS, 0, 3)); 
        $this->php_version = PHP_VERSION;
        //  Init vars
        $this->_db_type = $cfg_db_vars['dbtype'];
        switch ($this->_db_type) {
        case 'oracle':
            $cfg_db_vars['dbtype'] = 'oci8po';
            break;
        default:
            $cfg_db_vars['dbtype'] = 'mysql';
            break;            
        }   //end switch
        $this->_db_vars =& $cfg_db_vars;  

      
		$this->platform_xmlrpcs = &$cfg_platform_xmlrpcs;   //基础平台 XMLRPC 参数
        $this->web_path =& $cfg_web_path;
        $this->web_url  =& $cfg_web_url;
        $this->modules  =& $cfg_modules;
        $this->user_types   =& $cfg_user_types;
        if ($this->is_inner_addr()) {
            $this->logout_do_url= 'http://10.96.0.36/'.$cfg_logout_do_url;
	        $this->login_do_url = 'http://10.96.0.36/'.$cfg_login_do_url;
	    } else {
            $this->logout_do_url= 'http://222.66.2.11/'.$cfg_logout_do_url;
	        $this->login_do_url = 'http://222.66.2.11/'.$cfg_login_do_url;
	    }    
		$this->debug_xmlrpc =& $cfg_debug_xmlrpc;
        $this->debug_smarty =& $cfg_debug_smarty;
        $this->display_runtime =& $cfg_display_runtime;
        $this->sess_expiry =& $cfg_sess_expiry;
        $this->app_path     = $this->web_path . '/app';
        $this->app_url      = $this->web_url . '/app';
        $this->data_path    = $this->web_path . '/data';
        $this->data_url     = $this->web_url . '/data';
        $this->config_path  = $this->data_path . '/config';
        $this->tpl_path     = $this->data_path . '/templates';
        $this->tpl_url      = $this->data_url . '/templates';
		$this->image_path   = $this->tpl_path . '/images';
        $this->image_url    = $this->tpl_url . '/images';
		$this->tmp_path     = $this->web_path . '/tmp';
        //  设置时区
        setlocale(LC_TIME, 'zh_CN.GB18030');        
    }   //end function
    
    //  }}} 
    function get_remote_addr()
    {
    
        $remote_addr = (empty($_SERVER['HTTP_X_FORWARDED_FOR']) || $_SERVER['HTTP_X_FORWARDED_FOR'] == '127.0.0.1')
            ? $_SERVER['REMOTE_ADDR']
            : substr($_SERVER['HTTP_X_FORWARDED_FOR'], 0, strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ','));
        return $remote_addr;
    } 
    
    function is_inner_addr($remote_addr = '')
    {
        
        $inner_addr = '10.*.*.*|31.*.*.*';
        
        if (empty($inner_addr))
            return FALSE;
        if (empty($remote_addr))
            $remote_addr = $this->get_remote_addr();
        $reg = str_replace('.', '\.', $inner_addr);
        $reg = str_replace('*', '[0-9]*', $reg);
        $reg = '/^(' . $reg . ')$/';
        if (preg_match($reg, $remote_addr)) {
            return TRUE;
        } else {
            return FALSE;
        }   //end if
    }   //end function
    
    /**
     * 获取ZXZX平台的 ADOdb 数据库连接对象。
     *
     * @return  object ADOConnection    ADOdb 连接数据库对象。
     * @access  public
     */
    function &get_adodb_conn()
    {
    	global $ADODB_FETCH_MODE;

        //  包含 ADOdb 类文件
        require_once( 'adodb/adodb.inc.php');
        //  设置 ADOdb
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;    //设置 ADOdb 返回值模式为“数字下标”
        if (NULL == $this->_conn) {
            $this->_conn = &ADONewConnection($this->_db_vars['dbtype']);
            
            $this->_conn->NConnect($this->_db_vars['dbhost'],
                                $this->_db_vars['dbuser'],
                                $this->_db_vars['dbpass'],
                                $this->_db_vars['dbname']);
        }   //end if
        
        $this->set_adodb_debug($this->debug_adodb);
		$this->_conn->execute('set names utf8');
		return $this->_conn; 
		//print_r( $this->_conn);
    }   //end function

    /**
     * 设置ZXZX平台的 ADOdb 数据库的调试标记。
     *
     * @param   boolean $flag   调试标记，TRUE 为打开调试开关，FALSE 为关闭。
     * @return  void
     * @access  public
     */
    function set_adodb_debug($flag)
    {
        $flag = ($flag) ? TRUE : FALSE;
        if ($this->_conn) {            
            $this->_conn->debug = $flag;
        }   //end if
        $this->debug_adodb = $flag;
    }   //end function

    /**
     * 获取ZXZX平台使用的数据库类型。
     *
     * @return  string  数据库类型，如：mysql, oracle。
     * @access  public
     */    
    function get_db_type()
    {
        return $this->_db_type;
    }   //end function
    
    /**
     * 用于获取ZXZX平台所用的 Smarty 对象。
     *
     * @return  object Smarty
     * @access  public
     */    
      function &get_smarty()
    {     
        //包含 Smarty 类文件
        require_once(SMARTY_DIR . 'Smarty.class.php');

        if ('smarty' != get_class($this->_smarty)) {
            $this->_smarty = new Smarty;
 
           $this->_smarty->template_dir = $this->tpl_path . '/';
    
            $this->_smarty->compile_dir = $this->tmp_path . '/templates_c/';
            $this->_smarty->config_dir = $this->config_path . '/';
            $this->_smarty->cache_dir = $this->tmp_path . '/smarty_cache/';
            $this->_smarty->caching = FALSE;
  
     
		}   //end if
        $this->_smarty->debugging = $this->debug_smarty;
        return $this->_smarty;
    }   //end function




    /**
     * 用于获取ZXZX平台所用的 ZXZX_User 对象。
     *
     * @return  object K12EJOB_User
     * @access  public
     */    
    function &get_object_user()
    {
        //包含 ZXZX 类文件
        require_once(ZXZX_DIR . 'zxzx_user.class.php');
        if ('ZXZX_User' != get_class($this->_User)) {
            $this->_User = new ZXZX_User($this);            
        }   //end if
        return $this->_User;
    }   //end function        

	 /**
     * 用于获取ZXZX平台所用的 ZXZX_Common 对象。
     *
     * @return  object ZXZX_Common
     * @access  public
     */    
    function &get_object_common()
    {
        //包含 K12EJOB_User 类文件
        require_once(ZXZX_DIR . 'zxzx_common.class.php');
        if ('ZXZX_Common' != get_class($this->_Common)) {
            $this->_Common = new ZXZX_Common($this);            
        }   //end if
        return $this->_Common;
    }   //end function   
 
	
    /**
     * 输出头文件。
     * 
     * @access  public
     * @param   boolean $show_content   是否显示头文件中 body 中的内容，默认为 TRUE，即显示，FALSE 则是不显示。
     * @param   string  $append_title   在头文件中增加显示的标题字符串，默认为空。
     * @return  void
     * @see set_module()
     */
    function echo_header($show_content = TRUE, $append_title = '') 
    {
        if ('' != $append_title)
            $title = $append_title;


        if (TRUE === $show_content)
            $header_tpl = 'index_header.tpl';
        else
            $header_tpl = 'index_header_none.tpl';
        
        $this->_smarty->assign_by_ref('s_title', $title);
        $this->_smarty->display($header_tpl);
    }   //end function

    //  }}}
    
    //  {{{ echo_footer()
    
    /**
     * 输出尾文件。
     *
     * @access  public
     * @param   boolean $show_content   是否显示尾文件中 body 中的内容，默认为 TRUE，即显示，FALSE 则是不显示。
     * @return  void
     * @see set_module()
     */    
    function echo_footer($show_content = TRUE) 
    {
        if (TRUE === $show_content)
			$footer_tpl = 'index_footer.tpl';  
		else
			$footer_tpl = 'index_footer_none.tpl';
        
        $this->_smarty->display($footer_tpl);
    }   //end function


    
    //  }}}

    /**
     * 当前用户是否对当前页面有访问权限
     *
     * @access  public
     * @global  array   $_SESSION   PHP SESSION 的变量数组
     * @param   string  $type       权限类别，super对应系统管理员，adjust对应调度员，answer对应解答专家
     * @return  boolean
     */
    function zxzx_check_authority($type)
    {
		if (empty($_SESSION[$this->sess_user_id])) {//用户未登录，调转到登录页面！
			$errMsg = "请您先登陆！";
		} else {
		 switch ($type) {
				case 'super':
					{
						if ($_SESSION[$this->sess_user_pri] != PRI_SUPER_ADMIN)
							$errMsg = "您的权限不够，请您以系统管理员的身份登陆!";
					}  
					break;

				case 'adjust':
					{
						if ($_SESSION[$this->sess_user_pri] != PRI_ADJUST_USER)
							$errMsg = "您的权限不够，请您以调度员的身份登陆!";
					}
					break;
				case 'answer':
					{
						if ($_SESSION[$this->sess_user_pri] != PRI_ANSWER_USER) 
							$errMsg = "您的权限不够，请您以解答专家的身份登陆!";
					}
					break;
				default:
					 break;
				}   //end switch
		}  // end if
	 if (!empty($errMsg))
		{
					echo '
						<script language="JavaScript">
							function login() 
							{
								alert("'.$errMsg.'");
								top.document.location = "' . $this->web_url.'/admin.php";
							}            
							window.onload = login;
						</script>';
					exit; 
	    }
    }   //end function 


	 /**
     * 当前用户是否为建网通登录用户
     *
     * @access  public
     * @global  array   $_SESSION   PHP SESSION 的变量数组
     * @param   string  $target     目标包含文件
     * @return  boolean
     */
    function check_cms_user()
    {
		
		if (empty($_SESSION[$this->sess_user_id]) || $_SESSION[$this->sess_user_pri] != PRI_PLATFORM_USER) {//用户未登录，调转到登录页面！
		 $from = urlencode('http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
			$errMsg = "本功能只对网站用户开放，请您先注册并登录！";
		}  
		if (!empty($errMsg))
		{
					echo '
						<script language="JavaScript">
							function login() 
							{
								alert("'.$errMsg.'");
								top.document.location = "' . $this->app_url.'/login.php?from='.$from.'";
							}            
							window.onload = login;
						</script>';
					exit; 
	    }
    }   //end function 

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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

	function RemoveXSS($val) {
	   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
	   // this prevents some character re-spacing such as <java\0script>
	   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
	   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
	   // straight replacements, the user should never need these since they're normal characters
	   // this prevents like <IMG SRC=@avascript:alert('XSS')>
	   $search = 'abcdefghijklmnopqrstuvwxyz';
	   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	   $search .= '1234567890!@#$%^&*()';
	   $search .= '~`";:?+/={}[]-_|\'\\';
	   for ($i = 0; $i < strlen($search); $i++) {
		  // ;? matches the ;, which is optional
		  // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
	
		  // @ @ search for the hex values
		  $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
		  // @ @ 0{0,7} matches '0' zero to seven times
		  $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
	   } 
	   // 在此处将三零公司建议的特殊字符替换掉
	   $val = str_replace(str_split('()<>\'";`'), '', $val);
	   // now the only remaining whitespace attacks are \t, \n, and \r
	   $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
	   $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
	   $ra = array_merge($ra1, $ra2);
	
	   $found = true; // keep replacing as long as the previous round replaced something
	   while ($found == true) {
		  $val_before = $val;
		  for ($i = 0; $i < sizeof($ra); $i++) {
			 $pattern = '/';
			 for ($j = 0; $j < strlen($ra[$i]); $j++) {
				if ($j > 0) {
				   $pattern .= '(';
				   $pattern .= '(&#[xX]0{0,8}([9ab]);)';
				   $pattern .= '|';
				   $pattern .= '|(&#0{0,8}([9|10|13]);)';
				   $pattern .= ')*';
				}
				$pattern .= $ra[$i][$j];
			 }
			 $pattern .= '/i';
			 $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
			 $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
			 if ($val_before == $val) {
				// no replacements were made, so exit the loop
				$found = false;
			 }
		  }
	   }
	   return $val;
	}
    
}   //end class

/**
 * 获取用于防止 CSRF 攻击的表单令牌。
 *
 * @param string $form_id 表单 ID。
 * @param integer $expires 令牌过期时间，单位为秒。
 * @return string 返回令牌字符串。
 */
function zxzx_form_token($form_id, $expires = 300)
{
    if (empty($_SESSION['zxzx_token'][$form_id])) {
        $token = md5($form_id.uniqid(rand(), true));
        $_SESSION['zxzx_token'][$form_id] = $token;
    } else {
        $token = $_SESSION['zxzx_token'][$form_id];
    }
    $_SESSION['zxzx_token_time'][$form_id] = time() + $expires;
    return $token;
}

/**
 * 校验防 CSRF 攻击的表单令牌是否正确，不正确则输出错误信息，结束程序。
 *
 * @param string $form_id 表单 ID。
 * @return void
 */
function zxzx_check_token($form_id)
{
    $output = '<html>
<head>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <title>表单令牌校验失败</title>
</head>

<body>
<div style="width:600px; margin:30px auto; font-size:14px; font-family:Tahoma; color:black; background-color:#F9F2A7; border:2px solid red; padding:8px;">
    <font color="red">表单令牌校验失败：%s。</font><br/>
    出于安全考虑，网站中的表单提交均需要校验令牌，以防止跨站脚本攻击，若有疑问请联系管理员。
    <a href="'.($_SERVER['HTTP_REFERER'] ? 'javascript:history.go(-1);void(0)' : '/jyrc/').'"><font color="blue">请点击此处返回重试</font></a>。
</div>
</body>
</html>';
    if (empty($_SESSION['zxzx_token'][$form_id]) || empty($_SESSION['zxzx_token_time'][$form_id])) die(sprintf($output, '未设置令牌及令牌过期时间'));
    $token = @$_REQUEST['token'];
    if ($_SESSION['zxzx_token'][$form_id] != $token) die(sprintf($output, '令牌错误'));
    if (time() > $_SESSION['zxzx_token_time'][$form_id]) die(sprintf($output, '令牌超时'));
    //unset($_SESSION['zxzx_token'][$form_id], $_SESSION['zxzx_token_time'][$form_id]);
}

