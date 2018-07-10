<?php
//  $Id: cms_info.class.php,v 1.23 2003/07/18 09:17:48 nio Exp $

/**
 * 建网通信息发布类。
 * 此类主要用在建网通的信息发布，包括首页、栏目、内容、评论。
 *
 * @package K12CMS
 * @access public
 */
class CMS_Info
{
    //  {{{ private properties

    /**
     * CMS_Info 类文件的 Id，用于 CVS 版本追踪。
     * @var string
     * @access  private
     */
    var $_id = '$Id: cms_info.class.php,v 1.23 2003/07/18 09:17:48 nio Exp $';

    /**
     * CMS_Info 类中所用到的 CMS 对象实例。
     * @var object CMS
     * @access  private
     */
    var $_cms = null;

    //  }}}

    //  {{{ public properties

    /**
     * 栏目的头尾模板文件所在目录的物理路径。
     * @var string
     * @access  public
     */
    var $cat_hf_tpl_path = '';

    /**
     * 栏目的中间区域模板文件所在目录的物理路径。
     * @var string
     * @access  public
     */
    var $cat_middle_tpl_path = '';

    /**
     * 调查程序所在目录的 URL。
     * @var string
     * @access  public
     */
    var $poll_url = '';

    /**
     * 调查程序所在目录的物理路径。
     * @var string
     * @access  public
     */
    var $poll_path = '';

    /**
     * 静态 HTML 文件所在目录的 URL。
     * @var string
     * @access  public
     */
    var $html_url = '';

    /**
     * 静态 HTML 文件所在目录的物理路径。
     * @var string
     * @access  public
     */
    var $html_path = '';

    /**
     * 内容静态 HTML 文件所在目录的 URL。
     * @var string
     * @access  public
     */
    var $doc_html_url = '';

    /**
     * 内容静态 HTML 文件所在目录的物理路径。
     * @var string
     * @access  public
     */
    var $doc_html_path = '';

    /**
     * 调查静态 HTML 文件所在目录的 URL。
     * @var string
     * @access  public
     */
    var $poll_html_url = '';

    /**
     * 调查静态 HTML 文件所在目录的物理路径。
     * @var string
     * @access  public
     */
    var $poll_html_path = '';

    /**
     * 公告静态 HTML 文件所在目录的 URL。
     * @var string
     * @access  public
     */
    var $anno_html_url = '';

    /**
     * 公告静态 HTML 文件所在目录的物理路径。
     * @var string
     * @access  public
     */
    var $anno_html_path = '';

    /**
     * 栏目内容类型数组。
     * @var array
     * @access  public
     */

	 var $cat_body_types = array(
        'A' => '文章',
        'I' => '图像',
        'L' => '链接');

	 /**
     * 友情链接静态 HTML 文件所在目录的 URL。
     * @var string
     * @access  public
     */

	 var $link_url = '';

    /**
     * 友情链接静态 HTML 文件所在目录的物理路径。
     * @var string
     * @access  public
     */
    var $link_path = '';

    //  }}}

    //  {{{ constructor

    /**
     * CMS_Info 的类构造函数。
     * 在此构造函数中将对建网通信息发布所用到的目录路径进行初始化。
     *
     * @access  public
     * @param   object CMS  &$cms    建网通所用的 CMS 对象实例的引用。
     * @return  void
     */
    function CMS_Info(&$cms)
    {
        $this->_cms =& $cms;
        $this->cat_hf_tpl_path = $this->_cms->tpl_path.'/'.$this->_cms->modules['CAT']['DIR'].'/header_footer';
        $this->cat_middle_tpl_path = $this->_cms->tpl_path.'/'.$this->_cms->modules['CAT']['DIR'].'/middle';
        $this->poll_url = $this->_cms->app_url.'/'.$this->_cms->modules['POLL']['DIR'];
        $this->poll_path = $this->_cms->app_path.'/'.$this->_cms->modules['POLL']['DIR'];
        $this->html_url = $this->_cms->data_url.'/html';
        $this->html_path = $this->_cms->data_path.'/html';
        $this->doc_html_url = $this->html_url.'/doc';
        $this->doc_html_path = $this->html_path.'/doc';
        $this->anno_html_url = $this->html_url.'/announce';
        $this->anno_html_path = $this->html_path.'/announce';
        $this->poll_html_url = $this->html_url.'/poll';
        $this->poll_html_path = $this->html_path.'/poll';
		$this->link_url = $this->html_url.'/link';
        $this->link_path = $this->html_path.'/link';
	}   //end function

    //  }}}

    /**********************************************************************
     *                              栏 目                                 *
     **********************************************************************/

    //  {{{ get_cat_rs()

    /**
     * 获取符合条件的栏目数据记录集。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where  SQL 语句的 WHERE 主体。
     * @param   string  $order  SQL 语句的 ORDER BY 主体。
     * @return  object ADORecordSet
     */
    function &get_cat_rs(&$conn, $where = '', $order = 'cat_id')
    {
        $sql = "
            SELECT cat_id, cat_name, cat_thread_id, cat_parent_id, cat_user_type, cat_header_tpl_id, cat_footer_tpl_id, cat_confirm, cat_order, cat_forbidden
            FROM cat_main";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
        return $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ get_cat_next_id()

    /**
     * 获取栏目表的下一个自动递增 ID 值
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return  integer
     */
    function get_cat_next_id(&$conn)
    {
        if ('mysql' == $this->_cms->db_type) {
            $sql = "INSERT INTO cat_main SET cat_id=''";
            $rs = $conn->Execute($sql);
            return $conn->Insert_ID();
        } else if ('oracle' == $this->_cms->db_type) {
            $sql = "SELECT SEQ_CAT_MAIN.NEXTVAL FROM DUAL";
            $rs = $conn->Execute($sql);
            return $rs->fields[0];
        }   //end if
    }   //end function

    //  }}}

    //  {{{ update_cat_rs()

    /**
     * 更新栏目数据记录。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @param   array   $cats   包含栏目各字段信息的数组，其对应各字段如下：
     *                              array (
     *                                  'id'        => cat_id,
     *                                  'name'      => cat_name,
     *                                  'thread_id' => cat_thread_id,
     *                                  'parent_id' => cat_parent_id,
     *                                  'user_type' => cat_user_type,
     *                                  'header'    => cat_header_tpl_id,
     *                                  'footer'    => cat_footer_tpl_id,
     *                                  'confirm'    => cat_confirm)
     * @return  void
     */
    function update_cat_rs(&$conn, $cats)
    {
        if ('mysql' == $this->_cms->db_type) { //  MySQL
            $sql = "
                UPDATE cat_main
                SET cat_name=".$conn->qstr($cats['name']).",
                    cat_thread_id=".$cats['thread_id'].",
                    cat_parent_id=".$cats['parent_id'].",
                    cat_user_type=".$conn->qstr($cats['user_type']).",
                    cat_header_tpl_id=".$cats['header'].",
                    cat_footer_tpl_id=".$cats['footer'].",
                    cat_confirm=".$cats['confirm']."
                WHERE cat_id=".$cats['id'];
        } else if ('oracle' == $this->_cms->db_type) {  //  ORACLE
            $sql = "SELECT COUNT(*) FROM cat_main WHERE cat_id=".$cats['id'];
            $rs = $conn->Execute($sql);
            if ($rs->fields[0] > 0) {   //  UPDATE
                $sql = "
                    UPDATE cat_main
                    SET cat_name=".$conn->qstr($cats['name']).",
                        cat_thread_id=".$cats['thread_id'].",
                        cat_parent_id=".$cats['parent_id'].",
                        cat_user_type=".$conn->qstr($cats['user_type']).",
                        cat_header_tpl_id=".$cats['header'].",
                        cat_footer_tpl_id=".$cats['footer'].",
                        cat_confirm=".$cats['confirm']."
                    WHERE cat_id=".$cats['id'];
            } else {    //  INSERT
                $sql = "
                    INSERT INTO cat_main
                        (cat_id, cat_name, cat_thread_id, cat_parent_id, cat_user_type, cat_header_tpl_id, cat_footer_tpl_id, cat_confirm)
                    VALUES
                        (".$cats['id'].", ".$conn->qstr($cats['name']).", ".$cats['thread_id'].", ".$cats['parent_id'].", ".$conn->qstr($cats['user_type']).", ".$cats['header'].", ".$cats['footer'].", ".$cats['confirm'].")";
            }   //end if
        }   //end if
        $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ delete_cat_rs()

    /**
     * 删除栏目及其相关内容，其子栏目必须为空。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @param   integer $cat_id     需要删除的栏目的 ID 号。
     * @return  boolean
     */
    function delete_cat_rs(&$conn, $cat_id)
    {
        if ($cat_id) {
            $sql = "DELETE FROM cat_main WHERE cat_id=".$cat_id;
            $rs  = $conn->Execute($sql);
            $sql = "DELETE FROM cat_body WHERE cat_id=".$cat_id;
            $rs  = $conn->Execute($sql);
        }   //end if
    }   //end function
    //  }}}

    //  {{{ forbidden_cat_rs()

    /**
     * 隐藏栏目及其相关内容，其子栏目必须为空。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @param   integer $cat_id     需要删除的栏目的 ID 号。
     * @return  boolean
     */
    function forbidden_cat_rs(&$conn, $child_ids)
    {
        if ($child_ids) {
            foreach($child_ids as $k => $cat_id){
                $sql = "UPDATE cat_main SET CAT_FORBIDDEN = '1' WHERE cat_id=".$cat_id;
                $conn->Execute($sql);
            }
        }   //end if
    }   //end function
    //  }}}

    //  {{{ show_cat_rs()

    /**
     * 展示栏目及其相关内容
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @param   integer $cat_id     需要删除的栏目的 ID 号。
     * @return  boolean
     */
    function show_cat_rs(&$conn, $child_ids)
    {

        if ($child_ids) {
            foreach($child_ids as $k =>$cat_id){
                $sql = "UPDATE cat_main SET CAT_FORBIDDEN = '0' WHERE cat_id=".$cat_id;
                $conn->Execute($sql);
            }
        }   //end if
    }   //end function


    //  }}}

    //  {{{ get_cat_child_ids()

    /**
     * 递归获取 ID 号为 $cat_id 的栏目的所有子栏目（一直到最底层栏目） ID 号数组集合。
     *
     * @access  public
     * @param   integer $cat_id         需要获取的栏目 ID 号。
     * @param   array   $cat_child_ids  用于保存子栏目 ID 号的数组变量。
     * @return  void
     */
    function get_cat_child_ids($cat_id, &$cat_child_ids)
    {
        $this->_cms->require_cat_array();
        $child_ids = $this->_cms->cats[$cat_id]['CHILD'];
        if (is_array($child_ids)) {
            foreach ($child_ids as $child_id) {
                array_push($cat_child_ids, $child_id);
                $this->get_cat_child_ids($child_id, $cat_child_ids);
            }   //end foreach
        }   //end if
    }   //end function

    //  }}}

    //  {{{ move_cat_rs()

    /**
     * 移动栏目，处理数据库记录。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @param   integer $cat_id         栏目 ID 号。
     * @param   integer $cat_parent_id  上层栏目的 ID 号。
     * @param   array   $cat_children   子栏目的 ID 号保存的数组。
     * @return  void
     */
    function move_cat_rs(&$conn, $cat_id, $cat_parent_id, $cat_children)
    {
        $cat_thread_id = $this->_cms->cats[$cat_parent_id]['THREAD'];
        if (!$cat_thread_id)
            $cat_thread_id = $cat_id;
        $sql = "
            UPDATE cat_main
            SET cat_parent_id=".$cat_parent_id.", cat_thread_id=".$cat_thread_id."
            WHERE cat_id=".$cat_id;
        $conn->Execute($sql);

        if (count($cat_children)) {
            $sql = "
                UPDATE cat_main
                SET cat_thread_id=".$cat_thread_id."
                WHERE cat_id IN (".implode(',', $cat_children).")";
            $rs = $conn->Execute($sql);
        }   //end if
    }   //end function mMoveCat

    //  }}}

    //  {{{ create_cat_file()

    /**
     * 生成栏目设置数组变量文件。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @return  void
     */
    function create_cat_file(&$conn)
    {
        $cats = array();
        $cat_tops = array();
        $rs = &$this->get_cat_rs($conn);
        while (!$rs->EOF) {
            $cat_id         = $rs->fields[0];
            $cat_name       = $rs->fields[1];
            $cat_thread_id  = $rs->fields[2];
            $cat_parent_id  = $rs->fields[3];
            $cat_user_type  = $rs->fields[4];
            $cat_header     = $rs->fields[5];
            $cat_footer     = $rs->fields[6];
            $cat_confirm    = $rs->fields[7];
            $cat_order      = ($rs->fields[8]) ? $rs->fields[8] : $rs->fields[0];
            $cat_forbidden  = $rs->fields[9];
            $cats[$cat_id]['NAME']      = $cat_name;
            $cats[$cat_id]['THREAD']    = $cat_thread_id;
            $cats[$cat_id]['PARENT']    = $cat_parent_id;
            $cats[$cat_id]['USERTYPE']  = $cat_user_type;
            $cats[$cat_id]['HEADER']    = $cat_header;
            $cats[$cat_id]['FOOTER']    = $cat_footer;
            $cats[$cat_id]['CONFIRM']   = $cat_confirm;
            $cats[$cat_id]['FORBIDDEN'] = $cat_forbidden;
            if (!isset($cats[$cat_id]['CHILD']))
                $cats[$cat_id]['CHILD'] = array();
            if ($cat_parent_id)
                $cats[$cat_parent_id]['CHILD'][$cat_order] = $cat_id;
            else
                $cat_tops[$cat_order] = $cat_id;
            $rs->MoveNext();
        }   //end while
        $rs->Close();
        $content  = '<?'.'php'.CRLF;
        $content .= '$cfg_cats = '.var_export($cats, TRUE).';'.CRLF;
        $content .= '$cfg_cat_tops = '.var_export($cat_tops, TRUE).';'.CRLF;
        $content .= '?'.'>';
        CMS_Common :: write_file($this->_cms->config_path.'/cms_cat.inc.php', $content);
    }   //end function

    //  }}}

    //  {{{ get_cat_tpl_rs()

    /**
     * 获取符合条件的栏目模板文件数据记录集。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where  SQL 语句的 WHERE 主体。
     * @param   string  $order  SQL 语句的 ORDER BY 主体。
     * @return  object ADORecordSet
     */
    function &get_cat_tpl_rs(&$conn, $where = '', $order = '')
    {
        $sql = "
            SELECT cat_tpl_id, cat_tpl_name, cat_tpl_type
            FROM cat_tpl";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
        return $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ get_cat_tpl_next_id()

    /**
     * 获取栏目头尾模板文件表的下一个自动递增 ID 值
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return  integer
     */
    function get_cat_tpl_next_id(&$conn)
    {
        if ('mysql' == $this->_cms->db_type) {
            $sql = "INSERT INTO cat_tpl SET cat_tpl_id=''";
            $rs = $conn->Execute($sql);
            return $conn->Insert_ID();
        } else if ('oracle' == $this->_cms->db_type) {
            $sql = "SELECT SEQ_CAT_TPL.NEXTVAL FROM DUAL";
            $rs = $conn->Execute($sql);
            return $rs->fields[0];
        }   //end if
    }   //end function

    //  }}}


    //  {{{ update_cat_tpl_rs()

    /**
     * 更新栏目头尾模板文件数据。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $cat_tpl_id     模板文件 ID 号，对应字段 CAT_TPL_ID。
     * @param   string  $cat_tpl_name   模板文件名称，对应字段 CAT_TPL_NAME。
     * @param   integer $cat_tpl_type   模板类型，对应字段 CAT_TPL_TYPE，其值如下：
     *                                      'H' -> 头文件模板
     *                                      'F' -> 尾文件模板
     * @return  void
     */
    function update_cat_tpl_rs(&$conn, $cat_tpl_id, $cat_tpl_name, $cat_tpl_type)
    {
        if ('mysql' == $this->_cms->db_type) { //  MySQL
            $sql = "
                UPDATE cat_tpl
                SET cat_tpl_name=".$conn->qstr($cat_tpl_name).",
                    cat_tpl_type=".$conn->qstr($cat_tpl_type)."
                WHERE cat_tpl_id=".$cat_tpl_id;
        } else if ('oracle' == $this->_cms->db_type) { //  ORACLE
            $sql = "SELECT COUNT(*) FROM cat_tpl WHERE cat_tpl_id=".$cat_tpl_id;
            $rs = $conn->Execute($sql);
            if ($rs->fields[0] > 0) {   //UPDATE
                $sql = "
                    UPDATE cat_tpl
                    SET cat_tpl_name=".$conn->qstr($cat_tpl_name).",
                        cat_tpl_type=".$conn->qstr($cat_tpl_type)."
                    WHERE cat_tpl_id=".$cat_tpl_id;
            } else {    //  INSERT
                $sql = "
                    INSERT INTO cat_tpl
                        (cat_tpl_id, cat_tpl_name, cat_tpl_type)
                    VALUES
                        (".$cat_tpl_id.", ".$conn->qstr($cat_tpl_name).", ".$conn->qstr($cat_tpl_type).")";
            }   //end if
        }   //end if
        $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ delete_cat_tpl_rs()

    /**
     * 删除栏目头尾模板文件数据记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   $cat_tpl_id      integer 需要删除的栏目模板文件的 ID 号
     * @return  void
     */
    function delete_cat_tpl_rs(&$conn, $cat_tpl_id)
    {
        if ($cat_tpl_id) {
            $sql = "
                DELETE FROM cat_tpl
                WHERE cat_tpl_id=".$cat_tpl_id;
            $conn->Execute($sql);
        }   //end if
    }   //end function

    //  }}}

    //  {{{ create_cat_tpl_file()

    /**
     * 生成栏目头尾模板文件的设置数组变量文件。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return  void
     */
    function create_cat_tpl_file(&$conn)
    {
        $cat_tpls = array();
        $rs = &$this->get_cat_tpl_rs($conn);
        while (!$rs->EOF) {
            $cat_tpl_id     = $rs->fields[0];
            $cat_tpls[$cat_tpl_id] = array(
                'NAME' => $rs->fields[1],
                'TYPE' => $rs->fields[2]);
            $rs->MoveNext();
        }   //end while
        $rs->Close();
        $content  = '<?'.'php'.CRLF;
        $content .= '$cfg_cat_tpls = '.var_export($cat_tpls, TRUE).';'.CRLF;
        $content .= '?'.'>';
        CMS_Common :: write_file($this->_cms->config_path.'/cms_cat_tpl.inc.php', $content);
    }   //end function

    //  }}}

    //  {{{ update_cat_order()

    /**
     * 更新栏目排列顺序数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $cat_id     栏目 ID 号。
     * @param   string  $order   栏目顺序号。
     * @return  void
     */
    function update_cat_order($conn, $cat_id, $order) {
        $sql = "UPDATE cat_main SET cat_order=".$order." where cat_id=".$cat_id;
        $conn->Execute($sql);
    }   //end function

    //  }}}

    /**********************************************************************
     *                              内 容                                 *
     **********************************************************************/

    //  {{{ get_cat_body_rs()

    /**
     * 获取内容数据库记录，或符合条件的数据库记录总数。
     * 字段顺序如下：
     *   0 -> CAT_BODY_ID
     *   1 -> CAT_ID
     *   2 -> CAT_BODY_TITLE
     *   3 -> CAT_BODY_AUTHOR
     *   4 -> CAT_BODY_TYPE
     *   5 -> CAT_BODY_URL
     *   6 -> CAT_BODY_KEYWORD
     *   7 -> CAT_BODY_INDATE
     *   8 -> CAT_BODY_OUTEDATE
     *   9 -> CAT_BODY_CLICK
     *  10 -> CAT_BODY_RECOMMEND
     *  11 -> CAT_BODY_CONFIRM
     *  12 -> CAT_BODY_ICON
     *  13 -> CAT_BODY_CONTENT（参数 $simple = FALSE 时才有此字段）
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $where      SQL 语句的 WHERE 部分（不用带“WHERE”关键字）。
     * @param   string  $order      SQL 语句的 ORDER BY 部分（不用带“ORDER BY”关键字）。
     * @param   boolean $cache      是否使用 ADOdb cache，默认值为 TRUE。
     * @param   boolean $simple     是否获取简单记录集，默认值为 TRUE，若为 FALSE 则获取包括具体内容在内的所有字段。
     * @param   mixed   $numrows    获取的记录条数；若此参数为“count”，则返回符合查询条件的记录总数。
     * @param   integer $offset     开始记录的下标。
     * @return  object ADORecordSet
     */
    function &get_cat_body_rs(&$conn, $where = '', $order = '', $cache = TRUE, $simple = TRUE, $numrows = -1, $offset = -1)
    {
        if ('count' == $numrows)
        {
            $field_str = "COUNT(*)";
            $numrows = -1;
        } else {
            $field_str = "cat_body_id, cat_id, cat_body_title, cat_body_author, cat_body_type, cat_body_url, cat_body_keyword, cat_body_indate, cat_body_outdate, cat_body_click, cat_body_recommend, cat_body_confirm, cat_body_icon ";

            if (FALSE == $simple)
             { $field_str .= ', cat_body_content';}
           $field_str .= ',cat_body_adjunct,first_name,cat_body_sn,user_id';
        }   //end if
        $sql = "SELECT ".$field_str." FROM cat_body";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
        if (TRUE == $cache) {
            $rs = $conn->CacheSelectLimit($sql, $numrows, $offset);

        } else {
            $rs = $conn->SelectLimit($sql, $numrows, $offset);
        }
        return $rs;
    }   //end function

     function &get_cat_body_free_rs(&$conn, $where = '', $order = '', $cache = TRUE, $simple = TRUE, $numrows = -1, $offset = -1)
    {

    	//$conn->debug=true;
        if ('count' == $numrows)
        {
            $field_str = "COUNT(*)";
            $numrows = -1;
        } else {
            $field_str = "cat_body_free_id, cat_id, cat_body_title, cat_body_author, cat_body_type, cat_body_url, cat_body_keyword, cat_body_indate, cat_body_outdate, cat_body_click, cat_body_recommend, cat_body_confirm, cat_body_icon ";

            if (FALSE == $simple)
             { $field_str .= ', cat_body_content';}
           $field_str .= ',cat_body_adjunct,first_name';
           $field_str .=',status,is_commit,publish_time1';
        }   //end if
        $sql = "SELECT ".$field_str." FROM cat_body_free";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
        if (TRUE == $cache) {
            $rs = $conn->CacheSelectLimit($sql, $numrows, $offset);
        } else {
            $rs = $conn->SelectLimit($sql, $numrows, $offset);
        }   //end if
//echo $sql;
   //$conn->debug=false;
        return $rs;
    }   //end function

    //  }}}

    //  {{{ get_cat_body_next_id()

    /**
     * 获取内容表的下一个自动递增 ID 值
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return  integer
     */
    function get_cat_body_next_id(&$conn)
    {

        if ('mysql' == $this->_cms->db_type) {
			$user_id = $_SESSION[$this->_cms->sess_user_id];
            $user_name = $_SESSION[$this->_cms->sess_true_name];
            $sql = "INSERT INTO cat_body SET cat_body_id='', user_id=".$conn->qstr($user_id).", user_name=".$conn->qstr($user_name);

            $rs = $conn->Execute($sql);
            return $conn->Insert_ID();
        } else if ('oracle' == $this->_cms->db_type) {
            $sql = "SELECT SEQ_CAT_BODY.NEXTVAL FROM DUAL";
            $rs = $conn->Execute($sql);
            return $rs->fields[0];
        }   //end if

    }   //end function

    //  }}}
  //  {{{ get_cat_body_next_id()

    /**
     * 获取内容表的下一个自动递增 ID 值
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @return  integer
     */
    function get_cat_body_free_next_id(&$conn)
    {
        if ('mysql' == $this->_cms->db_type) {
            $sql = "INSERT INTO cat_body_free SET cat_body_free_id=''";

            $rs = $conn->Execute($sql);
            return $conn->Insert_ID();
        } else if ('oracle' == $this->_cms->db_type) {
            $sql = "SELECT SEQ_CAT_BODY_FREE.NEXTVAL FROM DUAL";
            $rs = $conn->Execute($sql);
            return $rs->fields[0];
        }   //end if

    }   //end function

    //  }}}
    //  {{{ update_cat_body_rs()

    /**
     * 更新内容数据记录。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @param   array   $docs   包含栏目各字段信息的数组，其对应各字段如下：
     *                              array (
     *                                  'id'        => cat_body_id,
     *                                  'cat_id'    => cat_id,
     *                                  'title'     => cat_body_title,
     *                                  'author'    => cat_body_author,
     *                                  'type'      => cat_body_type,
     *                                  'url'       => cat_body_url,
     *                                  'keyword'   => cat_body_keyword,
     *                                  'indate'    => cat_body_indate,
     *                                  'outdate'   => cat_body_outdate,
     *                                  'recommend' => cat_body_recommend,
     *                                  'confirm'   => cat_body_confirm,
     *                                  'icon'      => cat_body_icon,
     *                                  'content'   => cat_body_content)
     * @return  void
     */
    function update_cat_body_rs(&$conn, $docs)
    {

      if (empty($docs['recommend']))
            $docs['recommend'] = 0;
        if ($docs['id']) {
            if ('mysql' == $this->_cms->db_type) { //  MySQL
                $sql = "
                    UPDATE cat_body
                    SET cat_id=".$docs['cat_id'].",
                        cat_body_title=".$conn->qstr($docs['title']).",
                        cat_body_author=".$conn->qstr($docs['author']).",
                        cat_body_type=".$conn->qstr($docs['type']).",
                        cat_body_url=".$conn->qstr($docs['url']).",
                        cat_body_keyword=".$conn->qstr($docs['keyword']).",
                        cat_body_indate=".$conn->qstr($docs['indate']).",
                        cat_body_outdate=".$conn->qstr($docs['outdate']).",
                        cat_body_recommend=".$docs['recommend'].",
                        cat_body_confirm=".$docs['confirm'].",
                        cat_body_icon=".$conn->qstr($docs['icon']).",
                        cat_body_content=".$conn->qstr($docs['content']).",
                        cat_body_adjunct=".$conn->qstr($docs['adjunct']).",
                        first_name =".$conn->qstr($docs['first_name']);
					if (!empty($docs['cat_body_creater']))
						$sql .= ", user_id=".$conn->qstr($docs['cat_body_creater']);
					if (!empty($docs['cat_body_crname']))
						$sql .= ", user_name=".$conn->qstr($docs['cat_body_crname']);
				if (!empty($docs['sn']))
					$sql .= ", cat_body_sn=".intval($docs['sn']);
                $sql .= " WHERE cat_body_id=".$docs['id'];
                 // echo $sql;
                $conn->Execute($sql);
            } else if ('oracle' == $this->_cms->db_type) { //  ORACLE
                $sql = "SELECT COUNT(*) FROM cat_body WHERE cat_body_id=".$docs['id'];
                $rs = $conn->Execute($sql);
                if ($rs->fields[0] > 0) {   //  UPDATE
                    $sql = "
                        UPDATE cat_body
                        SET cat_id=".$docs['cat_id'].",
                            cat_body_title=".$conn->qstr($docs['title']).",
                            cat_body_author=".$conn->qstr($docs['author']).",
                            cat_body_type=".$conn->qstr($docs['type']).",
                            cat_body_url=".$conn->qstr($docs['url']).",
                            cat_body_keyword=".$conn->qstr($docs['keyword']).",
                            cat_body_indate=".$conn->qstr($docs['indate']).",
                            cat_body_outdate=".$conn->qstr($docs['outdate']).",
                            cat_body_recommend=".$docs['recommend'].",
                            cat_body_confirm=".$docs['confirm'].",
                            cat_body_icon=".$conn->qstr($docs['icon']).",
                            cat_body_adjunct=".$conn->qstr($docs['adjunct']).",
                            first_name =".$conn->qstr($docs['first_name']).",
						    user_id =".$conn->qstr($docs['cat_body_creater']).",
						    user_name =".$conn->qstr($docs['cat_body_crname']);
					if (!empty($docs['sn']))
						$sql .= ", cat_body_sn=".intval($docs['sn']);
                    $sql .= " WHERE cat_body_id=".$docs['id'];
                } else {    //  INSERT
				   // $user_id = $_SESSION[$this->_cms->sess_user_id];
                   // $user_name = $_SESSION[$this->_cms->sess_true_name];
                	if (empty($docs['sn'])) $docs['sn'] = 0;
                    $sql = "
                        INSERT INTO cat_body
                            (cat_body_id, cat_id, cat_body_title, cat_body_author, cat_body_type, cat_body_url, cat_body_keyword, cat_body_indate, cat_body_outdate, cat_body_recommend, cat_body_confirm, cat_body_icon, cat_body_content, cat_body_adjunct, first_name, cat_body_sn, user_id, user_name)
                        VALUES
                            (".$docs['id'].", ".$docs['cat_id'].", ".$conn->qstr($docs['title']).", ".$conn->qstr($docs['author']).", ".$conn->qstr($docs['type']).", ".$conn->qstr($docs['url']).", ".$conn->qstr($docs['keyword']).", ".$conn->qstr($docs['indate']).", ".$conn->qstr($docs['outdate']).", ".$docs['recommend'].", ".$docs['confirm'].", ".$conn->qstr($docs['icon']).", empty_clob(), ".$conn->qstr($docs['adjunct']).", ".$conn->qstr($docs['first_name']).", ".$conn->qstr($docs['sn']).",".$conn->qstr($docs['cat_body_creater']).", ".$conn->qstr($docs['cat_body_crname']).")";
					//echo$sql;exit;

                }
                $conn->Execute($sql);

                if ($docs['content'])

                    $conn->UpdateClob('cat_body', 'cat_body_content', $docs['content'], 'cat_body_id='.$docs['id']);
            }   //end if
              //echo $sql;
        }   //end if
    }   //end function

    //  }}}
    //  {{{ update_cat_body_free_rs()

    /**
     * 更新内容数据记录。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @param   array   $docs   包含栏目各字段信息的数组，其对应各字段如下：
     *                              array (
     *                                  'id'        => cat_body_id,
     *                                  'cat_id'    => cat_id,
     *                                  'title'     => cat_body_title,
     *                                  'author'    => cat_body_author,
     *                                  'type'      => cat_body_type,
     *                                  'url'       => cat_body_url,
     *                                  'keyword'   => cat_body_keyword,
     *                                  'indate'    => cat_body_indate,
     *                                  'outdate'   => cat_body_outdate,
     *                                  'recommend' => cat_body_recommend,
     *                                  'confirm'   => cat_body_confirm,
     *                                  'icon'      => cat_body_icon,
     *                                  'content'   => cat_body_content)
     * @return  void
     */
    function update_cat_body_free_rs(&$conn, $docs)
    {
    	//print_r($docs);
      //$conn->debug=true;
      if (empty($docs['recommend']))
            $docs['recommend'] = 0;
        if ($docs['id']) {
            if ('mysql' == $this->_cms->db_type) { //  MySQL
                $sql = "
                    UPDATE cat_body_free
                    SET cat_id=".$docs['cat_id'].",
                        cat_body_title=".$conn->qstr($docs['title']).",
                        cat_body_author=".$conn->qstr($docs['author']).",
                        cat_body_type=".$conn->qstr($docs['type']).",
                        cat_body_url=".$conn->qstr($docs['url']).",
                        cat_body_keyword=".$conn->qstr($docs['keyword']).",
                        cat_body_indate=".$conn->qstr($docs['indate']).",
                        cat_body_outdate=".$conn->qstr($docs['outdate']).",
                        cat_body_recommend=".$docs['recommend'].",
                        cat_body_confirm=".$docs['confirm'].",
                        cat_body_icon=".$conn->qstr($docs['icon']).",
                        cat_body_content=".$conn->qstr($docs['content']).",
                        cat_body_adjunct=".$conn->qstr($docs['adjunct']).",
                        first_name =".$conn->qstr($docs['first_name']);

                 if($docs['publisher']!=''){
                  $sql.=",publisher=".$conn->qstr($docs['publisher']);
                }
                    if($docs['is_commit']!=''){
                  $sql.=",is_commit=".$docs['is_commit'];
                }

                   $sql.=" WHERE cat_body_free_id=".$docs['id'];
                 // echo $sql;
                $conn->Execute($sql);
            } else if ('oracle' == $this->_cms->db_type) { //  ORACLE
                $sql = "SELECT COUNT(*) FROM cat_body_free WHERE cat_body_free_id=".$docs['id'];
                $rs = $conn->Execute($sql);
                if ($rs->fields[0] > 0) {   //  UPDATE
                    $sql = "
                        UPDATE cat_body_free
                        SET cat_id=".$docs['cat_id'].",
                            cat_body_title=".$conn->qstr($docs['title']).",
                            cat_body_author=".$conn->qstr($docs['author']).",
                            cat_body_type=".$conn->qstr($docs['type']).",
                            cat_body_url=".$conn->qstr($docs['url']).",
                            cat_body_keyword=".$conn->qstr($docs['keyword']).",
                            cat_body_indate=".$conn->qstr($docs['indate']).",
                            cat_body_outdate=".$conn->qstr($docs['outdate']).",
                            cat_body_recommend=".$docs['recommend'].",
                            cat_body_confirm=".$docs['confirm'].",
                            cat_body_icon=".$conn->qstr($docs['icon']).",
                            cat_body_adjunct=".$conn->qstr($docs['adjunct']).",
                            first_name =".$conn->qstr($docs['first_name']);
                           if($docs['is_commit']!=''){
                                 $sql.=",is_commit=".$docs['is_commit'];
                               }
                            $sql.=" WHERE cat_body_free_id=".$docs['id'];
                } else {    //  INSERT

                    $sql = "
                        INSERT INTO cat_body_free
                            (cat_body_free_id, cat_id, cat_body_title, cat_body_author, cat_body_type, cat_body_url, cat_body_keyword, cat_body_indate, cat_body_outdate, cat_body_recommend, cat_body_confirm, cat_body_icon, cat_body_content,cat_body_adjunct,first_name,publisher  )
                        VALUES
                            (".$docs['id'].", ".$docs['cat_id'].", ".$conn->qstr($docs['title']).", ".$conn->qstr($docs['author']).", ".$conn->qstr($docs['type']).", ".$conn->qstr($docs['url']).", ".$conn->qstr($docs['keyword']).", ".$conn->qstr($docs['indate']).", ".$conn->qstr($docs['outdate']).", ".$docs['recommend'].", ".$docs['confirm'].", ".$conn->qstr($docs['icon']).", empty_clob(), ".$conn->qstr($docs['adjunct']).", ".$conn->qstr($docs['first_name']).", ".$conn->qstr($docs['publisher']).")";
					//echo$sql;exit;

                }
                $conn->Execute($sql);

                if ($docs['content'])

                    $conn->UpdateClob('cat_body_free', 'cat_body_content', $docs['content'], 'cat_body_free_id='.$docs['id']);
            }   //end if
              //echo $sql;
        }   //end if

       // die("fffffffffffffffff");
    }   //end function

    //  }}}
    //  {{{ update_cat_body_url()

    /**
     * 更新内容的 URL 字段。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $cat_body_id    想要更新的内容的 ID 号。
     * @param   string  $cat_body_url   内容的 URL 值。
     * @return  void
     */
    function update_cat_body_url(&$conn, $cat_body_id, $cat_body_url)
    {
        if ($cat_body_id) {
            $sql = "UPDATE cat_body SET cat_body_url=".$conn->qstr($cat_body_url)." WHERE cat_body_id=".$cat_body_id;
            $conn->Execute($sql);
        }   //end if
    }  //end function

    //  }}}
//  {{{ update_cat_body_confirm()

    /**
     * 更新内容的 update_cat_body_free_confirm 字段，将内容通过验证。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $cat_body_idstr     想要更新的内容的 ID 号的组合字符串，以“,”分隔。
     * @return  void
     */
function update_cat_body_free_confirm(&$conn, $cat_body_arr,$m_status=1)
{
	$ret=0;
	//根据cat_body_free的相应记录添加到cat_body表中
	if ($cat_body_arr) {
	  while (list($key, $value) = each ($cat_body_arr)) {

            $field_str = "cat_body_free_id,
                          cat_id,
                          cat_body_title,
                          cat_body_author,
                          cat_body_type,
                          cat_body_url,
                          cat_body_keyword,
                          cat_body_content,
                          cat_body_indate,
                          cat_body_outdate,
                          cat_body_click,
                          cat_body_recommend,
                          cat_body_confirm,
                          cat_body_icon,
                          cat_body_adjunct,
                          first_name
                           ";

        $sql = "SELECT ".$field_str." FROM cat_body_free where status=0 and cat_body_free_id=".$value;
        $rs=$conn->Execute($sql);

        if($rs!="" && !$rs->EOF ){
               $docs['id'] = $this->get_cat_body_next_id($conn);
               $docs['cat_id'] = $rs->fields[1];
               $docs['title'] = $rs->fields[2];
               $docs['author'] = $rs->fields[3];
               $docs['type'] = $rs->fields[4];
               $docs['url'] = $rs->fields[5];
               $docs['keyword'] = $rs->fields[6];
               $docs['content'] = $rs->fields[7];
               $docs['indate'] = $rs->fields[8];
               $docs['outdate'] = $rs->fields[9];
               $docs['click'] = $rs->fields[10];
               $docs['recommend'] = $rs->fields[11];
               $docs['confirm'] = 1;
               $docs['icon'] = $rs->fields[13];
               $docs['adjunct'] = $rs->fields[14];
               $docs['first_name'] = $rs->fields[15];
               // $docs['publish_time']=date("Y-m-d");

               $this->update_cat_body_rs($conn,$docs);
               $sql1="update cat_body_free set publish_time1='".date("Y-m-d")."',status=$m_status,cat_body_id=".$docs['id']." where cat_body_free_id=".$value;
               $conn->Execute($sql1);
               $ret+=1;
           }//end of if($rs)
            // print_r($docs);
            // die("hh");

          }//end of while


        }   //end if
	return $ret;
}//end function

    //  }}}
    //  {{{ update_cat_body_confirm()

    /**
     * 更新内容的 cat_body_confirm 字段，将内容通过验证。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $cat_body_idstr     想要更新的内容的 ID 号的组合字符串，以“,”分隔。
     * @return  void
     */
    function update_cat_body_confirm(&$conn, $cat_body_idstr)
    {
        if ($cat_body_idstr) {

            $sql = "UPDATE cat_body SET cat_body_confirm=1 WHERE cat_body_id IN (".$cat_body_idstr.")";
            $conn->Execute($sql);
        }   //end if
    }  //end function

    //  }}}

      function update_cat_body_free_throw(&$conn, $cat_body_idstr)
    {
        $ret=0;
        if ($cat_body_idstr) {

            $sql = "UPDATE cat_body_free SET publish_time1='".date("Y-m-d")."',status=2 WHERE status=0 and cat_body_free_id IN (".$cat_body_idstr.")";
            //echo $sql;
            //die("");
            $conn->Execute($sql);
            $ret=$conn->Affected_Rows();
        }   //end if
        return $ret;
    }  //end function

    //  {{{ update_cat_body_click()

    /**
     * 更新内容的点击次数，即阅读数。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $cat_body_id    想要更新的内容的 ID 号。
     * @return  void
     */
    function update_cat_body_click(&$conn, $cat_body_id)
    {
        if ($cat_body_id) {
            $sql = "UPDATE cat_body SET cat_body_click=cat_body_click+1 WHERE cat_body_id=".$cat_body_id;
            $conn->Execute($sql);
        }   //end if
    }  //end function

    //  }}}

	/**
	 * 获取给予新内容的文章编码。
	 */
	function get_cat_body_new_sn(&$conn, $cat_id)
	{
		$sql = "SELECT MAX(cat_body_sn) FROM cat_body WHERE cat_id=".intval($cat_id);
		$rs = $conn->Execute($sql);
		return $rs->fields[0]+1;
	}

    //  {{{ delete_cat_body_free_rs()

    /**
     * 删除内容记录，必须设置 WHERE 主体语句。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @return  void
     */
    function delete_cat_body_free_rs(&$conn, $where)
    {
        $sql = "DELETE FROM cat_body_free WHERE ".$where;
        $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ delete_cat_body_rs()

    /**
     * 删除内容记录，必须设置 WHERE 主体语句。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @return  void
     */
    function delete_cat_body_rs(&$conn, $where)
    {
        //$conn->debug=true;
        $sql = "DELETE FROM cat_body WHERE ".$where;
        $conn->Execute($sql);
        //$conn->debug=false;
    }   //end function

    //  }}}

    //  {{{ move_cat_body_rs()

    /**
     * 移动内容到另外一个栏目
     *
     * @access  public
     * @param   string    $cat_body_id_str    需要移动的内容ID号组成的字符串，以逗号“,”分割。
     * @param   integer   $aCatId             目标栏目的 ID 号。
     * @return  void
     */
    function move_cat_body_rs(&$conn, $cat_body_id_str, $cat_id)
    {
        if (!$cat_id)
            return false;
		$cat_id = intval($cat_id);
		$cat_body_ids = explode(',', $cat_body_id_str);
		foreach ($cat_body_ids as $cat_body_id) {
			$cat_body_sn = $this->get_cat_body_new_sn($conn, $cat_id);
			$sql = "UPDATE cat_body SET cat_id=".$cat_id.", cat_body_sn=".$cat_body_sn." WHERE cat_body_id=".intval($cat_body_id);
			$conn->Execute($sql);
		}
    }   //end function

    //  }}}

	//  {{{ forward_cat_body_rs()  2009/09/27 coral

    /**
     * 转发内容到另外一个栏目
     *
     * @access  public
     * @param   string    $cat_body_id_str    需要转发的内容ID号组成的字符串，以逗号“,”分割。
     * @param   integer   $aCatId             目标栏目的 ID 号。
     * @return  void
     */
    function forward_cat_body_rs(&$conn, $cat_body_id_str, $cat_id)
    {
		$conn->debug=true;
        if (!$cat_id||!$cat_body_id_str)
            return false;
		$cat_id = intval($cat_id);
		$cat_body_ids = explode(',', $cat_body_id_str);
		$rs=$this->get_cat_rs($conn,'cat_id='.$cat_id);
		if($rs->fields[7]==1) $cat_body_confirm=0; else $cat_body_confirm=1;
		$cat_body_sn = $this->get_cat_body_new_sn($conn, $cat_id);
		foreach ($cat_body_ids as $cat_body_id) {
			$sql = "select cat_id , cat_body_title , cat_body_author , cat_body_type , cat_body_url , cat_body_keyword , cat_body_content , cat_body_indate , cat_body_outdate , cat_body_click , cat_body_recommend , cat_body_confirm , cat_body_icon , cat_body_adjunct , first_name , cat_body_sn , user_id , user_name from cat_body where cat_body_id=".intval($cat_body_id);
			$rs=$conn->Execute($sql);
			$cat_body_id = $this->get_cat_body_next_id($conn);
			$sql = "insert into cat_body (cat_body_id , cat_id , cat_body_title , cat_body_author , cat_body_type , cat_body_url , cat_body_keyword , cat_body_content , cat_body_indate , cat_body_outdate , cat_body_click , cat_body_recommend , cat_body_confirm , cat_body_icon , cat_body_adjunct , first_name , cat_body_sn , user_id , user_name) values (".$cat_body_id.",".$cat_id." , '".$rs->fields[1]."' , '".$rs->fields[2]."' , '".$rs->fields[3]."' , '".$rs->fields[4]."' , '".$rs->fields[5]."' , '".$rs->fields[6]."' , '".$rs->fields[7]."' , '".$rs->fields[8]."' , '".$rs->fields[9]."' , '".$rs->fields[10]."' , '".$cat_body_confirm."' , '".$rs->fields[12]."' , '".$rs->fields[13]."' , '".$rs->fields[14]."' , '".$cat_body_sn."' , '".$rs->fields[15]."' , '".$rs->fields[16]."')";
			$conn->Execute($sql);
		}
    }   //end function

    //  }}}

    //  {{{ get_doc_static_url()

    /**
     * 获取内容静态文件及附件存放目录的 URL，若想得到物理路径，只需在返回值之前加上 $_SERVER['DOCUMENT_ROOT'] 即可。
     * 最终的目录 URL 为：/cms/data/html/doc/YYYY-MM/DD/$cat_body_id，其中 YYYY-MM-DD = $cat_body_indate。
     *
     * @access  public
     * @param   integer $cat_body_id        内容的 ID 号。
     * @param   string  $cat_body_indate    内容的存储日期。
     * @return  string
     */
    function get_doc_static_url($cat_body_id, $cat_body_indate)
    {
        return $this->doc_html_url.'/'.substr($cat_body_indate, 0, 7).'/'.substr($cat_body_indate, 8, 2).'/'.$cat_body_id;
    }  //end function

    //  }}}

    //  {{{ make_doc_dir()

    /**
     * 为内容创建相关目录，用于保存附件和静态文件。
     *
     * @access  public
     * @param   integer $cat_body_id        内容的 ID 号。
     * @param   strting $cat_body_indate    内容入库的时间，格式：YYYY-MM-DD。
     * @global  array   $_SERVER            PHP 服务器变量数组。
     * @return  mixed   若出错则返回 FALSE；成功则返回数组，此数组的结构如下：
     *                      array(
     *                          'doc_url'       -> 静态文件存放目录的 URL,
     *                          'doc_path'      -> 静态文件存放目录的物理路径,
     *                          'attach_url'    -> 附件存放目录的 URL,
     *                          'attach_path'   -> 静态文件存放目录的物理路径,
     *                          'preffix'       -> 附件文件名的前缀
     *                      );
     */
    function make_doc_dir($cat_body_id, $cat_body_indate)
    {

        if (empty($cat_body_indate))
            return FALSE;
        $dir1 = substr($cat_body_indate, 0, 7);
        $dir2 = substr($cat_body_indate, 8);
        $returns['doc_url'] = $this->get_doc_static_url($cat_body_id, $cat_body_indate);
        $returns['doc_path'] = $_SERVER['DOCUMENT_ROOT'].$returns['doc_url'];
        $returns['attach_url'] = $returns['doc_url'].'/attach';
        $returns['attach_path'] = $_SERVER['DOCUMENT_ROOT'].$returns['attach_url'];
        $returns['preffix'] = date('Y-m-d_H-i-s');
        if (!file_exists($this->doc_html_path.'/'.$dir1))
            mkdir($this->doc_html_path.'/'.$dir1, 0755);
        if (!file_exists($this->doc_html_path.'/'.$dir1.'/'.$dir2))
            mkdir($this->doc_html_path.'/'.$dir1.'/'.$dir2, 0755);
        if (!file_exists($returns['doc_path']))
            mkdir($returns['doc_path'], 0755);
        if (!file_exists($returns['attach_path']))
            mkdir($returns['attach_path'], 0755);
        return $returns;
    }   //end function

    //  }}}

    //  {{{ create_doc_static_html()

    /**
     * 为 ID 号为 $cat_body_id 的内容创建静态页面。
     *
     * @access  public
     * @param   integer $cat_body_id    想要创建静态页面的内容的 ID 号。
     * @param   string  $static_file    静态页面的完全路径。
     * @return  void
     */
    function create_doc_static_html($cat_body_id, $static_file)
    {

        $url = 'http://localhost:'.$_SERVER['SERVER_PORT'].sprintf($this->_cms->doc_index_url, $cat_body_id).'/create_static';
        $content = Cache::cache_fetch($url);//将数组变成字符串
       // echo "((@@@@@@@@@@@".$content."))";
        CMS_Common :: write_file($static_file, $content);
    }  //end function

    //  }}}

    //  {{{ get_cat_note_rs()

    /**
     * 获取内容评论管理的数据库记录，或符合条件的数据库记录总数。
     * 字段顺序如下：
     *   0 -> CAT_NOTE_ID
     *   1 -> CAT_BODY_ID
     *   2 -> CAT_NOTE_NAME
     *   3 -> CAT_NOTE_EMAIL
     *   4 -> CAT_NOTE_DATETIME
     *   5 -> CAT_NOTE_IP
     *   6 -> CAT_NOTE_CONTENT
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   boolean $where      SQL 语句的 WHERE 部分（不用带“WHERE”关键字）。
     * @param   boolean $where      SQL 语句的 ORDER BY 部分（不用带“ORDER BY”关键字）。
     * @param   mixed   $numrows    获取的记录条数；若此参数为“count”，则返回符合查询条件的记录总数。
     * @param   integer $offset     开始记录的下标。
     * @return  object ADORecordSet
     */
    function get_cat_note_rs(&$conn, $where = '', $order = '', $numrows = -1, $offset = -1)
    {
        if ('count' == $numrows) {
            $field_str = "COUNT(*)";
            $numrows = -1;
        } else {
            $field_str = "cat_note_id, cat_body_id, cat_note_name, cat_note_email, cat_note_datetime, cat_note_ip, cat_note_content";
        }   //end if
        $sql = "SELECT ".$field_str." FROM cat_note";
        if ($where)
            $sql .= ' WHERE '.$where;
        if ($order)
            $sql .= ' ORDER BY '.$order;
        return $conn->SelectLimit($sql, $numrows, $offset);
    }   //end function

    //  }}}

    //  {{{ insert_cat_note_rs()

    /**
     * 插入内容评论管理的数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $cat_body_id        内容的 ID 号。
     * @param   string  $cat_note_name      发表评论的网友姓名。
     * @param   string  $cat_note_email     发表评论的网友 EMAIL。
     * @param   string  $cat_note_datetime  发表评论的时间。
     * @param   string  $cat_note_ip        发表评论的客户端 IP。
     * @param   string  $cat_note_content   评论内容。
     * @return  void
     */
    function insert_cat_note_rs(&$conn, $cat_body_id, $cat_note_name, $cat_note_email, $cat_note_datetime, $cat_note_ip, $cat_note_content)
    {
        $sql = "
            INSERT INTO cat_note
                (cat_body_id, cat_note_name, cat_note_email, cat_note_datetime, cat_note_ip, cat_note_content)
            VALUES
                (".$cat_body_id.", ".$conn->qstr($cat_note_name).", ".$conn->qstr($cat_note_email).", ".$conn->qstr($cat_note_datetime).", ".$conn->qstr($cat_note_ip).", ".$conn->qstr($cat_note_content).")";
        $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ delete_cat_note_rs()

    /**
     * 删除内容评论管理的记录，必须设置 WHERE 主体语句。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @return  void
     */
    function delete_cat_note_rs(&$conn, $where)
    {
        $sql = "DELETE FROM cat_note WHERE ".$where;
        $conn->Execute($sql);
    }   //end function

    //  }}}

    /**********************************************************************
     *                              公 告                                 *
     **********************************************************************/

    //  {{{ get_announce_rs()

    /**
     * 获取公告记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @param   string  $order  SQL 语句的 ORDER BY 实体。
     * @return  object ADORecordSet
     */
    function &get_announce_rs(&$conn, $where = '', $order = '')
    {
        $sql = "
            SELECT announce_id, announce_title, announce_viewtype, announce_content
            FROM admin_announce";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
        return $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ get_next_id()

    /**
     * 获取公告的下一个自动递增 ID 号。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @return  object ADORecordSet
     */
    function get_announce_next_id(&$conn)
    {
        if ('mysql' == $this->_cms->db_type) { //  MySQL
            $sql = "INSERT INTO admin_announce SET announce_id=''";
            $rs = $conn->Execute($sql);
            return $conn->Insert_ID();
        } else if ('oracle' == $this->_cms->db_type) { //  ORACLE
            $sql = "SELECT SEQ_ADMIN_ANNOUNCE.NEXTVAL FROM DUAL";
            $rs = $conn->Execute($sql);
            return $rs->fields[0];
        }   //end if
    }   //end function

    //  }}}

    //  {{{ update_announce_rs()

    /**
     * 更新公告记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   integer $ann_id     公告 ID 号。
     * @param   string  $title      公告标题。
     * @param   string  $view_type  公告类型，取值为：
     *                                  JUMP -> 弹出式;
     *                                  ROLL -> 滚动式。
     * @param   string  $content    公告内容。
     * @return  void
     */
    function update_announce_rs(&$conn, $ann_id, $title, $view_type, $content)
    {
        if ($ann_id) {
            if ('mysql' == $this->_cms->db_type) { //  MySQL
                $sql = "
                    UPDATE admin_announce
                    SET announce_title=".$conn->qstr($title).",
                        announce_viewtype=".$conn->qstr($view_type).",
                        announce_content=".$conn->qstr($content)."
                    WHERE announce_id=".$ann_id;
                $conn->Execute($sql);
            } else if ('oracle' == $this->_cms->db_type) { //  ORACLE
                $sql = "SELECT COUNT(*) FROM admin_announce WHERE announce_id=".$ann_id;
                $rs = $conn->Execute($sql);
                if ($rs->fields[0] > 0) {   //  UPDATE
                    $sql = "
                        UPDATE admin_announce
                        SET announce_title=".$conn->qstr($title).",
                            announce_viewtype=".$conn->qstr($view_type)."
                        WHERE announce_id=".$ann_id;
                } else {    //  INSERT
                    $sql = "
                        INSERT INTO admin_announce
                            (announce_id, announce_title, announce_viewtype, announce_content)
                        VALUES
                            (".$ann_id.", ".$conn->qstr($title).", ".$conn->qstr($view_type).", empty_clob())";
                }   //end if
                $conn->Execute($sql);
                $conn->UpdateClob('admin_announce', 'announce_content', $content, 'announce_id='.$ann_id);
            }   //end if
        }   //end if
    }   //end function

    //  }}}

    //  {{{ delete_announce_rs()

    /**
     * 删除公告记录，必须设置 WHERE 主体语句。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @return  void
     */
    function delete_announce_rs(&$conn, $where)
    {
        $sql = "DELETE FROM admin_announce WHERE ".$where;
        $conn->Execute($sql);
    }   //end function

    //  }}}

    /**********************************************************************
     *                            调  查                                  *
     **********************************************************************/

    //  {{{ get_poll_rs()

    /**
     * 获取调查记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @param   string  $order  SQL 语句的 ORDER BY 实体。
     * @return  object ADORecordSet
     */
    function &get_poll_rs(&$conn, $where = '', $order = '')
    {
        $sql = "
            SELECT poll_id, poll_title, poll_voters, poll_type, poll_create
            FROM admin_pollmain";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
        return $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ get_poll_next_id()

    /**
     * 获取调查的下一个自动递增 ID 号。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @return  object ADORecordSet
     */
    function get_poll_next_id(&$conn)
    {
        if ('mysql' == $this->_cms->db_type) { //  MySQL
            $sql = "INSERT INTO admin_pollmain SET poll_id=''";
            $rs = $conn->Execute($sql);
            return $conn->Insert_ID();
        } else if ('oracle' == $this->_cms->db_type) { //  ORACLE
            $sql = "SELECT SEQ_ADMIN_POLLMAIN.NEXTVAL FROM DUAL";
            $rs = $conn->Execute($sql);
            return $rs->fields[0];
        }   //end if
    }   //end function

    //  }}}

    //  {{{ update_poll_rs()

    /**
     * 更新调查记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   integer $poll_id    调查 ID 号。
     * @param   string  $poll_title 调查标题。
     * @param   integer $poll_type  调查类型，取值为：
     *                                  0 -> 单选;
     *                                  1 -> 复选。
	 * @param   integer  $create 调查创建时间
     * @return  void
     */
    function update_poll_rs(&$conn, $poll_id, $poll_title, $poll_type, $create=0)
    {
		if ($poll_id) {
            if ('mysql' == $this->_cms->db_type) { //  MySQL
				if($create){
					$sql = "
                    UPDATE admin_pollmain
                    SET poll_title=".$conn->qstr($poll_title).",
                        poll_type=".$poll_type.",
						poll_create=".$create."
                    WHERE poll_id=".$poll_id;
				}else{
					$sql = "
                    UPDATE admin_pollmain
                    SET poll_title=".$conn->qstr($poll_title).",
                        poll_type=".$poll_type."
                    WHERE poll_id=".$poll_id;
				}
            } else if ('oracle' == $this->_cms->db_type) { //  ORACLE
                $sql = "SELECT COUNT(*) FROM admin_pollmain WHERE poll_id=".$poll_id;
                $rs = $conn->Execute($sql);
                if ($rs->fields[0] > 0) {   //  UPDATE
                    $sql = "
                        UPDATE admin_pollmain
                        SET poll_title=".$conn->qstr($poll_title).",
                            poll_type=".$poll_type."
                        WHERE poll_id=".$poll_id;
                } else {    //  INSERT
                    $sql = "
                        INSERT INTO admin_pollmain
                            (poll_id, poll_title, poll_type, poll_voters, poll_create)
                        VALUES
                            (".$poll_id.", ".$conn->qstr($poll_title).", ".$poll_type.", 0, ".$create.")";
                }
            }   //end if
            $conn->Execute($sql);
        }   //end if
    }   //end function

    //  }}}

    //  {{{ delete_poll_rs()

    /**
     * 删除调查记录，必须设置 WHERE 主体语句。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @return  void
     */
    function delete_poll_rs(&$conn, $where)
    {
        $sql = "DELETE FROM admin_pollmain WHERE ".$where;
        $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ get_poll_item_rs()

    /**
     * 获取调查选项记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @param   string  $order  SQL 语句的 ORDER BY 实体。
     * @return  object ADORecordSet
     */
    function &get_poll_item_rs(&$conn, $where = '', $order = '')
    {
        $sql = "
            SELECT item_id, item_title, item_count, item_pollid
            FROM admin_pollitem";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
        return $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ update_poll_item_rs()

    /**
     * 添加或更新调查选项记录，若参数 $item_id 为空或 0，则做添加操作，否则为更新操作。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   integer $item_id        选项 ID 号。
     * @param   string  $item_title     选项内容。
     * @param   integer $item_pollid    调查 ID 号。
     * @return  void
     */
    function update_poll_item_rs(&$conn, $item_id = '', $item_title, $item_pollid)
    {
        if ($item_pollid) {
            if ($item_id) { //  UPDATE
                $sql = "
                    UPDATE admin_pollitem
                    SET item_title=".$conn->qstr($item_title)."
                    WHERE item_id=".$item_id;
            } else {    //  INSERT
                $sql = "
                    INSERT INTO admin_pollitem
                        (item_title, item_pollid)
                    VALUES
                        (".$conn->qstr($item_title).", ".$item_pollid.")";
            }   //end if
            $conn->Execute($sql);
        }   //end if
    }   //end function

    //  }}}

    //  {{{ delete_poll_item_rs()

    /**
     * 删除调查项目记录，必须设置 WHERE 主体语句。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   string  $where  SQL 语句的 WHERE 实体。
     * @return  void
     */
    function delete_poll_item_rs(&$conn, $where)
    {
        $sql = "DELETE FROM admin_pollitem WHERE ".$where;
        $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ update_poll_vote()

    /**
     * 更新调查的投票数。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $poll_id    想要更新的调查的 ID 号。
     * @param   array   $item_ids   被投票的选项的 ID 号集合。
     * @return  void
     */
    function update_poll_vote(&$conn, $poll_id, $item_ids)
    {
        if ($poll_id) {
            $sql = "UPDATE admin_pollmain SET poll_voters=poll_voters+1 WHERE poll_id=".$poll_id;
            $conn->Execute($sql);

            foreach ($item_ids as $item_id) {
                $sql = "UPDATE admin_pollitem SET item_count=item_count+1 WHERE item_id=".$item_id.' AND item_pollid='.$poll_id;
                $conn->Execute($sql);
            }   //end foreach
        }   //end if
    }  //end function

    //  }}}

    /**********************************************************************
     *                              下拉菜单                              *
     **********************************************************************/

    //  {{{ get_menu_rs()

    /**
     * 获取下拉菜单数据库记录，或符合条件的数据库记录总数。
     * 字段顺序如下：
     *   0 -> PMENU_ID
     *   1 -> PMENU_PARENTID
     *   2 -> PMENU_ORDERID
     *   3 -> PMENU_NAME
     *   4 -> PMENU_URL
     *   5 -> PMENU_NEWWIN
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   stinrg  $where      SQL 语句的 WHERE 部分（不用带“WHERE”关键字）。
     * @param   string  $order      SQL 语句的 ORDER BY 部分（不用带“ORDER BY”关键字）。
     * @return  object ADORecordSet
     */
    function &get_menu_rs(&$conn, $where = '', $order = 'pmenu_id')
    {
        $sql = "SELECT pmenu_id, pmenu_parentid, pmenu_orderid, pmenu_name, pmenu_url, pmenu_newwin FROM admin_pmenu";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
        return $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ insert_menu_rs()

    /**
     * 插入下拉菜单数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $menu_parent_id 上层菜单 ID 号。
     * @param   integer $menu_order     子菜单顺序。
     * @param   string  $menu_name      菜单名。
     * @param   string  $menu_url       菜单链接的 URL。
     * @param   string  $menu_new_win   是否在新窗口打开。
     * @return  void
     */
    function insert_menu_rs(&$conn, $menu_parent_id, $menu_order, $menu_name, $menu_url, $menu_new_win)
    {
        $sql = "
            INSERT INTO admin_pmenu
                (pmenu_parentid, pmenu_orderid, pmenu_name, pmenu_url, pmenu_newwin)
            VALUES
                (".$menu_parent_id.", ".$menu_order.", ".$conn->qstr($menu_name).", ".$conn->qstr($menu_url).", ".$conn->qstr($menu_new_win).")";
        $conn->Execute($sql);
    }  //end function

    //  }}}

    //  {{{ update_menu_rs()

    /**
     * 更新下拉菜单数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $menu_id        菜单 ID 号。
     * @param   string  $menu_name      菜单名。
     * @param   string  $menu_url       菜单链接的 URL。
     * @param   string  $menu_new_win   是否在新窗口打开。
     * @return  void
     */
    function update_menu_rs(&$conn, $menu_id, $menu_name, $menu_url, $menu_new_win)
    {
        $sql = "
            UPDATE admin_pmenu SET
                pmenu_name = ".$conn->qstr($menu_name).",
                pmenu_url = ".$conn->qstr($menu_url).",
                pmenu_newwin = ".$conn->qstr($menu_new_win)."
            WHERE pmenu_id = ".$menu_id;
        $conn->Execute($sql);
    }  //end function

    //  }}}

    //  {{{ update_menu_order()

    /**
     * 更新下拉菜单排列顺序数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $menu_id        菜单 ID 号。
     * @param   string  $order_id       菜单的顺序。
     * @return  void
     */
    function update_menu_order($conn, $menu_id, $order_id) {
        $sql = "update admin_pmenu set pmenu_orderid=".$order_id." where pmenu_id=".$menu_id;
        $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ delete_menu_rs()

    /**
     * 删除菜单数据记录。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @param   array   $menu_ids   需要删除的菜单的 ID 号组成的数组。
     * @return  boolean
     */
    function delete_menu_rs(&$conn, $menu_ids)
    {
        if (is_array($menu_ids) && count($menu_ids) > 0) {
            $sql = "DELETE FROM admin_pmenu WHERE pmenu_id IN (".implode(',', $menu_ids).")";
            $rs  = $conn->Execute($sql);
        }   //end if
    }   //end function

    //  }}}

    //  {{{ create_menu_file()

    /**
     * 生成下拉菜单设置数组变量文件。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @return  void
     */
    function create_menu_file(&$conn)
    {
        $menus = array();
        $menu_tops = array();
        $rs = &$this->get_menu_rs($conn);
        while (!$rs->EOF) {
            /* [0] -> pmenu_id, [1] -> pmenu_parentid, [2] -> pmenu_orderid, [3] -> pmenu_name, [4] -> pmenu_url, [5] -> pmenu_newwin */
            $menu_id        = $rs->fields[0];
            $menu_parent_id = $rs->fields[1];
            $menu_order_id  = $rs->fields[2];
            $menu_name      = $rs->fields[3];
            $menu_url       = $rs->fields[4];
            $menu_new_win   = $rs->fields[5];
            $menus[$menu_id] = array(
                'NAME'      => $menu_name,
                'URL'       => $menu_url,
                'PARENT'    => $menu_parent_id,
                'NEWWIN'    => $menu_new_win,
                'ORDER'     => $menu_order_id);
            if (!isset($menus[$menu_id]['CHILD']))
                $menus[$menu_id]['CHILD'] = array();
            if ($menu_parent_id)
                $menus[$menu_parent_id]['CHILD'][$menu_order_id] = $menu_id;
            else
                $menu_tops[$menu_order_id] = $menu_id;
            $rs->MoveNext();
        }   //end while
        $rs->Close();
        $content  = '<?'.'php'.CRLF;
        $content .= '$cfg_menus = '.var_export($menus, TRUE).';'.CRLF;
        $content .= '$cfg_menu_tops = '.var_export($menu_tops, TRUE).';'.CRLF;
        $content .= '?'.'>';
        CMS_Common :: write_file($this->_cms->config_path.'/cms_menu.inc.php', $content);
    }   //end function

    //  }}}

    /**********************************************************************
     *                            订  阅                                  *
     **********************************************************************/

    //  {{{ get_user_subscribe()

    /**
     * 获取用户订阅的信息记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   string  $user_id    用户帐号。
     * @return  object ADORecordSet
     */
    function get_user_subscribe(&$conn, $user_id)
    {
        $sql = "SELECT subscribe FROM user_main WHERE user_id=".$conn->qstr($user_id);
        $rs = $conn->Execute($sql);
        return $rs->fields[0];
    }   //end function

    //  }}}

    //  {{{ update_user_subscribe()

    /**
     * 更新用户订阅的信息记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn  建网通数据库数据库连接对象实例引用。
     * @param   string  $user_id    用户帐号。
     * @return  object ADORecordSet
     */
    function update_user_subscribe(&$conn, $user_id, $subscribe)
    {
        $sql = "UPDATE user_main SET subscribe=".$conn->qstr($subscribe)." WHERE user_id=".$conn->qstr($user_id);
        $conn->Execute($sql);
    }   //end function

    //  }}}

	  /**********************************************************************
     *                              友情链接                              *
     **********************************************************************/

    //  {{{ get_link_type_rs()

    /**
     * 获取友情链接类别数据库记录，或符合条件的数据库记录总数。
     * 字段顺序如下：
     *   0 -> LINKTYPE_ID
     *   1 -> LINKTYPE_NAME
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   stinrg  $where      SQL 语句的 WHERE 部分（不用带“WHERE”关键字）。
     * @param   string  $order      SQL 语句的 ORDER BY 部分（不用带“ORDER BY”关键字）。
     * @return  object ADORecordSet
     */
    function &get_link_type_rs(&$conn, $where = '', $order = '')
    {
        $sql = "SELECT linktype_id, linktype_name  FROM friend_link_type";
        if ($where)
            $sql .= " WHERE ".$where;
        if ($order)
            $sql .= " ORDER BY ".$order;
		return $conn->Execute($sql);

    }   //end function

    //  }}}

    //  {{{ insert_link_type_rs()

    /**
     * 插入友情链接类别数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $linktype_name    友情链接类别名称。
     * @return  void
     */
    function insert_link_type_rs(&$conn, $linktype_name)
    {
        $sql = "
            INSERT INTO friend_link_type
                (linktype_name)
            VALUES
                (".$conn->qstr($linktype_name).")";
        $conn->Execute($sql);
    }  //end function

    //  }}}

    //  {{{ update_link_type_rs()

    /**
     * 更新友情链接类别数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $linktype_id        友情链接类别 ID 号。
     * @param   string  $linktype_name      友情链接类别名称。
     * @return  void
     */
    function update_link_type_rs(&$conn, $linktype_id, $linktype_name)
    {
        $sql = "
            UPDATE friend_link_type SET
                linktype_name = ".$conn->qstr($linktype_name)."
            WHERE linktype_id = ".$linktype_id;
        $conn->Execute($sql);
    }  //end function


    //  }}}

	//  {{{ delete_link_type_rs()

	/**
     * 删除友情链接类别数据记录。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @param   integer $linktype_id    需要删除的友情链接类别的 ID 号。
     * @return  void
     */
    function delete_link_type_rs(&$conn, $linktype_id)
    {
            $sql = "DELETE FROM friend_link_type WHERE linktype_id = ".$linktype_id;
            $conn->Execute($sql);
			$sql2 = "DELETE FROM friend_link WHERE linktype_id = ".$linktype_id;
            $conn->Execute($sql2);
    }   //end function

    //  }}}

	 //  {{{ get_link_rs()

    /**
     * 获取友情链接数据库记录，或符合条件的数据库记录总数。
     * 字段顺序如下：
     *   0 -> LINK_ID
     *   1 -> LINK_NAME
     *   2 -> LINK_LOCATION
     *   3 -> LINKTYPE_ID
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   stinrg  $where      SQL 语句的 WHERE 部分（不用带“WHERE”关键字）。
     * @param   string  $order      SQL 语句的 ORDER BY 部分（不用带“ORDER BY”关键字）。
     * @return  object ADORecordSet
     */
    function &get_link_rs(&$conn, $where = '', $order = '')
    {
        $sql = "SELECT link_id, link_name, link_location,linktype_id  FROM friend_link";
        if ($where)
            $sql .= " WHERE ".$where;
		 if ($order)
            $sql .= " ORDER BY ".$order;
        return $conn->Execute($sql);
    }   //end function

    //  }}}

    //  {{{ insert_link_rs()

    /**
     * 插入友情链接数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   string  $link_name    友情链接名称。
     * @param   string  $link_location    友情链接地址。
     * @param   string  $linktype_id    友情链接类型 ID。
     * @return  void
     */
    function insert_link_rs(&$conn, $link_name, $link_location, $linktype_id)
    {
        $sql = "
            INSERT INTO friend_link
                (link_name, link_location, linktype_id)
            VALUES
                (".$conn->qstr($link_name).", ".$conn->qstr($link_location).", ".$linktype_id.")";
        $conn->Execute($sql);
    }  //end function

    //  }}}

    //  {{{ update_link_rs()

    /**
     * 更新友情链接数据库记录。
     *
     * @access  public
     * @param   object ADOConnection  &$conn    建网通信息平台使用的 ADOdb 数据库连接对象的引用。
     * @param   integer $link_id        友情链接 ID 号。
     * @param   string  $link_name      友情链接名称。
     * @param   string  $link_location      友情链接地址  。
     * @param   integer  $linktype_ID      友情链接类别 ID 号。
     * @return  void
     */
    function update_link_rs(&$conn, $link_name, $link_location, $linktype_id, $link_id)
    {
	   $sql = "
            UPDATE friend_link SET
                link_name = ".$conn->qstr($link_name).",
                link_location = ".$conn->qstr($link_location)." ,
				linktype_id = ".$linktype_id."
            WHERE link_id = ".$link_id;
        $conn->Execute($sql);
    }  //end function

    function update_link_order($conn, $cat_id, $order) {
        $sql = "UPDATE friend_link SET link_order=".$order." where link_id=".$cat_id;
        $conn->Execute($sql);
    }   //end function

    //  }}}

	//  {{{ delete_link_rs()

	/**
     * 删除友情链接数据记录。
     *
     * @access  public
     * @param   object ADOConnection    &$conn  建网通数据库数据库连接对象实例引用。
     * @param   integer $link_id    需要删除的友情链接的 ID 号。
     * @return  void
     */
    function delete_link_rs(&$conn, $linktype_id)
    {
            $sql = "DELETE FROM friend_link WHERE link_id = ".$linktype_id;
            $conn->Execute($sql);
    }   //end function

    //  }}}
}   //end class
?>
