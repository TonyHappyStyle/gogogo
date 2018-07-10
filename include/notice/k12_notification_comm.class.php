<?php
/**
 * k12_notification通用类。
 *
 * 创建各种k12_notification类可用下面的方法：
 * 	
 * $k12_notification  = new k12_notification();
 * 
 * 
 * @copyright   K12 Studio 
 * @package k12_notification
 * @access  public
 */ 

class k12_notification
{
   
   
	

	 /**
     * 访问基础平台 XMLRPC 的参数
     * @var array
     * @access  public
     */
    var  $platform_xmlrpcs = array();
    var  $local_xmlrpcs=array();
	/**
	  * k12_notification所在的物理目录路径
	  * var string $k12_notification_path
	  * @access  public
	*/
	var $k12_notification_path = '';

	/**
	  * k12_notification所在的URL
	  * var string $k12_notification_url
	  * @access  public
	*/
	var $k12_notification_url  = '';

	/**
	  * 模板所在的物理目录路径
	  * var string $tpl_path
	  * @access  public
	*/
	

    /**
     * k12_notification 的类构造函数。在此构造函数中将对小平台所用到的目录路径进行初始化。
     *
     * @access  public
     * @return  void
     */
    function k12_notification()
    {
        //load config file
		include 'notice/k12_notification_config.inc1.php';
                include 'notice/local_rpc_set.inc.php';
                
		$this->k12_notification_path  = $_SERVER["DOCUMENT_ROOT"]."/".$PRODUCT_ROOT;
		$this->k12_notification_url   = '/'.$PRODUCT_ROOT;
		
     
					
                $this->platform_xmlrpcs = &$cfg_platform_xmlrpcs;   //基础平台 XMLRPC 参数
               $this->local_xmlrpcs=&$cfg_local_xmlrpcs;//本地XMLRPC 参数
                //echo $this->platform_xmlrpcs;
               
     }//end function k12_notification
   
   

  
 /**
     * 获取基础平台的url
     *
     * @access  public
     * @param   string  $remote_addr 客户端IP
     * @return  string 
     */
     function xx_login_url($remote_addr)
     {
     	//xml-rpc......调用base.getPlatformUrl
     	//require_once("xmlrpc/xmlrpc.inc");
     	
     	
		 $f=new xmlrpcmsg('base.getPlatformUrl',
				   array(new xmlrpcval($remote_addr, "string")));
 // print "<pre>" . htmlentities($f->serialize()) . "</pre>\n";	
	 
	 
	 
	 
	 
	 
	 
	//echo $this->platform_xmlrpcs['username'];
	//echo  $this->platform_xmlrpcs['password'];
	 
	// die("");
	 
	 
	 
	 
	 		   
     	$c=new xmlrpc_client($this->platform_xmlrpcs['path'], $this->platform_xmlrpcs['host'], $this->platform_xmlrpcs['port']);
		$c->request_charset_encoding = 'GBK'; //指定请求编码为 GBK
     	$c->setCredentials($this->platform_xmlrpcs['username'],$this->platform_xmlrpcs['password']);
     	//$c->setDebug(1);
     	 $r=$c->send($f);
     	  $v=$r->value();
     	  //die("");
          if (!$r->faultCode()) 
          {
	    $ret_string=$v->scalarval()."/platform/app/login.php";
		//die("$ret_string");
	  return  $ret_string ;
	    
              }
           else
           {
           	$this->die_xmlrpc_error("error");
           }    
              
     	 
     	 
     	 
     	//return "http://192.168.212.68/platform/";
     	
     }//end function xx_login_url


   function xx_logout_url($remote_addr)
     {
     	//xml-rpc......调用base.getPlatformUrl
     	//require_once("xmlrpc/xmlrpc.inc");
     	
     	
		 $f=new xmlrpcmsg('base.getPlatformUrl',
				   array(new xmlrpcval($remote_addr, "string")));
 // print "<pre>" . htmlentities($f->serialize()) . "</pre>\n";	
	 
	 
	 
	 
	 
	 
	 
	//echo $this->platform_xmlrpcs['username'];
	//echo  $this->platform_xmlrpcs['password'];
	 
	// die("");
	 
	 
	 
	 
	 		   
     	$c=new xmlrpc_client($this->platform_xmlrpcs['path'], $this->platform_xmlrpcs['host'], $this->platform_xmlrpcs['port']);
		$c->request_charset_encoding = 'GBK'; //指定请求编码为 GBK
     	$c->setCredentials($this->platform_xmlrpcs['username'],$this->platform_xmlrpcs['password']);
     	//$c->setDebug(1);
     	 $r=$c->send($f);
     	  $v=$r->value();
     	  //die("");
          if (!$r->faultCode()) 
          {
	    $ret_string=$v->scalarval()."/platform/app/logout_do.php";
		//die("$ret_string");
	  return  $ret_string ;
	    
              }
           else
           {
           	$this->die_xmlrpc_error("error");
           }    
              
     	 
     	 
     	 
     	//return "http://192.168.212.68/platform/";
     	
     }//end function xx_logout_url

  
     /**
     * 获取登录者信息,并注册相应的SESSION
     *
     * @access  public
     * @param   string  $sessid 由基础管理平台传过来的参数,是在登录过程中产生的
     * @return  void
     */
        function setSessin($sessid)
        {
        	//1.调用xml-rpc   user.isUserLogin($sessid)
        	//2.调用xmlrpc  user.getUserInfo,  放入login_UserInfo
        	
        	
        	
        	//die("$xx1");
        	
        	if($sessid!=""){
        	
          //die("sessidooo=$sessid");
            //1.
			
            $f=new xmlrpcmsg('user.isUserLogin',
				   array(new xmlrpcval($sessid, "string")));
			//print "<pre>" . htmlentities($f->serialize()) . "</pre>\n";	
            $c=new xmlrpc_client($this->platform_xmlrpcs['path'], $this->platform_xmlrpcs['host'], $this->platform_xmlrpcs['port']);
            $c->request_charset_encoding = 'GBK'; //指定请求编码为 GBK
     	    $c->setCredentials($this->platform_xmlrpcs['username'],$this->platform_xmlrpcs['password']);
     	    $c->setDebug(0);
     	    $r=$c->send($f);
     	    $v=$r->value();
            if (!$r->faultCode()) 
            {
	          $the_login_userid=$v->scalarval();
              }
           else
           {
           	$this->die_xmlrpc_error($r->errstr);
           }    
              
     	 }
          return $the_login_userid;	
        	  
        	 
        }//end function setSessin


  /**
     * 访问基础平台 XMLRPC 出错时输出错误，同时结束程序运行。
     *
     * @param   string  $errmsg 错误信息
     * @return  void
     * @aceess  public
     */
    function die_xmlrpc_error($errmsg)
    {
?>
<html>
<head>
    <title>访问基础平台 XMLRPC 方法失败！</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    <div style="color:red"><?php echo nl2br($errmsg); ?></div>
</body>
</html>
<?php
        exit;
    }   //end function
    
////

	


///////////it is a test
 function require_togroup_array($m_full_path)
    {
       
            if (!file_exists($m_full_path))
              {  $this->gettopGroupInfo_platform($m_full_path);}
            
                require_once($m_full_path);
           
                return unserialize($cfg_group_tops);
            
       
    }   //end function    

 function gettopGroupInfo_platform($m_full_path='')
     {
     	
     	
     	$topgroup_info=$this->getGroupInfo_platform_1("parent_id=0");
     	if(is_array($topgroup_info))
     	{
     	   $topgroup_info=serialize($topgroup_info);
     	    $content  = '<?'.'php'."\r\n";
      
        $content .= '$cfg_group_tops = '.var_export($topgroup_info, TRUE).';'."\r\n";
        $content .= '?'.'>';
        $this->write_file($m_full_path, $content); 
        }
     	
     }//end function gettopGroupInfo_platform
	 
	 function getGroupInfo_platform_1($m_where=''){
		$info = explode('=',$m_where);
		$this->require_groups();
		$res = $this->getGroupsOrder($info[1]);
		$i = 0;
		if($res){
			foreach($res as $group){				
			    $ress[$i]['group_id'] = $group;
				$ress[$i]['group_name'] = $this->groups[$group]['name'];
				$i ++;
			}
			return $ress;
		}else{
			return $res;	 
		}
     	//return "http://192.168.212.68/platform/";
     	
     }//end function getGroupInfo_platform
	 
	function require_groups(){
		global $PRODUCT_ROOT;
		
		$file = $_SERVER["DOCUMENT_ROOT"]."/".$this->k12_notification_url."/include/top_group_array_f.php";
		$reload = false;
		if (file_exists($file)) {
			require $file;
			if (empty($cfg_group_tops) || empty($cfg_groups)) $reload = true;	
		} else {
			$reload = true;	
		}
		if ($reload) { //重新通过XMLRPC加载组数据并保存到文件中
			$groups = array();
			$group_tops = array();
			if(!$order) $order="parent_id,group_id";
			$f = new xmlrpcmsg('group.getGroupInfo2',
			  array(new xmlrpcval('', "string"), new xmlrpcval('', "string"), new xmlrpcval(-1, "int"), new xmlrpcval(0, "int")));
			$c=new xmlrpc_client($this->platform_xmlrpcs['path'], $this->platform_xmlrpcs['host'], $this->platform_xmlrpcs['port']);
			$c->request_charset_encoding = 'GBK'; //指定请求编码为 GBK
			$c->setCredentials($this->platform_xmlrpcs['username'],$this->platform_xmlrpcs['password']);
			$c->setDebug(0);
			$r=$c->send($f);
			$v=$r->value();
		
			if ($r->faultCode()) die('调用基础平台 group.getGroupInfo2 方法错误。'.$r->faultCode().' - '.$r->faultString());
			
			$max=$v->arraysize(); 
			for($i=0; $i<$max; $i++) 
			{
				$rec=$v->arraymem($i);
				$id=$rec->structmem("group_id");
				$name=$rec->structmem("group_name");
				$thread_id=$rec->structmem("thread_id");
				$parent_id=$rec->structmem("parent_id");
				$group_flag=$rec->structmem("group_flag");
				$goid=$rec->structmem("goid");
		////
				$group_id           = $id->scalarval();
				$group_name         = $name->scalarval();
				$group_thread_id    = $thread_id->scalarval();
				$group_parent_id    = $parent_id->scalarval();
				$group_flag         = $group_flag->scalarval();
				$goid               = $goid->scalarval();
				$groups[$group_id]['name']          = $group_name;
				$groups[$group_id]['thread']        = $group_thread_id;
				$groups[$group_id]['parent']        = $group_parent_id;
				$groups[$group_id]['flag']          = $group_flag;
				$groups[$group_id]['order_id']          = $goid;
				
				
				  if (!isset($groups[$group_id]['child']))
						$groups[$group_id]['child'] = array();
					if ($group_parent_id)
						$groups[$group_parent_id]['child'][] = $group_id;
					else
						$group_tops[] = $group_id;	
			}		
			//将组数据写入文件
			$content  = '<'.'?php'."\r\n";
			$content .= '$cfg_group_tops = '.var_export($group_tops, TRUE).';'."\r\n";
			$content .= '$cfg_groups = '.var_export($groups, TRUE).';'."\r\n";
			$content .= '?'.'>';
			$this->write_file($file, $content);
			//设置对象属性 
			$this->groups = $groups;
			$this->group_tops = $group_tops;					
		} else {
			//设置对象属性
			$this->groups = $cfg_groups;
			$this->group_tops = $cfg_group_tops;	
		}		
	}

	function getGroupInfo_platform($m_where='',$child='',$order=''){
		//xml-rpc......调用base.getPlatformUrl
		$this->getGroupInfoReadFile(1);
	}//end function getGroupInfo_platform


	function _get_group_option($group_id, $groups,$group_tops,$indent = 0) {
       define("CRLF","\r\n");
        $parent_id = $groups[$group_id]['parent'];
      
      
       $m_url="";
        $m_url="javascript://\\\" onclick=\\\"writeSelect(".$group_id.");";
        $options = 'var s' . $group_id . ' = new WebFXTreeItem("' . htmlspecialchars(addslashes($groups[$group_id]['name'])) . '';
        if (!$parent_id)
            $options .= '';
        $options .= '", "' . $m_url . '"';
        
        $options .= ');' . CRLF;
        if (count($groups[$group_id]['child']) > 0) {
            
            foreach ($groups[$group_id]['child'] as $child_id) {
                $options_tmp = $this->_get_group_option($child_id,$groups,$group_tops);
              
                $options .= $options_tmp;
            }   //end foreach
        }   //end if
        if ($parent_id)
            $options .= 's' . $parent_id . '.add(s' . $group_id . ');' . CRLF;
        else
            $options .= 'tree.add(s' . $group_id . ');' . CRLF;
        return $options;
    }   //end function

/////////////////////////
///////////it is a test
function getUserInfo_platform($where,$the_count='',$numrows=-1,$offset=-1){
	if ($the_count!=''){
		$f=new xmlrpcmsg('user.getUserTotal',array(new xmlrpcval($where, "string")));
		$c=new xmlrpc_client($this->platform_xmlrpcs['path'], $this->platform_xmlrpcs['host'], $this->platform_xmlrpcs['port']);
		$c->request_charset_encoding = 'GBK'; //指定请求编码为 GBK
		$c->setCredentials($this->platform_xmlrpcs['username'],$this->platform_xmlrpcs['password']);
		$c->setDebug(0);
		$r=$c->send($f);
		$v=$r->value();
		if (!$r->faultCode()) {
			$m_ret=$v->scalarval();
		}else{
			$this->die_xmlrpc_error("error");
		}
		return $m_ret;
	}//end of if ($the_count!='')
	$order = ' order_id ,user_uid desc';
	$f=new xmlrpcmsg('user.getUserInfo2',array(new xmlrpcval($where, "string"),new xmlrpcval($order, "string"),new xmlrpcval($numrows, "int"),new xmlrpcval($offset,"int")));
	// print "<pre>" . htmlentities($f->serialize()) . "</pre>\n";		
	
	$c=new xmlrpc_client($this->platform_xmlrpcs['path'], $this->platform_xmlrpcs['host'], $this->platform_xmlrpcs['port']);
	$c->request_charset_encoding = 'GBK'; //指定请求编码为 GBK
	$c->setCredentials($this->platform_xmlrpcs['username'],$this->platform_xmlrpcs['password']);
	$c->setDebug(0);
	$r=$c->send($f);
	$v=$r->value();
	
	if (!$r->faultCode()) {
		$max=$v->arraysize(); 
		
		for($i=0; $i<$max; $i++) 
		{
			$rec=$v->arraymem($i);
			$user_uid=$rec->structmem("user_uid");
			$user_id=$rec->structmem("user_id");
			$name=$rec->structmem("user_name");
			$birthday=$rec->structmem("email");
			$uoid=$rec->structmem("uoid");			
			
			$data[$i]["user_uid"] = $user_uid->scalarval();			
			$data[$i]["user_id"] = $user_id->scalarval();				
			$data[$i]["name"] = $name->scalarval();			
			$data[$i]["email"]=$birthday->scalarval();			
			$data[$i]["uoid"]=$uoid->scalarval();
		}
		return  $data ;
	}else{
		$this->die_xmlrpc_error("error");
	}
	
	//return "http://192.168.212.68/platform/";
	
	}//end function getUserInfo_platform


	function mytest($uidid)
	{		
		$f=new xmlrpcmsg('notice.getNewCount',
		array(new xmlrpcval($uidid, "string")));		
		print "<pre>" . htmlentities($f->serialize()) . "</pre>\n";		   
		$c=new xmlrpc_client('/k12_notification/rpc/', '192.168.212.172', '80');
		$c->request_charset_encoding = 'GBK'; //指定请求编码为 GBK
		$c->setCredentials('K12RPC','K12RPCPwd');
		$c->setDebug(1);
		$r=$c->send($f);
		$v=$r->value();
		//die("");
		if (!$r->faultCode()) {
			$ret_string=$v->scalarval();
			//die("$ret_string");
			return  $ret_string ;		
		}else{
			$this->die_xmlrpc_error("error");
		}    
		
	}//end of mytest
	
	
	 function write_file($file, $content,$none_del=0) 
    {
        if (!$content) {
            if($none_del==1){
             @unlink($file);
        }else{
            touch($file);
           }
            return TRUE;
        }   //end if
        if ($fp = fopen($file, 'w')) {
            if (!fwrite($fp, $content)) {
                return FALSE;
            }
            fclose($fp);
            return TRUE;
        } else {
            return FALSE;
        }   //end if    
    }   //end function
	
	function getGroupInfoReadFile($i = 0){		
		$this->require_groups();
		unset($groups);
		unset($group_tops);
		$groups = $this->getGroupsOrder();
		$js = "var t0 = new WebFXTree('用户组', 'javascript://\\\" onclick=\\\"writeSelect(0)');"."\n";
		$js .= "t0.target = ''"."\n";
		foreach($groups as $group_top){
			$m_url="javascript://\\\" onclick=\\\"writeSelect(".$group_top.");";
			if(count($this->groups[$group_top]['child'])>0){
				$js .= "var t".$group_top." = new WebFXLoadTreeItem('".$this->groups[$group_top]['name']."','"."/com/notice/admin/mygroup_loader.php?i=".$i."&parent_id=".$group_top."','javascript://\\\" onclick=\\\"writeSelect(".$group_top.")')"."\n";
				$js .= 't'.$group_top.'.target = ""'."\n";
				$js .= "t0.add(t".$group_top.");"."\n";
			}else{
				$js .= "var t".$group_top." = new WebFXTreeItem('".$this->groups[$group_top]['name']."','".$m_url."')"."\n";
				$js .= 't'.$group_top.'.target = ""'."\n";
				$js .= "t0.add(t".$group_top.");"."\n";
			}			
		}
		$js .= "document.write(t0);";
		return $js;
	}
	
	function getGroupsOrder($parent_id = 0){
		//ini_set('display_errors','on');
		$this->require_groups();
		unset($groups);
		unset($group_tops);
		$groups = array();
		$groupids = array();
		$i = 100000000;
		if(!$parent_id){
			foreach($this->group_tops as $group_top){
				$groups[$this->groups[$group_top]['order_id']] = $group_top;
			}
		}else{		
		    $children = $this->groups[$parent_id]['child'];
			if($children){
				foreach($children as $child){
					$key = $this->groups[$child]['order_id']*100000 + $child;
					if(is_float($key)){
						$groupids[$i] = $child;
						$i++;
					}else{
						$groups[$key] = $child;
					}
				}
			}
		}
		ksort($groups);			
		if($groupids){
			sort($groupids);
			foreach($groupids as $groupid){
				array_push($groups,$groupid);
			}			
		}
		return $groups;
	}
	
	function synchronUserOrder($where, $numrows,$offset = 0){
		$f=new xmlrpcmsg('user.getUserInfo2',array(new xmlrpcval($where, "string"),new xmlrpcval("", "string"),new xmlrpcval($numrows, "int"),new xmlrpcval($offset,"int")));

     	$c=new xmlrpc_client($this->platform_xmlrpcs['path'], $this->platform_xmlrpcs['host'], $this->platform_xmlrpcs['port']);
     	$c->request_charset_encoding = 'GBK'; //指定请求编码为 GBK
     	$c->setCredentials($this->platform_xmlrpcs['username'],$this->platform_xmlrpcs['password']);
     	$c->setDebug(0);
     	$r=$c->send($f);
     	$v=$r->value();

        if (!$r->faultCode()){
			$max=$v->arraysize(); 
			
			for($i=0; $i<$max; $i++) {
				$rec=$v->arraymem($i);
				$user_id=$rec->structmem("user_id");
				$order_id=$rec->structmem("uoid");
				$data[$i]["user_id"] = $user_id->scalarval();				
				$data[$i]["order_id"]=$order_id->scalarval();
			
			}
	        return  $data ;
		}else{
			$this->die_xmlrpc_error("error");
		}
	}
	
	function getUserCount($where){
		$f=new xmlrpcmsg('user.getUserTotal',array(new xmlrpcval($where, "string")));
		$c=new xmlrpc_client($this->platform_xmlrpcs['path'], $this->platform_xmlrpcs['host'], $this->platform_xmlrpcs['port']);
		$c->request_charset_encoding = 'GBK'; //指定请求编码为 GBK
		$c->setCredentials($this->platform_xmlrpcs['username'],$this->platform_xmlrpcs['password']);
		$c->setDebug(0);
		$r=$c->send($f);
		$v=$r->value();
		if (!$r->faultCode()) {
			$m_ret=$v->scalarval();
			return $m_ret;
		}
		else{
			$this->die_xmlrpc_error("error");
		}	
	}

}   //end class k12_notification
?>
