<?php
$dwgk_key = 'x!h@d#w$g%k^k*e&y';

//党务公开栏目ID
$dwgk_cat_ids = array(
    112 => '教育党工委文件',
    3   => '重大事项',
    124 => '廉政建设动态',
    125 => '廉政建设资料',
    163   => '工作指导',
    164   => '动态展示',
);

//党务公开获取方法
$dwgk_server = (preg_match('#\.dev$#i', $_SERVER['SERVER_NAME'])) ? 'http://dwgk.xh.dev/dwgk/info/fromCMSAdd' : 'http://dwgk.xhedu.sh.cn/dwgk/info/fromCMSAdd';

//发布机构ID
$dwgk_fwjg_id = 2;

//发布者ID
$dwgk_user_id = 2;

//cms域名
$cms_host = (preg_match('#\.dev$#i', $_SERVER['SERVER_NAME'])) ? 'http://www.xh2011.dev' : 'http://www.xhedu.sh.cn';
?>