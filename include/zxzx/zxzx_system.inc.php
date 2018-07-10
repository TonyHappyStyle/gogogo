<?php
//  $Id:$

//===========================================================
//  TODO
//===========================================================
if ($_GET['sessid']) {
    $sessid = $_GET['sessid'];
    session_id($_GET['sessid']);
}   //end if

@session_start();
require_once('zxzx_config.inc.php');
require_once(ZXZX_DIR . 'zxzx.class.php');
$zxzx = new ZXZX;

$do_logout = FALSE;
if (isset($_GET['sessid']) && $sessid = $_GET['sessid']) {
    //  XMLRPC
    require_once('xmlrpc/xmlrpc.inc');
    $xmlrpc_defencoding = '';

    //  调用基础平台的 XMLRPC user.isUserLogin() 方法
    $params = array(new xmlrpcval($sessid));
    $r =& $zxzx->call_xmlrpc_method('user.isUserLogin', $params);
    $v = $r->value();
    $user_id = $v->scalarval();	
    if ($user_id) {
        $params = array(new xmlrpcval("user_id='" . $user_id . "'"),
                        new xmlrpcval(''),
                        new xmlrpcval(1, 'int'),
                        new xmlrpcval(0, 'int'));
        $r =& $zxzx->call_xmlrpc_method('user.getUserInfo', $params);
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
            $_SESSION[$zxzx->sess_user_name] = $user_nick_name;
            $_SESSION[$zxzx->sess_user_flag] = '';
            $_SESSION[$zxzx->sess_user_pri] = PRI_PLATFORM_USER;
        }   //end if
        $_SESSION[$zxzx->sess_user_id] = $user_id;
    } else {    //用户未登录
        $do_logout = TRUE;
        unset($_SESSION[$zxzx->sess_user_id]);
        unset($_SESSION[$zxzx->sess_user_name]);
        unset($_SESSION[$zxzx->sess_user_flag]);
        unset($_SESSION[$zxzx->sess_user_pri]);
        session_destroy();                    
    }   //end if
} else if($_SESSION[$zxzx->sess_user_id]) {
    if (1 == $_GET['logout']) {  
        $do_logout = TRUE;
    } 
}  //end if
//  需要执行注销操作
if (TRUE === $do_logout) {
    unset($_SESSION[$zxzx->sess_user_id]);
	unset($_SESSION[$zxzx->sess_user_name]);
	unset($_SESSION[$zxzx->sess_user_flag]);
	unset($_SESSION[$zxzx->sess_user_pri]);
	session_destroy();                    
}   //end if

$smarty =& $zxzx->get_smarty();
$pri_super_admin = PRI_SUPER_ADMIN;
$pri_adjust_user = PRI_ADJUST_USER;
$pri_answer_user = PRI_ANSWER_USER;
$pri_platform_user = PRI_PLATFORM_USER;


$smarty->assign_by_ref('s_web_url', $zxzx->web_url);
$smarty->assign_by_ref('s_app_url', $zxzx->app_url);
$smarty->assign_by_ref('s_tpl_url', $zxzx->tpl_url);
$smarty->assign_by_ref('s_image_url', $zxzx->image_url);
$smarty->assign_by_ref('s_data_url', $zxzx->data_url);
$s_php_url = urlencode($_SERVER['REQUEST_URI']);
$smarty->assign_by_ref('s_php_url', $s_php_url);
$smarty->assign_by_ref('s_site_name', $zxzx->arguments['site_name']);
$smarty->assign_by_ref('s_pri_super_admin', $pri_super_admin);
$smarty->assign_by_ref('s_pri_adjust_user', $pri_adjust_user);
$smarty->assign_by_ref('s_pri_answer_user', $pri_answer_user);
$smarty->assign_by_ref('s_pri_platform_user', $pri_platform_user);

$smarty->assign_by_ref('s_user_pri', $_SESSION[$zxzx->sess_user_pri]);
$smarty->assign_by_ref('s_user_id', $_SESSION[$zxzx->sess_user_id]);
$smarty->assign_by_ref('s_user_name', $_SESSION[$zxzx->sess_user_name]);
$smarty->assign_by_ref('s_user_flag', $_SESSION[$zxzx->sess_user_flag]);
$s_logout_do_url = sprintf($zxzx->logout_do_url,urlencode('http://'.$_SERVER['HTTP_HOST'].$zxzx->web_url.'/?logout=1'));
$smarty->assign_by_ref('s_logout_do_url', $s_logout_do_url); 
$smarty->assign('s_change_pass_url','http://'.$_SERVER['HTTP_HOST'].$zxzx->web_url.'/admin_chang_pass.php'); 
$smarty->register_object('zxzx', $zxzx);
?>