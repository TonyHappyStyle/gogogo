<?php
//  $Id: cms_rcjl.class.php,v 1.25 2004/04/19 05:45:29 nio Exp $

/**
 * 人才交流类。
 * 
 *
 * @package K12CMS
 * @access public
 */
class CMS_Rcjl
{
	
	
	var $_cms = null;
	/**
	* 错误代码表
	* @var     array
	* @access  public
	*/
	var $errors = array(
		'USER_ID_EMPTY' => '用户名不能为空',
		'USER_ID_LENGTH' => '用户名长度错误（必须是 3～20 个字符）',
		'USER_ID_FORMAT' => '用户名第一个字符必须是字母，同时只能由字母、数字和下划线“_”组成', 
		'USER_ID_EXIST' => '用户名已经存在');

	//  }}}
     
	function CMS_Rcjl(&$cms){
		$this->_cms =& $cms;
	}
	
	/*
	** 设置cms用户的rcjl类型 1.个人用户 2.学校用户 5.人事干部
	*/	
    function &set_cmsuser_type(&$conn, $uid,$typevalue)
     {
     	if($typevalue==1 || $typevalue==2 || $typevalue==5){
     	 $sql="update user_main set rcjl_type=$typevalue where user_id='".$uid."'";
         $conn->Execute($sql);
        }
     }//end of function	
	
	/*
	** 获取cms用户的rcjl类型:1个人用户 2.学校用户 5.人事干部
	*/
     function &get_cmsuser_type_auth(&$conn, $uid)
     {
     	if($conn==""){
     	  $conn =& $this->_cms->get_adodb_conn();
     	}
     	//$conn->debug=true;
     	$ret[0]=-1;
     	$sql="select user_id,rcjl_type,rcjl_auth from user_main where user_id='".$uid."'";
     	$rs = $conn->Execute($sql);
        if ($rs!=null && !$rs->EOF ) {
             $ret[0]=$rs->fields[1];
             $ret[1]=$rs->fields[2];
        }
        return $ret;     	
     }//end of function	
     
     
     /**
     * 检测用户名是否已被使用。
     *
     * @param   string  $user_id    用户名。
     * @return  boolean 已被使用则返回 TRUE，未被使用则返回 FALSE。
     * @access  public
     */    
    function is_user_id_exists($user_id)
    {
        if (preg_match('/^(bin|daemon|adm|sync|shutdown|halt|mail|news|uucp|operator|ftp|gdm|nobody|www|admin|master|mailusers|httpd|sigmaupload|guest)$/', $user_id))
            return TRUE;
        if (preg_match('/^k12prj_/i', $user_id))
            return TRUE; 
              
        $conn =&$this->_cms->get_adodb_conn();
        
       
        $rs =& $this->get_user_register("m_uid=" . $conn->qstr($user_id), '', 'count');
        if ($rs->fields[0] > 0)
            return TRUE;
        return FALSE;
    }   //end function
     
  /**
     * 检测用户名是否正确可用。
     *
     * @param   string  $user_id    用户名。
     * @return  mixed   检测通过则返回 TRUE，否则返回错误代码。
     * @access  public
     */    
    function verify_user_id($user_id)
    {
        $user_id = strtolower($user_id);
        if (!$user_id)  
            return 'USER_ID_EMPTY';
        if (strlen($user_id) < 3 || strlen($user_id) > 20)  
            return 'USER_ID_LENGTH';
        if (!preg_match('/^[a-z][a-z0-9_]+$/', $user_id)) 
            return 'USER_ID_FORMAT';
       if ($this->is_user_id_exists($user_id))
            return 'USER_ID_EXIST';
        return TRUE;
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
    function &get_user_register($where = '', $order = '', $numrows = -1, $offset = -1)
    {
        $conn =& $this->_cms->get_adodb_conn();
        if ('count' == $numrows) {
            $field_str = 'COUNT(m_uid)';
            $numrows = -1;
        } else {
            $field_str = 'm_uid, m_uname, m_pwd, m_type,m_birthday, m_gender, m_email';
        }   //end if
        $sql = "SELECT $field_str FROM user_rcjl";
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
        return $rs;
    }   //end function
    
    
    
     /**
     * 增加注册用户。
     *
     * @param   array   $user_infos 用户各信息字段组成的数组：
     *                  array(  'user_id'       => '登录用户名',
     *                         
     *                          'user_name'     => '用户真实姓名',
     *                          'password'      => '密码',
     *                         
     *                          'birthday'      => '生日',
     *                          'gender'        => '性别',
     *                          'email'         => '电子邮件',
     *                          
     *                          'school_user'     => '是否为学校用户,即用户类型'
     *                  )
     * @return  void
     * @access  public
     */    
    function add_user_register($user_infos)
    {
        $conn =& $this->_cms->get_adodb_conn();
        $sql = "INSERT INTO user_rcjl
                    (m_uid, m_uname, m_pwd, m_type,m_birthday, m_gender, m_email, m_auth)
                VALUES
                    (" . strtolower($conn->qstr($user_infos['user_id'])) . ", "  . $conn->qstr($user_infos['user_name']) . ", " . $conn->qstr($user_infos['password']) . ", " .$user_infos['school_user'].", " . $conn->qstr($user_infos['birthday']) . ", " . $conn->qstr($user_infos['gender']) . ", " . $conn->qstr($user_infos['email']) . ", " . $conn->qstr($user_infos['auth']) . ")";
        $rs = $conn->Execute($sql);
        $retval = TRUE;
        if (!$rs) {
            $retval = '查询数据库时出错：' . $conn->ErrorMsg();
        }
        return $retval;
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
    function &Getuser_1($the_table,$where = '', $order = '', $cache = FALSE, $numrows = -1,$offset = -1,$m_num='')
    {
        $conn =& $this->_cms->get_adodb_conn();
       //$conn->debug=true;
        if($the_table=="1"){
        	$the_table="user_main";
        	
        }else{
          $the_table="user_rcjl";
        }
       
        if($the_table=="user_rcjl"){
        	$the_fields=array( "uid"=>"m_uid",
        	                   "uname"=>"m_uname",
        	                   "utype"=>"m_type",
        	                   "uemail"=>"m_email",
        	                   "uauth"=>"m_auth");
        	
        }else{
             $the_fields=array( "uid"=>"user_id",
        	                   "uname"=>"nickname",
        	                   "utype"=>"rcjl_type",
        	                   "uemail"=>"email",
        	                   "uauth"=>"rcjl_auth");
        }
        if ('count' == $numrows) {
            $field_str = 'COUNT('.$the_fields["uid"].')';
            $numrows = -1;
        } else {
            $field_str =$the_fields["uid"].','. $the_fields["uname"].','. $the_fields["utype"].','.$the_fields["uauth"]. ','. $the_fields["uemail"];
        }   //end if
        $sql = "SELECT $field_str FROM ".$the_table;
       
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }
      //    if (TRUE == $cache) {
        //    $rs = $conn->CacheSelectLimit($sql, $numrows, $offset);
      //  } else {
            $rs = $conn->SelectLimit($sql, $numrows, $offset);
     //   }   //end if
       
       
         if($m_num!=''){
			   return $rs->RecordCount();
			}
	
	
	$i= 0;
		while ($rs && !$rs->EOF)
			{
				$mRet[$i]['user_id']        = $rs->fields[0];
				
				$mRet[$i]['user_name']        = $rs->fields[1];
				
				$the_type     = $rs->fields[2];
              
				if($the_type==1)
                                   $type_string="个人用户";
				else if($the_type==2)
                                           $type_string="学校用户";
				
                                
				$mRet[$i]['user_type']=$type_string;
				$the_auth=$rs->fields[3];
				//echo "the_auth=====".$the_auth."substr=======".substr($the_auth,0,1)."<br/>";
				if($the_auth!=""){
					$auth_string="";
				     if(substr($the_auth,0,1)=='1')
				        $auth_string.="|岗位需求";
				     if(substr($the_auth,1,1)=='1')
				        $auth_string.="|人才推荐"; 
				     if( $auth_string!="")  
				        $auth_string=substr($auth_string,1);
			        }
			        $mRet[$i]['user_auth']=$auth_string;
			        $auth_string="";
			        $mRet[$i]['user_email']= $rs->fields[4];
				$i ++;
				$rs->MoveNext();
			}
			
			return $mRet;
       
    }   //end function
   
   
   
   
     /**
     * 根据用户来源，获取用户数据库表
     *
     * @param   string  $the_cms     指定的用户的来源:是大平台的用户还是人才交流的注册用户
   
     * @return  array 用户数据库表名及其字段名
     * @access  public
     */
   
   function which_table($the_cms)
   {
   	if($the_cms=="1")
   	{
   	   $the_table="user_main";
        }else{
        $the_table="user_rcjl";
       }
       
        if($the_table=="user_rcjl"){
        	$the_fields=array( "uid"=>"m_uid",
        	                   "uname"=>"m_uname",
        	                   "utype"=>"m_type",
        	                   "uemail"=>"m_email",
        	                   "uauth"=>"m_auth",
        	                   "uuid"=>"m_uuid");
        	
        }else{
             $the_fields=array( "uid"=>"user_id",
        	                   "uname"=>"nickname",
        	                   "utype"=>"rcjl_type",
        	                   "uemail"=>"email",
        	                   "uauth"=>"rcjl_auth",
        	                   "uuid"=>"sid"
        	                   );
        }
        $ret=array("the_table"=>$the_table,"the_fields"=>$the_fields);
        return $ret;
   }//end of function
    
    
    /*
    根据用户来源及用户id取得其sid
      * @param   string  $user_from_cms     指定的用户的来源:是大平台的用户还是人才交流的注册用户
      * @param   string  $uid     用户id
     * @return  int    用户的sid
     * @access  public
   */
   function get_user_uuid($user_from_cms,$uid)
   {
   
   	      $conn =& $this->_cms->get_adodb_conn();
   	   //$conn->debug=true;
   	
   	$the_userdbtable=$this->which_table($user_from_cms);
   	$the_user_table_name=$the_userdbtable['the_table'];
   	$the_user_table_fields=$the_userdbtable['the_fields'];
   	$sql="select ".$the_user_table_fields['uuid']." from ".$the_user_table_name." where ".$the_user_table_fields['uid']."=".$conn->qstr($uid);
          $rs=$conn->Execute($sql);  
         
      if($rs!=null && $rs->fields[0]!=null){
            return     	$rs->fields[0];
        }
   }
   
   function  set_auth($user_from_cms,$the_auth_string,$sel_users_string)
   {
   	$the_userdbtable=$this->which_table($user_from_cms);
   	$the_user_table_name=$the_userdbtable['the_table'];
   	$the_user_table_fields=$the_userdbtable['the_fields'];
   	
   	$sql="update ".$the_user_table_name." set ".$the_user_table_fields['uauth']."='".$the_auth_string
   	     ."' where ".$the_user_table_fields['uid']." in (".$sel_users_string.")";
   	
   	  $conn =& $this->_cms->get_adodb_conn();
   	 // $conn->debug=true;
   	  $conn->Execute($sql);  
   	  //$conn->debug=false;   
   }//end of function
    
   /**
     * 判断是否登录，如果用户没有参数 $pri 所对应的权限，则提示出错，并跳到首页要求用户登录。
     * @param integer $pri 想要判断的权限位。默认为空，只判断是否登录，不判断权限。
     */
    function detect_login($pri = '')
    {
        if (empty($_SESSION['rcjl_user_id'])) {
            $errmsg = '请先登录！';
        } else if ($pri && $_SESSION['rcjl_user_rol'] != $pri) {
            $errmsg = '当前登录的用户（'.$_SESSION['rcjl_user_id'].'）没有此功能的使用权限！';
            
            if($_SESSION['rcjl_user_rol']=="0"||$_SESSION['rcjl_user_rol']=="")
                 $errmsg .= '\\n请先指定用户类型！';
            else if ($pri == 1)
                $errmsg .= '\\n此功能只有个人用户可以使用！';
            else if ($pri == 2)
                $errmsg .= '\\n此功能只有单位用户可以使用！';
            else if ($pri == 3)
                $errmsg .= '\\n此功能只有超级管理员可以使用！';
            else if ($pri == 5)
                $errmsg .= '\\n此功能只有人事干部可以使用！';
        }
        if (!empty($errmsg)) {
            ?>
            <script language="JavaScript">
            function login() 
            {
                alert("<?php echo $errmsg; ?>");
           <?php
                 if($_SESSION['rcjl_user_rol']=="0"||$_SESSION['rcjl_user_rol']==""){
                   echo "document.location='".$this->_cms->app_url."/rcjl/set_cmsuser_type.php'";	
                }else{
           ?>   
                document.location = "<?php echo $this->_cms->cms_url; ?>/";
           <?php
                }
           ?>
            }
            window.onload = login;
            </script>    
            <?php
            exit;
        }
    }//end of function   
    
    /**
     * 根据指定的条件，获取用户简历信息记录集。
     *
     * @param   string  $where      SQL 的 WHERE 子句。
     * @param   string  $order      SQL 的 ORDER BY 子句。
     * @param   mixed   $numrows    获取的记录数，若此值为“count”，则查询记录总数。
     * @param   integer $offset     开始记录的下标。
     * @param   boolean $lite       是否使用轻量级数据，默认为 false。
     * @return  object ADORecordSet 用户信息记录集的引用。
     * @access  public
     */
    function &get_resumeinfo($where = '', $order = '', $numrows = -1, $offset = -1, $lite = false)
    {
       global $conn;
       if($conn==""){
        $conn =& $this->_cms->get_adodb_conn();
        }
       // $conn->debug=true;
        $from = 'rcjl_resume';
        if ('count' == $numrows) {
            $field_str = 'COUNT( id )';
            $numrows = -1;
        } else if ($lite) { //轻量级，少自动 resume [25]
            $field_str = 'puserid, truename, high, nation, politics, sex, number, borndate, bornaddress, liveaddress, photo, level, telephone, email, link, school, graduate, speciality, longevity, flanguage, llanguage, job, hopeaddress, job_type, hope_money, time, hunyin, subject, role, active, job1,dot,mode,id,uuid,origin,the_status,person_ly,job_lx';
            
        } else {
            $field_str = 'puserid, truename, high, nation, politics, sex, number, borndate, bornaddress, liveaddress, photo, level, telephone, email, link, school, graduate, speciality, longevity, flanguage, llanguage, job, hopeaddress, job_type, hope_money, resume, time, hunyin, subject, role, active, job1, dot,mode,id,uuid,origin,the_status';
            $field_str.=',health_sta,bak_base,bak_lxfs,p_special,bak_jyjl,bak_qzyx,zytc_cgjl,person_ly,job_lx,m_pass_remark,zaizhi,shouji,middleschool,qt_xueke,xueke,xueduan,ssgzjl,gzjl,m_pass'; 
        }   //end if
        $sql = "SELECT $field_str FROM $from";
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }	//end if
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
        return $rs;
    }   //end function
    
    //时间分割函数
    function fmttime($nfmt_time) 
    {
        if($nfmt_time==""){
			return "";
		}
		$str2 = explode("-", $nfmt_time);
        $year=$str2[0];
        $month=$str2[1];
        $day=$str2[2];
        $hour=0;
        $minu=0;
        $second=0;
        $fmt_time = mktime($hour,$minu,$second,$month,$day,$year);
        return $fmt_time;
    }//end function	 
    
     /**
     * 增加或修改简历。
     *
     * @param   array   $resume_infos 简历信息字段组成的数组：
     *                  array(  'puserid'		=> '个人用户id',
     *                          'truename'      => '真实姓名',
     *                          '......'      => '......',
     *                  )
     * @return  void
     * @access  public
     */    
    function Update_Resume($resume_infos,$m_auth='')
    {
        global $conn;
        if($conn==""){
         $conn =& $this->_cms->get_adodb_conn();
        }
         //$conn->debug=true;
         if($m_auth=="auth" || $m_auth=="nopass"){
            $where="id=".$resume_infos['id'];
          }else{
           $where="puserid=".$conn->qstr($resume_infos['puserid'])." and uuid=".$resume_infos['uuid']." and origin=".$resume_infos['origin']; 
          }
        $rs =& $this->get_resumeinfo($where);
	    if ($rs->fields[1] != '')  {
	    	if($m_auth=="auth"){
	    		$field_str="dot=0,m_pass=1,";
	    	}elseif($m_auth=="nopass"){//设置未通过状态
				$field_str="dot=0,m_pass=2,";
			}else{
	          $field_str="dot=0,m_pass=0,";
	        }
	    	$field_str.="the_status=0,job_lx='".$resume_infos['job_lx']."',person_ly='".$resume_infos['person_ly']."',";
            $field_str.="truename='" .$resume_infos['truename']. "',
            high='" .$resume_infos['high']. "' ,
            nation='".$resume_infos['nation']. "' , politics='".$resume_infos['politics']. "' , 			sex=".$resume_infos['sex']. " , 
          
            borndate='".$resume_infos['borndate']. "',
            bornaddress='".$resume_infos['bornaddress']. "',
            liveaddress='".$resume_infos['liveaddress']. "',
            photo='".$resume_infos['photo']. "',
            level='".$resume_infos['level']. "',
            telephone='".$resume_infos['telephone']."',
            email='".$resume_infos['email']. "',
            link='".$resume_infos['link']. "',
            school='".$resume_infos['school']. "',
            graduate='".$resume_infos['graduate']. "',
            speciality='".$resume_infos['speciality']. "',
            longevity='".$resume_infos['longevity']. "',
            flanguage='".$resume_infos['flanguage']. "',
            llanguage='".$resume_infos['llanguage']. "',
            job='".$resume_infos['job']. "',
            hopeaddress='".$resume_infos['hopeaddress']. "',
            job_type='".$resume_infos['job_type']. "',
            hope_money='".$resume_infos['hope_money']. "',
            resume=".$conn->qstr($resume_infos['resume']). ",
            time=".$resume_infos['time']. ",
            active=".$resume_infos['active']. ",
            role='".$resume_infos['true_part']. "',
            job1='".$resume_infos['job1']. "',
            hunyin='" .$resume_infos['hunyin']."',
			zaizhi='" .$resume_infos['zaizhi']."',
			shouji=".$conn->qstr($resume_infos['shouji']).",
			middleschool=".$conn->qstr($resume_infos['middleschool']).",
			qt_xueke=".$conn->qstr($resume_infos['qt_xueke']).",
			xueke=".$conn->qstr($resume_infos['xueke']).",
			xueduan=".$conn->qstr($resume_infos['xueduan']).",
			ssgzjl=".$conn->qstr($resume_infos['ssgzjl']).",
			gzjl=".$conn->qstr($resume_infos['gzjl']).",
            health_sta=".$conn->qstr($resume_infos['health_sta']).",
            bak_base=".$conn->qstr($resume_infos['bak_base']).",
			m_pass_remark=".$conn->qstr($resume_infos['zytc_mpassremark']).",
            bak_lxfs=".$conn->qstr($resume_infos['bak_lxfs']).",
            p_special=".$conn->qstr($resume_infos['p_special']).",
            bak_jyjl=".$conn->qstr($resume_infos['bak_jyjl']).",
            bak_qzyx=".$conn->qstr($resume_infos['bak_qzyx']).",
            zytc_cgjl=".$conn->qstr($resume_infos['zytc_cgjl']);
            $sql = "UPDATE rcjl_resume SET " . $field_str . "WHERE " .$where;
        } else {
	        if($m_auth!="auth"){
	        $sql = "INSERT INTO rcjl_resume
                    (puserid, truename, high, nation, politics, sex, number,borndate,bornaddress,liveaddress,photo,level,telephone,email,link,school,graduate,speciality,longevity,flanguage,llanguage,job,hopeaddress,job_type,hope_money,resume,time,hunyin,role,active,job1,person_ly,job_lx,uuid,origin,health_sta,bak_base,bak_lxfs,p_special,bak_jyjl,bak_qzyx,zytc_cgjl,zaizhi,shouji,middleschool,qt_xueke,xueke,xueduan,ssgzjl,gzjl)
                VALUES
                    ('".$resume_infos['puserid']."','".
                    $resume_infos['truename']."','"
                    .$resume_infos['high']."','".$resume_infos['nation']."','".$resume_infos['politics']."',".$resume_infos['sex']
                    .","."0".",'".$resume_infos['borndate']."','".$resume_infos['bornaddress']."','"
                    .$resume_infos['liveaddress']."','".$resume_infos['photo']."','".$resume_infos['level']."','".$resume_infos['telephone']."','".$resume_infos['email']."','".$resume_infos['link']."','".$resume_infos['school']."','".$resume_infos['graduate']."','".$resume_infos['speciality']."','".$resume_infos['longevity']."','".$resume_infos['flanguage']."','".$resume_infos['llanguage']."','".$resume_infos['job']."','".$resume_infos['hopeaddress']."','".$resume_infos['job_type']."','".$resume_infos['hope_money']."','".$resume_infos['resume']."',".$resume_infos['time'].",'".$resume_infos['hunyin']."','".$resume_infos['true_part']."',1,'".$resume_infos['job1']."','".$resume_infos['person_ly']."','".$resume_infos['job_lx']."',".$resume_infos['uuid'].",".$resume_infos['origin']
                    .",".$conn->qstr($resume_infos['health_sta'])
                    .",".$conn->qstr($resume_infos['bak_base']).",".$conn->qstr($resume_infos['bak_lxfs'])
                    .",".$conn->qstr($resume_infos['p_special']).",".$conn->qstr($resume_infos['bak_jyjl'])
                    .",".$conn->qstr($resume_infos['bak_qzyx'])
                    .",".$conn->qstr($resume_infos['zytc_cgjl'])
					.",".$conn->qstr($resume_infos['zaizhi'])
					.",".$conn->qstr($resume_infos['shouji'])
					.",".$conn->qstr($resume_infos['middleschool'])
					.",".$conn->qstr($resume_infos['qt_xueke'])
					.",".$conn->qstr($resume_infos['xueke'])
					.",".$conn->qstr($resume_infos['xueduan'])
					.",".$conn->qstr($resume_infos['ssgzjl'])
					.",".$conn->qstr($resume_infos['gzjl'])
                  .")";
                 }   
	    }
	  if($sql!=""){
             $rs = $conn->Execute($sql);
          }
         $conn->debug=false;
        return $rs;
    }   //end function
    
    
     function write_resume()
    {
        $rs =& $this->get_resumeinfo(" active=1 and the_status=1",'','count');
        $total = $rs->fields[0];

        $time_dan = time();
        $time_san = $time_dan - 259200;
        $rs = $this->get_resumeinfo(" the_status=1 and active =1 AND time>".$time_san." and time<".$time_dan, '', 'count');
        $total_san = $rs->fields[0];

        $content = '
            <font color="#FF6600">
                <span class="icon1">三天新增 <b>'.$total_san.'</b> 个</span>
                <span class="icon1">总数 <b>'.$total.'</b> 个</span>
            </font>';
        CMS_Common::write_file($this->_cms->tpl_path.'/rcjl/job/doc/index_resume.tpl', $content);      
    }   //end function
    
    
    
     /**
     * 根据指定的条件，获取登陆单位用户信息记录。
     *
     * @param   string  $where      SQL 的 WHERE 子句。
     * @param   string  $order      SQL 的 ORDER BY 子句。
     * @param   mixed   $numrows    获取的记录数，若此值为“count”，则查询记录总数。
     * @param   integer $offset     开始记录的下标。
     * @return  object ADORecordSet 用户信息记录集的引用。
     * @access  public
     */
    function &get_comuserinfo($where = '', $order = '', $numrows = -1, $offset = -1)
    {
         $conn =& $this->_cms->get_adodb_conn();
		//$conn->debug=true;
        if ('count' == $numrows) {
            $field_str = 'COUNT(id)';
            $numrows = -1;
        } else {
            $field_str = 'userid,firm,firmtype,address,linkman,telephone,linkaddress,postnumber,email,more,schoolnature,flag,introduction,time,job_time,fukuan_time,id,uuid,origin,intro_url';
        }   //end if
        $sql = "SELECT $field_str FROM rcjl_userinfo";
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }	//end if
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
       // echo $sql;
        return $rs;
    }   //end function
    
    
     /**
     * 修改企业的一般信息。
     *
     * @param   array   $user_infos 用户各信息字段组成的数组：
     *                  array(  'userid'   => '用户登录用户名',
     *                         
     *                          'firm' => '招聘单位名称',
     *                          'firmtype '  => '招聘单位性质',
     *                          'address'  => '公司所在地址',
     *                          'schoolnature' =>'招聘学校性质'
	                            'linkman'  => '联系人',
     *                          'telephone'    => '联系电话',
     *                          'linkaddress'     => '联系地址',
     *                          'postnumber'  => '邮编',
     *                          'email'    => 'E-mail',
     *                          'more'=> '其他联系方式（qq,msn...）',
     *                          'intro_url'=>'企业简介的外部链接'
     *                        
     *                  )
     * @return  void
     * @access  public
     */        
    function update_user($user_infos , $update='')
    {
          $conn =& $this->_cms->get_adodb_conn();
        //$conn->debug=true;
        $userid = $user_infos['userid'];
        $where="userid=".$conn->qstr($userid)." and uuid=".$user_infos['uuid']." and origin=".$user_infos['origin']; 
        
        $rs = $this->get_comuserinfo($where,'',1);
		if($rs->fields[0]!='') {
			$field_str="m_auth=0,intro_url=".$conn->qstr($user_infos['intro_url']).",firm=".$conn->qstr($user_infos['firm']).", 		 firmtype=".$conn->qstr($user_infos['firmtype']).",address=".$conn->qstr($user_infos['address']).",schoolnature=".$conn->qstr($user_infos['schoolnature']).",linkman=".$conn->qstr($user_infos['linkman']).",telephone=".$conn->qstr($user_infos['telephone']).", linkaddress=".$conn->qstr($user_infos['linkaddress']). " , postnumber=".$conn->qstr($user_infos['postnumber'])." ,  email=".$conn->qstr($user_infos['email']). ", more=" .$conn->qstr($user_infos['more']). ", introduction=" .$conn->qstr($user_infos['introduction']).", time=".$conn->qstr($user_infos['time']);
            $sql = "UPDATE rcjl_userinfo SET " .$field_str. "                        WHERE ".$where;
		} else {
                $sql = "INSERT INTO  rcjl_userinfo (userid,firm,firmtype,address,linkman,telephone,linkaddress,postnumber,email,more,schoolnature,flag,introduction,time,uuid,origin,intro_url) VALUES(".$conn->qstr($user_infos['userid']).",".$conn->qstr($user_infos['firm']).",".$conn->qstr($user_infos['firmtype']).",".$conn->qstr($user_infos['address']).",".$conn->qstr($user_infos['linkman']).",".$conn->qstr($user_infos['telephone']).",".$conn->qstr($user_infos['linkaddress']).",".$conn->qstr($user_infos['postnumber']).",".$conn->qstr($user_infos['email']).",".$conn->qstr($user_infos['more']).",".$conn->qstr($user_infos['schoolnature']).",1,".$conn->qstr($user_infos['introduction']).",".$conn->qstr($user_infos['time']).",".$user_infos['uuid'].",".$user_infos['origin'].",".$conn->qstr($user_infos['intro_url']).")";
        } //end if 
        $conn->Execute($sql);
    }   //end function
    
    
      function Select_Center_opt1($where = '')
	{
	   $conn =& $this->_cms->get_adodb_conn();
		//$conn->debug=true;
        $sql = "SELECT a.jobid  FROM rcjl_job a, rcjl_userinfo b where 1=1";
        if ($where)
            $sql .= " AND $where";
        $rs = $conn->SelectLimit($sql);
        return $rs;
    }//end function
    
      function set_expire_items($where = '')
	{
	      $conn =& $this->_cms->get_adodb_conn();
		//$conn->debug=true;
	
	    
	
		
		
        $sql = "SELECT a.jobid, b.messageid, b.starttime, b.endtime FROM rcjl_job a,b_orderlist b where b.messageid=a.jobid and a.active!=0 AND a.active!=3";
        if ($where)
            $sql .= " AND $where";
        $rs = $conn->SelectLimit($sql);
        return $rs;
    }//end function
    
    
     /**
     * 根据指定的条件，获取用户信息记录集。
     *
     * @param   string  $where      SQL 的 WHERE 子句。
     * @param   string  $order      SQL 的 ORDER BY 子句。
     * @param   mixed   $numrows    获取的记录数，若此值为“count”，则查询记录总数。
     * @param   integer $offset     开始记录的下标。
     * @param   boolean $lite       是否使用轻量级数据，默认为 false。
     * @return  object ADORecordSet 用户信息记录集的引用。
     * @access  public
     */
     //Getjoblist_1($where,'','count',-1);
    function &Getjoblist_1($where = '', $order = '', $numrows = -1, $offset = -1, $lite = false)
    {
    	 global $conn,$t_joblist;
    	 
        $ret_type=0;
        if($conn==""){
          $conn =& $this->_cms->get_adodb_conn();
       }
        //$conn->debug=true;
        if ('count' == $numrows) {
            $field_str = 'COUNT(a.jobid)';
            $numrows = -1;
            $ret_type=1;
        } else {
            if($lite==true){	
              $field_str = 'a.jobid,a.speciality,a.job_type,a.job_work,a.longevity,a.sex,a.age,a.year,a.bornaddress,a.hopemoney,a.jobnumber,
              a.jobaddress ,a.fdate ,a.jdot,a.m_auth_flag ,a.zc,a.lxfs,a.uptodate,a.firm ,
              
              a.firmtype,a.address,a.linkman,a.telephone,a.linkaddress,a.postnumber,a.email,a.more,a.schoolnature,a.flag,a.intro_url
              
              ';
              
             
            }else{
              $field_str = 'a.jobid,a.speciality,a.job_type,a.job_work,a.longevity,a.sex,a.age,a.year,a.bornaddress,a.hopemoney,a.jobnumber,
              a.jobaddress ,a.fdate ,a.jdot,a.m_auth_flag ,a.zc,a.lxfs,a.uptodate,a.firm,
              a.firmtype,a.address,a.linkman,a.telephone,a.linkaddress,a.postnumber,a.email,a.more,a.schoolnature,a.flag,a.intro_url,
              
              
              a.script';
            }
        }   //end if
        $sql = "SELECT $field_str FROM rcjl_job a";
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
        
        if( $ret_type==1){
        	if(!$rs->EOF){
           	   $ret= $rs->fields[0];
           	}
           	return $ret;
        }
        
        
        
        $i=0;
        $xh=$offset+1;
	while ($rs && !$rs->EOF)
	 {
	 	                $mRet[$i]['xuhao']=$xh;
				$mRet[$i]['jobid']        = $rs->fields[0];
				
				$mRet[$i]['speciality']        = $rs->fields[1];
				$mRet[$i]['job_type']        = $rs->fields[2];
				
				$the_job_key=$rs->fields[3];
				
				$the_job_name=$t_joblist[$the_job_key];
				if($the_job_name==""){
				  $mRet[$i]['job_work']        = $the_job_key;
				}else{
				   $mRet[$i]['job_work']        = $the_job_name;
			       }
				$mRet[$i]['job_work_s']= $the_job_key;
				$mRet[$i]['longevity']        = $rs->fields[4];
				$mRet[$i]['sex']        = $rs->fields[5];
				$mRet[$i]['age']        = $rs->fields[6];
				$mRet[$i]['year']        = $rs->fields[7];
				$mRet[$i]['bornaddress']        = $rs->fields[8];
				$mRet[$i]['hopemoney']        = $rs->fields[9];
				$mRet[$i]['jobnumber']        = $rs->fields[10];
				$mRet[$i]['jobaddress']        = $rs->fields[11];
				$mRet[$i]['fdate']        = date("Y-m-d",$rs->fields[12]);
				$mRet[$i]['jdot']        = $rs->fields[13];
				$mRet[$i]['m_auth_flag']        = $rs->fields[14];
				$mRet[$i]['zc']        = $rs->fields[15];
				$mRet[$i]['lxfs']        = $rs->fields[16];
				if($rs->fields[17]>0){
				  $mRet[$i]['uptodate']        = date("Y-m-d",$rs->fields[17]);
				}else{
					 $mRet[$i]['uptodate']        ="";
				}
				$mRet[$i]['firm']        = $rs->fields[18];
				$mRet[$i]['firmtype']        = $rs->fields[19];
				$mRet[$i]['address']        = $rs->fields[20];
				$mRet[$i]['linkman']        = $rs->fields[21];
				$mRet[$i]['telephone']        = $rs->fields[22];
				$mRet[$i]['linkaddress']        = $rs->fields[23];
				$mRet[$i]['postnumber']        = $rs->fields[24];
				$mRet[$i]['email']        = $rs->fields[25];
				$mRet[$i]['more']        = $rs->fields[26];
				$mRet[$i]['schoolnature']        = $rs->fields[27];
				$mRet[$i]['flag']        = $rs->fields[28];
				$mRet[$i]['intro_url']        = $rs->fields[29];
				if($lite==false){
				    $mRet[$i]['script']        = $rs->fields[30];
			        }
				$i++;
				$xh++;
			        $rs->MoveNext();
         }//end of while
         return $mRet;
    }   //end function
    
      function &Getjoblist_2(&$conn,$where = '', $order = '', $numrows = -1, $offset = -1, $lite = false)
    {
        $ret_type=0;
         if($conn==""){
         	
          $conn =& $this->_cms->get_adodb_conn();
        }
        //$conn->debug=true;
        if ('count' == $numrows) {
            $field_str = 'COUNT(a.jobid)';
            $numrows = -1;
            $ret_type=1;
        } else {
            	
              $field_str = 'a.jobid,a.speciality,a.job_type,a.job_work,a.longevity,a.sex,a.age,a.year,a.bornaddress,a.hopemoney,a.jobnumber,
              a.jobaddress ,a.fdate ,a.jdot,a.m_auth_flag ,a.zc,a.lxfs,a.uptodate,a.firm,a.userinfo_id,a.intro_url,a.other';
              
             
           
        }   //end if
        $sql = "SELECT $field_str FROM rcjl_job a";
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
        
        if( $ret_type==1){
        	if(!$rs->EOF){
           	   $ret= $rs->fields[0];
           	}
           	return $ret;
        }
        
           return $rs;
        }//end of function
        
      
      /**
     * 根据指定的条件，获取用户简历信息记录集。
     *
     * @param   string  $where      SQL 的 WHERE 子句。
     * @param   string  $order      SQL 的 ORDER BY 子句。
     * @param   mixed   $numrows    获取的记录数，若此值为“count”，则查询记录总数。
     * @param   integer $offset     开始记录的下标。
     * @param   boolean $lite       是否使用轻量级数据，默认为 false。
     * @return  object ADORecordSet 用户信息记录集的引用。
     * @access  public
     */
    function &get_resumeinfo_list($conn='',$where = '', $order = '', $numrows = -1, $offset = -1)
    {
         $ret_type=0;
        if($conn==""){
          $conn =& $this->_cms->get_adodb_conn();
        }
        //$conn->debug=true;
        $from = 'rcjl_resume';
        if ('count' == $numrows) {
            $field_str = 'COUNT( id )';
            $numrows = -1;
             $ret_type=1;
        } else { 
            $field_str = 'id, truename, sex,  hopeaddress,job,role,longevity,school,level , time,  dot ,other';
            
        } 
        $sql = "SELECT $field_str FROM $from";
        if ($where) {
            $sql .= " WHERE $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }	//end if
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
        
        if( $ret_type==1){
        	if(!$rs->EOF){
           	   $ret= $rs->fields[0];
           	}
           	return $ret;
        }
        
        return $rs;
        
	 
    }   //end function
    
    
    
     function insert_center($center_infos,$user_infos)
    {
         $conn =& $this->_cms->get_adodb_conn();
        //$conn->debug=true;
            if($center_infos['uptodate'] ==""){
			  $center_infos['uptodate']=0;
			}
$sql = "INSERT INTO  rcjl_userinfo (userid,firm,firmtype,address,linkman,telephone,linkaddress,postnumber,email,more,schoolnature,flag,introduction,time,uuid,origin,intro_url)
 VALUES(".$conn->qstr($user_infos['userid']).",".$conn->qstr($user_infos['firm']).",".$conn->qstr($user_infos['firmtype']).",".$conn->qstr($user_infos['address']).",".$conn->qstr($user_infos['linkman']).",".$conn->qstr($user_infos['telephone']).",".$conn->qstr($user_infos['linkaddress']).",".$conn->qstr($user_infos['postnumber']).",".$conn->qstr($user_infos['email']).",".$conn->qstr($user_infos['more']).",".$conn->qstr($user_infos['schoolnature']).",1,".$conn->qstr($user_infos['introduction']).",".$conn->qstr($user_infos['time']).",".$user_infos['uuid'].",".$user_infos['origin'].",".$conn->qstr($user_infos['intro_url']).")";
       
        $sql = "INSERT INTO rcjl_job (userinfo_id ,speciality,job_type,job_work,longevity,sex,age,year,bornaddress,hopemoney,jobnumber,jobaddress,uptodate,script,fdate,zc,lxfs,
        userid,firm,firmtype,address,linkman,telephone,linkaddress,postnumber,email,more,schoolnature,flag,uuid,origin,intro_url
        )VALUES('".$center_infos['userinfo_id']."','".$center_infos['speciality']."','".$center_infos['job_type']."','".$center_infos['job_work']."','".$center_infos['longevity']."','".$center_infos['sex']."','".$center_infos['age']."','".$center_infos['year']."','".$center_infos['bornaddress']."','".$center_infos['hopemoney']."','".$center_infos['jobnumber']."','".$center_infos['jobaddress']."',".$center_infos['uptodate'].",".$conn->qstr($center_infos['script']).",".$center_infos['fdate'].",".$conn->qstr($center_infos['zc']).",".$conn->qstr($center_infos['lxfs']).",".
        $conn->qstr($user_infos['userid']).",".$conn->qstr($user_infos['firm']).",".$conn->qstr($user_infos['firmtype']).",".$conn->qstr($user_infos['address']).",".$conn->qstr($user_infos['linkman']).",".$conn->qstr($user_infos['telephone']).",".$conn->qstr($user_infos['linkaddress']).",".$conn->qstr($user_infos['postnumber']).",".$conn->qstr($user_infos['email']).",".$conn->qstr($user_infos['more']).",".$conn->qstr($user_infos['schoolnature']).",1,".$user_infos['uuid'].",".$user_infos['origin'].",".$conn->qstr($user_infos['intro_url']).")";
        
        ")";
        //echo $sql;
        $rs = $conn->Execute($sql);
	}//end function 
	
	
     function edit_b_userinfo($time, $userid)
    {
	     $conn =& $this->_cms->get_adodb_conn();
        $sql="UPDATE rcjl_userinfo SET job_time = ".$time." WHERE id = ".$userid;
        $conn->Execute($sql);
	    return $rs;	
	}//end of function	
    
     function Update_Center($center_infos,$user_infos,$m_au=0)
	{
		
        if ($center_infos['jobid']) {
        	 if($center_infos['uptodate'] ==""){
			  $center_infos['uptodate']=0;
			}
        	//die("ddd");
             $conn =& $this->_cms->get_adodb_conn();
          //  $conn->debug=true;
            $sql = "UPDATE rcjl_job SET
            
            jdot=0,
            speciality =".$conn->qstr($center_infos['speciality']).",
            job_type ='".$center_infos['job_type']."',
            job_work ='".$center_infos['job_work']."',
            longevity ='".$center_infos['longevity']."',
            sex ='".$center_infos['sex']."',
            age ='".$center_infos['age']."',
            year ='".$center_infos['year']."',
            bornaddress ='".$center_infos['bornaddress']."',
            hopemoney ='".$center_infos['hopemoney']."',
            jobnumber ='".$center_infos['jobnumber']."',
            jobaddress ='".$center_infos['jobaddress']."',
            script =".$conn->qstr($center_infos['script']).",
            uptodate =".$center_infos['uptodate'].",
            zc=".$conn->qstr($center_infos['zc']).",
            lxfs=".$conn->qstr($center_infos['lxfs']).",
            firm=".$conn->qstr($user_infos['firm']).",
            firmtype=".$conn->qstr($user_infos['firmtype']).",
            address=".$conn->qstr($user_infos['address']).",
            linkman=".$conn->qstr($user_infos['linkman']).",
            telephone=".$conn->qstr( $user_infos['telephone']).",
            linkaddress=".$conn->qstr($user_infos['linkaddress']).",
            postnumber=".$conn->qstr($user_infos['postnumber']).",
             email=".$conn->qstr($user_infos['email']).",
             more=".$conn->qstr($user_infos['more']).",
             schoolnature=".$conn->qstr($user_infos['schoolnature']).", 
             fdate=".$center_infos['fdate'] .",
              intro_url=".$conn->qstr($user_infos['intro_url']); 
              
            if($center_infos['auth_pass']!=""){
            	$sql.=",m_auth_flag=".$center_infos['auth_pass'];
              }
             if($m_au==1){
             	
             	$sql.=" WHERE jobid=".$center_infos['jobid']." and m_auth_flag=0";
             	
              }else{
              $sql.=" WHERE jobid=".$center_infos['jobid']." and uuid=".$user_infos['uuid']." and origin=".$user_infos['origin']." and userid=".$conn->qstr($user_infos['userid']);
              }
            $rs = $conn->Execute($sql);
        }
        return $rs;
	}//end function	 
	
	
	 /**
     * 根据指定的 $jobid，删除相应类别。
     *
     * @param   integer $jobid   类别的唯一 ID 号。
     * @return  void
     * @access  public
     */
    function delete_center($center_jobid)
    {
        $conn =& $this->_cms->get_adodb_conn();
        //$conn->debug=true;

        $sql = "DELETE FROM rcjl_job WHERE  m_auth_flag=0 and jobid=" . $center_jobid;
        $conn->Execute($sql);
       

    }   //end function	 
    function set_jobs_auth_flag($ids)
    {
    	global $conn;
    	if($conn==""){
        	$conn =& $this->_cms->get_adodb_conn();
         }
      //  $conn->debug=true;
        $m_time=time();
        $sql = "update  rcjl_job set jdot=0,m_auth_flag=1 , fdate=".$m_time." WHERE m_auth_flag=0 and  jobid in (" . $ids.")";
        $conn->Execute($sql);
    	
    }//end of function
    
     function set_resumes_auth_flag($ids)
    {
    	global $conn;
    	if($conn==""){
    	  $conn =& $this->_cms->get_adodb_conn();
         }
     //$conn->debug=true;
        $m_time=time();
        $sql = "update  rcjl_resume set dot=0,m_pass=1 , time=".$m_time." WHERE m_pass=0 and  id in (" . $ids.")";
        $conn->Execute($sql);
    	
    }//end of function
    function del_jobs_unauth($ids)
    {
    	global $conn;
    	if($conn==""){
    	  $conn =& $this->_cms->get_adodb_conn();
         }
        //$conn->debug=true;

        $sql = "delete from rcjl_job WHERE m_auth_flag=0 and jobid in (" . $ids.")";
        $conn->Execute($sql);
    	
    }
     function del_resumes_unauth($ids)
    {
    	global $conn;
    	if($conn==""){
    	  $conn =& $this->_cms->get_adodb_conn();
         }
        //$conn->debug=true;
        // 之前有个 m_pass=0 的条件，只能删除未审核的简历，现徐汇要求所有状态的简历均可删除。
        // 参见任务 https://proj.k12studio.com/issues/5400 (Nio, 2012-11-09)
        $sql = "delete from rcjl_resume WHERE id in (" . $ids.")";
       //echo $sql;
        $conn->Execute($sql);
    	
    }
    function update_dot(&$conn,$id)
    {
    	
    	if($conn==""){
          $conn =& $this->_cms->get_adodb_conn();
        }
        
        $sql="update rcjl_resume set dot=dot+1 where id=$id and active=1 and m_pass=1";
        $conn->Execute($sql);
        
        
    }
    
    function update_jobdot($id)
    {
     global $conn;
     if($conn==""){
          $conn =& $this->_cms->get_adodb_conn();
        }
    $sql="update rcjl_job set jdot=jdot+1 where jobid=$id and m_auth_flag=1";
        $conn->Execute($sql);
    }
    
    
    function get_tj_dw($where)
    {
    	 global $conn;
         if($conn==""){
          $conn =& $this->_cms->get_adodb_conn();
         }
         //$conn->debug=true;
         $sql="select count(jobid),m_auth_flag from rcjl_job a where $where group by m_auth_flag order by m_auth_flag";
         $rs=$conn->Execute($sql);
         while($rs && !$rs->EOF){
         	
            $key=$rs->fields[1];
            $ret[$key]=$rs->fields[0];	
            $rs->MoveNext();	
        }
        return $ret;
    }  //end of function
    
    /**
     * 根据指定的条件，获取用户简历信息记录集。
     *
     * @param   string  $where      SQL 的 WHERE 子句。
     * @param   string  $order      SQL 的 ORDER BY 子句。
     * @param   mixed   $numrows    获取的记录数，若此值为“count”，则查询记录总数。
     * @param   integer $offset     开始记录的下标。
     * @param   boolean $lite       是否使用轻量级数据，默认为 false。
     * @return  object ADORecordSet 用户信息记录集的引用。
     * @access  public
     */
    function &get_user_resume_info($conn='',$where = '', $order = '', $numrows = -1, $offset = -1)
    {
        $ret_type=0;
        if($conn==""){
          $conn =& $this->_cms->get_adodb_conn();
        }
        if ('count' == $numrows) {
            $field_str = 'COUNT( r.id )';
            $numrows = -1;
             $ret_type=1;
        } else { 
            $field_str = 'r.id, r.truename, r.sex, r.bornaddress, r.job, r.role, r.longevity, r.school, r.level, r.time, r.dot , r.other, r.xueke, r.xueduan';
            
        } 
        $sql = "SELECT $field_str FROM rcjl_resume r, user_rcjl u";
        if ($where) {
            $sql .= " WHERE u.m_uuid=r.uuid AND $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }	//end if
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
        
        if( $ret_type==1){
        	if(!$rs->EOF){
           	   $ret= $rs->fields[0];
           	}
           	return $ret;
        }
        
        return $rs;
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
    function &get_not_resume_user($where = '', $order = '', $numrows = -1, $offset = -1)
    {
        $conn =& $this->_cms->get_adodb_conn();
        if ('count' == $numrows) {
            $field_str = 'COUNT(m_uid)';
            $numrows = -1;
            $ret_type=1;
        } else {
            $field_str = 'u.m_uuid, u.m_uid, u.m_uname, u.m_pwd, u.m_type, u.m_birthday, u.m_gender, u.m_email';
        }   //end if
        $sql = "SELECT $field_str FROM user_rcjl u";
        if ($where) {
            $sql .= " WHERE not exists(select uuid from rcjl_resume r WHERE u.m_uuid = r.uuid) AND $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }
        $sql .= " GROUP BY u.m_uid";
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
        if( $ret_type==1){
        	if(!$rs->EOF){
           	   $ret= $rs->fields[0];
           	}
           	return $ret;
        }
        return $rs;
    }   //end function
    
    function clear_redundant_user($where){
    	$conn = &$this->_cms->get_adodb_conn();
    	$rs = $this->get_not_resume_user($where);
        if($rs){
			while (!$rs->EOF){
				$sql = "DELETE FROM user_rcjl WHERE m_uuid = " . $rs->fields[0];
				$conn->Execute($sql);
				$rs->MoveNext();	
			}//end of while
			$rs->Close();
		}
		$res = $conn->Execute("select max(m_uuid) from user_rcjl");
		$max_id = $res->fields[0]+1;
		$conn->Execute("alter table user_rcjl AUTO_INCREMENT=".$max_id);
    }
    
    function get_last_auto_user(){
		$conn = &$this->_cms->get_adodb_conn();
		$sql="select m_uid from user_rcjl where m_uid like 'xczp_%' order by m_uuid DESC limit 1";
        $rs=$conn->Execute($sql);
        $start = 0;
        if($rs){
			$start = $rs->fields[0];
			$rs->Close();
		}
		return $start;
    }
    
    function &get_user_info($where = '', $order = '', $numrows = -1, $offset = -1)
    {
        $conn =& $this->_cms->get_adodb_conn();
        if ('count' == $numrows) {
            $field_str = 'COUNT(m_uid)';
            $numrows = -1;
            $ret_type=1;
        } else {
            $field_str = 'u.m_uuid, u.m_uid, u.m_uname, u.m_pwd, u.m_type, u.m_birthday, u.m_gender, u.m_email';
        }   //end if
        $sql = "SELECT $field_str FROM user_rcjl u";
        if ($where) {
            $sql .= " WHERE 1=1 AND $where";
        }   //end if
        if ($order) {
            $sql .= " ORDER BY $order";
        }
        $rs = $conn->SelectLimit($sql, $numrows, $offset);
        if( $ret_type == 1){
        	if(!$rs->EOF){
           	   $ret= $rs->fields[0];
           	}
           	return $ret;
        }
        return $rs;
    }   //end function
    
}//end of class

?>