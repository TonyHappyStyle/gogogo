<?php
//  $Id: cms_home.class.php,v 1.4 2003/06/25 09:06:33 nio Exp $

/**
 * 建网通主页空间类。
 * 此类主要用于主页空间的文件管理、上传，主页排行等。
 *
 * @package K12CMS
 * @access public
 */
class CMS_Home
{
    //  {{{ private properties
    
    /**
     * CMS_Home 类文件的 Id，用于 CVS 版本追踪。
     * @var string
     * @access  private
     */
    var $_id = '$Id: cms_home.class.php,v 1.4 2003/06/25 09:06:33 nio Exp $';
    
    /**
     * CMS_Home 类中所用到的 CMS 对象实例。
     * @var object CMS
     * @access  private
     */ 
    var $_cms = null;
    
    //  }}}

    //  {{{ public properties

    /**
     * 主页空间模板文件存放目录的 URL。
     * @var  string
     * @access  public
     */ 
    var $tpl_url = '';
    
    /**
     * 主页空间模板文件存放目录物理路径。
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
     * 系统提供的模板存放目录的 URL。
     * @var  string
     * @access  public
     */ 
    var $module_url = '';
    
    /**
     * 系统提供的模板存放目录物理路径。
     * @var  string
     * @access  public
     */ 
    var $module_path = '';

    /**
     * 计数器的数组图片所在目录物理路径。
     * @var  string
     * @access  public
     */ 
    var $digit_path = '';
    
    /**
     * 计数器的数组图片所在目录的 URL。
     * @var  string
     * @access  public
     */ 
    var $digit_url = '';    

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
    function CMS_Home(&$cms)
    {
        
        $this->_cms =& $cms;
        $this->tpl_url = $cms->tpl_url.'/'.$cms->modules['HOME']['DIR'];
        $this->tpl_path = $_SERVER['DOCUMENT_ROOT'].$this->tpl_url;
        $this->graphic_url = 'http://'.$_SERVER['SERVER_NAME'].$this->tpl_url.'/graphics';
        $this->graphic_path = $this->tpl_path.'/graphics';
        $this->module_url = 'http://'.$_SERVER['SERVER_NAME'].$this->tpl_url.'/graphics/modules';
        $this->module_path = $this->graphic_path.'/modules';
        $this->digit_path = $cms->tpl_path.'/images/counters';
        $this->digit_url = $cms->tpl_url.'/images/counters';
    }   //end function
    
    //  }}}

    //  {{{ get_total_space()
    
    /**
     * 获取用户主页可用空间的大小，单位为字节（byte）。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $user_id    用户帐号（ID）。
     * @return  integer
     */
    function get_total_space(&$conn, $user_id)
    {
        $sql = "SELECT  QuotaSize FROM ftp_users WHERE  username=".$conn->qstr($user_id);
        $rs = $conn->Execute($sql);
        return ($rs->fields[0] * 1048576);  // * 1024 * 1024
    }   //end function
    
    //  }}}

    //  {{{ is_deny()

    /**
     * 判断当前用户主页空间是否被禁用。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $user_id    用户帐号（ID）。
     * @return  boolean 被禁用则返回 TURE，否则返回 FALSE。
     */
    function is_deny(&$conn, $user_id) 
    {
        $sql = "SELECT active FROM ftp_users WHERE username=".$conn->qstr($user_id);
        $rs = $conn->Execute($sql);
        return ('N' == $rs->fields[0]) ? TRUE : FALSE;
    }   //end function
    
    //  }}}

    //  {{{ update_aver_sort()

    /**
     * 更新所有用户的日均访问数，此操作每日进行一次。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $today  当天的日期。
     * @return  void
     */
    function update_aver_sort(&$conn, $today)
    {
        $time = strtotime($today);
        $sql ="
            SELECT m.user_id, s.counter_sort_indate, m.counter_number
            FROM counter_main AS m
            LEFT JOIN counter_sort AS s
            USING (user_id)";
        $rs = $conn->Execute($sql);     
        while (!$rs->EOF) {
            $user_id = $rs->fields[0];
            $indate = $rs->fields[1];
            $total = $rs->fields[2];
            
            $days = (int)(($time - strtotime($indate)) / 86400 + 1);   //计算天数
            $aver = ($days) ? (int)($total / $days) : $total;
            $sql = "
                UPDATE counter_sort
                SET counter_sort_aver=".$aver."
                WHERE user_id=".$conn->qstr($user_id);
            $conn->Execute($sql);
            $rs->MoveNext();
        }   //end while
        CMS_Common :: write_file('./date.dat', $today);
    }   //end function
    
    //  }}}

    //  {{{ get_sort_total()

    /**
     * 获取符合条件的主页排行的记录总数。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where  SQL 语句中的 WHERE 主体，不包含“WHERE”。
     * @return  integer
     */
    function get_sort_total(&$conn, $where='')
    {
        $sql = "
            SELECT COUNT(*)
            FROM counter_sort AS s
            LEFT JOIN counter_type AS t
                USING (counter_type_id)";
        if ($where)
            $sql .= " WHERE ".$where;
        $rs = $conn->Execute($sql);
        return $rs->fields[0];
    }   //end function  

    //  }}}
    
    //  {{{ get_sort_rs()

    /**
     * 获取符合条件的主页排行的记录集（需要跨表操作，获取了总访问数，并且可以按总访问数排序）。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where    SQL 语句中的 WHERE 主体，不包含“WHERE”。
     * @param   string  $order    SQL 语句中的 ORDER BY 主体，不包含“ORDERY BY”。
     * @param   integer $numrows    获取的记录数。
     * @param   integer $offset  记录开始的下标。
     * @return  object ADORecordSet
     */ 
    function &get_sort_rs(&$conn, $where = '', $order = '', $numrows = -1, $offset = -1)
    {
        $sql = "
            SELECT s.user_id, s.counter_sort_name, s.counter_sort_url, s.counter_sort_aver AS aver, FLOOR(m.counter_number) AS total, t.counter_type_name
            FROM counter_sort AS s
            LEFT JOIN counter_main AS m 
                ON (s.user_id=m.user_id)
            LEFT JOIN counter_type AS t
                ON (s.counter_type_id=t.counter_type_id)";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order.' DESC';
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
        return $rs;
    }   //end function
    
    //  }}}

    //  {{{ get_counter_type_rs()

    /**
     * 获取主页分类的记录集。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where      SQL 语句中的 WHERE 主体，不包含“WHERE”。
     * @param   string  $order      SQL 语句中的 ORDER BY 主体，不包含“ORDERY BY”。     
     * @return  object ADORecordSet
     */     
    function &get_counter_type_rs(&$conn, $where='', $order='')
    {
        $sql = "
            SELECT counter_type_id, counter_type_name, counter_type_desc
            FROM counter_type";
        if ($where)
            $sql .= " WHERE ".$where;            
        if ($order)
            $sql .= " ORDER BY ".$order;            
        return $conn->Execute($sql);
    }   //end function

    //  }}}
    
    //  {{{ update_counter_type_rs()

    /**
     * 插入／更新主页分类的记录集。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $counter_type_id    主页分类 ID 号。
     * @param   string  $counter_type_id    主页分类名称。
     * @param   string  $counter_type_id    主页分类描述。
     * @return  void
     */     
    function update_counter_type_rs(&$conn, $counter_type_id = 0, $counter_type_name, $counter_type_desc)
    {
        if (empty($counter_type_id))
            $sql = "INSERT INTO counter_type (counter_type_name, counter_type_desc) VALUES (".$conn->qstr($counter_type_name).", ".$conn->qstr($counter_type_desc).")";
        else
            $sql = "UPDATE counter_type SET counter_type_name=".$conn->qstr($counter_type_name).", counter_type_desc=".$conn->qstr($counter_type_desc)." WHERE counter_type_id=".$counter_type_id;            
        $conn->Execute($sql);
    }   //end function

    //  }}}    

    //  {{{ delete_counter_type_rs()
    
    /**
     * 删除主页分类的记录集。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where      SQL 语句中的 WHERE 主体，不包含“WHERE”。
     * @return  void
     */         
    function delete_counter_type_rs($conn, $where)
    {
        $sql = "DELETE FROM counter_type WHERE ".$where;
        $conn->Execute($sql);
    }   //end function
    
    //  }}}
    
    //  {{{ get_counter_sort_rs()
    
    /**
     * 获取符合条件的主页排行的记录集（没有跨表操作，不需要获取总访问数）。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where      SQL 语句中的 WHERE 主体，不包含“WHERE”。
     * @param   string  $order      SQL 语句中的 ORDER BY 主体，不包含“ORDERY BY”。
     * @param   integer $numrows    获取的记录数，此参数为“count”时返回符合条件的记录值。
     * @param   integer $offset     记录开始的下标。
     * @param   boolean $join       是否连接（LEFT JOIN）表 counter_type。
     * @return  object ADORecordSet
     */     
    function &get_counter_sort_rs(&$conn, $where = '', $order = '', $numrows = -1, $offset = -1, $join = FALSE)
    {
        $field = 'user_id, counter_sort_name, counter_sort_url, counter_sort_desc, counter_sort_indate, counter_sort_aver';
        if ($join)
            $field .= ', counter_type_name';
        else
            $field .= ', counter_type_id';
        if ('count' == $numrows) {
            $field = 'COUNT(*)';
            $numrows = -1;
        }
        $sql = "SELECT ".$field." FROM counter_sort";
        if ($join)
            $sql .= " LEFT JOIN counter_type USING (counter_type_id)";            
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;            
        return $conn->SelectLimit($sql, $numrows, $offset);
    }   //end function  
    
    //  }}}

    //  {{{ update_counter_sort_rs()
    
    /**
     * 插入／更新主页排行的记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $user_id            用户帐号。
     * @param   string  $counter_sort_name  网站名称。
     * @param   string  $counter_sort_url   网站 URL。
     * @param   integer $counter_type_id    网站分类的类别 ID 号。
     * @param   string  $counter_sort_desc  网站描述。
     * @return  void
     */         
    function update_counter_sort_rs(&$conn, $user_id, $counter_sort_name, $counter_sort_url, $counter_type_id, $counter_sort_desc)
    {
        $rs = &$this->get_counter_sort_rs($conn, "user_id=".$conn->qstr($user_id));
        if ($rs->RecordCount() < 1)
        {
            $today = date('Ymd');
            $month = date('Ym');
            $sql = "
                INSERT INTO counter_main
                    (user_id, counter_number, counter_last_d, counter_d0, counter_last_m, counter_m1)
                VALUES
                    ('".$user_id."', '1', '".$today.":0', '".$today.":1', '".$month.":1', '".$month.":1')";
            $conn->Execute($sql);

            $sql = "
                INSERT INTO counter_sort
                    (user_id, counter_sort_name, counter_sort_url, counter_type_id, counter_sort_desc, counter_sort_indate, counter_sort_aver)
                VALUES
                    ('".$user_id."', '".$counter_sort_name."', '".$counter_sort_url."', ".$counter_type_id.", '".$counter_sort_desc."', '".date('Y-m-d')."', 0)";
            $conn->Execute($sql);
        } else {
            $sql = "
                UPDATE counter_sort
                SET counter_sort_name='".$counter_sort_name."', 
                    counter_sort_url='".$counter_sort_url."', 
                    counter_type_id=".$counter_type_id.", 
                    counter_sort_desc='".$counter_sort_desc."'
                WHERE USER_ID='".$user_id."'";
            $conn->Execute($sql);
        }   //end if
    }   //end function

    //  }}} 

    //  {{{ delete_counter_sort_rs()
    
    /**
     * 删除计数器信息的记录集。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where      SQL 语句中的 WHERE 主体，不包含“WHERE”。
     * @return  void
     */         
    function delete_counter_sort_rs($conn, $where)
    {
        $sql = "DELETE FROM counter_sort WHERE ".$where;
        $conn->Execute($sql);
    }   //end function
    
    //  }}}
    
    //  {{{ get_gbook_main_rs()

    /**
     * 获取符合条件的留言板用户设置的记录集。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where    SQL 语句中的 WHERE 主体，不包含“WHERE”。
     * @param   string  $order    SQL 语句中的 ORDER BY 主体，不包含“ORDERY BY”。
     * @param   integer $numrows    获取的记录数。
     * @param   integer $offset  记录开始的下标。
     * @return  object ADORecordSet
     */         
    function get_gbook_main_rs(&$conn, $where = '', $order = '', $numrows = -1, $offset = -1)
    {
        $sql  = "SELECT user_id, gbook_name, gbook_font_color FROM gbook_main";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
        return $conn->SelectLimit($sql, $numrows, $offset);
    }   //end function
    
    //  }}}
    
    //  {{{ insert_gbook_main_rs()

    /**
     * 插入留言板用户设置的记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $user_id            留言板属主的用户帐号（用户 ID 号）。
     * @param   string  $gbook_name      留言板名称。
     * @param   string  $gbook_font_color   留言板字体信息。
     * @return  void
     */             
    function insert_gbook_main_rs(&$conn, $user_id, $gbook_name, $gbook_font_color)
    {
        $sql = "
            INSERT INTO gbook_main
                (user_id, gbook_name, gbook_font_color)
            VALUES
                (".$conn->qstr($user_id).", ".$conn->qstr($gbook_name).", ".$conn->qstr($gbook_font_color).")";
        $conn->Execute($sql);               
    }   //end function  
    
    //  }}}

    //  {{{ update_gbook_main_rs()

    /**
     * 更新留言板用户设置的记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $user_id            留言板属主的用户帐号（用户 ID 号）。
     * @param   string  $gbook_name      留言板名称。
     * @param   string  $gbook_font_color   留言板字体信息。
     * @return  void
     */             
    function update_gbook_main_rs(&$conn, $user_id, $gbook_name, $gbook_font_color)
    {
        $sql = "
            UPDATE gbook_main SET
                gbook_name=".$conn->qstr($gbook_name).",
                gbook_font_color=".$conn->qstr($gbook_font_color)."
            WHERE user_id=".$conn->qstr($user_id); 
        $conn->Execute($sql);               
    }   //end function  
    
    //  }}}
    
    //  {{{ get_gbook_message_rs()
    
    /**
     * 获取符合条件的留言板留言信息的记录集。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where    SQL 语句中的 WHERE 主体，不包含“WHERE”。
     * @param   string  $order    SQL 语句中的 ORDER BY 主体，不包含“ORDERY BY”。
     * @param   mixed   $numrows    获取的记录数，若为“count”，则返回符合条件的记录总数。
     * @param   integer $offset  记录开始的下标。
     * @return  object ADORecordSet
     */             
    function &get_gbook_message_rs(&$conn, $where = '', $order = '', $numrows = -1, $offset = -1)
    {
        global $gbook_user_id;
        if ('count' == $numrows) {
            $fields = 'COUNT(*)';
            $numrows = -1;
        } else {
            $fields = "gbook_message_id, user_id, gbook_message, gbook_message_name, gbook_message_email, gbook_message_time, gbook_message_ip";
        }   //end if
        $user_id = $_SESSION[$this->_cms->sess_user_id];
        if (($gbook_user_id == $user_id && '' != $user_id)
            || ('administrator' == $gbook_user_id && 'wsly_admin' == $user_id)) {
            $sql = "SELECT ".$fields." FROM gbook_message WHERE 1=1 ";
        } else {    
            $sql .= "SELECT ".$fields." FROM gbook_message m , gbook_msg_rpl r
                WHERE m.gbook_message_id=r.msg_id AND r.msg_id is NOT NULL ";
            $where = str_replace('gbook_id', 'm.gbook_id', $where);
        }    
        if ($where)
            $sql .= " AND ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
        return $conn->SelectLimit($sql, $numrows, $offset);
    }   //end function
    
    //  }}}
     function &get_gbook_msg_rpl_rs(&$conn, $where = '', $order = '', $numrows = -1, $offset = -1)
    {
       // $conn->debug=true;
        if ('count' == $numrows) {
            $fields = 'COUNT(*)';
            $numrows = -1;
        } else {
            $fields = "msg_id,msg_rpl";
        }   //end if
        $sql = "SELECT ".$fields." FROM gbook_msg_rpl";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
        return $conn->SelectLimit($sql, $numrows, $offset);
        
    }   //end function

    //  {{{ get_gbook_message_rs()
    
    /**
     * 插入留言信息记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $user_id                留言板属主的用户帐号（用户 ID 号）。
     * @param   string  $gbook_message        留言内容。
     * @param   string  $gbook_message_name  留言者姓名。
     * @param   string  $gbook_message_email    留言者 Email。
     * @param   string  $gbook_message_time  留言时间。
     * @param   string  $gbook_message_ip      留言客户端 IP。
     * @return  void
     */             
    function insert_gbook_message_rs(&$conn, $user_id, $gbook_message, $gbook_message_name, $gbook_message_email, $gbook_message_time, $gbook_message_ip, $gbook_id = 0)
    {
        $sql = "
            INSERT INTO gbook_message
                (user_id, gbook_message, gbook_message_name, gbook_message_email, gbook_message_time, gbook_message_ip, gbook_id)
            VALUES
                (".$conn->qstr($user_id).", ".$conn->qstr($gbook_message).", ".$conn->qstr($gbook_message_name).", ".$conn->qstr($gbook_message_email).", ".$conn->qstr($gbook_message_time).", ".$conn->qstr($gbook_message_ip).", ".$gbook_id.")";
        $conn->Execute($sql);
    }   //end function
    
    //  }}}
     function insert_gbook_message_rpl_rs(&$conn, $id, $gbook_message_rpl, $gbook_id = 0)
    {
        $sql = "
            replace INTO gbook_msg_rpl
                (msg_id, msg_rpl, gbook_id)
            VALUES
                (".$id.", ".$conn->qstr($gbook_message_rpl).", ".$gbook_id.")";
        $conn->Execute($sql);
    }   //end function

    //  {{{ delete_gbook_message_rs()

    /**
     * 删除留言信息记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $user_id    留言板属主的用户帐号（用户 ID 号）。
     * @param   string  $where    SQL 语句的 WHERE 主体，删除留言的条件，必须设置。
     * @return  void
     */              
    function delete_gbook_message_rs(&$conn, $user_id, $where)
    {
        if ($where) {
            $sql = "DELETE FROM gbook_message WHERE user_id=".$conn->qstr($user_id)." AND ".$where;
            
            $conn->Execute($sql);
        }   //end if
    }   //end function
    
    //  }}}
     function delete_gbook_message_rs_rpl(&$conn,$where)
    {
        if ($where) {
            $sql = "DELETE FROM gbook_msg_rpl WHERE ".$where;
            
            $conn->Execute($sql);
        }   //end if
    }   //end function
    //  {{{ get_counter_main_rs()

    /**
     * 获取符合条件的计数器信息的记录集。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where    SQL 语句中的 WHERE 主体，不包含“WHERE”，必须设置。
     * @return  object ADORecordSet
     */             
    function &get_counter_main_rs(&$conn, $where)
    {
        $sql = "
            SELECT user_id, counter_number, counter_last_d, counter_d0, counter_d1, counter_d2, counter_d3, counter_d4, counter_d5, counter_d6, counter_last_m, counter_m1, counter_m2, counter_m3, counter_m4, counter_m5, counter_m6, counter_m7, counter_m8, counter_m9, counter_m10, counter_m11, counter_m12, counter_last_ip
            FROM counter_main
            WHERE ".$where;
        return $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ delete_counter_main_rs()
    
    /**
     * 删除计数器信息的记录集。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where      SQL 语句中的 WHERE 主体，不包含“WHERE”。
     * @return  void
     */         
    function delete_counter_main_rs($conn, $where)
    {
        $sql = "DELETE FROM counter_main WHERE ".$where;
        $conn->Execute($sql);
    }   //end function
    
    //  }}}
    
    //  {{{ update_counter_number()
    
    /**
     * 更新计数器数据库计数记录。
     *
     * @access    public
     * @global    array    $_SERVER     PHP 服务器环境变量数组。
     * @param     object ADOConnection  &$conn  建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param     string   $user_id     用户ID号。
     * @param     array    $last_ds     最后访问天、计数的数组。
     * @param     array    $ds          最后天字段的时间、计数的数组。
     * @param     array    $last_ms     最后访问月、计数的数组。
     * @param     array    $ms          最后月字段的时间、计数的数组。
     * @return    void
     */
    function update_counter_number(&$conn, $user_id, $last_ds, $ds, $last_ms, $ms)
    {

        $sql = "
            UPDATE counter_main
            SET counter_number=counter_number+1,
                counter_last_ip='".$_SERVER['REMOTE_ADDR']."',
                counter_last_d='".$last_ds[0].":".$last_ds[1]."', counter_d".$last_ds[1]."='".$ds[0].":".$ds[1]."',
                counter_last_m='".$last_ms[0].":".$last_ms[1]."', counter_m".$last_ms[1]."='".$ms[0].":".$ms[1]."'
            WHERE user_id='".$user_id."'";
        $conn->Execute($sql);
    }   //end function
    
    //  }}}
    
    //  {{{ get_sort_number()

    /**
     * 获取某一主页在主页排行中的总排行名次。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通主页空间使用的 ADOdb 数据库连接对象的引用。
     * @param   string    $counter_number       当前用户的计数器统计数字。
     * @return  integer
     */    
    function get_sort_number(&$conn, $counter_number)
    {
        $sql = "
            SELECT COUNT(*) 
            FROM counter_main
            WHERE FLOOR(counter_number)>".$counter_number;
        $rs = $conn->Execute($sql);
        return $rs->fields[0] + 1;
    }   //end function
    
    //  }}}

}   //end class 
?>
