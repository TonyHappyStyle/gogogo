<?php
//  $Id: cms_kicq.class.php,v 1.11 2003/09/01 06:25:18 my9381 Exp $

/**
 * 建网通网络寻呼类。
 * 此类主要用于网络寻呼的用户、消息等信息的管理等。
 *
 * @package K12CMS
 * @access public
 */
class CMS_Kicq
{
    //  {{{ private properties
    
    /**
     * CMS_Kicq 类文件的 Id，用于 CVS 版本追踪。
     * @var string
     * @access  private
     */
    var $_id = '$Id: cms_kicq.class.php,v 1.11 2003/09/01 06:25:18 my9381 Exp $';
    
    /**
     * CMS_Kicq 类中所用到的 CMS 对象实例。
     * @var object CMS
     * @access  private
     */ 
    var $_cms = null;
    
    //  }}}

    //  {{{ public properties

    /**
     * 网络寻呼模板文件存放目录的 URL。
     * @var  string
     * @access  public
     */ 
    var $tpl_url = '';
    
    /**
     * 网络寻呼模板文件存放目录物理路径。
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
     * 系统定义删除消息的时间(即超过 $delete_time (秒钟)的消息都删除掉！)。
     * @var  data
     * @access  public
     */ 
	var $delete_time = 60;
	
	//  }}}

    //  {{{ constructor
    
    /**
     * CMS_Info 的类构造函数。
     * 在此构造函数中将对建网通信息发布所用到的目录路径进行初始化。
     *
     * @access  public
     * @param   object CMS  &$cms   建网通所用的 CMS 对象实例的引用。
     * @return  void
     */     
    function CMS_Kicq(&$cms)
    {
        
        $this->_cms =& $cms;
        $this->tpl_url = $this->_cms->tpl_url.'/'.$this->_cms->modules['KICQ']['DIR'];
        $this->tpl_path = $_SERVER['DOCUMENT_ROOT'].$this->tpl_url;
        $this->graphic_url = 'http://'.$_SERVER['SERVER_NAME'].$this->tpl_url.'/graphics';
        $this->graphic_path = $this->tpl_path.'/graphics';
		$this->table_list =array('user' => 'user_main',	'netbp' => 'kicq_main','curuser' => 'kicq_cur');
		$this->help_url = './faq.php';
		
    }   //end function
    
    //  }}}

    //  {{{ update_current_user()

    /**
     * 更新用户在线数据，即更新 在线用户表
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return  void
     */

	function update_current_user (&$conn) {
		global $user_id,$user_nickname;
	    $fSql="SELECT C_ID FROM ".$this->table_list['curuser']." WHERE C_ID='".$user_id."'";
		$fRecordSet = $conn->Execute($fSql);
		$rows = $fRecordSet->GetArray();
		if(!$rows)
			$fSql = "INSERT INTO ".$this->table_list['curuser']." (C_ID, last_action,C_IP, C_Name ) VALUES ('".$user_id."','".time()."', '".getenv("REMOTE_ADDR")."', '".$user_nickname."')";
		else
			$fSql = "UPDATE ".$this->table_list['curuser']." SET last_action=".time().", C_IP='".getenv("REMOTE_ADDR")."', C_Name='".$user_nickname."' WHERE C_ID='".$user_id."'";
		$fRecordSet = $conn->Execute($fSql);
		return $fRecordSet;
	}//end function
    //  }}}

    //  {{{ delete_message()

    /**
     * 删除用户过时的消息
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return  void
     */
	
	function delete_message (&$conn) {
		
	    $fsql = "DELETE FROM ".$this->table_list['netbp']." WHERE m_time<'".date("Y-m-d H-i-s",(time() - $cms_kicq->delete_time * 60))."' AND m_read=0";
		$fRecordSet = $conn->Execute($fsql);
		return $fRecordSet;
	}//end function
	//}}}

    //  {{{ save_message()

    /**
     * 保存发送的消息
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 *@param   string $to 信息发送目的用户
	 *@param   string $body 信息内容
     * @return  void
     */

	function save_message (&$conn, $to, $body) {
		global $user_id,$user_nickname;
		$sql = "INSERT INTO ".$this->table_list["netbp"]." (m_from, m_to, m_body, m_read, m_time) VALUES ('".$user_id."', '".$to."', '".$body."', 1, '".date("Y-m-d H:i:s", time())."')";
		$fRecordSet = $conn->Execute($sql);
	
		return $fRecordSet;
	}//end function

	//}}}

    //  {{{ get_message()

    /**
     * 获取用户的信息数据集
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @return  object ADORecordSet
     */

	function get_message (&$conn) {
		global $user_id,$user_nickname;
		$fsql = "SELECT t1.m_from, t2.nickname, t2.user_face, t1.m_body, t1.m_time FROM ".$this->table_list["netbp"]." as t1, ".$this->table_list["user"]." as t2 WHERE t1.m_to='".$user_id."' AND t1.m_read=1 AND t1.m_from=t2.user_id";
		$fRecordSet = $conn->Execute($fsql);
		$rows = $fRecordSet->GetArray();
		$this->set_message_readed($conn);
		return $rows;
	}//end function

	//}}}

    //  {{{ set_message_readed()

    /**
     * 将用户接受到的短信息设置成已读
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @return  void
     */
	function set_message_readed (&$conn) {
		global $user_id,$user_nickname;
		$fsql = "UPDATE ".$this->table_list["netbp"]." SET m_read=0 WHERE m_read=1 AND m_to='".$user_id."'";
		$fRecordSet = $conn->Execute($fsql);
		return $fRecordSet;
	}//end function

	//}}}

    //  {{{ get_friend()

    /**
     * 获取用户的好友列表
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @return  string
     */

	function get_friend (&$conn) {
		global $user_id,$user_nickname;
		$fsql = "SELECT friend FROM ".$this->table_list["user"]." WHERE user_id='".$user_id."'";
		$fRecordSet = $conn->Execute($fsql);
		$frows = $fRecordSet->GetArray();
		if (count($frows) !=0)
			$friends = split("\+", $frows[0][0]);
		else
			$friends = array();

		return $friends;
	}//end function
	//}}}

    //  {{{ get_friend()

    /**
     * 获得好友信息组成的数组
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	$friends 好友列表
	 * @return  string
     */

	function get_friend_info (&$conn, $friends) {
		
		for ($i=0; $i<count($friends); $i++) {
			$where .= " OR user_id='".$friends[$i]."'";
		}

		if ($i) {
			$where = " WHERE ".subsTR($where, 4);
			$fsql = "SELECT user_id, nickname, user_face FROM ".$this->table_list["user"].$where;
			$fRecordSet = $conn->Execute($fsql);
			$friendobjects = $fRecordSet->GetArray();
		}
		else
			$friendobjects = array();

		return $friendobjects;
	}//end function

	//}}}

    //  {{{ get_friend()

    /**
     * 将用户加入当前用户的好友列表
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	$uid 用户帐号
	 * @return  void
     */

	
	function add_friend (&$conn, $uid) {
		global $user_id,$user_nickname;
		$friends = $this->get_friend($conn);
		$num = count($friends);
		if (0 == $num) {
			$friend = $uid;
		}
		else {
			if (!in_array($uid, $friends))
				array_push($friends, $uid);
			$friend = implode("+", $friends);
		}

		$fsql = "UPDATE ".$this->table_list["user"]." SET friend='".$friend."' WHERE user_id='".$user_id."'";
		$fRecordSet = $conn->Execute($fsql);

	}//end function

	//}}}

    //  {{{ del_friend()

    /**
     * 从当前用户的好友列表中删除好友
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	$uid 用户帐号
	 * @return  void
     */

	function del_friend (&$conn, $uid) {
		global $user_id,$user_nickname;
	    $friends = $this->get_friend($conn);
	    $num = count($friends);
		if ($num>0) {
			for ($i=0; $i<$num; $i++) {
				if ($uid != $friends[$i])
					$newfriends[] = $friends[$i];
			}
			if ($num != count($newfriends)) {	//	执行删除操作
				$friend = implode("+", $newfriends);
				$fsql = "UPDATE ".$this->table_list["user"]." SET friend='".$friend."' WHERE user_id='".$user_id."'";
				$fRecordSet = $conn->Execute($fsql);
			}
		}
	}//end function

	//}}}

    //  {{{ get_user_all_number()

    /**
     * 获取所有用户的总数
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	$uid 用户帐号默认为空
	 * @param	$nickname 用户昵称默认为空
	 * @return  integer
     */

	function get_user_all_number (&$conn, $uid="", $nickname="") {
		global $user_id,$user_nickname;
		$f_uid="%".$uid."%";
		$f_nickname="%".$nickname."%";
		$fsql = "SELECT COUNT(*) FROM ".$this->table_list["user"]." WHERE user_id!='".$user_id."' AND user_id LIKE '".$f_uid."' AND nickname LIKE '".$f_nickname."'";
		$fRecordSet = $conn->Execute($fsql);
	
		$frows = $fRecordSet->fields[0];

		return $frows;
	}//end function

	//}}}

    //  {{{ get_user_all()

    /**
     * 获取所有用户数据数组
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	$uid 用户帐号默认为空
	 * @param	$nickname 用户昵称默认为空
	 * @param	$limit 获取数据的数量条件
	 * @return  string
     */

	function get_user_all (&$conn, $uid="", $nickname="", $limit="") {
		global $user_id,$user_nickname;
		$f_uid="%".$uid."%";
		$f_nickname="%".$nickname."%";

		$fsql = "SELECT user_id, nickname, user_face FROM ".$this->table_list["user"]." WHERE user_id!='".$user_id."' AND user_id LIKE '".$f_uid."' AND nickname LIKE '".$f_nickname."' ".$limit;
	
		$fRecordSet = $conn->Execute($fsql);
		$rows = $fRecordSet->GetArray();
		return $rows;
	}//end function

	//}}}

    //  {{{ get_user_online_number()

    /**
     * 获取在线用户总数
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	$uid 用户帐号默认为空
	 * @param	$nickname 用户昵称默认为空
	 * @return  integer
     */

	function get_user_online_number (&$conn, $uid="", $nickname="" ) {
		global $user_id,$user_nickname;
		$f_uid="%".$uid."%";
		$f_nickname="%".$nickname."%";

		$fsql = "SELECT COUNT(C_ID) FROM ".$this->table_list["curuser"]." WHERE C_ID!='".$user_id."' AND C_ID LIKE '".$f_uid."' AND C_Name LIKE '".$f_nickname."'";
	
		$fRecordSet = $conn->Execute($fsql);
		$frows = $fRecordSet->fields[0];
	
		return $frows;
	}//end function

	//}}}

    //  {{{ get_user_online()

    /**
     * 获取在线用户数据数组
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	$uid 用户帐号默认为空
	 * @param	$nickname 用户昵称默认为空
	 * @param	$limit 用户昵称默认为空
	 * @return  string
     */

	function get_user_online (&$conn, $uid="", $nickname="", $limit="" ) {
		global $user_id,$user_nickname;
		$f_uid="%".$uid."%";
		$f_nickname="%".$nickname."%";

		$fsql = "SELECT t1.C_ID, t2.nickname, t2.user_face FROM ".$this->table_list["curuser"]." as t1, ".$this->table_list["user"]." as t2 WHERE t1.C_ID!='".$user_id."' AND t2.user_id=t1.C_ID AND t1.C_ID LIKE '".$f_uid."' AND t2.nickname LIKE '".$f_nickname."' ".$limit;
	
		$fRecordSet = $conn->Execute($fsql);
		$rows = $fRecordSet->GetArray();
		return $rows;
	}//end function

	//}}}

    //  {{{ is_user_online()

    /**
     * 判断帐号是否是在线用户
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	$uid 用户帐号
	 * @return  boolean 在线则返回 TURE，否则返回 FALSE。
     */

	function is_user_online (&$conn, $uid ) {
		
		$fsql = "SELECT C_ID FROM ".$this->table_list["curuser"]." WHERE C_ID!='".$uid."'";

		$fRecordSet = $conn->Execute($fsql);
		$rows = $fRecordSet->GetArray();
		if (count($rows) > 0)
			return true;
		else
			return false;
	}//end function

	//}}}

    //  {{{ is_in_array()

    /**
     * 判断 $needle 是否在数组 $array 中
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	$needle 待判断的字串
	 * @param	$array 待判断的所在数组
	 * @param	$flag 判断后返回的标志 
	 * @return  boolean 在线则返回 TURE，否则返回 FALSE。
     */

	function is_in_array ( $needle, $array, $flag ) {
		for ($i=0; $i<count($array); $i++) {
			if ($needle[0] == $array[$i][0])
				return ($flag=="index") ? $i : "true";
		}
		return ($flag=="index") ? -1 : "false";
	}//end function

	//}}}

    //  {{{ delete_invalid_user()

    /**
     * 删除在线用户表里的无效用户
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	$edle_time 清理无效用户的时限
	 * @return  void
     */

	function delete_invalid_user(&$conn, $idle_time){
		//$conn->debug = true;	
		$fsql = "DELETE FROM ".$this->table_list["curuser"]." WHERE last_action < '".(time()-$idle_time)."'";
		$fRecordSet = $conn->Execute($fsql);
	} //delete_invalid_user()
	//}}}

}   //end class 

?>