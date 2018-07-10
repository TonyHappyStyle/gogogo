<?php
//  $Id:$

/**
 * K12在线咨询系统用户类
 *
 * @package     K12_k12EJOB
 * @copyright   K12 Studio
 * @access      public
 */
class ZXZX_User
{
    //  {{{ private properties

    /**
     * ZXZX_User 类文件的 Id，用于 CVS 版本追踪。
     * @var string
     * @access  private
     */
    var $_id = '$Id:$';

    /**
     * ZXZX对象实例。
     * @var     boolean
     * @access  private
     */
    var $_zxzx = NULL;

    //  }}}


    /**
     * ZXZX_User 构造函数。
     *
     * @param   object K12EJOB_Base   $zxzx   ZXZX平台对象的引用。
     * @return  void
     * @access  public
     */
    function ZXZX_User(&$zxzx)
    {
        $this->_zxzx =& $zxzx;
    }   //end function


   /**
     * 根据指定的条件，获取用户信息记录集。
     *
     * @param   string  $where      SQL 的 WHERE 子句。
     * @param   string  $order      SQL 的 ORDER BY 子句。
     * @param   mixed   $numrows    获取的记录数，若此值为“count”，则查询记录总数。
     * @param   integer $offset     开始记录的下标。
     * @return  object ADORecordSet 用户信息记录集的引用。
     * @access  public
     */
    function &check_user_info($where = '', $order = '', $numrows = -1, $offset = -1)
    {

        $conn =& $this->_zxzx->get_adodb_conn();
        if ('count' == $numrows) {
            $field_str = 'COUNT(id)';
            $numrows = -1;
        } else {
            $field_str = '*';
        }   //end if
        $sql = "SELECT $field_str FROM zx_adjust";
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }	//end if
       //echo $sql;exit;
	   //$a=$conn->debug=1;

	   //var_dump($conn );exit;
        $rs = $conn->SelectLimit($sql, $numrows, $offset);

        return $rs;
    }   //end function



	/**
 * 平台管理的tab标签显示函数。
 *
 * @param   array  $Wtab_txts	tab文字集合数组
 * @param   array  $Wtab_links	tab链接集合数组
 * @param   array  $tabed		当前tab序号
 * @param   array  $num			一行显示的tab数量
 * @return  string
 */

function echo_tabs($Wtab_txts,$Wtab_links,$tabed,$num = 5)
{
	if(count($Wtab_txts)!=count($Wtab_links))
	{
	$html = "<hr><b>Notice:<b>tab文字和链接数量不匹配<hr>";
	}
	else
	{
		$html = array();
		$oTxt = "";
		$n_array = count($Wtab_txts);
		$L = 0;
		while($L<$n_array)
		{
		$html[$wi] .="<div class=\"tabs\">";
		$html[$wi] .="<ul class=\"tabs primary\">";
		$s = $L;
		$n = $num;
		if($num>($n_array-$L)){$forn = $n_array;}
		else{$forn = $L+$n;}

			for($i=$s;$i<$forn;$i++)
			{
				$url_array = parse_url($Wtab_links[$i]);
				if($url_array['query']!=''){$and = "&";}else{$and = "?";}
				if($tabed!='' && $tabed==$i)
				{
				$html[$wi] .="<li class=\"active\"><a href=\"".$Wtab_links[$i].$and."tabed=".$i."\" class=\"active\">".$Wtab_txts[$i]."</a></li>";
				}
				else if($tabed=='' && $i==0)
				{
				$html[$wi] .="<li class=\"active\"><a href=\"".$Wtab_links[$i].$and."tabed=".$i."\" class=\"active\">".$Wtab_txts[$i]."</a></li>";
				}
				else
				{
				$html[$wi] .="<li><a href=\"".$Wtab_links[$i].$and."tabed=".$i."\">".$Wtab_txts[$i]."</a></li>";
				}
			}
		$html[$wi] .="</ul>";
		$html[$wi] .="</div>";
		$L = $L + $num;
		if($tabed>=$s && $tabed<$forn)
			{
			$oTxtF = $html[$wi];
			}
			else
			{
			$oTxt .= $html[$wi];
			}
		$wi++;

		}
		$oTxt .= $oTxtF;
		return $oTxt;
	}
}

 /**
     * 根据指定的条件，获取咨询项目信息记录集。
     *
     * @param   string  $where      SQL 的 WHERE 子句。
     * @param   string  $order      SQL 的 ORDER BY 子句。
     * @param   mixed   $numrows    获取的记录数，若此值为“count”，则查询记录总数。
     * @param   integer $offset     开始记录的下标。
     * @return  object ADORecordSet 用户信息记录集的引用。
     * @access  public
     */
    function &get_zx_project($where = '', $order = '', $numrows = -1, $offset = -1)
    {
        $conn =& $this->_zxzx->get_adodb_conn();
        if ('count' == $numrows) {
            $field_str = 'COUNT(id)';
            $numrows = -1;
        } else {
            $field_str = '*';
        }   //end if
        $sql = "SELECT $field_str FROM zx_project";
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }	//end if
     // echo $sql;exit;
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
        return $rs;
    }   //end function

//  {{{ insert_zx_project()

    /**
     * 插入咨询项目的数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $zx_name        咨询项目的 名称。
     * @param   string  $zx_time      咨询项目 时间
     * @param   string  $zx_jj     咨询项目 简介
     * @return  void
     */
    function insert_zx_project($conn,$zx_name, $zx_time, $zx_jj)
    {

        $sql = "
            INSERT INTO zx_project
                (zx_name, zx_time, zx_jj)
            VALUES
                (".$conn->qstr($zx_name).", ".$conn->qstr($zx_time).", ".$conn->qstr($zx_jj).")";
        $conn->Execute($sql);
    }   //end function

    //  }}}

	// update_zx_project
	 /**
     * 更新咨询项目记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   integer $zx_id     咨询项目 ID 号。
     * @param   string  $zx_name   咨询项目 名称
     * @param   string  $zx_time  咨询项目  时间
     * @param   string  $zx_jj    咨询项目  简介
     * @return  void
     */
    function update_zx_project($conn,$zx_id, $zx_name = '', $zx_time = '', $zx_jj = '', $state ='')
    {
     if (isset($state) && $state !='') {
        $sql = "
                    UPDATE zx_project
                    SET now=".$state."
                    WHERE id=".$zx_id;
                $conn->Execute($sql);
        } else {
		$sql = "
                    UPDATE zx_project
                    SET zx_name=".$conn->qstr($zx_name).",
                        zx_time=".$conn->qstr($zx_time).",
                        zx_jj=".$conn->qstr($zx_jj)."
                    WHERE id=".$zx_id;
                $conn->Execute($sql);
		} //end if
    }   //end function

    //  }}}

	// {{ delete_zx_project
    /**
     * 删除咨询项目，必须设置 WHERE 主体语句。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  在线咨询数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @return  void
     */
    function delete_zx_project(&$conn, $where)
    {
        $sql = "DELETE FROM zx_project WHERE ".$where;
        $conn->Execute($sql);
    }   //end function

    //  }}}

 /**
     * 根据指定的条件，获取咨询分类信息记录集。
     *
     * @param   string  $where      SQL 的 WHERE 子句。
     * @param   string  $order      SQL 的 ORDER BY 子句。
     * @param   mixed   $numrows    获取的记录数，若此值为“count”，则查询记录总数。
     * @param   integer $offset     开始记录的下标。
     * @return  object ADORecordSet 用户信息记录集的引用。
     * @access  public
     */
    function &get_zx_type($where = '', $order = '', $numrows = -1, $offset = -1)
    {
        $conn =& $this->_zxzx->get_adodb_conn();
        if ('count' == $numrows) {
            $field_str = 'COUNT(id)';
            $numrows = -1;
        } else {
            $field_str = '*';
        }   //end if
        $sql = "SELECT $field_str FROM zx_q_type";
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }	//end if
     //   echo $sql;
        $rs = $conn->Execute($sql);
        return $rs;
    }   //end function

	// {{ delete_zx_q_type
    /**
     * 删除咨询分类，必须设置 WHERE 主体语句。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  在线咨询数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @return  void
     */
    function delete_zx_q_type(&$conn, $where)
    {
        $sql = "DELETE FROM zx_q_type WHERE ".$where;
        $conn->Execute($sql);
    }   //end function

    //  }}}


	// update_zx_q_type
	 /**
     * 更新咨询分类记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  在线咨询数据库数据库连接对象实例引用。
     * @param   integer $id     咨询分类 ID 号。
     * @param   string  $q_type   咨询分类
     * @return  void
     */
    function update_zx_q_type($conn, $id, $q_type)
    {
		$sql = "
                    UPDATE zx_q_type
                    SET q_type=".$conn->qstr($q_type)."
                    WHERE id=".$id;
                $conn->Execute($sql);
    }   //end function


	//  {{{ insert_zx_q_type()

    /**
     * 插入咨询分类的数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $q_type        咨询分类的 名称。
     * @param   string  $zx_id      咨询分类所属的咨询项目 ID
     * @return  void
     */
    function insert_zx_q_type($conn,$q_type, $zx_id)
    {
        $sql = "
            INSERT INTO zx_q_type
                (q_type, zx_id)
            VALUES
                (".$conn->qstr($q_type).", ".$zx_id.")";
        $conn->Execute($sql);
    }   //end function

    //  }}}

	// {{{ get_zx_adjust
	 /**
     * 根据指定的条件，获取咨询专家信息记录集。
     *
     * @param   string  $where      SQL 的 WHERE 子句。
     * @param   string  $order      SQL 的 ORDER BY 子句。
     * @param   mixed   $numrows    获取的记录数，若此值为“count”，则查询记录总数。
     * @param   integer $offset     开始记录的下标。
     * @return  object ADORecordSet 用户信息记录集的引用。
     * @access  public
     */
    function &get_zx_adjust($where = '', $order = '', $numrows = -1, $offset = -1)
    {
        $conn =& $this->_zxzx->get_adodb_conn();
        if ('count' == $numrows) {
            $field_str = 'COUNT(id)';
            $numrows = -1;
        } else {
            $field_str = '*';
        }   //end if
        $sql = "SELECT $field_str FROM zx_adjust";
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }	//end if
     //   echo $sql;
        $rs = $conn->Execute($sql);
        return $rs;
    }   //end function
// }}

// {{ delete_zx_adjust
    /**
     * 删除咨询分类，必须设置 WHERE 主体语句。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  在线咨询数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @return  void
     */
    function delete_zx_adjust(&$conn, $where)
    {
        $sql = "DELETE FROM zx_adjust WHERE ".$where;
        $conn->Execute($sql);
    }   //end function

    //  }}}


	// update_zx_adjust
	 /**
     * 更新咨询分类记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  在线咨询数据库数据库连接对象实例引用。
     * @param   array  $adjust   专家信息数组
     * @return  void
     */
    function update_zx_adjust($conn, $adjust, $id)
    {
		$sql = "
                    UPDATE zx_adjust
                    SET userid=".$conn->qstr($adjust['userid']).",
                        pwd=".$conn->qstr($adjust['pwd']).",
                        name=".$conn->qstr($adjust['name']).",
                        bz=".$conn->qstr($adjust['bz']).",
                        flag=".$conn->qstr($adjust['flag']).",
                        com=".$conn->qstr($adjust['com'])."
                    WHERE id=".$id;
                $conn->Execute($sql);
    }   //end function

     function admin_chang_pass($conn,$uid_pass){
     	$sql = "
                    UPDATE zx_adjust
                    SET
                        pwd=".$conn->qstr($uid_pass)."

                     WHERE flag='sysadmin'";
                $conn->Execute($sql);

     }
	//  {{{ insert_zx_adjust()

    /**
     * 插入咨询分类的数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   array $adjust       专家信息的数组。
     * @param   string  $zx_id      该专家所属的咨询项目 ID
     * @return  void
     */
    function insert_zx_adjust($conn,$adjust, $zx_id)
    {
        $sql = "
            INSERT INTO zx_adjust
                (userid, pwd, name, zx_id, bz, flag,com)
            VALUES
                (".$conn->qstr($adjust['userid']).", ".$conn->qstr($adjust['pwd']).", ".$conn->qstr($adjust['name']).",".$conn->qstr($zx_id).", ".$conn->qstr($adjust['bz']).",".$conn->qstr($adjust['flag']).",".$conn->qstr($adjust['com']).")";
        $conn->Execute($sql);
    }   //end function

    //  }}}


	/**
     * 插入调度员权限的数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $userid        调度员账号。
     * @param   string  $dd_p		   调度员权限字符串,如 1|2|3|4|5|
     * @return  void
     */
    function insert_zx_dd_p($conn, $userid, $dd_p)
    {
        $sql = "
            INSERT INTO zx_dd_p
                (userid, dd_p)
            VALUES
                (".$conn->qstr($userid).", ".$conn->qstr($dd_p).")";
        $conn->Execute($sql);
    }   //end function

    //  }}}

	/**
     * 更新调度员权限的数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $id        调度员权限表 ID。
     * @param   string  $dd_p      调度员权限字符串,如1|2|3|4|5|
     * @return  void
     */
     function update_zx_dd_p($conn, $id, $dd_p)
    {
		$sql = "
                    UPDATE zx_dd_p
                    SET dd_p=".$conn->qstr($dd_p)."
                    WHERE id=".$id;
                $conn->Execute($sql);
    }   //end function
    //  }}}

	// {{{ get_zx_dd_p
	 /**
     * 根据指定的条件，获取调度员权限信息记录集。
     *
     * @param   string  $where      SQL 的 WHERE 子句。
     * @param   string  $order      SQL 的 ORDER BY 子句。
     * @param   mixed   $numrows    获取的记录数，若此值为“count”，则查询记录总数。
     * @param   integer $offset     开始记录的下标。
     * @return  object ADORecordSet 用户信息记录集的引用。
     * @access  public
     */
    function &get_zx_dd_p($where = '', $order = '', $numrows = -1, $offset = -1)
    {
        $conn =& $this->_zxzx->get_adodb_conn();
        if ('count' == $numrows) {
            $field_str = 'COUNT(id)';
            $numrows = -1;
        } else {
            $field_str = '*';
        }   //end if
        $sql = "SELECT $field_str FROM zx_dd_p";
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }	//end if
     //   echo $sql;
        $rs = $conn->Execute($sql);
        return $rs;
    }   //end function
// }}

// {{ delete_zx_dd_p
    /**
     * 删除咨询分类，必须设置 WHERE 主体语句。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  在线咨询数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @return  void
     */
    function delete_zx_dd_p(&$conn, $where)
    {
        $sql = "DELETE FROM zx_dd_p WHERE ".$where;
        $conn->Execute($sql);
    }   //end function

    //  }}}


	// {{{ get_zx_question
	 /**
     * 根据指定的条件，获取咨询问题信息记录集。
     *
     * @param   string  $where      SQL 的 WHERE 子句。
     * @param   string  $order      SQL 的 ORDER BY 子句。
     * @param   mixed   $numrows    获取的记录数，若此值为“count”，则查询记录总数。
     * @param   integer $offset     开始记录的下标。
     * @return  object ADORecordSet 用户信息记录集的引用。
     * @access  public
     */
    function &get_zx_question($where = '', $order = '', $numrows = -1, $offset = -1)
    {
        $conn =& $this->_zxzx->get_adodb_conn();
        //$conn->debug=true;
        if ('count' == $numrows) {
            $field_str = 'COUNT(id)';
            $numrows = -1;
        } else {
            $field_str = '*';
        }   //end if
        if (empty($_SESSION['ZXZX_USER_ID']))
            $sql = "SELECT $field_str FROM zx_question WHERE an_flag='yes'";
        else
            $sql = "SELECT $field_str FROM zx_question WHERE 1=1";
        if ($where) {
            $sql .= " AND $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }	//end if
     //   echo $sql;
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
        return $rs;
    }   //end function



	/**
     * 插入用户提问的数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   array $questions        提问数组。
     * @return  void
     */
    function insert_zx_question($conn, $questions)
    {
        $sql = "
            INSERT INTO zx_question
                (userid, q_title, question, q_type, aer_id, an_flag, date, zx_id, is_pub)
            VALUES
                (".$conn->qstr($questions['userid']).",".$conn->qstr($questions['q_title']).",".$conn->qstr($questions['question']).",".$questions['q_type'].",".$conn->qstr($questions['aer_id']).",".$conn->qstr($questions['an_flag']).",".$conn->qstr($questions['date']).", ".$questions['zx_id'].", ".intval($questions['is_pub']).")";
        $conn->Execute($sql);
        return $conn->Insert_ID();
    }   //end function

    //  }}}


	/**
     * 问题浏览次数加1
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $id        调度员权限表 ID。
     * @param   string  $dd_p      调度员权限字符串,如1|2|3|4|5|
     * @return  void
     */
     function update_question_viewnum($conn, $id)
    {
		$sql = "
                    UPDATE zx_question
                    SET view_num = view_num+1
                    WHERE id=".$id;
                $conn->Execute($sql);
    }   //end function
    //  }}}


	// {{ delete_zx_question
    /**
     * 删除咨询题目，必须设置 WHERE 主体语句。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  在线咨询数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @return  void
     */
    function delete_zx_question(&$conn, $where)
    {
        $sql = "DELETE FROM zx_question WHERE ".$where;
        $conn->Execute($sql);
    }   //end function

    //  }}}

	/**
     * 调度员分配问题
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $id        调度员权限表 ID。
     * @param   string  $dd_p      调度员权限字符串,如1|2|3|4|5|
     * @return  void
     */
     function adjust_q_question($conn,$q_id,$q_type,$answer_id)
    {
		$sql = "
                    UPDATE zx_question
                    SET q_type = ".$q_type.",
                        check_flag=".$conn->qstr('yes').",
                        answer_id='',
                       aer_id=".$conn->qstr($answer_id)."
                    WHERE id=".$q_id;
                $conn->Execute($sql);
    }   //end function
    //  }}}


	/**
     * 专家回答问题
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $q_id      咨询问题 ID。
     * @param   string  $answer    回复信息
     * @return  void
     */
     function answer_zx_question($conn,$q_id,$answer)
    {
		$an_time = time();
		$an_flag = 'yes';
		$sql = "
                    UPDATE zx_question
                    SET answer = ".$conn->qstr($answer).",
                        an_time=".$conn->qstr($an_time).",
                        an_flag=".$conn->qstr($an_flag)."
                    WHERE id=".$q_id;
                $conn->Execute($sql);
    }   //end function
    //  }}}


	/**
     * 专家退回问题
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $q_id        退回问题 ID。
     * @param   integer $answer_id   反馈信息。
     * @return  void
     */
     function back_zx_question($conn,$q_id,$answer_id)
    {
		$an_time = '';
		$answer = '';
		$an_flag = 'no';
		$check_flag = 'no';
		$sql = "
                    UPDATE zx_question
                    SET answer = ".$conn->qstr($answer).",
                        an_time='',
                        check_flag=".$conn->qstr($check_flag).",
                        answer_id=".$conn->qstr($answer_id).",
                        an_flag=".$conn->qstr($an_flag)."
                    WHERE id=".$q_id;
                $conn->Execute($sql);
    }   //end function
    //  }}}

	/**
     * 系统管理员修改问题
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $id        调度员权限表 ID。
     * @param   string  $dd_p      调度员权限字符串,如1|2|3|4|5|
     * @return  void
     */
     function update_zx_question($conn,$q_id, $question, $q_title, $q_type,$answer)
    {
		$an_flag = "yes";
		$sql = "
                    UPDATE zx_question
                    SET q_type = ".$conn->qstr($q_type).",
                        question=".$conn->qstr($question).",
                        q_title=".$conn->qstr($q_title).",
                        an_flag=".$conn->qstr($an_flag).",
                        answer=".$conn->qstr($answer)."
                    WHERE id=".$conn->qstr($q_id);
                $conn->Execute($sql);
    }   //end function
    //  }}}

	/**
     * 系统管理员推荐问题
     *
     * @access  public
     * @param   object ADOConnection  &$conn    在线咨询系统使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $q_id		问题id。
     * @param   string  $at			是否推荐
     * @return  void
     */
     function at_zx_question($conn,$q_id, $at)
    {
		$sql = "
                    UPDATE zx_question
                    SET at_home = ".$at."
                    WHERE id=".$q_id;
                $conn->Execute($sql);
    }   //end function
    //  }}}

 //  {{{ create_zx_projects_types($conn)

    /**
     * 生成咨询项目-类别数组变量文件。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  在线咨询数据库数据库连接对象实例引用。
     * @return  void
     */
    function create_zx_projects_types(&$conn)
    {
    //生成权限数组
	$where ='';
	$i = 0;
	$zxs = Array();
	$rs = $this->get_zx_type($where, "id DESC");
	while (!$rs->EOF) {
		$q_type_id = $rs->fields[0];
		$q_type_name = $rs->fields[1];
		$zx_id = $rs->fields[2];

		//$zxs[$zx_id]['item'] = $rs->fields[1];
		if (!isset($zxs[$zx_id]['type']))
                $zxs[$zx_id]['type'] = array();
		 if ($zx_id)
                $zxs[$zx_id]['type'][$q_type_id] = $q_type_name;

		$i++;
		$rs->MoveNext();
	}

	$content  = '<?php'.CRLF;
    $content .= '$cfg_zx_projects_types = '.var_export($zxs, TRUE).';'.CRLF;
    $content .= '?'.'>';
	ZXZX_Common :: write_file($this->_zxzx->data_path.'/configs/zxzx_zx_projects_types.inc.php', $content);
    }   //end function


 //  {{{ create_zx_projects_answers($conn)

    /**
     * 生成咨询项目-解答专家数组变量文件。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  在线咨询数据库数据库连接对象实例引用。
     * @return  void
     */
    function create_zx_projects_answers(&$conn)
    {
    //生成权限数组
	$where ='flag ="answer"';
	$i = 0;
	$zxs = Array();
	$rs = $this->get_zx_adjust($where, "id DESC");
	//var_dump($rs->fields);exit;
	while (!$rs->EOF) {
		$userid = $rs->fields[1];
		$name = $rs->fields[3];
		$zx_id = $rs->fields[6];

		//$zxs[$zx_id]['item'] = $rs->fields[1];
		if (!isset($zxs[$zx_id]['answer']))
                $zxs[$zx_id]['answer'] = array();
		 if ($zx_id)
                $zxs[$zx_id]['answer'][$userid] = $name;
        $zxs['all']['answer'][$userid] = $name;
		$i++;
		$rs->MoveNext();
	}

	$content  = '<?php'.CRLF;
    $content .= '$cfg_zx_projects_answers = '.var_export($zxs, TRUE).';'.CRLF;
    $content .= '?'.'>';
	ZXZX_Common :: write_file($this->_zxzx->data_path.'/configs/zxzx_zx_projects_answers.inc.php', $content);
    }   //end function


/*
函数名称：str_check()
函数作用：对提交的编辑内容进行处理
参　　数：$str: 要提交的内容
返 回 值：$str: 返回过滤后的内容
*/
function str_check($str) {
  if (!get_magic_quotes_gpc()) {    // 判断magic_quotes_gpc是否为打开
    $str = addslashes($str);    // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
  }
  $str = str_replace("_", "\_", $str);    // 把 '_'过滤掉
  $str = str_replace("%", "\%", $str);    // 把 '%'过滤掉
  $str = nl2br($str);    // 回车转换
  $str = htmlspecialchars($str);    // html标记转换

  return $str;
}

function get_answer_pro(){

		$conn =& $this->_zxzx->get_adodb_conn();
		$sql="select a.dd_p,b.userid,b.name from zx_dd_p as a left join  zx_adjust as b  on a.userid=b.userid where  b.flag='answer'";
		$answer=array();
		$rs=$conn->Execute($sql);
		while (!$rs->EOF) {

			$answer[]=$rs->fields;
			$rs->MoveNext();

		}
		return $answer;
		//var_dump($answer);exit;
	}

    //  }}}
}   //end class
?>