<?php
//  $Id: cms_forum.class.php,v 1.7 2003/07/10 08:44:35 yangh Exp $
/**
 * 建网通论坛类。
 * 此类主要用于论坛。
 *
 * @package K12CMS
 * @access public
 */
class CMS_Forum
{
    //  {{{ private properties
	
	/**
     * CMS_Forum 类文件的 Id，用于 CVS 版本追踪
     * @var string
     * @access  private
     */
    var $_id = '$Id: cms_forum.class.php,v 1.7 2003/07/10 08:44:35 yangh Exp $';
    
    /**
     * CMS_Forum 类中所用到的 CMS 对象实例
     * @var object CMS
     * @access  private
     */ 
    var $_cms = null;

    //  }}}
    
    //  {{{ public properties
	
	/**
     * 论坛的 URL，例如：http://192.168.212.66/cms/app/forum/
     * @var string
     * @access  public
     */
	var $url;
	
	/**
     * 论坛的实际路径，例如：/home/httpd/html/cms/app/forum/
     * @var string
     * @access  public
     */	
	var $path;
	
	/**
     * 论坛模板文件的路径
     * @var string
     * @access  public
     */		
	var $tpl_path;
 	
	/**
     * 论坛模板文件的 URL
     * @var string
     * @access  public
     */   
	var $tpl_url;
	
	/**
     * 论坛模板中图片的 URL
     * @var string
     * @access  public
     */
	var $images;
	
	/**
     * 论坛用户头像图片的 URL
     * @var string
     * @access  public
     */	
	var $avatar_url;
	
	/**
     * 论坛帖子图标的 URL
     * @var string
     * @access  public
     */		
	var $icons_url;
	
	/**
     * 论坛用户等级图片的路径
     * @var string
     * @access  public
     */	
	var $rank_path;

	/**
     * 论坛用户等级图片的 URL
     * @var string
     * @access  public
     */		
	var $rank_url;
	
	/**
     * 论坛笑脸标记图片的 URL
     * @var string
     * @access  public
     */		
	var $smiles_url;

	/**
     * 论坛发表帖子的工具的图片的 URL
     * @var string
     * @access  public
     */		
	var $tools_img_url;

	/**
     * 论坛附件存贮的路径
     * @var string
     * @access  public
     */		
	var $attach_path;

	/**
     * 论坛附件的 URL
     * @var string
     * @access  public
     */		
	var $attach_url;

    /**    
     * 数据库相关的变量数组。
     * @var array
     * @access  public
     */ 
	var $db = array();

    /**    
     * 论坛的名称 :)
     * @var array
     * @access  public
     */ 	
	var $app_name = 'KForum';

    /**    
     * 论坛的版本 :)
     * @var array
     * @access  public
     */ 	
	var $app_version = '2.0';

    //  }}}
    
    //  {{{ constructor
	
    /**
     * CMS_Forum 的类构造函数。
     * 在此构造函数中将对建网通信息发布所用到的目录路径进行初始化。
     *
     * @access  public
     * @param   object CMS  &$cms   建网通所用的 CMS 对象实例的引用。
     * @return  void
     */     
    function CMS_Forum(&$cms)
    {
        
        $this->_cms =& $cms;
		$this->url 			= 'http://'.$_SERVER['SERVER_NAME'].$cms->app_url. '/'. $cms->modules['FORUM']['DIR'] . '/';
		$this->path			= $cms->app_path. '/'. $cms->modules['FORUM']['DIR'] . '/';
		$this->tpl_path		= $cms->tpl_path. '/'. $cms->modules['FORUM']['DIR'] . '/';
        $this->tpl_url 		= $cms->tpl_url . '/' . $cms->modules['FORUM']['DIR'] . '/';
		$this->images		= $cms->tpl_url . '/images/forum/';
		$this->avatar_url	= $cms->face_url;
		$this->icons_url	= $this->images . 'msg_icons/';
		$this->rank_path	= $cms->tpl_path . '/images/forum/rank/';
		$this->rank_url		= $this->images . 'rank/';
		$this->smiles_url	= $this->images . 'smiles/';
		$this->tools_img_url= $this->images . 'tools/';
		$this->attach_path	= $this->tpl_path . 'attach/';
		$this->attach_url	= $this->tpl_url . 'attach/';

		$this->db = array(
					'Prefix'	=> 'forum_',
					'UserTable'	=> 'user_main',
					'UserId'	=> 'sid',
					'UserName'	=> 'user_id',
					'NickName'	=> 'nickname',
					'UserPosts'	=> 'forum_posts',
					'UserRank'	=> 'forum_rank',
					'UserLevel'	=> 'forum_level',
					'UserSig'	=> 'signature',
					'UserFace'	=> 'user_face',
					'UserEmail'	=> 'email',
					'UserNickname'	=> 'nickname',
				);
		$this->_get_option($cms->conn);
		$this->app_name = 'KForum';
		$this->app_version = '2.0';
    }   //end function

    /**
     * 初始化论坛的选项
     * 
     * @access  private 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return  void
     */
	function _get_option (&$conn) {
		
		$rtn = array();
		$sql = "SELECT allow_html, allow_bbcode, allow_sig, posts_per_page, hot_threshold, topics_per_page, attach_max  
				FROM ".$this->db['Prefix']."config 
				WHERE selected=1";
		$res = $conn->CacheExecute($sql);

		$rtn['AllowHtml'] 		= $res->fields['allow_html'];
		$rtn['AllowBbCode'] 	= $res->fields['allow_bbcode'];
		$rtn['AllowSig'] 		= $res->fields['allow_sig'];
		$rtn['PostsPerPage'] 	= $res->fields['posts_per_page'];
		$rtn['HotThreshold']	= $res->fields['hot_threshold'];
		$rtn['TopicsPerPage']	= $res->fields['topics_per_page'];
		$rtn['AttachMax']		= $res->fields['attach_max'];
		 
		$this->option = $rtn;
	}
	
    /**
     * 生成论坛index页的数据供smarty使用
     * 
     * @access  public 
     * @global  integer  $LAST_VISIT_TIME    最后一次访问时间 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer  $view_cat    要查看的分类的id
     * @return  array
     */
	function gen_data_index (&$conn, $view_cat){
		global $LAST_VISIT_TIME;

		$rtn = array();
		
		// 论坛分类
/*		$sql = "SELECT c.cat_id, c.cat_title, c.cat_order, c.cat_parent
				FROM ".$this->db['Prefix']."catagories c, ".$this->db['Prefix']."forums f
				WHERE f.cat_id=c.cat_id
				GROUP BY c.cat_id, c.cat_title, c.cat_order
				ORDER BY c.cat_order
			";
*/

		$sql = "SELECT c.cat_id, c.cat_title, c.cat_order, c.cat_parent
				FROM ".$this->db['Prefix']."catagories c, ".$this->db['Prefix']."forums f
				WHERE 1=1
				GROUP BY c.cat_id, c.cat_title, c.cat_order
				ORDER BY c.cat_order
			";
		$result = $conn->CacheExecute($sql);
		$cat_rows = $result->GetRows();
		$rtn['cat_list'] = $cat_rows;

		// 各个分类下的论坛
		$sql_cond_forums = '';
		if ($view_cat != -1) {
			$sql_cond_forums = "WHERE f.cat_id=$view_cat";
		}
		$sql = "SELECT f.*, u.".$this->db['NickName']." as nickname,
					u.".$this->db['UserName']." as username, 
					p.post_time, p.post_id, p.topic_id, p.post_title
				FROM ".$this->db['Prefix']."forums f
				LEFT JOIN ".$this->db['Prefix']."posts p ON p.post_id=f.forum_last_post_id
				LEFT JOIN ".$this->db['UserTable']." u ON u.".$this->db['UserId']."=p.poster_id
				".$sql_cond_forums."
				ORDER BY f.cat_id, f.view_order
			";
		$result = $conn->CacheExecute($sql);
		$forum_rows = $result->GetRows();
		
		// 循环生成
		for ($j = 0; $j < count($cat_rows); $j++) {
			if($view_cat != -1) {	//浏览一个分类
				if($cat_rows[$j]['cat_id'] != $view_cat) {	//不是所要浏览的分类
					continue;
				}
			}
			
			for ($i = 0; $i < count($forum_rows); $i++) {
				$list = array();
				
				if($cat_rows[$j]['cat_id'] == $forum_rows[$i]['cat_id']) {
					//td 5 显示最后帖子
					if($forum_rows[$i]['post_time']) {
						if(!$forum_rows[$i]['username'])
							$list['last_post_user'] = 'anonymous';	//匿名用户
						else
							$list['last_post_user'] = '<a href="'.sprintf($this->_cms->user_info_url, $forum_rows[$i]['username']).'" target="_blank">'.$forum_rows[$i]['nickname'].'</a>';
						$list['last_post_time'] = $forum_rows[$i]['post_time'];
						$list['last_post_title'] = $forum_rows[$i]['post_title'];
						$list['last_post_link'] = 'viewtopic.php?pTopicId='.$forum_rows[$i]['topic_id'].'&pForumId='.$forum_rows[$i]['forum_id'].'&pGoto='.$forum_rows[$i]['post_id'];
					}
						
					//td 1 显示有无新帖子的图片
					if (!empty($forum_rows[$i]['post_time']) && strtotime($forum_rows[$i]['post_time']) > $LAST_VISIT_TIME) {
						$list['new_post'] = true; 
					} else {
						$list['new_post'] = false;
					}		
					
					//td 2 显示论坛名和论坛描述
					$list['forum_id'] = $forum_rows[$i]['forum_id'];
					$list['forum_name'] = $forum_rows[$i]['forum_name'];
					$list['forum_desc'] = $forum_rows[$i]['forum_desc'];
					
					//td 3 显示主题数
					$list['forum_topics'] = $forum_rows[$i]['forum_topics'];
					
					//td 4 显示帖子数
					$list['forum_posts'] = $forum_rows[$i]['forum_posts'];
					
					//td 6 显示版主
					$moderators = $this->_get_moderators($conn, $forum_rows[$i]['forum_id']);
					$mods_str = '';
					if (count($moderators) > 0) {
						foreach ($moderators as $key => $mod) {
							 $mods_str .= '<img src="'.$this->images.'moderator.gif" align="middle" border="0"><a href="'.sprintf($this->_cms->user_info_url, $mod['username']).'" target="_blank">'.$mod['nickname'].'</a>&nbsp;';
						}
					}					
					$list['forum_mods'] = $mods_str;
					
					$rtn['forum_list'][$j][$i] = $list;
				} //end if	
			} // end for forum
		}// end for cat
		//echo "<pre>";print_r($rtn);
		return $rtn;	
	}

    /**
     * 获取某个论坛的版主信息
     * 
     * @access	private 
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$forum_id	某个论坛id
     * @return	array	array (
     * 						'username' => '用户名',
     * 						'nickname'  => '昵称',
     *                  )
     */
	function _get_moderators(&$conn, $forum_id) {
		
		$sql ="SELECT u.".$this->db['UserName']." as username, u.".$this->db['NickName']." as nickname 
				FROM ".$this->db['UserTable']." u, ".$this->db['Prefix']."mods f 
				WHERE f.forum_id=".$forum_id." AND f.user_id=u.".$this->db['UserId'];
		$result = $conn->CacheExecute($sql);
		return $result->GetArray();
	}

    /**
     * 生成某个论坛的版主信息
     * 
     * @access	public 
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$forum_id	某个论坛id
     * @return	string
     */
	function get_mods(&$conn, $forum_id) {
		
		$moderators = $this->_get_moderators($conn, $forum_id);
		$mods_str = '';
		if (count($moderators) > 0) {
			foreach ($moderators as $key => $mod) {
				 $mods_str .= '<img src="'.$this->images.'moderator.gif" align="middle" border="0"><a href="'.sprintf($this->_cms->user_info_url, $mod['username']).'" target="_blank">'.$mod['nickname'].'</a>&nbsp;';
			}
			return $mods_str;
		}
	}	
	
	/**
     * 获取整个论坛、某个论坛或某个主题的帖子总数
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$id		可能的id值
	 * @param	string	$type	允许的值：all | forum | topic
     * @return	integer
     */
	function get_total_posts(&$conn, $id, $type) {
		
		switch ($type) {
			//case 'users':	//没有用到这种情况
			//	$sql = "SELECT COUNT(*) AS total FROM ".$this->db['UserTable']." WHERE (".KF_USERID."!=-1) AND (".KF_USERLEVEL."!=-1)";
			//	break;
			case 'all':	//统计所有帖子(posts)数
				$sql = "SELECT COUNT(*) AS total FROM ".$this->db['Prefix']."posts";
				break;
			case 'forum':	//统计某个论坛的帖子数
				$sql = "SELECT COUNT(*) AS total FROM ".$this->db['Prefix']."posts WHERE forum_id=$id";
				break;
			case 'topic':	//统计某个主题的帖子数
				//$sql = "SELECT COUNT(*) AS total FROM ".$this->db['Prefix']."posts WHERE topic_id=$id";
				$sql = "SELECT topic_replies+1 AS total FROM ".$this->db['Prefix']."topics WHERE topic_id=$id";
				break;
		}
		
		$result = $conn->Execute($sql);
		return $result->fields['total'];
	}
	
	/**
     * 获取整个论坛或某个论坛的主题总数
     * 
     * @access  public
     * @param   object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$forum_id		论坛id
	 * @param	string	$type	允许的值：all | forum
     * @return	integer
     */
	function get_total_topics (&$conn, $forum_id, $type) {
		
		switch ($type) {
			case 'all':		// 统计所有主题(topics)数
				$sql = "SELECT COUNT(*) AS total FROM ".$this->db['Prefix']."topics WHERE moved_to=0";
				break;
			case 'forum':	//统计某个论坛的主题数
				$sql = "SELECT COUNT(*) AS total FROM ".$this->db['Prefix']."topics WHERE forum_id=$forum_id AND moved_to=0";
				break;
		}
		
		$result = $conn->Execute($sql);
		return $result->fields['total'];
	}
    
	/**
     * 通过id获取论坛信息
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param	integer	$forum_id		论坛id
     * @return	array
     */		
	function get_forum_info_from_id (&$conn, $forumid) {
		
		$sql = "SELECT forum_name, forum_access, forum_type FROM ".$this->db['Prefix']."forums WHERE forum_id=$forumid";
		$res = $conn->Execute($sql);
		
		$rtn['Name']	= $res->fields['forum_name'];
		$rtn['Access']	= $res->fields['forum_access'];
		$rtn['Type']	= $res->fields['forum_type'];
		
		return $rtn;
	}
	
	/**
     * 判断某个userid是否可以查看某个私有论坛。如果"isposting"为真，进一步检查发贴子的权限。
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param	integer	$userid		用户id
     * @param	integer	$forumid	论坛id
     * @param	boolean	$isposting	是否检查可以发贴子的权限
     * @return	boolean
     */	
	 function check_priv_forum_auth (&$conn, $userid, $forumid, $isposting) {
		
		$sql = "SELECT COUNT(*) AS user_count FROM ".$this->db['Prefix']."access WHERE (user_id=$userid) AND (forum_id=$forumid)";
		
		if ($isposting)
			$sql .= " AND (can_post)=1";
		
		if (!$res = $conn->Execute($sql))
			return  FALSE;
		
		if ($res->fields['user_count'] <= 0)
			return  FALSE;

		return TRUE;
	}

	/**
     * 论坛的出错处理函数
     * 
     * @access	public
     * @param	string	$type	错误类型
     * @return	void
     */	
	function error_die ($type = '') {
		
		switch ($type) {
			case 'ip_banned':
				$error_msg= '你的IP被禁止进入论坛！';
				break;				
			case 'user_banned':
				$error_msg= '你的用户名被禁止进入论坛！';
				break;		
			case 'forum_not_exisit':
				$error_msg= '你要找的论坛不存在！';
				break;
			case 'topic_not_exisit':
				$error_msg= '你要找的主题不存在！';
				break;
			case 'no_post':
				$error_msg= '你没有在这个论坛发表文章的权限！';
				break;
			case 'private_forum_no_post':
				$error_msg= '这是一个 <b>私有论坛</b>。<br />你没有被授权在这个论坛发帖子！';
				break;
			case 'private_forum_no_read':
				$error_msg= '这是一个 <b>私有论坛</b>。<br />你没有被授权在这个论坛浏览帖子！';
				break;
			case 'attach_maxed':
				$error_msg= '上传的文件超过了规定的大小 '.($this->option['AttachMax'] / 1024).'KB！';
				break;
			case 'attach_failed':
				$error_msg= '上传文件时出错！';
				break;
			case 'empty_msg':
				$error_msg= '你必须输入帖子的内容！';
				break;
			case 'attach_not_found':
				$error_msg= '你要找的附件不存在！';
				break;
			case 'quote_not_exisit':
				$error_msg= '你要引用的帖子不存在！';
				break;
			case 'topic_locked_no_post':
				$error_msg= '主题已锁定，不能回复或者编辑帖子！';
				break;
			case 'post_not_exisit':
				$error_msg= '你要找的帖子不存在！';
				break;
			case 'arg_error':
				$error_msg= '参数错误！';
				break;
			case 'no_edit_permission':
				$error_msg= '你没有编辑该帖的权限！';
				break;
			case 'not_mod_permission_deny':
				$error_msg= '你不是版主，没有权限！';
				break;
			case 'user_not_exist':
				$error_msg= '你指定的作者不存在！';
				break;
			case 'no_keyword':
				$error_msg= '你没有输入关键词，不能进行搜索！';
				break;
			case 'keyword_too_short':
				$error_msg= '你输入关键词太短，必须大于2个字符！';
				break;
			default:
				$error_msg= '论坛出错，请稍后再试！';
				break;
		} //end switch
		
		if ($error_msg) {
			$this->_cms->echo_header();
			$this->_cms->smarty->assign('error_msg', $error_msg);
			$this->_cms->smarty->display($this->_cms->modules['FORUM']['DIR'].'/forum_error.tpl'); 
			$this->_cms->echo_footer();
			exit();
		}
	}

	/**
     * 判断某个论坛或主题是否存在，用来阻止用户修改URL中的参数发生错误。
     * 
     * @access  public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$id		id值
	 * @param	string	$type	允许的值：forum | topic
     * @return	boolean
     */
	function forum_does_exists (&$conn, $id, $type) {
		switch ($type)	{
			case 'forum':
				$sql = "SELECT forum_id as tmp FROM ".$this->db['Prefix']."forums WHERE forum_id=$id";
				break;
			case 'topic':
				$sql = "SELECT topic_id as tmp FROM ".$this->db['Prefix']."topics WHERE topic_id=$id";
				break;
		}
		
		if (!$res = $conn->Execute($sql))
			return FALSE;

		if ($res->fields['tmp'] == '')
			return FALSE;

		return  TRUE;
	}	

	/**
     * 判断某个用户或IP地址是否被禁止。
     * 
     * @access  public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	string	$ipuser	用户id值或IP地址
	 * @param	string	$type	允许的值：ip | username
     * @return	boolean
     */
	function is_banned (&$conn, $ipuser, $type) {

		// Remove old bans
		$sql = "DELETE FROM ".$this->db['Prefix']."banlist WHERE (ban_end<". time() .") AND (ban_end>0)";
		$result = $conn->Execute($sql);
		
		switch ($type)	{
			case 'ip':
				$sql = "SELECT ban_ip FROM ".$this->db['Prefix']."banlist";
				$result = $conn->Execute($sql);
				
				//不输入 IP 的最后一位，可以禁用一个 IP 域，如： 192.168.1. 表示禁用 192.168.1.0-255
				while (!$result->EOF) {
					$ip = $result->fields['ban_ip'];
					
					if (substr($ip, -1) == '.') {	//禁止C类地址
						if (ereg("^$ip", $ipuser))
							return true;
					} else {	//一个地址
						if ($ipuser == $ip)
							return true;
					}
					
					$result->MoveNext();
				}
				break;
				
			case 'username':
				$sql = "SELECT COUNT(*) as total FROM ".$this->db['Prefix']."banlist WHERE ban_userid=$ipuser";
	      		$result = $conn->Execute($sql);

	      		if ($result->fields['total'] > 0) {
	      			return true;
	      		}
				break;
		}
		
		return false;
	}

    /**
     * 生成论坛viewforum页的数据供smarty使用
     * 
     * @access  public 
     * @global  integer	$LAST_VISIT_TIME	最后一次访问时间 
     * @global  string	$KF_USERNAME	登录的用户名 
     * @global  array	$cfg_forum		论坛的配置数组 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer  $start		分页显示的起始值
	 * @param   integer  $forumid	论坛id
	 * @param   integer  $before	显示多长时间以前的帖子
     * @return  array
     */	
	function gen_data_viewforum (&$conn, $start, $forumid, $before) {
		global $LAST_VISIT_TIME, $KF_USERNAME, $cfg_forum;

		/* Remove expired moved thread pointers */
		$tmp_time = time() - 86400 * $cfg_forum['MOVED_THR_PTR_EXPIRY'];
		$sql = "DELETE FROM ".$this->db['Prefix']."topics WHERE forum_id=".$forumid." AND post_stamp<".$tmp_time." AND moved_to!=0";
		//var_dump($sql);exit;
		$conn->Execute($sql);
		
		//从全局变量取出
		$posts_per_page = $this->option['PostsPerPage'];
		$topics_per_page = $this->option['TopicsPerPage'];
		$time = time();
		
		if ($before == '-1')	//精华帖
			$sql_cond = " AND t.is_good='Y'";
		elseif ($before == '0')	//所有帖子
			$sql_cond = " ";
		else	
			$sql_cond = " AND (t.post_stamp+".$before.">".$time.")";
			
		//公告和置顶（ordertype 3-公告,2-置顶,1-普通）	增加 4-调查
		$sql = "SELECT t.*, u.".$this->db['UserName']." as username, u.".$this->db['NickName']." as nickname,
						u2.".$this->db['UserName']." as last_poster, u2.".$this->db['NickName']." as last_poster_nickname,
						p.post_time, p.poster_id, 
						p2.poll_id, p2.post_icon, p2.post_title,
				CASE WHEN
					t.ordertype>1
					AND (t.post_stamp+t.orderexpiry>".$time." 
					OR t.orderexpiry=0)
				THEN
					4294967294
				ELSE
					t.post_stamp
				END AS sort_order_fld
				
				FROM ".$this->db['Prefix']."topics t
		        LEFT JOIN ".$this->db['UserTable']." u ON t.topic_poster=u.".$this->db['UserId']."
		        LEFT JOIN ".$this->db['Prefix']."posts p ON t.topic_last_post_id=p.post_id
		        LEFT JOIN ".$this->db['UserTable']." u2 ON p.poster_id=u2.".$this->db['UserId']."
				LEFT JOIN ".$this->db['Prefix']."posts p2 ON t.root_post_id=p2.post_id 
		        WHERE t.forum_id=$forumid" . $sql_cond ."
		        ORDER BY sort_order_fld DESC, t.ordertype DESC, t.topic_last_post_id DESC 
		        LIMIT $start, ". $topics_per_page ."";
		//echo $sql;
//$sql="SELECT t.*, u.user_id as username, u.nickname as nickname, u2.user_id as last_poster, u2.nickname as last_poster_nickname, p.post_time, p.poster_id, p2.poll_id, p2.post_icon, p2.post_title, CASE WHEN t.ordertype>1 AND (t.post_stamp+t.orderexpiry>1299050069 OR t.orderexpiry=0) THEN 4294967294 ELSE t.post_stamp END AS sort_order_fld FROM forum_topics t LEFT JOIN user_main u ON t.topic_poster=u.sid LEFT JOIN forum_posts p ON t.topic_last_post_id=p.post_id LEFT JOIN user_main u2 ON p.poster_id=u2.sid LEFT JOIN forum_posts p2 ON t.root_post_id=p2.post_id WHERE t.forum_id=37 ORDER BY sort_order_fld DESC, t.ordertype DESC, t.topic_last_post_id DESC LIMIT 0, 25";
		$res = $conn->Execute($sql);
		
		$topicscount = $res->RecordCount();
		//var_dump($topicscount);exit;
		//生成中间的主要部分		
		if ($topicscount > 0) {
			while (!$res->EOF) {
				$list = array();
				
				$list['topic_id'] = $topicid = $res->fields['topic_id'];
				
				//回复
				$replys = $res->fields['topic_replies'];
				
				//td 1	是否有新帖子的或者是否锁定的图片
				$last_post_stamp = $res->fields['post_stamp'];
				if ($replys >= $this->option['HotThreshold']) {	//超过阀值，显示hot的图标
					if ($last_post_stamp > $LAST_VISIT_TIME && !empty($KF_USERNAME))
						$image = 'hot_red_folder.gif';
					else
						$image = 'hot_folder.gif';
				} else {
					if ($last_post_stamp > $LAST_VISIT_TIME && !empty($KF_USERNAME))
						$image = 'red_folder.gif';
					else
						$image = 'folder.gif';
				}
				if ($res->fields['topic_status'] == 1)
					$image = 'lock.gif';
				$list['status_image'] = $image;
				
				//td 2	显示帖子的图表
				$list['post_icon'] = $res->fields['post_icon'];

				//td 3	显示主题，如果多页显示分页
				$list['topic_title']  = $res->fields['post_title'];
				
				$pagination = '';
				$tmp_str = '';
				$topic_link = 'viewtopic.php?pTopicId='.$topicid.'&pForumId='.$forumid.'&pTopicStart='.$start.'&pBefore='.$before.'';
				if ($replys+1 > $posts_per_page) {
					$pagination .= '(<img src="'.$this->images.'multipage.gif" title="goto" alt="goto" border="0" />&nbsp;';
					
					$pagenr = 1;
					$skip_pages = 0;
					
					for ($x = 0; $x < $replys + 1; $x += $posts_per_page) {
						$last_page = (($x + $posts_per_page) >= $replys + 1);
						
						if ($x != 0)
							$tmp_str = '&pPostStart='.$x;

						if ($pagenr > 3 && $skip_pages != 1) {
							$pagination .= " ... ";
							$skip_pages = 1;
						}
	
						if ($skip_pages != 1 || $last_page) {
							if ($x != 0) 
								$pagination .= " ";
							$pagination .= '<a href="'.$topic_link.$tmp_str.'">'.$pagenr.'</a>';
						}
						
						$pagenr++;
					}
					
					$pagination .= ')';
				}
				
				$topic_link .= '&' . $replys;
				$list['topic_link'] = $topic_link;
				$list['pagination'] = $pagination;			

				//显示公告，置顶的信息
				$list['order_type'] = $res->fields['ordertype'];
				$list['order_expiry'] = $res->fields['orderexpiry'];
				$list['last_post_stamp'] = $last_post_stamp;
				
				$list['is_good']  = $res->fields['is_good'];		//显示精华帖的信息
				$list['attach_cnt'] = $res->fields['attach_cnt'];	//有附件的话显示图标
				
				//td 4	显示作者
				if ($res->fields['topic_poster'] == -1 ) {	//匿名用户发的帖子
					$list['author'] .= 'anonymous';
				} else {
					$list['author'] = '<a href="'.sprintf($this->_cms->user_info_url, $res->fields['username']).'" target="_blank">'.$res->fields['nickname'].'</a>';
				}
				
				//td 5	显示回复
				$list['replys'] = $replys;
	
				//td 6	显示浏览次数
				$list['topic_views'] = $res->fields['topic_views'];
				
				//td 7	显示最新帖子
				//获得最新帖子的信息
				$list['post_time'] = $res->fields['post_time'];	
				if ($res->fields['poster_id'] == -1 ) {	//匿名用户发的帖子
					$list['last_poster'] .= 'anonymous';
				} else {
					$list['last_poster'] = '<a href="'.sprintf($this->_cms->user_info_url, $res->fields['last_poster']).'" target="_blank">'.$res->fields['last_poster_nickname'].'</a>';
				}
				
				// 扩充新功能，投票
				$list['poll_id'] = $res->fields['poll_id'];
				
				// 保留移动主题的链接
				$moved_to = $res->fields['moved_to'];
				if ($moved_to) {
					$sql2 = "SELECT forum_id, forum_name FROM ".$this->db['Prefix']."forums WHERE forum_id=".$moved_to;
					$res2 = $conn->Execute($sql2);
					$list['moved_to_forum_name'] = $res2->fields['forum_name'];
					$list['moved_to_forum_id'] = $res2->fields['forum_id'];
					
					$sql2 = "SELECT topic_id FROM ".$this->db['Prefix']."posts WHERE post_id=".$res->fields['root_post_id'];
					$res2 = $conn->Execute($sql2);
					$list['topic_id'] = $res2->fields['topic_id'];
				}
				
				$res->MoveNext();
				$topic_list[] = $list;
			} //end while			
		}
		
		// 分页显示
		$sql = "SELECT COUNT(*) AS total FROM ".$this->db['Prefix']."topics t WHERE forum_id=".$forumid.$sql_cond;
		$res = $conn->Execute($sql);
		$all_topics = $res->fields['total'];
		
		$pages = $this->create_pager($start, $topics_per_page, $all_topics, 'viewforum.php?pForumId='.$forumid.'&pBefore='.$before.'&pTopicStart=');

		return array($topic_list, $pages);
	}

    /**
     * 生成跳转论坛的Select Box
     * 
     * @access  public 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer  $curr_forumid	当前所处的论坛id
     * @return  string
     */		
	function make_jumpbox (&$conn, $curr_forumid) {
		$sql = "SELECT cat_id, cat_title FROM ".$this->db['Prefix']."catagories	WHERE cat_parent=0 ORDER BY cat_order";
		$res = $conn->CacheExecute($sql);
		while (!$res->EOF) {
			$cat_id = $res->fields['cat_id'];
			$cat_title = $res->fields['cat_title'];
			
			$str .= '<option value="-1">'.$cat_title.'</option>'.CRLF;
			// add 2005年8月19日星期五
            $sql2 = "SELECT cat_id, cat_title FROM ".$this->db['Prefix']."catagories WHERE cat_parent='$cat_id' ORDER BY cat_order";
            
            if($m2 = $conn->CacheExecute($sql2)) {
                while (!$m2->EOF) {
                    $str .= '<option value="-1">&nbsp;&nbsp;|-' . $m2->fields['cat_title'] . '</option>' . CRLF;
                    
        			$subsql2 = "SELECT forum_id, forum_name	FROM ".$this->db['Prefix']."forums	WHERE cat_id=".$m2->fields['cat_id']." ORDER BY forum_id";
        			$subres2 = $conn->CacheExecute($subsql2);
        			$subres2 = $conn->Execute($subsql2);
        			while (!$subres2->EOF) {
        				$forumid = $subres2->fields['forum_id'];
        				$forum_name = $subres2->fields['forum_name'];
        				$forum_name = stripslashes($forum_name);
        				$str .= '<option value="'.$forumid.'"';
        				if ($curr_forumid == $forumid)
        					$str .= ' selected="selected"';
        				$str .= '>&nbsp;&nbsp;&nbsp;&nbsp;|- '.$forum_name.'</option>'.CRLF;
        					
        				$subres2->MoveNext();
        			}   

        			$m2->MoveNext();          
                }
            }
			
			
			$subsql = "SELECT forum_id, forum_name	FROM ".$this->db['Prefix']."forums	WHERE cat_id=".$cat_id." ORDER BY forum_id";
			$subres = $conn->CacheExecute($subsql);
			while (!$subres->EOF) {
				$forumid = $subres->fields['forum_id'];
				$forum_name = $subres->fields['forum_name'];
				$forum_name = stripslashes($forum_name);
				$str .= '<option value="'.$forumid.'"';
				if ($curr_forumid == $forumid)
					$str .= ' selected="selected"';
				$str .= '>&nbsp;&nbsp;|- '.$forum_name.'</option>'.CRLF;
					
				$subres->MoveNext();
			}
			
			$res->MoveNext();
		}
		
		return $str;
	}
	
    /**
     * 生成分页显示的数据
     * 
     * @access  public 
	 * @param   integer  $start	起始值
	 * @param   integer  $count	一页多少
	 * @param   integer  $total	记录总数
	 * @param   integer  $arg	链接
	 * @param   integer  $pager_count	显示多少页数然后就省略
     * @return  array	array(0=>'总页数', 1=>'分页显示的数据');
     */
	function create_pager($start, $count, $total, $arg, $pager_count='')
	{
		global $cfg_forum;
		
		if ( $total <= $count ) return;
		
		$cur_pg = ceil($start/$count);
		$ttl_pg = ceil($total/$count);
		if (!$pager_count)
			$pager_count = $cfg_forum["GENERAL_PAGER_COUNT"];
		
		$page_pager_data='';
		
		if ( $start-$count > -1 ) {
			$page_start = $start-$count;
			$page_first_url = $arg.'0';
			$page_prev_url = $arg.$page_start;
		
			$page_pager_data .= '<a href="'.$page_first_url.'">&lt;&lt;</a>&nbsp;<a href="'.$page_prev_url.'">&lt;</a>&nbsp;';
		}	
	
		$mid = ceil($pager_count /2);
		
		if( $ttl_pg > $pager_count) {
			if( ($mid+$cur_pg)>=$ttl_pg ) {
				$end = $ttl_pg;
				$mid += ($mid+$cur_pg)-$ttl_pg;
				$st = $cur_pg-$mid;
			}
			else if( ($cur_pg-$mid)<=0 ) {
				$st = 0;				
				$mid += $mid-$cur_pg;
				$end = $mid+$cur_pg;
			}
			else {
				$st = $cur_pg-$mid;
				$end = $mid+$cur_pg;
			}
			
			if( $st < 0 ) $start = 0;
			if( $end > $ttl_pg ) $end = $ttl_pg;
		}
		else {
			$end = $ttl_pg;
			$st = 0;
		}
		
		while ( $st<$end ) {
			if( $st != $cur_pg ) {
				$page_start = $st*$count;
				$st++;
				$page_page_url = $arg.$page_start;

				$page_pager_data .= '<a href="'.$page_page_url.'">'.$st.'</a>&nbsp;';
			}
			else {
				$st++;
				$page_pager_data .= $st.'&nbsp;';
			}	
		}
		
		$page_pager_data = substr($page_pager_data,0,strlen('&nbsp;')*-1);
			
		if ( ($start+$count)<$total ) { 
			$page_start = $start+$count;
			$page_start_2 = ($st-1)*$count;
			$page_next_url = $arg.$page_start;
			$page_last_url = $arg.$page_start_2;
			$page_pager_data .= '&nbsp;<a href="'.$page_next_url.'">&gt;</a>&nbsp;<a href="'.$page_last_url.'">&gt;&gt;</a>';
		}
		
		return array($ttl_pg,$page_pager_data);
	}
	
	/**
     * 通过id获取主题信息，返回数组
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param	integer	$topicid		主题id
     * @return	array
     */		
	 function get_topic_info_from_id (&$conn, $topicid) {
		
		$rtn = array();
		
		$sql = "SELECT post_title, topic_status, is_good FROM ".$this->db['Prefix']."topics t, ".$this->db['Prefix']."posts p WHERE t.topic_id=$topicid AND t.root_post_id=p.post_id";
		$res = $conn->Execute($sql);
		$rtn['Title']	= $res->fields['post_title'];
		$rtn['Status']	= $res->fields['topic_status'];
		$rtn['Good']	= $res->fields['is_good'];
		
		return $rtn;
	}

	/**
     * 更新主题的浏览数
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param	integer	$topicid		主题id
     * @return	void
     */		
	function update_topic_views (&$conn, $topicid) {
	
		$sql = "UPDATE ".$this->db['Prefix']."topics SET topic_views=topic_views+1 WHERE topic_id=$topicid";	
		$res = $conn->Execute($sql);
	}
	
    /**
     * 生成论坛viewtopic页的数据供smarty使用
     * 
     * @access  public 
     * @global  string	$KF_USERID	登录的用户id 
     * @global  array	$_POST		POST过来的变量 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer  $topicid	主题id
	 * @param   integer  $forumid	论坛id
	 * @param   integer  $before	显示多长时间以前的帖子
	 * @param   integer  $topic_start	主题分页显示的起始值	 
	 * @param   integer  $post_start	帖子分页显示的起始值	 
	 * @param   integer  $topic_status	主题的状态，是否锁定	 
     * @return  array
     */		
	function gen_data_viewtopic (&$conn, $topicid, $forumid, $before, $topic_start, $post_start, $topic_status) {
		global $KF_USERID, $_POST;

		//从全局变量取出
		$posts_per_page = $this->option['PostsPerPage'];
		$time = time();
		
		//生成分页显示
		$total = $this->get_total_posts($conn, $topicid, 'topic');
		$pages = $this->create_pager($post_start, $posts_per_page, $total, 'viewtopic.php?pTopicId='.$topicid.'&pForumId='.$forumid.'&pTopicStart='.$topic_start.'&pBefore='.$before.'pForumId='.$forumid.'&pBefore='.$before.'&pPostStart=');
		
		$sql = "SELECT p.*, pt.post_text, 
					u.".$this->db['NickName']." as nickname,
					u.".$this->db['UserFace']." as userface,
					u.".$this->db['UserName']." as username, 
					u.".$this->db['UserPosts']." as userposts, 
					u.".$this->db['UserRank']." as userrank, 
					u.".$this->db['UserSig']." as usersig 
				FROM ".$this->db['Prefix']."posts p, ".$this->db['Prefix']."posts_text pt 
				LEFT JOIN ".$this->db['UserTable']." u ON p.poster_id=u.".$this->db['UserId']."
				WHERE topic_id=$topicid AND p.post_id=pt.post_id
				ORDER BY post_id LIMIT $post_start, $posts_per_page";
		$res = $conn->Execute($sql);

		while (!$res->EOF) {
			$list = array();
			
			$postid = $list['post_id'] = $res->fields['post_id'];
			$posterid = $list['poster_id'] = $res->fields['poster_id'];
			
			//tr 1	【作者和主题信息】开始
			// td 1	作者信息开始
			if ($list['poster_id'] == -1 ) {	//匿名用户发的帖子
				$list['poster'] .= 'anonymous';
			} else {
				$list['poster'] = '<a href="'.sprintf($this->_cms->user_info_url, $res->fields['username']).'" target="_blank">'.$res->fields['nickname'].'</a>';
				$list['poster_link'] = sprintf($this->_cms->user_info_url, $res->fields['username']);
				// 用户头像
				$list['avatar'] = '<img src="'.$this->avatar_url.'/'.$res->fields['userface'].'" border="0" />';
				// rank
				$ranks = $this->get_user_rank($conn, $res->fields['userrank'], $res->fields['userposts']);
				if ($ranks) {
					$list['rank_text']	= $ranks[0];
					$list['rank_img']	= '<img src="'.$this->rank_url.'/'.$ranks[1].'" border="0" />';
				}	
				// 如果发帖者是该版版主
				$list['is_mod']	= $this->is_mod($conn, $forumid, $list['poster_id']);
				// 显示发帖数
				$list['user_posts'] = $res->fields['userposts'];
			}			
			// td 1	作者信息结束
			
			//td 2	主题内容开始
			$fStr .= '<td valign="top">'.CRLF;
			
			// 图标
			$list['post_icon'] = $res->fields['post_icon'];
			// 帖子时间
			$list['post_time'] = $res->fields['post_time'];
			// 帖子标题
			$list['post_title'] = $res->fields['post_title'];
				
			//获取附件的信息
			$list['attach'] = $this->get_post_attach($conn, $postid);
			//主要内容
			$message = forum_strip_slashes($res->fields['post_text']);
			//替换签名档
			$user_sig = $res->fields['usersig'];
			if (!$this->option['AllowHtml']) {
				$user_sig = htmlspecialchars($user_sig);
				$user_sig = preg_replace("#&lt;br&gt;#is", "<br />", $user_sig);
			}
			if (strrpos($message, "[addsig]")) {
				$message = substr($message, 0, -8);
				$list['sig'] = forum_strip_slashes(bbencode($user_sig, !$this->option['AllowHtml']));
			}
			$list['message'] .= $message;
			//td 2	主题内容结束
			//tr 1	【作者和主题信息】结束

			//tr 2	【操作栏】开始
			//td 2	编辑（删除在它的选项中）/引用按钮
			//引用按钮
			//是帖子的主人或版主，显示编辑按钮
			if ($KF_USERID == $posterid) {
				$list['can_edit'] = 1;
				$list['can_delete'] = 1;
				if ($this->is_first_post($conn, $topicid, $postid) && $total > 1) {
					$list['can_delete'] = 0;
				}
			}
			//是版主，显示查看IP按钮
			if ($this->is_mod ($conn, $forumid, $KF_USERID)) { 
				$list['can_edit'] = 1;
				$list['can_viewip'] = 1;
				$list['can_delete'] = 1;
			}	
			
			// 投票
			if ($res->fields['poll_id']) {
				$show_res=1;

				$poll_obj = new forum_poll();
				$poll_obj->get($conn, $res->fields['poll_id']);
				
				$sql = "SELECT id FROM ".$this->db['Prefix']."poll_opt_track WHERE poll_id=".$poll_obj->id." AND user_id=".intzero($KF_USERID);
				$res2 = $conn->Execute($sql);
	
				if ($KF_USERID && $_POST["pl_view"] != $poll_obj->id && $topic_status == '0' && !isset($res2->fields['id'])) $show_res=0;
				
				/* determine if poll is expired or reach max count */
				$sql = "SELECT sum(count) as count FROM ".$this->db['Prefix']."poll_opt WHERE poll_id=".$poll_obj->id." GROUP BY poll_id";
				$res2 = $conn->Execute($sql);
				$total_votes = $res2->fields['count'];
				if (!$show_res && $poll_obj->max_votes && $total_votes >= $poll_obj->max_votes) $show_res = 1;
				if (!$show_res && $poll_obj->expiry_date && ($poll_obj->creation_date + $poll_obj->expiry_date) <= time()) $show_res = 1;

				$opt = new forum_poll_opt();
				$opt->get_poll($conn, $poll_obj->id);
				$opt->reset_opt();
				$i=0;
				$poll_data='';
				while ($obj=$opt->next_opt()) {
					$i++;
					$tr_class = ($tr_class == 'tr1') ? 'tr2' : 'tr1';
					if ($show_res) {
						$length = ($obj->count) ? round($obj->count/$total_votes*100) : 0;
						$poll_data .= '<tr class="'.$tr_class.'"><td>'.$i.'.</td><td>'.$obj->name.'</td><td><img src="'.$this->images.'poll_pix.gif" alt="" height="10" width="'.$length.'" />('.$length.'%, '.$obj->count.')</td></tr>';
					}
					else 
						$poll_data .= '<tr class="'.$tr_class.'"><td>'.$i.'.</td><td colspan="2"><input type="radio" name="opt" value="'.$obj->id.'">&nbsp;&nbsp;'.$obj->name.'</td></tr>';
				}
				
				if (!$show_res) {
					if ($total_votes) $view_poll_results_button = '<input type="submit" class="btn" name="pl_res" value="查看结果">';
					$poll_buttons = '<tr class="tr3"><td colspan="3" align="right"><input type="submit" class="btn" name="pl_vote" value="投票">&nbsp;'.$view_poll_results_button.'</td></tr>';
				}
				
				$poll = '<form action="'.$GLOBALS['__POLL_ACTION_URL'].'" method="post" style="padding: 0px; margin: 0px;">
		<table border="0" cellspacing="1" cellpadding="2" class="common">
		<tr class="th1" align="left"><th nowrap colspan="3">'.$poll_obj->name.'&nbsp;<font size="-1">[票数：'.$total_votes.']</font></th></tr>
		'.$poll_data.'
		'.$poll_buttons.'
		</table><input type="hidden" name="pl_view" value="'.$poll_obj->id.'"></form>';
				
				$list['poll_str'] = $poll;
			}
			
			$res->MoveNext();
			$post_list[] = $list;
		}
	
		return array($post_list, $pages);
	}	

    /**
     * 获取用户等级
     * 
     * @access  public 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer  $userrank	用户等级id值
	 * @param   integer  $userposts	用户帖子数
     * @return  array
     */		
	function get_user_rank (&$conn, $userrank, $userposts) {

		$sql = "SELECT COUNT(*) AS total FROM ".$this->db['Prefix']."ranks";
		$res = $conn->Execute($sql);
		$count = $res->fields['total'];
		
		if ($count == 0 || $userposts < 0 || $userrank < 0) {	//还没有等级的定义
			return;
		} else {
			if ($userrank != 0)
				$sql = "SELECT rank_title, rank_image FROM ".$this->db['Prefix']."ranks WHERE rank_id=$userrank";
			else
				$sql = "SELECT rank_title, rank_image FROM ".$this->db['Prefix']."ranks WHERE rank_min <= $userposts AND rank_max >= $userposts AND rank_special=0";
			
			$res = $conn->Execute($sql);
			$ranktext = $res->fields['rank_title'];
			$rankimage = $res->fields['rank_image'];
			
			return array($ranktext, $rankimage);
		}
	}
	
    /**
     * 检查某个用户是不是某个论坛的版主
     * 
     * @access  public 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer  $forumid	论坛id
	 * @param   integer  $userid	用户id
     * @return  boolean
     */		 
	function is_mod (&$conn, $forumid, $userid) {

		if ($userid != '' && $userid != -1) {
			$sql = "SELECT user_id FROM ".$this->db['Prefix']."mods WHERE forum_id=$forumid AND user_id=$userid";	

			if(!$res = $conn->Execute($sql))
				return FALSE;
	
			if ($res->fields['user_id'] != '')
				return TRUE;
			else
				return FALSE;
		} else {
			return FALSE;
		}
	}
	
    /**
     * 由PostId获取附件信息
     * 
     * @access  public 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer  $postid	帖子id
     * @return  string
     */	
	function get_post_attach (&$conn, $postid) {
		
		$str = '';
		
		$sql = "SELECT file_actual_name, file_name, file_type, embed FROM ".$this->db['Prefix']."attach WHERE message_id=$postid";
		$res = $conn->Execute($sql);
		if ($res->fields['file_name'] != '') {
			if (ereg("image", $res->fields['file_type']) && $res->fields['embed'] == 1)	// 显示贴图
				$str .= '<img src="'.$this->attach_url . $res->fields['file_name'].'" border="0"/>';
			else	
				$str = '<img src="'.$this->images.'attachment.gif" border="0" align="middle" />附件：&nbsp;<a href="'.$this->attach_url . $res->fields['file_name'].'">'.$res->fields['file_actual_name'].'</a>';
		}
		return $str;	
	}

    /**
     * 由username获取用户级别
     * 
     * @access  public 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   string  $username	用户名
     * @return  integer	用户级别
     */		 
	function get_user_data (&$conn, $username) {

		$rtn = array();
		$sql = "SELECT ".$this->db['UserLevel']." as userlevel FROM ".$this->db['UserTable']." WHERE ".$this->db['UserName']."='$username'";
		$res = $conn->Execute($sql);
		if ($res) {
			$rtn['UserLevel'] = $res->fields['userlevel'];
		}
		
		return $rtn;
	}
	
	//=================================================================================================
	//	笑脸标记函数
	//=================================================================================================
	/**
	 * Changes :) to an <IMG> tag based on the smiles table in the database.
	 *
	 * Smilies must be either: 
	 * 	- at the start of the message.
	 * 	- at the start of a line.
	 * 	- preceded by a space or a period.
	 * This keeps them from breaking HTML code and BBCode.
     *
	 * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   string  $msg	需要替换的string
     * @return  string	替换后的string 
	 */
	function smile(&$conn, $msg) {

		$msg = ' ' . $msg;
		
		$sql = "SELECT * FROM ".$this->db['Prefix']."smiles ORDER BY vieworder";
		$res = $conn->CacheExecute($sql);
		while (!$res->EOF) {
			$code = $res->fields['code'];
			$img = $res->fields['img'];
			
			$code = preg_quote($code);
			$code = str_replace('/', '//', $code);
			$msg = preg_replace("/([\n\\ \\.])$code/s", '\1<IMG SRC="'.$this->smiles_url.$img.'">', $msg);
				
			$res->MoveNext();
		}
		
		// Remove padding, return the new string.
		$msg = substr($msg, 1);
		
		return($msg);
	}
	
	/**
	 * Changes a Smiliy <IMG> tag into its corresponding smile
     *
	 * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   string  $msg	需要替换的string
     * @return  string	替换后的string 
	 */
	function desmile(&$conn, $msg) {
		
		$sql = "SELECT * FROM ".$this->db['Prefix']."smiles ORDER BY vieworder";
		$res = $conn->CacheExecute($sql);
		while (!$res->EOF) {
			$code = $res->fields['code'];
			$img = $res->fields['img'];
			
			$msg = str_replace("<IMG SRC=\"".$this->smiles_url.$img."\">", $code, $msg);
			
			$res->MoveNext();
		}
		
	   return($msg);
	}
	
	/**
	 * 论坛笑脸标记列表
     *
	 * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   string  $limit	允许值：''->显示一个页面; 'Limit 15'->显示一行
     * @return  string
	 */	
	function list_forum_smiles (&$conn, $limit='') {
			
		$sql = "SELECT * FROM ".$this->db['Prefix']."smiles ORDER BY vieworder $limit";
		$res = $conn->CacheExecute($sql);
		
		$col_count = 5;
		$col_pos = -1;
		
		while (!$res->EOF) {
			$code = $res->fields['code'];
			$code = ($a=strpos($code, '~')) ? substr($code,0,$a) : $code;
			$img = $res->fields['img'];
			$descr = $res->fields['descr'];
			if ($limit == '') {
				if (++$col_pos > $col_count) {
					$sml_row .= '<tr valign="bottom"><td>'.$sml_entry.'</td></tr>';
					$sml_entry = '';
					$col_pos = 0;
				}	
				$sml_entry .= '<a href="javascript: insertParentTag(\' '.$code.' \',\'\');"><img src="'.$this->smiles_url.$img.'" border="0" title="'.$descr.'"></a>&nbsp;&nbsp;';
			} else {
				$sml_row .= '<a href="javascript: insertTag(document.pPostForm.pBody, \'\', \' '.$code.' \');"><img title="'.$descr.'" src="'.$this->smiles_url.$img.'" border="0"></a>&nbsp';	
			}
				
			$res->MoveNext();
		}
		
		return $sml_row;
	}

	/**
	 * 替换被禁止的词句
     *
	 * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   string  $msg	需要替换的string
     * @return  string	替换后的string 
	 */	 
	function censor_string(&$conn, $string) {
		
		$sql = "SELECT word, replacement FROM ".$this->db['Prefix']."words";
		$res = $conn->CacheExecute($sql);
		while (!$res->EOF) {		
			$word = $res->fields['word'];
			$replacement = $res->fields['replacement'];
			
			$string = eregi_replace("$word", "$replacement", $string);
			
			$res->MoveNext();
		}
		
		return($string);
	}	

	/**
	 * 上传文件，同时对上传的文件进行“文件大小”等的判断
     *
	 * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   array	$upload_file	PHP的$_FILES
	 * @param   boolean $is_embed	是否内嵌显示	 
     * @return  array
	 */	 	
	function upload_file (&$conn, $upload_file, $is_embed = null) {
		
		$rtn = array();

		//	获取上传文件的扩展名
		$ext = strtolower(substr(strrchr($upload_file['name'], '.'), 1));
		//	获取文件的Mime Type
		$rtn['MimeType'] = $this->get_mime_type($conn, $ext);
		$rtn['ActualName'] = $upload_file['name'];
		
		$prefix1 = date("Ym");
		$dest_dir1 = $this->attach_path . $prefix1;
		if (!file_exists($dest_dir1))
			mkdir ($dest_dir1, 0755);
		$prefix2 = date("d");
		$dest_dir2 = $dest_dir1 . '/' . $prefix2;
		if (!file_exists($dest_dir2))
			mkdir ($dest_dir2, 0755);
		
		$prefix = $prefix1 . '/' . $prefix2;
		$dest_file = time() . '-' . ($is_embed ? 'embed-' : '') . $upload_file['name'];
		$rtn['Name'] = $prefix . '/' . $dest_file;
		
		if ($upload_file['size'] > $this->option['AttachMax']) {
			$rtn['ErrMsg'] = 'attach_maxed';
		} else if (!move_uploaded_file($upload_file['tmp_name'], $dest_dir2 . '/' .$dest_file)) {
			$rtn['ErrMsg'] = 'attach_failed';
		} else {
			$rtn['ErrMsg'] = '';
		} //end if

		return $rtn;
	}	//end function upload_file	

	/**
	 * 通过文件扩展名获取文件的MIME Type
     *
	 * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   string $ext	文件扩展名
     * @return  string
	 */	
	function get_mime_type(&$conn, $ext) {
		
		$sql = "SELECT mime_type FROM ".$this->db['Prefix']."mime WHERE mime_ext='$ext'";
		$res = $conn->Execute($sql);
		
		$mime_type = $res->fields['mime_type'];
		if (empty($mime_type))
			$mime_type = 'application/octet-stream';
		
		return $mime_type;		
	}

	/**
	 * 新加主题
     *
     * @access  public 
     * @global  string	$KF_USERID	登录的用户id 
     * @global  array	$_POST		POST过来的变量 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer	$forumid	论坛id
	 * @param   string	$title	帖子的标题
	 * @param   string	$body	帖子的内容	 
	 * @param   array	$attach	函数upload_file的返回值
	 * @see		upload_file()
     * @return  void
	 */
	function add_topic (&$conn, $forumid, $title, $body, $attach) {
		global $KF_USERID;

		$poster_ip = $_SERVER['REMOTE_ADDR'];	//IP
		$time = date("Y-m-d H:i");	//当前时间
		$userid = !empty($KF_USERID) ? $KF_USERID : -1;	//用户Id，匿名用户Id等于-1
		$post_icon = $_POST['pIcon'] == 'no' ? Null : $_POST['pIcon'];
		$topic_notify = $_POST['pNotify'] == 'on' ? 1 : 0;
		$attach_embed = $_POST['pEmbed'] == 'on' ? 1 : 0;
		
		// 有附件上传
		if (!empty($attach['Name']))
			$attach_cnt = 1;
		else
			$attach_cnt = 0;
		
		if ($this->is_mod($conn, $forumid, $KF_USERID)) {	//版主
			$order_type = $_POST['pOrderType'];
			$order_expiry = $_POST['pOrderExpiry'];
		} else {
			$order_type = 1;
			$order_expiry = 0;
		}
		
		// 添加Topics 表中的记录	
		$sql = "INSERT INTO ".$this->db['Prefix']."topics (
					topic_poster, forum_id, topic_notify, post_stamp, attach_cnt, ordertype, orderexpiry) VALUES (
					$userid, $forumid, $topic_notify, ".time().", $attach_cnt, $order_type, $order_expiry)";
		$conn->Execute($sql);
		$topicid = $conn->Insert_ID();
		
		// 添加Posts 表中的记录	
		$poll_id = intzero($_POST['pl_id']);
		$sql = "INSERT INTO ".$this->db['Prefix']."posts (
					topic_id, forum_id, poster_id, post_time, poster_ip, post_icon, post_title, poll_id) VALUES (
					$topicid, $forumid, $userid, '$time', '$poster_ip', '$post_icon', '$title', $poll_id)";
		$conn->Execute($sql);
		$postid = $conn->Insert_ID();
		
		// 添加Posts text表中的记录，记录帖子内容
		$sql = "INSERT INTO ".$this->db['Prefix']."posts_text (post_id, post_text) VALUES ($postid, '$body')";
		$conn->Execute($sql);
		
		// 添加附件表中的记录
		if (!empty($attach['Name'])) {
			$sql = "INSERT INTO ".$this->db['Prefix']."attach (
						attach_id, message_id, user_id, file_type, file_name, file_actual_name, embed) VALUES (
						'', $postid, $userid, '$attach[MimeType]', '$attach[Name]', '$attach[ActualName]', $attach_embed)";
			$conn->Execute($sql);
		}
		
		// 更新操作
		// 更新主题的最后的帖子 和  root_post_id
		$sql = "UPDATE ".$this->db['Prefix']."topics SET topic_last_post_id=$postid, root_post_id=$postid WHERE topic_id=$topicid";
		$conn->Execute($sql);

		// 不是匿名用户更新发帖数
		if ($userid != -1) {
			$sql = "UPDATE ".$this->db['UserTable']." SET ".$this->db['UserPosts']."=".$this->db['UserPosts']."+1 WHERE ".$this->db['UserId']."=".$userid."";
			$conn->Execute($sql);
		}
	
		// 更新论坛表的记录
		$sql = "UPDATE ".$this->db['Prefix']."forums SET forum_posts=forum_posts+1, forum_topics=forum_topics+1, forum_last_post_id=$postid WHERE forum_id=$forumid";
		$conn->Execute($sql);
		
		//清除cache
		//$sql = sprintf($sCacheSqls['ForumForums'], '');
		$conn->CacheFlush();
		
		//Yangh 2003-3-24 13:11
		//使所有的论坛和主题同步
		//mForumSync('', 'all forums');
		//mForumSync('', 'all topics');
	}
	
	/**
	 * 新加帖子（回复）
     *
     * @access  public 
     * @global  string	$KF_USERID	登录的用户id 
     * @global  array	$_POST		POST过来的变量 
	 * @global  array	$cfg_forum	论坛的配置数组
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer	$forumid	论坛id
	 * @param   integer	$topicid	主题id
	 * @param   string	$title	帖子的标题
	 * @param   string	$body	帖子的内容	 
	 * @param   array	$attach	函数upload_file的返回值
	 * @see		upload_file()
     * @return  integer	新加帖子的id
	 */	
	function add_post (&$conn, $forumid, $topicid, $title, $body, $attach) {
		global $KF_USERID;
		global $cfg_forum;
		
		$poster_ip = $_SERVER['REMOTE_ADDR'];	//IP
		$time = date("Y-m-d H:i");	//当前时间
		$userid = !empty($KF_USERID) ? $KF_USERID : -1;	//用户Id，匿名用户Id等于-1
		$post_icon = $_POST['pIcon'] == 'no' ? Null : $_POST['pIcon'];
		//$topic_notify = $_POST['pNotify'] == 'on' ? 1 : 0;
		$attach_embed = $_POST['pEmbed'] == 'on' ? 1 : 0;
		
		// 添加Posts 表中的记录	
		$poll_id = intzero($_POST['pl_id']);
		$sql = "INSERT INTO ".$this->db['Prefix']."posts (
					topic_id, forum_id, poster_id, post_time, poster_ip, post_icon, post_title, poll_id) VALUES (
					$topicid, $forumid, $userid, '$time', '$poster_ip', '$post_icon', '$title', $poll_id)";
		$conn->Execute($sql);
		$postid = $conn->Insert_ID();
		
		// 添加Posts text表中的记录，记录帖子内容
		$sql = "INSERT INTO ".$this->db['Prefix']."posts_text (post_id, post_text) VALUES ($postid, '$body')";
		$conn->Execute($sql);
		
		// 添加附件表中的记录
		if (!empty($attach['Name'])) {
			$sql = "INSERT INTO ".$this->db['Prefix']."attach (
						attach_id, message_id, user_id, file_type, file_name, file_actual_name, embed) VALUES (
						'', $postid, $userid, '$attach[MimeType]', '$attach[Name]', '$attach[ActualName]', $attach_embed)";
			$conn->Execute($sql);
		}
		
		// 更新操作
		// 更新主题的最后的帖子 和 回复数 和 topic_time,post_stamp, attach_cnt
		//$sql = "UPDATE ".$this->db['Prefix']."topics SET topic_replies=topic_replies+1, topic_last_post_id=$postid, post_stamp='".time()."', attach_cnt=IF(attach_cnt=0,".$attach_cnt.",0) WHERE topic_id=$topicid";
		$sql = "UPDATE ".$this->db['Prefix']."topics SET topic_replies=topic_replies+1, topic_last_post_id=$postid, post_stamp='".time()."' WHERE topic_id=$topicid";
		$conn->Execute($sql);

		// 不是匿名用户更新发帖数
		if ($userid != -1) {
			$sql = "UPDATE ".$this->db['UserTable']." SET ".$this->db['UserPosts']."=".$this->db['UserPosts']."+1 WHERE ".$this->db['UserId']."=".$userid."";
			$conn->Execute($sql);
		}
		// 更新论坛表的记录
		$sql = "UPDATE ".$this->db['Prefix']."forums SET forum_posts=forum_posts+1, forum_last_post_id=$postid WHERE forum_id=$forumid";
		$conn->Execute($sql);
		
		//清除cache
		//$sql = sprintf($sCacheSqls['ForumForums'], '');
		$conn->CacheFlush();
		
		// notified 的topic，发信		
		// 匿名用户的没有notify
		$sql = "SELECT t.topic_notify,
				u.".$this->db['UserEmail']." as user_email, 
				u.".$this->db['NickName']." as nickname, 
				u.".$this->db['UserId']." as user_id  
				FROM ".$this->db['Prefix']."topics t, ".$this->db['UserTable']." u 
				WHERE t.topic_id=".$topicid." AND t.topic_poster=u.".$this->db['UserId']." AND topic_poster!='-1'";
		$res = $conn->Execute($sql);
		if($res->fields['topic_notify'] == 1 && $res->fields['user_id'] != $KF_USERID) {
			$subject = $cfg_forum['NOTIFY_SUBJECT'];
			$message = $cfg_forum['NOTIFY_BODY'];
			$email_from = $cfg_forum['NOTIFY_FROM'];
			
			$username = $res->fields['nickname'];
			$goto_url = $this->url . 'viewtopic.php?pForumId='.$forumid.'&pTopicId='.$topicid.'&pGoto='.$postid;
			
			$forum_url= $this->url;
			$sitename = $this->_cms->arguments['site_name']; 
			eval("\$message =\"$message\";");
			mail($res->fields[user_email], $subject, $message, "From: $email_from\r\nX-Mailer: ".$this->app_name. " ".$this->app_version);
		}
		
		return $postid;
	}	
	
	/**
	 * 生成引言模式的回复的内容
     *
     * @access  public 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer	$postid		帖子id
     * @return  array
	 */
	function make_quoted_reply_body (&$conn, $postid) {
		
		$rtn = array();
		
		$sql = "SELECT poster_id FROM ".$this->db['Prefix']."posts WHERE post_id=$postid";
		$res = $conn->Execute($sql);
		
		if ($res->RecordCount() == 0) {
			$rtn['Error'] = 1;
		
		} else  {
			$poster_id = $res->fields['poster_id'];
			
			if ($poster_id == -1) {	//匿名用户的帖子
				$sql = "SELECT pt.post_text, p.post_time 
						FROM ".$this->db['Prefix']."posts p, ".$this->db['Prefix']."posts_text pt 
						WHERE p.post_id=$postid AND pt.post_id = p.post_id";
				$poster = 'anonymous';
			} else {
				$sql = "SELECT pt.post_text, p.post_time, u.".$this->db['UserNickname']." as usernickname, u.".$this->db['UserName']." as username
						FROM ".$this->db['Prefix']."posts p, ".$this->db['Prefix']."posts_text pt,  ".$this->db['UserTable']." u
						WHERE p.post_id=$postid AND pt.post_id=p.post_id AND p.poster_id=u.".$this->db['UserId']."";
			}

			$res = $conn->Execute($sql);
			$post_text = $res->fields['post_text'];
			$post_time = $res->fields['post_time'];
			if ($poster_id != -1) 
				$poster = $res->fields['usernickname'];

			//内容的decode
			$post_text = $this->desmile($conn, $post_text);
			$post_text = str_replace("<BR>", "\n", $post_text);
			$post_text = stripslashes($post_text);
			$post_text = bbdecode($post_text);
			$post_text = undo_make_clickable($post_text);
			$post_text = str_replace("[addsig]", "", $post_text);
			
			$msg = "[quote]\n".$post_time."，".$poster." 写道：\n".$post_text."\n[/quote]\n";
			
			$rtn['QuoteText'] = $msg;
			$rtn['Error'] = 0;
		}
		
		return $rtn;
	}

	/**
	 * 判断某个帖子是不是某个主题的第一个帖子
     *
     * @access  public 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer	$topicid	主题id
	 * @param   integer	$postid		帖子id
     * @return  boolean
	 */
	function is_first_post(&$conn, $topicid, $postid) {
		
		$sql = "SELECT root_post_id FROM ".$this->db['Prefix']."topics WHERE topic_id=$topicid";
		$res = $conn->Execute($sql);
		
		if ($res->fields['root_post_id'] == $postid)
			return TRUE;
		
		return FALSE;
		
	}

	/**
     * 通过id获取帖子信息，返回数组
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param	integer	$postid		帖子id
     * @return	array
     */			
	function get_post_info_from_id (&$conn, $postid) {
		
		$sql = "SELECT topic_id, forum_id, poster_id, post_time, post_icon, poll_id, post_title FROM ".$this->db['Prefix']."posts WHERE post_id=$postid";
		$res = $conn->Execute($sql);
		$rtn = $res->GetArray();
		
		return $rtn[0];
	}

	/**
	 * 获取编辑帖子时的页面的内容
     *
     * @access  public 
     * @global  string	$KF_USERID	登录的用户id 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer	$forumid	论坛id
	 * @param   integer	$topicid	主题id
	 * @param   integer	$postid		帖子id	 
	 * @param   array	$posts		函数get_post_info_from_id的返回值
	 * @see		get_post_info_from_id()
     * @return  array
	 */	
	function get_edit_content(&$conn, $forumid, $topicid, $postid, $posts) {
		global $KF_USERID;
		
		$rtn = array();
		
		if ($posts['poster_id'] != -1)
			$sql = "SELECT pt.post_text, t.ordertype, t.orderexpiry, t.topic_notify,  
						u.".$this->db['NickName']." as nickname, 
						u.".$this->db['UserId']." as userid 
					FROM ".$this->db['Prefix']."posts p, ".$this->db['Prefix']."topics t, ".$this->db['Prefix']."posts_text pt, ".$this->db['UserTable']." u 
					WHERE (p.post_id=$postid) 
					AND (pt.post_id=p.post_id)
					AND (p.topic_id=t.topic_id)
					AND (p.poster_id=u.".$this->db['UserId'].")";
		else
			$sql = "SELECT pt.post_text, t.ordertype, t.orderexpiry, t.topic_notify 
					FROM ".$this->db['Prefix']."posts p, ".$this->db['Prefix']."topics t, ".$this->db['Prefix']."posts_text pt
					WHERE (p.post_id=$postid) 
					AND (pt.post_id=p.post_id)
					AND (p.topic_id=t.topic_id)";
		$res = $conn->Execute($sql);
		$rtn['Notified'] = $res->fields['topic_notify'];
		
		// 生成body部分
		$message = $res->fields['post_text'];	//body部分的内容
		//$fPosterSig = $res->fields['sig'];	//签名档
		if(eregi("\[addsig]$", $message)) {
			$add_sig = 1;
			$message = substr($message, 0, -8);
		} else {
			$add_sig = 0;
		}
		$rtn['AddSig'] = $add_sig;
		
		//$message = eregi_replace("\[addsig]$", "<br /><br /><hr noshade=\"noshade\" size=\"1\" width=\"50%\" align=\"left\">" . $fPosterSig, $message);
		$message = str_replace("<BR>", "\n", $message);
		$message = stripslashes($message);
		$message = $this->desmile($conn, $message);
		$message = bbdecode($message);
		$message = undo_make_clickable($message);
		$message = undo_html_specialchars($message);

		// Special handling for </textarea> tags in the message, which can break the editing form..
		$message = preg_replace('#</textarea>#si', '&lt;/TEXTAREA&gt;', $message);
		$rtn['Body'] = $message;
		
		// 如果编辑的帖子是topic
		$rtn['IsFirst'] = $this->is_first_post($conn, $topicid, $postid);
		if ($rtn['IsFirst'] && $this->is_mod($conn, $forumid, $KF_USERID)) {
			// 是否显示版主选项
			$rtn['ShowModTools'] = 1;
			$rtn['TopicOrder'] = array('Type' => $res->fields['ordertype'],
				'Expiry' => $res->fields['orderexpiry'],
			);
		}
		
		// 附件
		$sql = "SELECT attach_id, file_actual_name from ".$this->db['Prefix']."attach WHERE message_id=".$postid;
		$res = $conn->Execute($sql);
		if ($res) {
			$rtn['Attach']['Id'] = $res->fields['attach_id'];
			$rtn['Attach']['Name'] = $res->fields['file_actual_name'];
		}	
		
		return $rtn;	
	}

	/**
	 * 更新帖子或者是删除帖子（或主题）
     *
     * @access  public
     * @global  array	$_POST		POST过来的变量      
	 * @global  string	$KF_USERID	登录的用户id 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer	$forumid	论坛id
	 * @param   integer	$topicid	主题id
	 * @param   integer	$postid		帖子id
	 * @param   string	$title		帖子的标题
	 * @param   string	$body		帖子的内容	 
	 * @param   array	$postinfo	函数get_post_info_from_id的返回值
	 * @param   boolean	$delete		是否删除操作
	 * @see		get_post_info_from_id()
     * @return  boolean		是否是删除一个主题
	 */	
	function udpate_post(&$conn, $forumid, $topicid, $postid, $title, $body, $attach, $postinfo, $delete) {
		global $_POST, $KF_USERID;
		
		$is_firs_post = $this->is_first_post($conn, $topicid, $postid);
		if ($delete == 1) {
			$is_delete = 1;
			if ($is_firs_post) {
				if ($this->is_mod($conn, $forumid, $KF_USERID) || $this->get_total_posts($conn, $topicid, 'topic') == 1)
					$is_delete = 1;
				else 
					$is_delete = 0;
			}
		}
		
		if ($is_delete == 1) {	//删除的checkbox选中
			if (!$is_firs_post) {	//删除帖子(post)
				$last_post_in_thread = $this->get_last_post($conn, $topicid);
				$this->delete_post($conn, $forumid, $postid);

				//更新topic 的 lastpost的时间
				if ($last_post_in_thread == $postinfo['PostTime']) {
					$topic_time_fixed = $this->get_last_post($conn, $topicid);
					$last_post_timestamp = strtotime($topic_time_fixed);
					
					$sql = "UPDATE ".$this->db['Prefix']."topics SET post_stamp=".$last_post_timestamp." WHERE topic_id=$topicid";
					$conn->Execute($sql);
				}
				
				//同步更新
				$this->sync($conn, $forumid, 'forum');
				$this->sync($conn, $topicid, 'topic');
				
			} else {	//删除主题(topic)
				
				$is_delete_topic = TRUE;
				
				$this->delete_topic($conn, $forumid, $topicid);
				$this->sync($conn, $forumid, 'forum');//同步更新
			}
			
		} else {	//编辑帖子提交
			
			$time = date("Y-m-d H:i");	//当前时间
			$userid = !empty($KF_USERID) ? $KF_USERID : -1;	//用户Id，匿名用户Id等于-1
			$post_icon = $_POST['pIcon'] == 'no' ? Null : $_POST['pIcon'];
			$topic_notify = $_POST['pNotify'] == 'on' ? 1 : 0;
			$attach_embed = $_POST['pEmbed'] == 'on' ? 1 : 0;
			// 有附件上传
			// 添加附件表中的记录
			if (!empty($attach['Name'])) {
				$sql = "INSERT INTO ".$this->db['Prefix']."attach (
							attach_id, message_id, user_id, file_type, file_name, file_actual_name, embed) VALUES (
							'', $postid, $userid, '$attach[MimeType]', '$attach[Name]', '$attach[ActualName]', $attach_embed)";
				$conn->Execute($sql);
				// 更新主题的attach_cnt
				$sql = "UPDATE ".$this->db['Prefix']."topics SET attach_cnt=1 WHERE topic_id=$topicid";
				$conn->Execute($sql);
			}

			$poll_id = intzero($_POST['pl_id']);
			//更新posts_text中的text记录
			$sql = "UPDATE ".$this->db['Prefix']."posts_text SET post_text='$body' WHERE post_id=$postid";
			$conn->Execute($sql);
			//更新posts中的icon记录
			$sql = "UPDATE ".$this->db['Prefix']."posts SET post_title='$title', post_icon='$post_icon', poll_id=".$poll_id." WHERE topic_id=$topicid AND post_id=$postid";
			$res = $conn->Execute($sql);
			
			// 删除附件
			if ($_POST['pDeleteAttach'] == 'on' AND $_POST['pAttachId']) {
				$this->delete_attach($conn, $postid);
				if ($is_firs_post) {
					// 更新主题的attach_cnt
					$sql = "UPDATE ".$this->db['Prefix']."topics SET attach_cnt=0 WHERE topic_id=$topicid";
					$conn->Execute($sql);
				}
			}
			
			// 更新topic的内容
			if ($is_firs_post) {
				$sql = "UPDATE ".$this->db['Prefix']."topics SET topic_notify=".$topic_notify;
				if ($this->is_mod($conn, $forumid, $KF_USERID)) {
					$sql .= ", ordertype=".$_POST['pOrderType'].", orderexpiry=".$_POST['pOrderExpiry'];
				}					
				$sql .= " WHERE topic_id=$topicid";
				$conn->Execute($sql);
			}
		}
		
		return $is_delete_topic;
	}	

	/**
	 * 删除帖子(post)	
     *
     * @access  public
	 * @global  string	$KF_USERID	登录的用户id 
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer	$forumid	论坛id
	 * @param   integer	$postid		帖子id
	 * @param   boolean	$is_delete_topic	删除主题时为真
     * @return  void
	 */	
	function delete_post (&$conn, $forumid, $postid, $is_delete_topic=false) {
		include_once($this->path . 'include/forum.poll.php');
		global $KF_USERID;
		
		$sql = "SELECT poster_id, poll_id FROM ".$this->db['Prefix']."posts WHERE post_id=$postid";
		$res = $conn->Execute($sql);
		$poster_id = $res->fields['poster_id'];
		if ($poster_id == '')
			$this->error_die('arg_error');
		//update user_posts -1 		
		if ($poster_id != -1) {
			$sql = "UPDATE ".$this->db['UserTable']." SET ".$this->db['UserPosts']."=".$this->db['UserPosts']."-1 WHERE ".$this->db['UserId']."=".$poster_id."";
			$conn->Execute($sql);
		}
		
		$pl_id = $res->fields['poll_id'];
		// 删除投票
		if($pl_id > 0) {
			$poll = new forum_poll();
			$poll->get($conn, $pl_id);
			if ($this->is_mod($conn, $forumid, $KF_USERID) || $poll->owner == $KF_USERID) {
				$poll->delete($conn);
			}	
			$pl_id = 0;
			unset($poll);
		}
		
		if (!$is_delete_topic) {	// 删除主题时不通过循环来删除，通过topic_id和条件语句OR来删除
			//delete posts
			$sql = "DELETE FROM ".$this->db['Prefix']."posts WHERE post_id=$postid";
			$conn->Execute($sql);

			//delete posts text
			$sql = "DELETE FROM ".$this->db['Prefix']."posts_text WHERE post_id=$postid";
			$conn->Execute($sql);
		}
		//delete attach
		$this->delete_attach($conn, $postid);
	}	

	/**
	 * 删除主题（topic）
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer	$forumid	论坛id
	 * @param   integer	$topicid	主题id
     * @return  void
	 */	 	
	function delete_topic (&$conn, $forumid, $topicid) {
		
		$sql = "SELECT post_id FROM ".$this->db['Prefix']."posts WHERE topic_id=$topicid";
		$res = $conn->Execute($sql);
		while (!$res->EOF) {
			$postid = $res->fields['post_id'];
			$posts_to_remove[] = $postid;
			$this->delete_post($conn, $forumid, $postid, true);	//更新 user_posts 和 删除附件的文件及记录	
			
			$res->MoveNext();
		}

		//删除posts_text中的记录
		$sql = "DELETE FROM ".$this->db['Prefix']."posts_text WHERE ";
		for($x = 0; $x < count($posts_to_remove); $x++) {
			if ($set)
				$sql .= " OR ";
			$sql .= "post_id=".$posts_to_remove[$x];
			$set = TRUE;
		}
		$conn->Execute($sql);

		//删除posts中的记录
		$sql = "DELETE FROM ".$this->db['Prefix']."posts WHERE topic_id=$topicid";
		$res = $conn->Execute($sql);
		
		//删除topic中的记录	
		$sql = "DELETE FROM ".$this->db['Prefix']."topics WHERE topic_id=$topicid";
		$res = $conn->Execute($sql);
	}
	
	/**
	 * 删除附件
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param   integer	$postid		帖子id
     * @return  void
	 */		
	function delete_attach($conn, $postid) {
		
		$sql = "SELECT file_name FROM ".$this->db['Prefix']."attach WHERE message_id=".$postid;
		$res = $conn->Execute($sql);
		$attach_file = $res->fields['file_name'];
		if ($attach_file != '') {
			 if (file_exists($this->attach_path . $attach_file))
				unlink($this->attach_path . $attach_file);
		}
		$sql = "DELETE FROM ".$this->db['Prefix']."attach WHERE message_id=".$postid;
		$conn->Execute($sql);
	}
	
	/**
     * 获取某个主题的最新帖子的时间
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param	integer	$id		主题id
     * @return	integer
     */	 
	function get_last_post(&$conn, $id) {
		
		$sql = "SELECT p.post_time FROM ".$this->db['Prefix']."posts p WHERE p.topic_id=$id ORDER BY post_time DESC LIMIT 1";
		$res = $conn->Execute($sql);	
		return $res->fields['post_time'];
	}

	/**
     * 同步论坛（或主题）的帖子数，主题数，最新帖子等信息
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$id		可能的id值
	 * @param	string	$type	允许的值：forum | topic | all forums | all topics
     * @return	integer
     */	
	function sync (&$conn, $id, $type) {

		switch ($type){
			case 'forum':
				//论坛的最新帖子
				$sql = "SELECT max(post_id) AS last_post FROM ".$this->db['Prefix']."posts WHERE forum_id=$id";
				$res = $conn->Execute($sql);
				$last_post = $res->fields['last_post'];
				if ($last_post == '')
					$last_post = 0;	
				
				//论坛的帖子数
				$sql = "SELECT count(*) AS total FROM ".$this->db['Prefix']."posts WHERE forum_id=$id";
				$res = $conn->Execute($sql);
				$total_posts = $res->fields['total'];
				
				//论坛的主题数
				$sql = "SELECT count(*) AS total FROM ".$this->db['Prefix']."topics WHERE forum_id=$id AND moved_to=0";
				$res = $conn->Execute($sql);
				$total_topics = $res->fields['total'];
				
				//更新forum表
				$sql = "UPDATE ".$this->db['Prefix']."forums SET forum_last_post_id=$last_post, forum_posts=$total_posts, forum_topics=$total_topics WHERE forum_id=$id";
				$conn->Execute($sql);
				
				break;
			
			case 'topic':
				//主题的最新帖子
				$sql = "SELECT max(post_id) AS last_post FROM ".$this->db['Prefix']."posts WHERE topic_id=$id";
				$res = $conn->Execute($sql);
				$last_post = $res->fields['last_post'];
				if ($last_post == '')
					$last_post = 0;		
									
				//主题的帖子数
				$sql = "SELECT count(post_id) AS total FROM ".$this->db['Prefix']."posts WHERE topic_id=$id";
				$res = $conn->Execute($sql);
				$total_posts = $res->fields['total'];
				
				$total_posts -= 1;

				$sql = "UPDATE ".$this->db['Prefix']."topics SET topic_replies=$total_posts, topic_last_post_id=$last_post WHERE topic_id=$id";
				$conn->Execute($sql);
			
				break;
				
			case 'all forums':
				$sql = "SELECT forum_id FROM ".$this->db['Prefix']."forums";
				$res = $conn->Execute($sql);
				while (!$res->EOF) {
					$forumid = $res->fields['forum_id'];
					$this->sync($conn, $forumid, 'forum');
				
					$res->MoveNext();
				}
			
				break;
			
			case 'all topics':
				$sql = "SELECT topic_id FROM ".$this->db['Prefix']."topics";
				$res = $conn->Execute($sql);
				while (!$res->EOF) {
					$topicid = $res->fields['topic_id'];
					$this->sync($conn, $topicid, 'topic');
				
					$res->MoveNext();
				}
				
				break;
		} //end switch
		
		//清除cache
		$conn->CacheFlush();
	}
	
	/**
     * 生成图标列表(editpost.php)
     * 
     * @access	public
	 * @param	string	$path	图标的URL
	 * @param	string	$icon	选中的图标
     * @return	string
     */	
	 function edit_post_icons ($path, $icon='') {

		$str .= '
			<table border="0" cellspacing="0" cellpadding="2">
			<tr><td colspan="9"><input type="radio" name="pIcon" value="no"';
		if ($icon == '')
			$str .= ' checked="checked"';
		 $str .= '/>没有图标</td></tr>
			<tr><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon1.gif"';
		if ($icon == 'icon1.gif')
			$str .= ' checked="checked"';
		$str .= ' /><img src="'.$path.'icon1.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon10.gif"';
		if ($icon == 'icon10.gif')
			$str .= ' checked="checked"';
		$str .= ' /><img src="'.$path.'icon10.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon11.gif"';
		if ($icon == 'icon11.gif')
			$str .= ' checked="checked"';				  
		$str .= ' /><img src="'.$path.'icon11.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon12.gif"';
		if ($icon == 'icon12.gif')
			$str .= ' checked="checked"';				   
		$str .= ' /><img src="'.$path.'icon12.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon13.gif"';
		if ($icon == 'icon13.gif')
			$str .= ' checked="checked"';				    
		$str .= ' /><img src="'.$path.'icon13.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon14.gif"';
		if ($icon == 'icon14.gif')
			$str .= ' checked="checked"';				  
		$str .= ' /><img src="'.$path.'icon14.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon2.gif"';
		if ($icon == 'icon2.gif')
			$str .= ' checked="checked"';				  
		$str .= ' /><img src="'.$path.'icon2.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon3.gif"';
		if ($icon == 'icon3.gif')
			$str .= ' checked="checked"';				  
		$str .= ' /><img src="'.$path.'icon3.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon4.gif"';
		if ($icon == 'icon4.gif')
			$str .= ' checked="checked"';				  
		$str .= ' /><img src="'.$path.'icon4.gif" /></td></tr><tr><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon5.gif"';
		if ($icon == 'icon5.gif')
			$str .= ' checked="checked"';				  
		$str .= ' /><img src="'.$path.'icon5.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon6.gif"';
		if ($icon == 'icon6.gif')
			$str .= ' checked="checked"';				  
		$str .= ' /><img src="'.$path.'icon6.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon7.gif"';
		if ($icon == 'icon7.gif')
			$str .= ' checked="checked"';				  
		$str .= ' /><img src="'.$path.'icon7.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon8.gif"';
		if ($icon == 'icon8.gif')
			$str .= ' checked="checked"';				  
		$str .= ' /><img src="'.$path.'icon8.gif" /></td><td nowrap="nowrap" valign="middle"><input type="radio" name="pIcon" value="icon9.gif"';
		if ($icon == 'icon9.gif')
			$str .= ' checked="checked"';				  
		$str .= ' /><img src="'.$path.'icon9.gif" /></td></tr>
			</table>
		';
		
		return $str;
	}

	/**
     * faq中笑脸标记的说明(faq.php)
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return	string
     */		 
	function faq_smile_list (&$conn) {
		
		$sql = "SELECT * FROM ".$this->db['Prefix']."smiles	ORDER BY vieworder";
		$res = $conn->CacheExecute($sql);
 		
		while (!$res->EOF) {
		
			$code = $res->fields['code'];
			$img = $res->fields['img'];
			
			$str .= '<tr><td align="center">'.$code.'</td>';
			$str .= '<td align="center"><img src="'.$this->smiles_url.$img.'" border="0"></td></tr>'.CRLF;
				
			$res->MoveNext();
		}
		
		return $str;
	}
	
	/**
     * faq中的等级的说明(faq.php)
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return	string
     */		 
	function faq_rank_list (&$conn) {
		
		$sql = "SELECT * FROM ".$this->db['Prefix']."ranks WHERE rank_special=0	ORDER BY rank_id";
		$res = $conn->CacheExecute($sql);
 		
		while (!$res->EOF) {
			$str .= '<tr><td align="center">'.$res->fields['rank_title'].'</td>';
			$str .= '<td align="center">'.$res->fields['rank_min'].'</td>';
			$str .= '<td align="center">'.$res->fields['rank_max'].'</td>';
			$str .= '<td align="center"><img src="'.$this->rank_url.$res->fields['rank_image'].'" border="0"></td>';
			$str .= '</tr>'.CRLF;
				
			$res->MoveNext();
		}
		
		return $str;
	}

	/**
     * 生成论坛列表(search.php)
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return	string
     */		 
	function search_forum_list (&$conn) {
		
		//私有论坛不搜
		$sql = "SELECT forum_id, forum_name FROM ".$this->db['Prefix']."forums WHERE forum_type!=1 ORDER BY forum_id";
		$res = $conn->CacheExecute($sql);
		
		if ($res->RecordCount() > 0) {
			while (!$res->EOF) {
				$str .= '<option value="'.$res->fields['forum_id'].'">'.$res->fields['forum_name'].'</option>'.CRLF;
				$res->MoveNext();
			}
		} else {
			$str .= '<option value="-1">没有论坛！</option>'.CRLF;
		}
	
		return $str;
	}

	/**
     * 搜索论坛的操作
     * 
     * @access	public
	 * @global  array	$cfg_forum	论坛的配置数组	 
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$start	分页显示的起始值
     * @return	array
     */		 
	function search (&$conn, $start) {	
		global $cfg_forum;
		
		//todo:加上可以搜索匿名用户发的文章；
		
		$topics_per_page = $cfg_forum['SEARCH_RESULT_PAGES'];
		//$query is the basis of the query                                                            
		//$addquery is all the additional search fields - necessary because of the WHERE clause in SQL	
		

		$query = "SELECT u.".$this->db['NickName']." as nickname, u.".$this->db['UserName']." as username, u.".$this->db['UserId']." as userid,   
						f.forum_id, f.forum_name,
						p.topic_id, p.post_time, p.post_title, p.post_id
					FROM ".$this->db['Prefix']."posts p,
						".$this->db['Prefix']."posts_text pt,
						".$this->db['UserTable']." u,
						".$this->db['Prefix']."forums f,
						".$this->db['Prefix']."topics t";
		
		//获取变量
		/*
		$term = $_POST['term'];
		$addterms = $_POST['addterms'];
		$forum = $_POST['forum'];
		$search_username = $_POST['search_username'];
		$searchboth = $_POST['searchboth'];
		$sortby = $_POST['sortby'];
		*/
		$term = $GLOBALS['term'];
		$addterms = $GLOBALS['addterms'];
		$forum = $GLOBALS['forum'];
		$search_username = $GLOBALS['search_username'];
		$searchboth = $GLOBALS['searchboth'];
		$sortby = $GLOBALS['sortby'];
		
		if (empty($term) && empty($search_username))
			$this->error_die('no_keyword');
		if (strlen($term) < 3)
			$this->error_die('keyword_too_short');
		
		//关键词
		if(isset($term) && $term != "")
		{
			$terms = split(" ",addslashes($term));				// Get all the words into an array
			$addquery .= "(pt.post_text LIKE '%$terms[0]%'";//正文
			$subquery .= "(p.post_title LIKE '%$terms[0]%'"; //主题
		
			//检索结果中应包含上述的
			//所有关键词(and)　 至少一个关键词(or)
			if($addterms=="any")					// AND/OR relates to the ANY or ALL on Search Page
				$andor = "OR";
			else
				$andor = "AND";

			$size = sizeof($terms);
			for($i=1; $i<$size; $i++) {
				$addquery.=" $andor pt.post_text LIKE '%$terms[$i]%'";
				$subquery.=" $andor p.post_title LIKE '%$terms[$i]%'";
			}
			$addquery.=")";
			$subquery.=")";
		}
		
		//查找哪个论坛
		if(isset($forum) && $forum!="all")
		{
			if(isset($addquery)) {
				$addquery .= " AND ";
				$subquery .= " AND ";
			}
		
			$addquery .=" p.forum_id=$forum";
			$subquery .=" p.forum_id=$forum";
		}
		
		//查找作者
		if(isset($search_username) && $search_username!="") {
			$search_username = addslashes($search_username);
			$temp_sql = "SELECT ".$this->db['UserId']." AS userid
						FROM ".$this->db['UserTable']." 
						WHERE ".$this->db['NickName']."='$search_username'";
			$res = $conn->Execute($temp_sql);
			$userid = $res->fields['userid'];
			
			if ($userid == '')
				$this->error_die('user_not_exist');
			
			if(isset($addquery)) {
				
				$addquery.=" AND p.poster_id=$userid AND u.".$this->db['NickName']."='$search_username'";
				$subquery.=" AND p.poster_id=$userid AND u.".$this->db['NickName']."='$search_username'";
			}
			else {
				$addquery.=" p.poster_id=$userid AND u.".$this->db['NickName']."='$search_username'";
				$subquery.=" p.poster_id=$userid AND u.".$this->db['NickName']."='$search_username'";
			}
		}
		
		//搜索范围
		if(isset($addquery)) {
			switch ($searchboth) {
				case "both" :
					$query .= " WHERE ( $subquery OR $addquery ) AND ";
					break;
				case "title" :
					$query .= " WHERE ( $subquery ) AND ";
					break;
				case "text" :
					$query .= " WHERE ( $addquery ) AND ";
					break;
			}
		}
		else
		{
			$query.=" WHERE ";
		}
		
		//sql语句的结束
		$query .= " p.post_id = pt.post_id
					AND p.topic_id = t.topic_id
					AND p.forum_id = f.forum_id
					AND p.poster_id = u.".$this->db['UserId']."
					AND f.forum_type != 1";
		
		//  100100 bartvb  Uncomment the following GROUP BY line to show matching topics instead of all matching posts.
		//   $query .= " GROUP BY t.topic_id";
		
		//排序方式
		$query .= " ORDER BY $sortby";

		$res = $conn->Execute($query);
		$total = $res->RecordCount();
		$apend = 'term='.$term.'&addterms='.$addterms.'&forum='.$forum.'&search_username='.$search_username.'&searchboth='.$searchboth.'&sortby='.$sortby;
		$pages = $this->create_pager($start, $topics_per_page, $total, 'search.php?'.$apend.'&pStart=');
		
		// 分页
		$query .= " LIMIT $start, $topics_per_page";

		$res = $conn->Execute($query);
		if ($res->RecordCount() > 0) {
			while (!$res->EOF) {
				//表格交替的颜色
				$tr_class = ('tr2' == $tr_class) ? 'tr1' : 'tr2';
				
				$str .= '<tr class="'.$tr_class.'">'.CRLF;
				$str .= '<td align="center" width="20%"><a href="viewforum.php?pForumId='.$res->fields['forum_id'].'">'.stripslashes($res->fields['forum_name']).'</a></td>'.CRLF;
				$str .= '<td align="center" width="40%"><a href="viewtopic.php?pForumId='.$res->fields['forum_id'].'&pTopicId='.$res->fields['topic_id'].'&pGoto='.$res->fields['post_id'].'">'.stripslashes($res->fields['post_title']).'</a></td>'.CRLF;
				$str .= '<td align="center" width="25%"><a href="'. sprintf($this->_cms->user_info_url, $res->fields['username']).'" target="_blank">'.$res->fields['nickname'].'</a></td>'.CRLF;
				$str .= '<td align="center" width="25%">'.$res->fields['post_time'].'</td>'.CRLF;
	            
	            $str .= '</tr>'.CRLF;
	
				$res->MoveNext();
			}
		} else {
			$str .= '<tr class="tr2"><td align="center" colspan="4">没有符合条件的纪录，请扩大您的搜索范围后重试。</td></tr>'.CRLF;
		}
		
		return array($str, $pages);
	}	

	/**
     * 生成可以移动到的论坛的列表(topicadmin.php)
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$forumid	当前所处的论坛id
     * @return	string
     */			 
	function tadmin_list_forums (&$conn, $forumid) {
	
		$sql = "SELECT forum_id, forum_name FROM ".$this->db['Prefix']."forums WHERE forum_id!=$forumid ORDER BY forum_id";
		$res = $conn->Execute($sql);
		
		$str = '移动主题到论坛：<select name="pNewForumId" size="0">';

		if ($res->RecordCount() > 0) {
			while (!$res->EOF) {
				$str .= '<option value="'.$res->fields['forum_id'].'">'.$res->fields['forum_name'].'</option>'.CRLF;
				$res->MoveNext();
			}
		} else {
			$str .= '<option value="-1">没有其它的论坛可供移动！</option>'.CRLF;
		}
		$str .= '</select>';
	
		return $str;
	}

	/**
     * 将某个主题加入精华区/从精华区中删除(topicadmin.php)
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$topicid	主题id
	 * @param	boolean	$remove		true->删除; false->加入; 
     * @return	void
     */		
	function set_good_topic (&$conn, $topicid, $remove=false) {
		if ($remove)
			$sql = "UPDATE ".$this->db['Prefix']."topics SET is_good='N' WHERE topic_id=$topicid";
		else
			$sql = "UPDATE ".$this->db['Prefix']."topics SET is_good='Y' WHERE topic_id=$topicid";
		$conn->Execute($sql);
	}	

	/**
     * 锁定/解锁主题(topicadmin.php)
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$topicid	主题id
	 * @param	boolean	$unlock		true->解锁; false->加锁; 
     * @return	void
     */			
	function set_locked_topic (&$conn, $topicid, $unlock=false) {
		if ($unlock)
			$sql = "UPDATE ".$this->db['Prefix']."topics SET topic_status=0 WHERE topic_id=$topicid";
		else
			$sql = "UPDATE ".$this->db['Prefix']."topics SET topic_status=1 WHERE topic_id=$topicid";
		$conn->Execute($sql);
	}

	/**
     * 查看发帖用户的IP及相关信息(topicadmin.php)
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$postid		帖子id
	 * @param   array	$postinfo	函数get_post_info_from_id的返回值
	 * @see		get_post_info_from_id()
     * @return	array
     */		
	function view_post_ip (&$conn, $postid, $postinfo) {

		$poster_id = $postinfo['poster_id'];		
		
		if ($poster_id == -1)
			$sql = "SELECT p.poster_ip FROM ".$this->db['Prefix']."posts p WHERE p.post_id=$postid";
		else
			$sql = "SELECT u.".$this->db['UserName']." AS username, u.".$this->db['NickName']." AS nickname, p.poster_ip 
					FROM ".$this->db['UserTable']." u, ".$this->db['Prefix']."posts p 
					WHERE p.post_id=$postid AND u.".$this->db['UserId']."=p.poster_id
			";
		$res = $conn->Execute($sql);
		
		$poster_ip = $res->fields['poster_ip'];
		$poster_host = gethostbyaddr($poster_ip);		
		if ($poster_id != -1)
			$poster = '<a href="'. sprintf($this->_cms->user_info_url, $res->fields['username']).'" target="_blank">'.$res->fields['nickname'].'</a>';
		else
			$poster = 'anonymous';
		
		// 统计从该IP连接的用户的信息
		$sql = "SELECT u.".$this->db['UserName']." AS username, u.".$this->db['NickName']." AS nickname, count(*) as postcount 
				FROM ".$this->db['UserTable']." u, ".$this->db['Prefix']."posts p 
				WHERE poster_ip='$poster_ip' AND p.poster_id=u.".$this->db['UserId']." 
				GROUP BY ".$this->db['UserId']."";
		$res = $conn->Execute($sql);
		
		while (!$res->EOF) {
			//表格交替的颜色
			$tr_class = ('tr1' == $tr_class) ? 'tr2' : 'tr1';
			$users .= '<tr class="'.$tr_class.'"><td>&nbsp;<a href="'. sprintf($this->_cms->user_info_url, $res->fields['username']).'" target="_blank">'.$res->fields['nickname'].'</a></td><td>&nbsp;'.$res->fields['postcount'].' 篇帖子</td>'.CRLF;
			$res->MoveNext();
		}
		
		return array(
			'Poster' => $poster, 
			'Ip' => $poster_ip,
			'Host' => $poster_host,
			'Users' => $users,
		);
	}

	/**
     * 移动主题(topicadmin.php)
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
	 * @param	integer	$topicid		主题id
	 * @param	integer	$new_forumid	要移动到的论坛id
	 * @param	integer	$old_forumid	原来所在的论坛id
     * @return	void
     */		
	function move_topic (&$conn, $topicid, $new_forumid, $old_forumid) {
		
		if ($new_forumid == -1) {
			$kf->error_die('arg_error');
		} else {
			// 更新topic
			$sql = "UPDATE ".$this->db['Prefix']."topics SET forum_id=$new_forumid WHERE topic_id=$topicid";	
			$conn->Execute($sql);
			// 更新posts
			$sql = "UPDATE ".$this->db['Prefix']."posts SET forum_id=$new_forumid WHERE topic_id=$topicid";
			$conn->Execute($sql);
			
			// 保留移动主题的链接			
			$sql = "SELECT post_stamp,root_post_id FROM ".$this->db['Prefix']."topics WHERE topic_id=$topicid";
			$res = $conn->Execute($sql);
			$sql = "INSERT INTO ".$this->db['Prefix']."topics (
					forum_id, post_stamp, root_post_id, moved_to) VALUES (
					$old_forumid, ".$res->fields['post_stamp'].", ".$res->fields['root_post_id'].", $new_forumid)";
			$conn->Execute($sql);
			
			//同步更新
			$this->sync($conn, $new_forumid, 'forum');
			$this->sync($conn, $old_forumid, 'forum');
		}
	}
	
	/**
     * 升级时用的
     * 
     * @access	public
     * @param	object ADOConnection	&$conn	建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return	void
     */		 
	function update_to_ver2 (&$conn) {
		$sql = "SELECT topic_id, topic_title FROM ".$this->db['Prefix']."topics WHERE 1";
		$res = $conn->Execute($sql);
		while (!$res->EOF) {
			$sql = "SELECT MIN(post_id) as tmp_id FROM ".$this->db['Prefix']."posts WHERE topic_id=".$res->fields['topic_id']."";
			$res2 = $conn->Execute($sql);
			$sql = "UPDATE ".$this->db['Prefix']."topics set root_post_id=".$res2->fields['tmp_id']." WHERE topic_id=".$res->fields['topic_id']."";
			$conn->Execute($sql);
			$sql = "UPDATE ".$this->db['Prefix']."posts set post_title='".$res->fields['topic_title']."' WHERE post_id=".$res2->fields['tmp_id']."";
			//echo $sql.'<br>';
			$conn->Execute($sql);
			$res->MoveNext();
		}
		
		// 更新论坛排序
		$sql = "SELECT cat_id FROM ".$this->db['Prefix']."catagories WHERE 1";
		$res = $conn->Execute($sql);
		while (!$res->EOF) {
			$sql = "SELECT forum_id FROM ".$this->db['Prefix']."forums WHERE cat_id=".$res->fields['cat_id']." ORDER BY forum_id";
			//$sql = "SELECT MIN(post_id) as tmp_id FROM ".$this->db['Prefix']."posts WHERE topic_id=".$res->fields['topic_id']."";
			$res2 = $conn->Execute($sql);
			$i = 0;
			while (!$res2->EOF) {
				$sql = "UPDATE ".$this->db['Prefix']."forums set view_order=".$i." WHERE forum_id=".$res2->fields['forum_id']."";
				$conn->Execute($sql);
				//echo $sql.'<br>';
				$i ++;
				$res2->MoveNext();
			}
			$res->MoveNext();
		}		
	}
	
}   //end class 
/**
 管理界面可参考	http://www.phpbb.com/admin_demo/

 数据库改动
 ------------
 forum_topics 的topic_title和topic_time可以drop掉
 
 forum_posts 表增加字段post_title，便于显示某论坛的最新发表的帖子的标题
 ALTER TABLE `forum_posts` ADD `post_title` VARCHAR( 100 ) NOT NULL 
 
 forum_topics移动帖子时保存原帖子的连接
 ALTER TABLE `forum_topics` ADD `moved_to` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL ;
 记录一个topic的第一个post的id
 ALTER TABLE `forum_topics` ADD `root_post_id` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL ;
 
 forum_posts用于记录是否有投票 
 ALTER TABLE `forum_posts` ADD `poll_id` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL ;

UPDATE `forum_smiles` SET `code` = ':question:' WHERE `id` = '18' LIMIT 1 ;

# 创建时间: 2003 年 06 月 13 日 10:28
# 最后更新时间: 2003 年 06 月 20 日 17:33
#
CREATE TABLE `K12JWT`.`forum_poll` (
`id` int( 10 ) unsigned NOT NULL AUTO_INCREMENT ,
`name` char( 255 ) NOT NULL default '',
`owner` int( 10 ) unsigned NOT NULL default '0',
`creation_date` int( 10 ) unsigned NOT NULL default '0',
`expiry_date` int( 10 ) unsigned NOT NULL default '0',
`max_votes` int( 10 ) unsigned default NULL ,
PRIMARY KEY ( `id` )
) TYPE = MYISAM AUTO_INCREMENT = 7;

# 创建时间: 2003 年 06 月 13 日 10:28
# 最后更新时间: 2003 年 06 月 20 日 15:13
#
CREATE TABLE `K12JWT`.`forum_poll_opt` (
`id` int( 10 ) unsigned NOT NULL AUTO_INCREMENT ,
`poll_id` int( 10 ) unsigned NOT NULL default '0',
`name` char( 255 ) NOT NULL default '',
`count` int( 10 ) unsigned NOT NULL default '0',
PRIMARY KEY ( `id` ) ,
KEY `poll_id` ( `poll_id` )
) TYPE = MYISAM AUTO_INCREMENT = 10;

# 创建时间: 2003 年 06 月 13 日 10:28
# 最后更新时间: 2003 年 06 月 13 日 10:28
#
CREATE TABLE `K12JWT`.`forum_poll_opt_track` (
`id` int( 10 ) unsigned NOT NULL AUTO_INCREMENT ,
`poll_id` int( 10 ) unsigned NOT NULL default '0',
`user_id` int( 10 ) unsigned NOT NULL default '0',
PRIMARY KEY ( `id` ) ,
UNIQUE KEY `poll_id` ( `poll_id` , `user_id` )
) TYPE = MYISAM AUTO_INCREMENT = 1;


ALTER TABLE `forum_attach` ADD `embed` TINYINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL ;

ALTER TABLE `forum_forums` ADD `view_order` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL ;

*/
?>