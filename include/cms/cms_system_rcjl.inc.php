<?php
//  $Id: cms_system.inc.php,v 1.20.6.1 2007/12/31 10:13:16 nio Exp $
//  建网通系统公告包含文件。
//  建网通的所以文件都必须包含此文件，此文件相当于 4.0 版本中的 system.inc.php。
set_magic_quotes_runtime(0);    //Turn off magic_quotes!
//@session_start();
//  包含建网通的基础类文件，其它类对象均可通过此类获取。
require_once 'cms/cms.class.php';

//  获取当前 PHP 开始运行的时间。
$cfg_start_time = CMS_Common :: get_micro_time();

//  如果设置了参数 $_GET['CVS']，则此参数所指定的建网通 CLASS 的 CVS 版本信息。
if ('CMS' == $_GET['CVS']) {
    $cms =& new CMS;
    $rs['CMS'] = $cms->_id;
    $cms_common =& new CMS_Common;
    $rs['CMS_Common'] = $cms_common->_id;
    $object =& $cms->get_cms_info();
    $rs['CMS_Info'] = $object->_id;
    $object =& $cms->get_cms_chat();
    $conn =& $cms->get_adodb_conn('mysql');
    $rs['CMS_Chat'] = $object->_id;
    $object =& $cms->get_cms_forum();
    $rs['CMS_Forum'] = $object->_id;
    $object =& $cms->get_cms_home();
    $rs['CMS_Home'] = $object->_id;  
    $object =& $cms->get_cms_kicq();
    $rs['CMS_Kicq'] = $object->_id;      
?>
<html>
<head>
    <title>cmsinfo</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
    <!--
        a { text-decoration: none; }
        a:hover { text-decoration: underline; }
        h1 { font-family: arial, helvetica, sans-serif; font-size: 18pt; font-weight: bold;}
        h2 { font-family: arial, helvetica, sans-serif; font-size: 14pt; font-weight: bold;}
        body, td { font-family: arial, helvetica, sans-serif; font-size: 10pt; }
        th { font-family: arial, helvetica, sans-serif; font-size: 11pt; font-weight: bold; }
    //-->
    </style>    
</head>

<body>
    <center>
    <h2 align="center"><a name="module_wddx">CMS 类文件 CVS 信息</a></h2>
    <table border="0" cellpadding="3" cellspacing="1" width="600" bgcolor="#000000" align="center">
    <tbody><tr valign="middle" bgcolor="#9999cc"><th>类名</th><th>CVS $Id: cms_system.inc.php,v 1.20.6.1 2007/12/31 10:13:16 nio Exp $</th></tr>
    <?php 
        foreach ($rs as $key => $value) {
    ?>
    <tr valign="baseline" bgcolor="#cccccc"><td bgcolor="#ccccff"><b><?php echo $key; ?></b></td><td align="left"><?php echo $value; ?></td></tr>
    <?php
        }   //end foreach
    ?>
    <tr valign="baseline" bgcolor="#9999cc" align="right"><td colspan="2">
    Copyright &copy; 2003, 2004, 2005 K12 Studio. All rights reserved.<br />
    版权所有&nbsp; K12 Studio - <a href="http://www.k12.com.cn/">K12中国中小学教育教学网</a></tr>    
    </tbody></table>
    </center>
</body>
</html>    
<?php
    exit;
}   //end if

$cms =& new CMS();
//  过滤sql注入、XSS
require_once dirname(__FILE__) . '/reject_debug.php';

//  建网通系统的正式开始。
if ($_GET['sessid']) {
    $sessid = $_GET['sessid'];
    session_id($_GET['sessid']);
}   //end if
@session_start();
$do_logout = FALSE;
 
 
//  在建网通的其它程序中不需要再创建类 Cms 的对象实例，均使用 $cms 即可，$smarty 也是一样！
$cms->db_type = 'mysql';
if (!empty($sessid)) {
    //  XMLRPC
    require_once('xmlrpc/xmlrpc.inc');
    $xmlrpc_defencoding = '';

    //  调用基础平台的 XMLRPC user.isUserLogin() 方法
    $params = array(new xmlrpcval($sessid));
    $r =& $cms->call_xmlrpc_method('user.isUserLogin', $params);
    $v = $r->value();
    $user_id = $v->scalarval();
    if ($user_id) {
        $params = array(new xmlrpcval("user_id='" . $user_id . "'"),
                        new xmlrpcval(''),
                        new xmlrpcval(1, 'int'),
                        new xmlrpcval(0, 'int'));
        $r =& $cms->call_xmlrpc_method('user.getUserInfo', $params);
        $v = $r->value();
        $max = $v->arraysize();
        if ($max > 0) {
            $rec = $v->arraymem(0);
            $v = $rec->structmem('user_name');
            $user_true_name = $v->scalarval();
            $v = $rec->structmem('nickname');
            $user_nick_name = $v->scalarval();
            $v = $rec->structmem('user_type');
            $user_type = $v->scalarval();                
            $_SESSION[$cms->sess_true_name] = $user_true_name;
            $_SESSION[$cms->sess_nick_name] = $user_nick_name;
            $_SESSION[$cms->sess_user_type] = $user_type;
        }   //end if
        $_SESSION[$cms->sess_user_id] = $user_id;
        $cms->do_after_login($user_id, $_SESSION);
 
		//处理和招聘有关的session
        
        $_SESSION['rcjl_user_id']=$_SESSION[$cms->sess_user_id];
	$_SESSION['rcjl_from_cms']=1;
	$_SESSION['rcjl_user_name']= $_SESSION[$cms->sess_nick_name];
	
	
	if($_SESSION['PF_USER_ADMIN']!=2){
		$_SESSION['rcjl_user_admin']=0;
	}else{
	      $_SESSION['rcjl_user_admin']=1;//超级管理员
        }
		$cms->db_type = 'mysql';
        $conn = $cms->get_adodb_conn();
       // $conn->debug = TRUE;

        $o_rcjl=$cms->get_cms_rcjl();
        $sid=$o_rcjl->get_user_uuid($_SESSION['rcjl_from_cms'],$_SESSION['rcjl_user_id']);
        $_SESSION['u_sid']=$sid;
        
        
        $the_type_auth=$o_rcjl->get_cmsuser_type_auth($conn,$_SESSION['rcjl_user_id']);
	$_SESSION['rcjl_user_auth']=$the_type_auth[1];
	if($the_type_auth[0]>0){
            $_SESSION['rcjl_user_rol']=$the_type_auth[0];
	}else{
	  if($the_type_auth[0]==0){//第一次进入人事交流,请用户指定其为个人用户还是学校用户
	  	
	  	header("Location: ".$cms->app_url."/rcjl/set_cmsuser_type.php"); 
	  	die("");
	  }else{
	        //没找到该用户名
	        unset($_SESSION['rcjl_user_id']);
	        unset($_SESSION['rcjl_user_name']);
	        unset($_SESSION['rcjl_user_auth']);
	        unset($_SESSION['rcjl_user_rol']);
	         unset($_SESSION['u_sid']);
	       unset($_SESSION['rcjl_from_cms']);
             unset( $_SESSION['rcjl_user_admin']);
             unset($_SESSION['rcjl_cms_has_set']);
             $do_logout = TRUE;
        unset($_SESSION[$cms->sess_user_id]);
        unset($_SESSION[$cms->sess_true_name]);
        unset($_SESSION[$cms->sess_nick_name]);
        unset($_SESSION[$cms->sess_user_type]);
        unset($_SESSION[$cms->sess_user_face]);
        unset($_SESSION[$cms->sess_user_sid]);
        unset($_SESSION[$cms->sess_user_perm]);
        unset($_SESSION[$cms->sess_platform_url]);
         unset($_SESSION['PF_USER_ADMIN']);
        session_destroy();
	  }
        }
        ////////////////////////////
    } else {    //用户未登录
        $do_logout = TRUE;   
		unset($_SESSION[$cms->sess_user_id]);
        unset($_SESSION[$cms->sess_true_name]);
        unset($_SESSION[$cms->sess_nick_name]);
        unset($_SESSION[$cms->sess_user_type]);
        unset($_SESSION[$cms->sess_user_face]);
        unset($_SESSION[$cms->sess_user_sid]);
        unset($_SESSION[$cms->sess_user_perm]);
        unset($_SESSION[$cms->sess_platform_url]);
         unset($_SESSION['PF_USER_ADMIN']);
         
           unset($_SESSION['rcjl_user_id']);
	        unset($_SESSION['rcjl_user_name']);
	        unset($_SESSION['rcjl_user_auth']);
	        unset($_SESSION['rcjl_user_rol']);
	         unset($_SESSION['u_sid']);
	       unset($_SESSION['rcjl_from_cms']);
             unset( $_SESSION['rcjl_user_admin']);
             unset($_SESSION['rcjl_cms_has_set']);
        session_destroy();                   
    }   //end if
} else if($_SESSION[$cms->sess_user_id]) {
    if (1 == $_GET['logout']) {  
        $do_logout = TRUE;
    } else {
        $conn =& $cms->get_adodb_conn('mysql');
        $sql = "SELECT COUNT(*) FROM user_sess WHERE sess_id=".$conn->qstr(session_id());
        $rs = $conn->Execute($sql);
        if ($rs->fields[0] <= 0)
            $do_logout = TRUE;
    }   //end if
}  //end if
//  需要执行注销操作

if (1 == $_GET['logout'] && $_GET['sessid']=="") {
    	
        $do_logout = TRUE;
       
    }


if (TRUE === $do_logout) {

	$conn =& $cms->get_adodb_conn('mysql');
    $sql = "DELETE FROM user_sess WHERE sess_id=".$conn->qstr(session_id());
    $rs = $conn->Execute($sql);     
    unset($_SESSION[$cms->sess_user_id]);
    unset($_SESSION[$cms->sess_true_name]);
    unset($_SESSION[$cms->sess_nick_name]);
    unset($_SESSION[$cms->sess_user_type]);
    unset($_SESSION[$cms->sess_user_face]);
    unset($_SESSION[$cms->sess_user_sid]);
    unset($_SESSION[$cms->sess_user_perm]);
    unset($_SESSION[$cms->sess_platform_url]);
	unset($_SESSION['PF_USER_ADMIN']);
     
     unset( $_SESSION['rcjl_local_user_id']);
        unset($_SESSION['rcjl_local_user_name']);
        unset( $_SESSION['rcjl_local_user_rol']);
        unset($_SESSION['rcjl_local_user_auth']);
        unset($_SESSION['rcjl_local_user_sid']);
    
	unset($_SESSION['rcjl_user_id']);
	unset($_SESSION['rcjl_user_name']);
	unset($_SESSION['rcjl_user_auth']);
	unset($_SESSION['rcjl_user_rol']);
	unset($_SESSION['u_sid']);
	unset($_SESSION['rcjl_from_cms']);
	unset($_SESSION['rcjl_user_admin']);
    session_destroy();
}   //end if
//  获取基础平台的 URL
/*
if (empty($_SESSION[$cms->sess_platform_url])) {
    //  XMLRPC
    require_once('xmlrpc/xmlrpc.inc');
    $xmlrpc_defencoding = '';
    //  调用基础平台的 XMLRPC base.getPlatformUrl() 方法
    $params = array(new xmlrpcval($_SERVER['REMOTE_ADDR']));
    $r =& $cms->call_xmlrpc_method('base.getPlatformUrl', $params);    
    $v = $r->value();
    $_SESSION[$cms->sess_platform_url] = $v->scalarval();
}   //end if
*/
if (!empty($sessid) || TRUE === $do_logout) {
    $replace_url = 'http://'.$_SERVER['SERVER_NAME'].preg_replace('/(\?|&)(sessid=[^&]*|logout=[^&]*)/', '', $_SERVER["REQUEST_URI"]);
    ?>
    <script language="JavaScript">
        top.document.location.replace("<?php echo $replace_url; ?>");
    </script>
    <?php
    exit;
}   //end if
//$cms->set_platform_url();
$smarty = &$cms->get_smarty();
$smarty->assign_by_ref("m_sess",$_SESSION);
$smarty->register_object('cms', $cms);
?>