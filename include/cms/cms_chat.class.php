<?php
//  $Id: cms_chat.class.php,v 1.11 2003/09/04 07:55:31 lcs Exp $
 /**
 * 聊天室系统事务处理类
 * 聊天室相关操作
 *
 * @package K12CMS
 * @access public
 */
class CMS_Chat
{
	 /**
     * CMS_Home 类文件的 Id，用于 CVS 版本追踪。
     * @var string
     * @access  private
     */
    var $_id = '$Id: cms_chat.class.php,v 1.11 2003/09/04 07:55:31 lcs Exp $';
    /**
     * CMS_Chat 类中所用到的 CMS 对象实例。
     * @var object CMS
     * @access  private
     */ 
    var $_cms = null;

	/**
     * 聊天室模板文件存放目录的 URL。
     * @var  string
     * @access  public
     */ 
    var $tpl_url = '';

	/**
     * 聊天室模板文件存放目录物理路径。
     * @var  string
     * @access  public
     */ 
    var $tpl_path = '';

	/**
     * 系统提供的图片存放目录的 URL。
     * @var  string
     * @access  public
     */ 
    var $graphic_url = '';

    /**
     * 系统提供的图片存放目录物理路径。
     * @var  string
     * @access  public
     */ 
    var $graphic_path = '';

	/**
     * 系统帮助文件所在路径。
     * @var  string
     * @access  public
     */ 
	var $help_url = '';

	/**
     * 系统使用到的数据表名称列表。
     * @var  string
     * @access  public
     */ 
    var $table_list = '';
	/**
     * 用户超时时间限制。
     * @var  string
     * @access  public
     */ 
	var $mDateLimit = 100;

    /**
     * CMS_Info 的类构造函数。
     * 在此构造函数中将对建网通信息发布所用到的目录路径进行初始化。
     *
     * @access  public
     * @param   object CMS  &$cms   建网通所用的 CMS 对象实例的引用。
     * @return  void
     */ 

function CMS_Chat(&$cms)
{
        
        $this->_cms =& $cms;
        $this->tpl_url = $this->_cms->tpl_url.'/'.$this->_cms->modules['CHAT']['DIR'];
        $this->tpl_path = $_SERVER['DOCUMENT_ROOT'].$this->tpl_url;
        $this->graphic_url = 'http://'.$_SERVER['SERVER_NAME'].$this->tpl_url.'/graphics';
        $this->graphic_path = $this->tpl_path.'/graphics';
        $this->table_list =array('user' => 'user_main',	'netbp' => 'chat_main','curuser' => 'chat_cur');
        $this->help_url = '';		
}   //end function

/**
     * 取得聊天室列表
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  &$chat_lever   聊天室级别。
     * @return  object ADORecordSet
*/
function list_chat(&$conn,$chat_lever)
{

        $now_num = $this->check_ol_num_now(&$conn);
        $sql = "select id,chat_name,ol_num from chat_room where lever='$chat_lever'";
        $pRecordSet = $conn->Execute($sql);
        $rows_num = 0;
        $pRsArrs = array();
        while (!$pRecordSet->EOF)
        {
        $roomid = $pRecordSet->fields[0];
		$pRsArrs[$rows_num]['room_id'] = $pRecordSet->fields[0];
        $pRsArrs[$rows_num]['room_value'] = "chat.php?mRoomID=".$pRecordSet->fields[0];
        $pRsArrs[$rows_num]['chat_name'] = $pRecordSet->fields[1];
        $pRsArrs[$rows_num]['ol_num'] = $now_num[$roomid];
        if($now_num[$roomid]=='')
                {
        $pRsArrs[$rows_num]['ol_num'] = '0';
                }
        $rows_num ++;
        $pRecordSet->MoveNext();
        }
        return $pRsArrs;
}
/**
     * 取得当前在线用户数量。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @return  array
*/
function check_ol_num_now(&$conn)
{

	$this->recheck_olnum(&$conn);
    $sql = "select id,roomid from chat_user where ol='yes'";
    $rs = $conn->Execute($sql);
    $rows_num = 0;
	$pRsArrs = array();
	while (!$rs->EOF)
    {

        $id = $rs->fields[0];
        $roomid = $rs->fields[1];
        if(!$now_num[$roomid]){$now_num[$roomid] = 0;}
        $now_num[$roomid] = $now_num[$roomid]+1;
        $rs->MoveNext();
        $rows_num ++;
    }

return $now_num;
}
/**
     * 对当前在线人数进行清理
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @return  void
*/
function recheck_olnum(&$conn)
{
	    $the_time = time();
        $the_limit_time = $the_time-$this->mDateLimit;
        $sql = "update chat_user set ol='no' where l_time<'$the_limit_time' and ol='yes'";
		$conn->Execute($sql);
}
/**
     * 初始化聊天室
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $mRoomID   聊天室ID号
     * @return  void
*/
function start_chat(&$conn,$mRoomID)
{
	    $sql = "select id,chat_name,max_num,chat_item,lever,master,fid,color_top,color_show,color_list,color_say,welcome from chat_room where id='$mRoomID'";
		//echo$sql;
        $pRecordSet = $conn->Execute($sql);
        return $pRecordSet;
}
/**
     * 检查聊天室用户数量
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $mRoomID   聊天室ID号
     * @return  int
*/
function check_maxnum(&$conn,$mRoomID)
{
        $sql = "select count(*) from chat_user where ol='yes' and roomid='$mRoomID'";
        $pRecordSet = $conn->Execute($sql);
        $maxnum = $pRecordSet->fields[0];
        return $maxnum;
}
/**
     * 检查ip地址是否被禁止
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $mRoomID   聊天室ID号
	 * @param   string $sUserId   用户系统帐号
     * @return  boolean
*/
function check_ip(&$conn,$mRoomID,$sUserId)
{
	$pIp = getenv("REMOTE_ADDR");
	$sql = "select count(*) from chat_ip where the_ip='$pIp' and roomid='$mRoomID' or out='$sUserId' and roomid='$mRoomID' or the_ip='$pIp' and roomid='all' or out='$sUserId' and roomid='all'";
	$pRecordSet = $conn->Execute($sql);
	$num = $pRecordSet->fields[0];
	if($num=='0')
	{return true;}
	else
	{return false;}
}
/**
     * 检测用户改名和头像变动情况
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $sUserId   用户系统帐号
     * @return  object ADORecordSet
*/
function say_name(&$conn,$sUserId)
{
	$pSQL = "select usernick,head from chat_user where id='$sUserId'";
	$pRecordSet = $conn->Execute($pSQL);

	return $pRecordSet;
}
/**
     * 显示聊天信息
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $sUserId   用户系统帐号
	 * @param   string $mRoomID   聊天室ID号
	 * @param   string $mStartTime   用户登陆聊天室的时间
     * @return  object ADORecordSet
*/
function show_msg(&$conn,$sUserId,$mRoomID,$mStartTime)
{
//-----检查用户屏蔽信息
	$pSQL_a="select filtrate from chat_user where id='$sUserId'";
	$pRecordSet = $conn->Execute($pSQL_a);
	$pStr_all = $pRecordSet->fields[0];
//-----SQL分析
	$pSql_temp = " where especially='$mRoomID' and time>='$mStartTime' and quiet!='1' or especially='$mRoomID' and time>='$mStartTime' and quiet='1' and userid='$sUserId' or especially='$mRoomID' and time>='$mStartTime' and quiet='1' and aimid='$sUserId' order by id asc";

//-----SQL分析
	$pSQL="select msg,userid from chat_msg".$pSql_temp;
	//echo$pSQL;
	$pRecordSet = $conn->Execute($pSQL);
	$pOutput[] = ''; 
	$rows_num = 0;
	$pRsArrs = array();
if(!$pRecordSet)
	{
	$pSQL_a = "select welcome from chat_room where id='$mRoomID'";
	$pRecordSet = $conn->Execute($pSQL_a);
	$pRsArrs[] = $pRecordSet->fields[0];
	$pRsArrs[0] = $pRecordSet->fields[0];
	}
else
	{
while (!$pRecordSet->EOF)
	{
//------------过滤处理
	if($pStr_all!='')
		{
	$pIn_temp = $pRecordSet->fields[1];
	$pIn_temp = "|".$pIn_temp."|";
	$pStr_yn =str_in($pIn_temp,$pStr_all);
		if($pStr_yn)
			{
		$pRsArrs[$rows_num] = $pRecordSet->fields[0];
			}
		}
		else
		{
	$pRsArrs[$rows_num] = $pRecordSet->fields[0];
		}//end else
	$rows_num ++;
	$pRecordSet->MoveNext();
	}//end while
	}//end else
	return $pRsArrs;
}
/**
     * 删除过期用户
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $sUserId   用户系统帐号

     * @return  string
*/
function delete_old(&$conn,$sUserId)
{
	$pSQL = "select l_time from chat_user where id='$sUserId'";
	$pRecordSet = $conn->Execute($pSQL);
	$pL_time = $pRecordSet->fields[0];
	return $pL_time;
}

/**
     * 检查本人是否超时
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $sUserId   用户系统帐号

     * @return  string
*/
function check_ol_self(&$conn,$sUserId)
{
	$sql = "select ol from chat_user where id='$sUserId'";
	$pRecordSet = $conn->Execute($sql);
	$user_ol = $pRecordSet->fields[0];
	return $user_ol;
}
/**
     * 将过期用户设置成掉线
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $sSessUserId   用户系统帐号(同$sUserID)

     * @return  string
*/
function user_down(&$conn,$sSessUserId)
{
	$pSQL = "update chat_user set ol='no' where id='$sSessUserId'";
	$pRecordSet = $conn->Execute($pSQL);
	$pSQL = "select usernick from chat_user where id='$sSessUserId'";
	$pRecordSet = $conn->Execute($pSQL);
	$pUsernick = $pRecordSet->fields[0];
	return $pUsernick;
}

/**
     * 发布公告
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $msg   公告内容
	 * @param   string $mRoomID   聊天室ID号

     * @return  string
*/
function Tell_all(&$conn,$msg,$mRoomID)
{	
	$time = time();
	$date = date("h:i:s",$time);
	$sql = "insert into chat_msg (userid,usernick,msg,aimid,aim,face,quiet,in_private,time,date,especially,ip) values ('','','$msg','all','','','','','$time','$date','$mRoomID','')";
	$pRecordSet = $conn->Execute($sql);
}

/**
     * 显示在线用户
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $mRoomID   聊天室ID号
	 * @param   string $limit_last   当前时间－用户无相应警告时间
     * @return  string
*/
function list_ol(&$conn,$mRoomID,$limit_last)
{
	$pSQL = "select id,usernick,degree,head from chat_user where roomid='$mRoomID' and ol='yes'";
	//echo$pSQL;
	$pRecordSet = $conn->Execute($pSQL);
	$rows_num = 0;
	$pRsArrs = array();
	while (!$pRecordSet->EOF)
	{
	$pRsArrs[$rows_num]['result0'] = $pRecordSet->fields[0];
	$pRsArrs[$rows_num]['result1'] = $pRecordSet->fields[1];
	if($pRecordSet->fields[1]==''){$pRsArrs[$rows_num]['result1'] = $pRecordSet->fields[0];}
	$pRsArrs[$rows_num]['result2'] = $pRecordSet->fields[2];
	$pRsArrs[$rows_num]['result3'] = $pRecordSet->fields[3];
	$pRecordSet->MoveNext();
	$rows_num ++;
	}
	$pSQL = "update chat_user set ol='no' where l_time<'$limit_last' and roomid='$mRoomID'";
	$pRecordSet = $conn->Execute($pSQL);
	//print_r($pRsArrs);
	return $pRsArrs;
}

/**
     * 更新在线人数
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $mRoomID   聊天室ID号
     * @return  string
*/
function check_ol_pnum(&$conn,$mRoomID)
{
	$sql = "select count(*) from chat_user where roomid='$mRoomID' and ol='yes'";
	$pRecordSet = $conn->Execute($sql);
	$pOl_people_num = $pRecordSet->fields[0];
	$sql_ins = "update chat_room set ol_num='$pOl_people_num' where id='$mRoomID'";
	$pRecordSet = $conn->Execute($sql_ins);
	return $pOl_people_num;
}
/**
     * 发言前检测用户状态和身份
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pUserid    用户系统帐号(同$sUserID)
     * @return  string
*/
function speak_check(&$conn,$pUserid)
{
	$pSQL_a = "select degree from chat_user where id='$pUserid'";
	$pRecordSet = $conn->Execute($pSQL_a);
	$pDegree = $pRecordSet->fields[0];
	return $pDegree;
}

/**
     * 写入最后发言的时间
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pUserid    用户系统帐号(同$sUserID)
	 * @param   string $pTime    发言时间
     * @return  void
*/
function update_lastime(&$conn,$pUserid,$pTime)
{
	$pSQL_a = "update chat_user set l_time='$pTime' where id='$pUserid'";
	$pRecordSet = $conn->Execute($pSQL_a);
}

/**
     * 检测用户库中是否有该用户，如有调出该用户必要的信息
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $sUserId    用户系统帐号
     * @return  object ADORecordSet
*/
function start_user(&$conn,$sUserId)
{
	$sql = "select usernick,degree,l_time,head,id,ol from chat_user where id='$sUserId'";

	$pRecordSet = $conn->Execute($sql);
	//echo$conn->debug=true;
	return $pRecordSet;
}
/**
     * 根据最近一次登陆更新用户信息
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pLastTime	用户最后登陆时间
	 * @param   string $pIp	用户IP地址
	 * @param   string $sUserId	用户系统帐号
	 * @param   string $roomid		聊天室ID号
	 * @param   string $sSessNickName    用户昵称
	 * @param   string $pHead    用户头像
     * @return  void
*/
function update_login(&$conn,$pLastTime,$pIp,$sUserId,$roomid,$sSessNickName,$pHead)
{
	$sql = "update chat_user set l_time='$pLastTime',ol='yes',ip='$pIp',roomid='$roomid',usernick='$sSessNickName',head='$pHead' where id='$sUserId'";
	//echo$sql."<hr>";
	$pRecordSet = $conn->Execute($sql);
}
/**
     * 为第一次登陆的用户建立用户信息
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $sUserId	用户系统帐号
	 * @param   string $sSessNickName	用户昵称
	 * @param   string $pDegree	  “”
	 * @param   string $pLastTime		用户最后登陆时间
	 * @param   string $pHead    用户头像
	 * @param   string $mRoomID    聊天室ID号
	 * @param   string $pIp    用户IP地址
     * @return  void
*/
function ins_info_user(&$conn,$sUserId,$sSessNickName,$pDegree,$pLastTime,$pHead,$mRoomID,$pIp)
{
	$sql = "insert into chat_user (id,usernick,degree,l_time,head,filtrate,ol,roomid,ip) values ('$sUserId','$sSessNickName','$pDegree','$pLastTime','$pHead','','yes','$mRoomID','$pIp')";

	//echo$sql."<hr>";

	$pRecordSet = $conn->Execute($sql);
}
/**
     * 更新在线人数
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $mRoomID    聊天室ID号

     * @return  void
*/
function update_ol_num(&$conn,$mRoomID)
{
	$sql = "update chat_room set ol_num=ol_num+1 where id='$mRoomID'";
	$pRecordSet = $conn->Execute($sql);
}
/**
     * 为第一次登陆的用户建立用户信息
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pUserid	用户系统帐号（同sUserId）
	 * @param   string $pUsernick	用户昵称
	 * @param   string $mOutput	  消息内容
	 * @param   string $pAimid		对方ID号
	 * @param   string $pAim    对方名字
	 * @param   string $pFace    用户头像
	 * @param   string $pQuiet    是否悄悄话
	 * @param   string $pIn_private    屏蔽
	 * @param   string $pTime    发言时间
	 * @param   string $mRoomID    聊天室ID号
     * @return  object ADORecordSet
*/
function speak(&$conn,$pUserid,$pUsernick,$mOutput,$pAimid,$pAim,$pFace,$pQuiet,$pIn_private,$pTime,$mRoomID)
{
	$pDate = date("h:i:s",$pTime);
	$pIp = getenv("REMOTE_ADDR");
	$sql = "insert into chat_msg (userid,usernick,msg,aimid,aim,face,quiet,in_private,time,date,especially,ip) values ('$pUserid','$pUsernick','$mOutput','$pAimid','$pAim','$pFace','$pQuiet','$pIn_private','$pTime','$pDate','$mRoomID','$pIp')";
	$pRecordSet = $conn->Execute($sql);
	return $pRecordSet;
}

/**
     * 工具条聊天室列表
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。

     * @return  object ADORecordSet
*/
function list_tools_chat(&$conn)
{
	$pSQL="select id,chat_name from chat_room where lever='2' or lever='3'";
	$pRecordSet = $conn->Execute($pSQL);
	$rows_num = 0;
	$pRsArrs = array();
while (!$pRecordSet->EOF)
{
	$pRsArrs[$rows_num]['result.0'] = $pRecordSet->fields[0];
	$pRsArrs[$rows_num]['result.1'] = $pRecordSet->fields[1];
	$rows_num ++;
	$pRecordSet->MoveNext();
}
return $pRsArrs;
}

/**
     * 用户更改性命和头像
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pUserid	用户系统帐号（同sUserId）
	 * @param   string $pNewnick	用户新昵称
	 * @param   string $pHead    用户新头像
     * @return  object ADORecordSet
*/
function modify_name(&$conn,$pUserid,$pNewnick,$pHead)
{
	$pSQL = "update chat_user set usernick='$pNewnick',head='$pHead' where id='$pUserid'";
	$pRecordSet = $conn->Execute($pSQL);
}
/**
     * 当前聊天室列表
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。

     * @return  object ADORecordSet
*/
function the_list_chat(&$conn)
{
	global $sConn;
	$now_num = $this->check_ol_num_now(&$conn);
	$sql = "select id,chat_name,ol_num from chat_room where lever='2' or lever='3'";
	$pRecordSet = $conn->Execute($sql);
	$rows_num = 0;
	$pRsArrs = array();
	while (!$pRecordSet->EOF)
	{
	$roomid = $pRecordSet->fields[0];
	$pRsArrs[$rows_num]['result0'] = $pRecordSet->fields[0];
	$pRsArrs[$rows_num]['result1'] = $pRecordSet->fields[1];
	$pRsArrs[$rows_num]['result2'] = $now_num[$roomid];
	if($now_num[$roomid]=='')
		{
	$pRsArrs[$rows_num]['result2'] = '0';
		}
	$rows_num ++;
	$pRecordSet->MoveNext();
	}
	return $pRsArrs;
}

/**
     * 检测信息更新情况
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $mRoomID    聊天室ID号
	 * @param   string $pTime    当前时间
     * @return  string
*/
function check_msg(&$conn,$mRoomID,$pTime)
{
	$sql = "select max(id) from chat_msg where especially='$mRoomID' and time>='$pTime'";
	$pRecordSet = $conn->Execute($sql);
	$pID = $pRecordSet->fields[0];
	return $pID;
}

/**
     * 用户退出设置
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $sUserId    用户帐号
	 * @param   string $roomid    聊天室ID号
     * @return  void
*/
function user_exit(&$conn,$sUserId,$roomid)
{
	$sql = "update chat_user set ol='no' where id='$sUserId' and ol='yes'";
	$pRecordSet = $conn->Execute($sql);
}

/**
     * 显示用户自定义屏蔽情况
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $sUserId    用户帐号
	 * @param   string $roomid    聊天室ID号
     * @return  object ADORecordSet
*/
function list_filtrate(&$conn,$pUserid,$mRoomID)
{
	$pSQL_a = "select filtrate from chat_user where id='$pUserid'";

	$pRecordSet_a = $conn->Execute($pSQL_a);
	$pPeople = $pRecordSet_a->fields[0];
	$pSQL_b = "select id,usernick,degree,head from chat_user where roomid='$mRoomID' and ol='yes' and id!='$pUserid'";
	//echo$pSQL_b;
	$pRecordSet = $conn->Execute($pSQL_b);
	$rows_num = '0';
	$pRsArrs = array();
while (!$pRecordSet->EOF)
	{
	$pTd = ($pTd=='tr1') ? 'tr2' : 'tr1';
	$pRsArrs[$rows_num]['RECORDSET_TR_CLASS'] = $pTd;
	$pRsArrs[$rows_num]['result0'] = $pRecordSet->fields[0];
	//-------运算
	$pIn_yn = str_in("|".$pRsArrs[$rows_num]['result0']."|",$pPeople);
	$pRsArrs[$rows_num]['result3'] = '';
	$pRsArrs[$rows_num]['result5'] = '';
	if($pIn_yn)
		{
		$pRsArrs[$rows_num]['result3'] = '---';
		$pRsArrs[$rows_num]['result5'] = '屏蔽此人';
		$pRsArrs[$rows_num]['result6'] = '1';
		}
	else
		{
		$pRsArrs[$rows_num]['result3'] = '已屏蔽';
		$pRsArrs[$rows_num]['result5'] = '解除屏蔽';
		$pRsArrs[$rows_num]['result6'] = '2';
		}
	$pRsArrs[$rows_num]['result1'] = $pRecordSet->fields[1];
	$pRsArrs[$rows_num]['result2'] = $pRecordSet->fields[2];
	$pRsArrs[$rows_num]['result4'] = $pRecordSet->fields[3];
	$pRecordSet->MoveNext();
	$rows_num ++;
	}
	return $pRsArrs;
}

/**
     * 检测用户屏蔽情况，并进行相应的屏蔽操作
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $sUserId    用户帐号
	 * @param   string $mFiltrateID    要屏蔽的帐号
	 * @param   string $mFiltrateID    屏蔽方式
     * @return  object ADORecordSet
*/
function check_filtrate(&$conn,$pUserid,$mFiltrateID,$mAction)
{
	$pSQL_a = "select filtrate from chat_user where id='$pUserid'";
	$pRecordSet = $conn->Execute($pSQL_a);
	$pFiltrate = $pRecordSet->fields[0];
	if($mAction=='1')//屏蔽操作
	{
	$pFiltrate = $pFiltrate."|".$mFiltrateID."|";
	}
	if($mAction=='2')//解除屏蔽
	{
	$pFiltrate_temp = explode("|".$mFiltrateID."|", $pFiltrate);
	$pFiltrate = $pFiltrate_temp[0].$pFiltrate_temp[1];
	}
	$pSQL_b = "update chat_user set filtrate='$pFiltrate' where id='$pUserid'";
	//echo$pSQL_b;
	$pRecordSet = $conn->Execute($pSQL_b);
	return $pRecordSet;
}

/**
     * 取出配置文件的数据
     *
     * @access  public
	 * @param   string $mChatconfig    配置文件位置
     * @return  array
*/
function take_config_data($mChatconfig)
{
	$fop = fopen($mChatconfig,"r");
	$config_data = fread($fop, filesize($mChatconfig));
	fclose($fop);
	$config_array = explode("|**|",$config_data);
	return $config_array;
}

/**
     * 对配置文件进行修改
     *
     * @access  public
	 * @param   string $mChatconfig    配置文件位置
	 * @param   string $input    基本信息数组
	 * @param   array $color_type    颜色位置信息
	 * @param   array $color_in    颜色信息
     * @return  void
*/
function modify_admin_config($mChatconfig,$input,$color_type,$color_in)
{
	$fop = fopen($mChatconfig,"w+");
	$num = count($input);
	if($color_type!='0')
	{
		if($color_type=='color_top'){$input[3]=$color_in;}
		if($color_type=='color_show'){$input[4]=$color_in;}
		if($color_type=='color_list'){$input[5]=$color_in;}
		if($color_type=='color_say'){$input[6]=$color_in;}
	}
	$ins = '';
	for($i=0;$i<$num;$i++)
	{
	$c = $input[$i].'|**|';
	$ins.= $c;
	}
	$ins = substr($ins,0,-4);
	fputs($fop,$ins);
	fclose($fop);
}

/**
     * 获取聊天类型
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @return  string
*/
function choose_item(&$conn)
{
	$pSQL = "select chat_name from chat_room where lever='1'";
	$pRecordSet = $conn->Execute($pSQL);
	$rows_num = 0;
	$pRsArrs = array();
	while (!$pRecordSet->EOF)
	{
	$pRsArrs[$rows_num]['result0'] = $pRecordSet->fields[0];
	$rows_num ++;
	$pRecordSet->MoveNext();
	}
	return $pRsArrs;
}

/**
     * 添加聊天室参数
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pChat_name    聊天室名称
	 * @param   string $pChat_pnum    聊天室最大人数
	 * @param   string $pChat_item    聊天室类型
	 * @param   string $pWelcome    欢迎词
	 * @param   string $pLever   聊天室等级
	 * @param   string $sUserId   管理员帐号
	 * @param   string $sSessNickName    管理员昵称
	 * @param   string $mColor_tools_con    工具条颜色
	 * @param   string $mColor_show_con    留言区颜色
	 * @param   string $mColor_list_con    用户列表颜色
	 * @param   string $mColor_say_con    发言区颜色

     * @return  void
*/
function add_chat(&$conn,$pChat_name,$pChat_pnum,$pChat_item,$pWelcome,$pLever,$sUserId,$sSessNickName,$mColor_tools_con,$mColor_show_con,$mColor_list_con,$mColor_say_con)
{
	$pWelcome.='<br>';
	$pSQL = "insert into chat_room (chat_name,max_num,chat_item,welcome,lever,master,fid,color_top,color_show,color_list,color_say) values ('$pChat_name','$pChat_pnum','$pChat_item','$pWelcome','$pLever','$sUserId','$sSessNickName','$mColor_tools_con','$mColor_show_con','$mColor_list_con','$mColor_say_con')";
	$pRecordSet = $conn->Execute($pSQL);
	$pSQL_2 = "update chat_room set max_num=max_num+1 where chat_name='$chat_item' and lever='1'";
	$pRecordSet = $conn->Execute($pSQL_2);

}

/**
     * 检测重复聊天室
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pChat_name    聊天室名称

     * @return  void
*/
function check_re_chat(&$conn,$chat_name)
{
	$sql = "select count(*) from chat_room where chat_name='$chat_name'";
	$pRecordSet = $conn->Execute($sql);
	$pTotal_num = $pRecordSet->fields[0];
	$id = '';
	if($pTotal_num=='0' || $pTotal_num==''){$id = '1';}
	else{$id = '0';}
	return $id;
}

/**
     * 获取聊天室列表数据
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pLever    聊天室等级
	 * @param   string $pFid    “”
	 * @param   string $mDateLimit    警告时间
     * @return  object ADORecordSet
*/
function admin_chat(&$conn,$pLever,$pFid,$mDateLimit)
{
	$the_num_max = $this->admin_check_ol_user($conn,$mDateLimit);
	if($pLever=='1')
	{
	$pSQL = "select id,chat_name,max_num,chat_item,welcome,lever,master,fid from chat_room where lever='1'";
	}
	if($pLever=='2' || $pLever=='3')
	{
		$pSQL = "select id,chat_name,max_num,chat_item,welcome,lever,master,fid from chat_room where lever='2' or lever='3'";
	}
	if($pLever=='list')
	{
	$pSQL = "select id,chat_name,max_num,chat_item,welcome,lever,master,fid from chat_room where lever='2' or lever='3'";
	}
	$pRecordSet = $conn->Execute($pSQL);
		$rows_num = 0;
		$pRsArrs = array();
	while (!$pRecordSet->EOF)
	{
	$pTd = ($pTd=='tr1') ? 'tr2' : 'tr1';
	$pRsArrs[$rows_num]['RECORDSET_TR_CLASS'] = $pTd;
	$id = $pRecordSet->fields[0];
	$pRsArrs[$rows_num]['result0'] = $pRecordSet->fields[0];
	$pRsArrs[$rows_num]['result1'] = $pRecordSet->fields[1];
	$pRsArrs[$rows_num]['result2'] = $the_num_max[$id];
		if($the_num_max[$id]=='')
		{
	$pRsArrs[$rows_num]['result2'] = '0';
		}
	$pRsArrs[$rows_num]['result3'] = $pRecordSet->fields[3];
	$pRsArrs[$rows_num]['result4'] = $pRecordSet->fields[4];
	//-----类型分析
	$pRsArrs[$rows_num]['result5'] = '';
	$pChat_lever = $pRecordSet->fields[5];
	if($pChat_lever=='1')
		{$pRsArrs[$rows_num]['result5'] = '---';}
	if($pChat_lever=='2')
		{$pRsArrs[$rows_num]['result5'] = '系统聊天室';}
	if($pChat_lever=='3')
		{$pRsArrs[$rows_num]['result5'] = '个人聊天室';}
	//----------------
	$pRsArrs[$rows_num]['result6'] = $pRecordSet->fields[6];
	$pRsArrs[$rows_num]['result7'] = $pRecordSet->fields[7];

	$rows_num ++;
	$pRecordSet->MoveNext();
	}
	return $pRsArrs;
}

/**
     * 检测数量
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $mDateLimit    警告时间
     * @return  object ADORecordSet
*/

function admin_check_ol_user(&$conn,$mDateLimit)
{
	$the_time = time();
	$the_limit_time = $the_time-$mDateLimit;
	$sql = "update chat_user set ol='no' where l_time<'$the_limit_time' and ol='yes'";
	$pRecordSet_o = $conn->Execute($sql);
	$sql_ol = "select id,roomid from chat_user where ol='yes'";
	$pRecordSet = $conn->Execute($sql_ol);
	$rows_num = 0;
$pRsArrs = array();
while(!$pRecordSet->EOF)
	{
	$id = $pRecordSet->fields[0];
	$roomid = $pRecordSet->fields[1];
	if(!$now_num[$roomid]){$now_num[$roomid] = 0;}
	$now_num[$roomid] = $now_num[$roomid]+1;
	$pRecordSet->MoveNext();
	$rows_num ++;
	}
return $now_num;
}

/**
     * 获取当前要修改聊天室属性
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pChatroom_ID    聊天室ID号
     * @return  array
*/
function modify_chat(&$conn,$pChatroom_ID)
{
	$pSQL = "select id,chat_name,max_num,chat_item,welcome,color_top,color_show,color_list,color_say from chat_room where id='$pChatroom_ID'";

	$pRecordSet = $conn->Execute($pSQL);

	$pOutput['id'] = $pRecordSet->fields[0];
	$pOutput['chat_name'] = $pRecordSet->fields[1];
	$pOutput['max_num'] = $pRecordSet->fields[2];
	$pOutput['chat_item'] = $pRecordSet->fields[3];
	$welcome = $pRecordSet->fields[4];
	$pOutput['welcome'] = substr($welcome, 0, -4);
	$pOutput['c_top'] = $pRecordSet->fields[5];
	$pOutput['c_show'] = $pRecordSet->fields[6];
	$pOutput['c_list'] = $pRecordSet->fields[7];
	$pOutput['c_say'] = $pRecordSet->fields[8];

	return $pOutput;
}

/**
     * 修改聊天室属性
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pChat_name    聊天室名称
	 * @param   string $pChat_pnum    聊天室最大人数
	 * @param   string $pChat_item    聊天室类型
	 * @param   string $pWelcome    欢迎词
	 * @param   string $pRoomID    聊天室ID号
	 * @param   array $pColor_type    颜色位置
	 * @param   array $pColor_in    颜色数组
     * @return  void
*/
function modify_chat_ac(&$conn,$pChat_name,$pChat_item,$pChat_pnum,$pWelcome,$pRoomID,$pColor_type,$pColor_in)
{	
	$pWelcome.='<br>';
	$pSQL = "update chat_room set chat_name='$pChat_name',max_num='$pChat_pnum',chat_item='$pChat_item',welcome='$pWelcome' where id='$pRoomID'";

	$pRecordSet = $conn->Execute($pSQL);
	if($pColor_type!='0')
	{
	$pSQL = "update chat_room set $pColor_type='$pColor_in' where id='$pRoomID'";
	$pRecordSet = $conn->Execute($pSQL);
	}
}

/**
     * 删除过期用户
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $mDateLimit    警告时间
     * @return  void
*/
function delete_old_admin(&$conn,$mDateLimit)
{
	$pDate_now = time();
	$pDate_limit = $pDate_now-$mDateLimit;
	$sql = "update set chat_user set ol='no' where ol='yes' and l_time<'$pDate_limit'";
	$pRecordSet = $conn->Execute($sql);
}

/**
     * 聊天室用户功能列表
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pChatroom_ID    聊天室ID号
	 * @param   string $pagenum    页数
	 * @param   string $pItem    功能类型
	 * @param   string $pLimit    记录显示数量
     * @return  array
*/

function admin_user(&$conn,$pChatroom_ID,$pagenum,$pItem,$pLimit)
{
	$Out = '';
	$pSQL_t = '';
	if($pItem=='master'){$pSQL_t = '';}
	if($pItem=='admin_ol'){$pSQL_t = "where roomid='$pChatroom_ID' and ol='yes'";}
	if($pItem=='user'){$pSQL_t = '';}
	if($pItem=='chat'){$pSQL_t = "where roomid='$pChatroom_ID'";}

	//--------------分页计算
	$pSQL_n = "select count(*) from chat_user $pSQL_t";

	$pRecordSet_n = $conn->Execute($pSQL_n);

	$pTotal_num = $pRecordSet_n->fields[0];
	if($pTotal_num!='0'){
	$pagemax=($pTotal_num-$pTotal_num%$pLimit)/$pLimit+1;
	if($pagemax<$pagenum){$pagenum=$pagemax;}
	if($pagenum<'1'){$pagenum='1';}
	$pStart=($pagenum-1)*$pLimit;
	$pQin=$pagenum-1;
	$pHou=$pagenum+1;
	//--------------
	
	$pSQL = "select id,usernick,degree,l_time,head,filtrate,ol,roomid,ip from chat_user $pSQL_t order by l_time desc limit $pStart,$pLimit";

	$pRecordSet = $conn->Execute($pSQL);
	$pRsArrs = array();
	$rows_num = 0;
	while (!$pRecordSet->EOF)
	{
	$pTd = ($pTd=='tr1') ? 'tr2' : 'tr1';
	$pRsArrs[$rows_num]['RECORDSET_TR_CLASS'] = $pTd;
	$pRsArrs[$rows_num]['result0'] = $pRecordSet->fields[0];
	$pRsArrs[$rows_num]['result1'] = $pRecordSet->fields[1];
	$pRsArrs[$rows_num]['result2'] = $pRecordSet->fields[2];
	$pTime_temp = $pRecordSet->fields[3];
	$pRsArrs[$rows_num]['result3'] = date("Y-n-j h:i:s",$pTime_temp);
	$pRsArrs[$rows_num]['result4'] = $pRecordSet->fields[4];
	$pRsArrs[$rows_num]['result5'] = $pRecordSet->fields[5];
	$pRsArrs[$rows_num]['result6'] = $pRecordSet->fields[6];
	$pRsArrs[$rows_num]['result7'] = $pRecordSet->fields[7];
	$pRsArrs[$rows_num]['result8'] = $pRecordSet->fields[8];
	$rows_num ++;
	$pRecordSet->MoveNext();
	}
	}
	$Out['pRsArrs'] = $pRsArrs;
	$Out['pQin'] = $pQin;
	$Out['pHou'] = $pHou;
	$Out['pagemax'] = $pagemax;
	$Out['pTotal_num'] = $pTotal_num;
	return $Out;
}

/**
     * 更改聊天室管理员
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pRoomID    聊天室ID号
	 * @param   string $pUserid    用户帐号
	 * @param   string $pUserNick    用户昵称
     * @return  array
*/
function add_master(&$conn,$pRoomID,$pUserid,$pUserNick)
{
	$pSQL = "update chat_user set degree='master' where id='$pUserid'";
	$pRecordSet = $conn->Execute($pSQL);
	$pSQL = "update chat_room set master='$pUserid',fid='$pUserNick' where id='$pRoomID'";
	$pRecordSet = $conn->Execute($pSQL);
}

/**
     * 删除数据表中的某条记录
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $tablename    数据表名称
	 * @param   string $del_id    要删除记录的ID号
     * @return  array
*/
function del_data(&$conn,$tablename,$del_id)
{

	$sql="delete from $tablename where id='$del_id'";
	$pRecordSet = $conn->Execute($sql);
}

/**
     * 踢出聊天室
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pRoomID    聊天室ID号
	 * @param   string $pUserid    用户帐号
	 * @param   string $pUserNick    用户昵称
     * @return  array
*/
function kick_people(&$conn,$pRoomID,$pUserid,$pUserNick)
{
	$pSQL = "update chat_user set ol='no' where id='$pUserid'";
	$pRecordSet = $conn->Execute($pSQL);
}
/**
     * 后退
     *
     * @access  public
	 * @param   string $num    后退层数
     * @return  array
*/
function go_back($num)
{
	echo"<script>";
	echo"history.go(".$num.");";
	echo"</script>";
}

/**
     * 阻止某用户进入聊天室
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pRoomID    聊天室ID号
	 * @param   string $pUserid    用户帐号
	 * @param   string $pUserNick    用户昵称
     * @return  array
*/
function out_of(&$conn,$pRoomID,$pUserid,$pUserNick)
{
	$pSQL = "insert into chat_ip (out,roomid) values ('$pUserid','$pRoomID')";
	$pRecordSet = $conn->Execute($pSQL);
	
}
/**
     * 记录被封锁的ip地址
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $pThe_ip    被封锁的IP地址
	 * @param   string $pRoomID    聊天室ID号
     * @return  void
*/
function ip_false(&$conn,$pThe_ip,$pRoomID)
{	

	$pSQL = "select count(*) from chat_ip where the_ip='$the_ip'";
	$pRecordSet = $conn->Execute($pSQL);
	$pNum = $pRecordSet->fields[0];
	if($pNum!='')
	{
	$pSQL = "insert into chat_ip (the_ip,roomid) values ('$pThe_ip','$pRoomID')";
	$pRecordSet = $conn->Execute($pSQL);
	}
}

/**
     * 屏蔽用户列表
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $item    功能选项
     * @return  void
*/
function list_out(&$conn,$item)
{
	$sql = '';
	if($item=='out_chat'){
	$sql = "select `id`,`the_ip`,`out`,`roomid` from chat_ip where `out`!=''";
	}
	if($item=='out_ip'){
	$sql = "select `id`,`out`,`the_ip`,`roomid` from chat_ip where `the_ip`!=''";}
	$pRecordSet = $conn->Execute($sql);
	$rows_num = 0;
	$pRsArrs = array();
	while (!$pRecordSet->EOF)
	{
		$pTd = ($pTd=='tr1') ? 'tr2' : 'tr1';
		$pRsArrs[$rows_num]['RECORDSET_TR_CLASS'] = $pTd;
		$pRsArrs[$rows_num]['result0'] = $pRecordSet->fields[0];
		$pRsArrs[$rows_num]['result1'] = $pRecordSet->fields[1];
		$pRsArrs[$rows_num]['result2'] = $pRecordSet->fields[2];
		$out_area = $pRecordSet->fields[3];
		if($out_area=='all')
			{
		$pRsArrs[$rows_num]['result3'] = '全部';
		}
		else{$pRsArrs[$rows_num]['result3'] = $out_area;}
		$rows_num ++;
	$pRecordSet->MoveNext();
	}
	return $pRsArrs;
}

/**
     * 词语限制列表
     *
     * @access  public
	 * @param   string $mChatlimit    限制词语库文件
     * @return  void
*/
function list_thekey($mChatlimit)
{
	$fop = fopen($mChatlimit,"r");
	$thekey = fread($fop, filesize($mChatlimit));
	fclose($fop);
	$thekey_array = explode("|*|",$thekey);
	$num = count($thekey_array);
	$pRsArrs = array();
	for($i=0;$i<$num-1;$i++)
	{
	$pTd = ($pTd=='tr1') ? 'tr2' : 'tr1';
	$pRsArrs[$i]['RECORDSET_TR_CLASS'] = $pTd;
	$pRsArrs[$i]['id'] = $i;
	$pRsArrs[$i]['thekey'] = $thekey_array[$i];
	}
	return $pRsArrs;
}

/**
     * 词语限制添加
     *
     * @access  public
	 * @param   string $mChatlimit    限制词语库文件
	 * @param   string $input    限制词语
     * @return  void
*/
function add_thekey($mChatlimit,$input)
{
	$fop = fopen($mChatlimit,"a+");
	$input = $input.'|*|';
	fputs($fop,$input);
	fclose($fop);
}

/**
     * 词语限制删除
     *
     * @access  public
	 * @param   string $mChatlimit    限制词语库文件
	 * @param   string $thed    删除词语
     * @return  void
*/
function delete_thekey($mChatlimit,$thed)
{
	$fop = fopen($mChatlimit,"r+");
	$thekey = fread($fop, filesize($mChatlimit));
	$thed = $thed.'|*|';
	$thekey_array = explode($thed,$thekey);
	$input = $thekey_array[0].$thekey_array[1];
	fclose($fop);
	$fop = fopen($mChatlimit,"w+");
	fputs($fop,$input);
	fclose($fop);
}

/**
     * 统计数据库记录数量
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @return  void
*/
function count_rows(&$conn)
{
	$sql = "select count(*) from chat_msg";
	$pRecordSet = $conn->Execute($sql);
	$rows_num = $pRecordSet->fields[0];
	return $rows_num;
}

/**
     * 删除老数据，清除范围由管理员控制
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @return  void
*/
function delete_old_data_admin(&$conn)
{
	$sql = "delete from chat_msg";
	$pRecordSet = $conn->Execute($sql);
}
/**
     * 用户自建聊天室设置
     *
     * @access  public
     * @param   string $file   开关文件
     * @return  void
*/
function cust_file($file)
{
	$fop = fopen($file,"r+");
	$data = fread($fop, filesize($file));
	if($data=='yes'){$out['yes'] = "checked";$out['no'] = "";}
	if($data=='no'){$out['yes'] = "";$out['no'] = "checked";}
	fclose($fop);
	return $out;
}
/**
     * 更改用户自建聊天室设置
     *
     * @access  public
     * @param   string $file   开关文件
     * @param   string $str   开 or 关
     * @return  void
*/
function up_cust_file($file,$str)
{
	$fop = fopen($file,"w+");
	fwrite($fop, $str);
	fclose($fop);
}
/**
     * 提取聊天室名称
     *
     * @access  public
     * @param   string $chat_id   聊天室ID号
     * @return  void
*/
function take_chat_name(&$conn,$chat_id)
{
	$sql = "select chat_name from chat_room where id='$chat_id'";
	$rs = &$conn->Execute($sql);

	$out = $rs->fields[0];
	return $out;
}
	/**
	 * 函数		out_thekey
	 *
	 * @desc	词语限制提取
	 * @global	
	 * @return	$thekey
	 */
function out_thekey()
{
	$filepath = 'limit/limit.dat';
	$fop = fopen($filepath,"r");
	$thekey = fread($fop,filesize($filepath));
	fclose($fop);
	return $thekey;
}
	/**
	 * 函数		out_thekey
	 *
	 * @desc	词语限制提取
	 * @global	
	 * @return	$thekey
	 */
function delete_old_chat(&$conn,$user)
{
	$sql = "select id from chat_room";
	$rs = $conn->Execute($sql);
	while (!$rs->EOF)
	{
		$room_id = $rs->fields[0];
		$sql = "select count(*) from chat_user where roomid='$room_id' and ol='yes'";
		$rs_num = $conn->Execute($sql);
		$user_num = $rs_num->fields[0];
		if($user_num==0)
		{
		$sql = "delete from chat_room where id='$room_id' and master!='$user' and lever='3'";
		$conn->Execute($sql);
		}
	$rs->MoveNext();
	}

}
}
?>