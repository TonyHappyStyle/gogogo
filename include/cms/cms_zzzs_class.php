<?php
/********************************
 自主招生（自主学习）栏目访问权限文件
********************************/

// 自主招生栏目包括子栏目的id号数组
$column_ids = array('6949','6950','6951','6952','6969','6970','6978','6979','6980','6981','6982','6983','6984','6985','6986','6987','6988','6989','7029','7030','7031','7032','7033','7034','7035','7036');
    
// 可以访问自主招生（自主学习）栏目的用户名数组
$user_ids = array ('jyxy_chengy','jyxy_xudl','jyxy_mengs','jyxy_sangy','jyxy_zhanggh','jyxy_zhonggl','jyxy_yangjy','jyxy_xiaokw','jyxy_fangyong','jyxy_shanww','jyxy_shanww');

// 可以访问自主招生（自主学习）栏目的公共用户名、密码数组，此数组中的帐号登录不走基础平台，属于 CMS 内部用户
$zzxx_user_ids = array(
    'zzzs' => 'zzzs@xhedu', //用户名 => 密码
    'zzxx' => 'zzxx@xh',
);

function zzzs_access($cat_id) {
    global $cms, $smarty, $column_ids, $user_ids, $zzxx_user_ids;
    
    if (in_array($cat_id,$column_ids)) {
        $cms->need_login();
        if (in_array($_SESSION[$cms->sess_user_id], $user_ids)
            || (!empty($_SESSION['CMS_ZZXX_USER_ID']) && isset($zzxx_user_ids[$_SESSION['CMS_ZZXX_USER_ID']]))
           ) {
            // 有权限访问
        } else {
            // 无权限访问
            $cms->echo_header(TRUE, $cms->cats[$cat_id]['NAME']); ?>
            <div align="center" style="padding:40px 6px; font-weight:bold;">
            您没有权限访问本栏目。<br/>本栏目只有固定的用户可访问!
            </div>
            <? 
            $cms->echo_footer();
            exit;
        }
    }
}
?>