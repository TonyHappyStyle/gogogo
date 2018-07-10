<?php
//  $Id:$

/**
 * 在线咨询通用类。
 * 此类包含了各种 static 方法，相当于将公用函数集成到此类中。
 * 调用此类的方法时可以不用创建 ZXZX_Common 对象实例，而是直接用 ZXZX_Common :: 方法名，例如：ZXZX_Common :: do_substr($str, $length)。
 *
 * @package K12ZXZX
 * @access  public
 */
class ZXZX_Common
{ 
    //  {{{ private properties
    
    /**
     * ZXZX_Common 类文件的 Id，用于 CVS 版本追踪。
     * @var string
     * @access  private
     */    
    var $_id = '$Id:$';
    
    //  }}}
    
    //  {{{ substr()

    /**
     * 用于截取特定长度的字符串，同时处理中文字符，避免中文字符显示成问号或乱码，返回截取之后的字符串；若字符串长度小于或等于参数 $str_len 则返回原字符串。
     *
     * @access  public
     * @param   string  $str        需要截取的字符串。
     * @param   array   $str_len    截取的字符串长度。
     * @param   array   $str_append 截取操作发生时，在被截取字符串最后边增加的字符串，默认值是“…”。
     * @return  string
     * @static
     */
    function substr($str, $str_len, $str_append = '...', $x3 = 0)
    {
        global $CFG; // 全局变量保存 x3 的值
		if ($str_len <= 0 || $str_len >= strlen($str)) {  
			return $str;  
		}  
		$arr = str_split($str);  
		$len = count($arr);  
		$w = 0;  
		$str_len *= 10;  
	
		// 不同字节编码字符宽度系数  
		$x1 = 11;   // ASCII  
		$x2 = 16;  
		$x3 = $x3===0 ? ( $CFG['cf3']  > 0 ? $CFG['cf3']*10 : $x3 = 21 ) : $x3*10;  
		$x4 = $x3;  
	
		// http://zh.wikipedia.org/zh-cn/UTF8  
		for ($i = 0; $i < $len; $i++) {  
			if ($w >= $str_len) {
				break;  
			}  
			$c = ord($arr[$i]);  
			if ($c <= 127) {  
				$w += $x1;  
			}  
			elseif ($c >= 192 && $c <= 223) { // 2字节头  
				$w += $x2;  
				$i += 1;  
			}  
			elseif ($c >= 224 && $c <= 239) { // 3字节头  
				$w += $x3;  
				$i += 2;  
			}  
			elseif ($c >= 240 && $c <= 247) { // 4字节头  
				$w += $x4;  
				$i += 3;  
			}  
		}  
	
		return implode('', array_slice($arr, 0, $i) ). $str_append;  
    }   //end function    
    
    //  }}}    
    
    //  {{{ rm()
    
    /**
     * 递归删除文件或目录，包括子目录及其目录下的所有文件；类似于“rm -rf”，一旦删除将无法挽回，请小心使用！
     *
     * @access  public
     * @param   string  $file   需要删除的文件或目录的物理路径，不要以“/”结尾。
     * @param   boolen  $system 是否使用系统命令做删除操作。Linux 使用“rm -rf”，而 Windows 使用“del /s”。s
     * @return  void
     * @static
     */
    function rm($file, $system = FALSE) 
    {
        if ($system) {
            $os = strtolower(substr(PHP_OS, 0, 3));
            if ('win' == $os)
                $cmd = 'del /s/q '.str_replace('/', '\\', $file);
            else
                $cmd = 'rm -rf '.$file;
            exec($cmd);                
        } else if (file_exists($file)) {
            if (is_dir($file)) {
                $dir_handle = opendir($file); 
                while ($file_name = readdir($dir_handle)) {
                    if ('.' != $file_name && '..' != $file_name) 
                        CMS_Common :: rm($file.'/'.$file_name); //递归调用
                }   //end while
                closedir($dir_handle);
                rmdir($file);
            } else {
                unlink($file);
            }   //end if
        }   //end if
    }   //end function

    //  }}} 

    //  {{{ write_file()

    /**
     * 将内容写入文件。
     *
     * @access  public
     * @param   string  $file       需要写入的文件。
     * @param   string  $content    需要写入的内容。
     * @return  boolean
     * @static
     */
    function write_file($file, $content) 
    {
        if (!$content) {
            touch($file);
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

    //  }}}

    //  {{{ get_upload_max_filesize()

    /**
     * 获取上传文件大小的最大值（单位为“字节” - byte）。
     *
     * @access  public
     * @param   string  $size   大小字符串，如 10M、10MB、10K、10KB等，可用的单位为：M、MB、K 和 KB。
     * @return  integer
     * @static
     */
    function get_upload_max_filesize() 
    {
        if (!ini_get("file_uploads"))
            return 0;
        $upload_max_filesize = CMS_Common :: get_real_size(ini_get("upload_max_filesize"));
        $post_max_size = CMS_Common :: get_real_size(ini_get("post_max_size"));
        $memory_limit = round((CMS_Common :: get_real_size(ini_get("memory_limit"))) / 2);
        if ($upload_max_filesize>$post_max_size)
            $max = $post_max_size;
        else
            $max = $upload_max_filesize;
        if (('' != $memory_limit) && ($memory_limit < $max))
            $max = $memory_limit;
        return $max;
    }   //end function

    // }}}

    //  {{{ get_real_size()

    /**
     * 获取存储容量大小字符串的真正字节数。如传入参数“1K”，将返回“1024”。
     *
     * @access  public
     * @param   string  $size   大小字符串，如 10M、10MB、10K、10KB等，可用的单位为：M、MB、K 和 KB。
     * @return  integer
     * @static
     */        
    function get_real_size($size) 
    {
        if ('' == $size)
            return 0;
        $scans = array(
            'MB' => 1048576,
            'M'  => 1048576,
            'KB' => 1024,
            'K'  => 1024);
        while (list($key) = each($scans)) {
            if ((strlen($size) > strlen($key)) 
                && (substr($size, strlen($size) - strlen($key)) == $key)) {
                $size = substr($size, 0, strlen($size) - strlen($key)) * $scans[$key];
                break;
            }   //end if
        }   //end while
        return $size;
    }   //end function

    //  }}}

    //  {{{ upload_file()

    /**
     * 上传文件的处理函数，同时对上传的文件进行“文件类型”、“文件大小”等的判断。
     *
     * @access  public
     * @param   array   $upload_files       从 $_FILES 获取的上传文件（单个文件）的数组变量。
     * @param   string  $dest_file_name    上传文件复制后的目标文件的物理路径，此参数不需要带扩展名，函数会自动给文件加上上传文件原有的扩展名。
     * @param   string  $allow_file_type   上传文件允许的类型，使用“|”隔开，如：gif|jpg|bmp。
     * @param   string  $allow_file_size   上传文件大小允许的最大值，单位 Bytes。
     * @return  array   $returns    此数组有两个元素：
     *                              $returns['errmsg']  -> 如果为空，则表示上传成功，否则是错误信息；
     *                              $returns['ext']     -> 上传文件的扩展名。
     * @static
     */
    function upload_file($upload_files, $dest_file_name, $allow_file_type, $allow_file_size)
    {
        //  获取上传文件的扩展名
        $returns['ext'] = strtolower(substr($upload_files['name'], strrpos($upload_files['name'], '.')+1));

        if ($upload_files['error'] == 4) {
            $returns['errmsg'] = '错误：没有选择上传的文件。';
        } else if (!preg_match("/^(".$allow_file_type.")$/i", $returns['ext'])) {
            $returns['errmsg'] = '错误：上传的文件类型不正确。';
        } else if ($upload_files['error'] == 2 || $upload_files['size'] > $allow_file_size) {
            $returns['errmsg'] = '错误：上传的文件超过了规定的大小 '.($allow_file_size/1000).'K。';
        } else if (!move_uploaded_file($upload_files['tmp_name'], $dest_file_name.'.'.$returns['ext'])) {
            $returns['errmsg'] = '错误：复制上传文件时出错。';
        } else {
            $returns['errmsg'] = '';
        }//end if
        
        return $returns;
    }   //end function

    //  }}}

    //  {{{ get_micro_time()

    /**
     * 获取当前毫秒级时间。
     *
     * @access  public
     * @return  float
     * @static
     */    
    function get_micro_time()
    {
        list($usec, $sec) = explode(" ", microtime()); 
        return ((float)$usec + (float)$sec);   
    }   //end function

    //  }}}

    //  {{{ copy_remote_file()
    
    /**
     * 将远程文件复制到本地。
     *
     * @access  public
     * @param   $remote_file_name   string  远程文件名（包括完全路径）。
     * @param   $local_file_name    string  本地（本服务器）文件名（包括完全物理路径）。
     * @return  boolean
     * @static
     */
    function copy_remote_file($remote_file_name, $local_file_name)
    {
        $fp1 = @fopen($remote_file_name, 'r');
        $fp2 = @fopen($local_file_name, 'w');
        if (!$fp1 || !$fp2)
            return FALSE;
        while (!feof($fp1)) {
            $line = fread($fp1, 1024);
            fputs($fp2, $line, strlen ($line));
        }   //end while
        @fclose($fp1);
        @fclose($fp2);
        return TRUE;
    }   //end function
    
    //  }}}

    //  {{{ strip_crlf()
    
    /**
     * 将“\r”替换成“`0D”，“\n”替换成“`0A”，“`”替换成“`.”，使得字符串变成一行，不换行。
     *
     * @access  public
     * @param   string  $str  需要替换 CRLF 的字符串。
     * @return  string
     * @static
     */
    function strip_crlf($str)
    {
        return str_replace(
            array("`", "\r", "\n"),
            array("`.", "`0A", "`0D"),
            $str);
    }   //end function
    
    //  }}}

    //  {{{ restore_crlf()
    
    /**
     * 恢复回车换行符，将“`0D”替换成“\r”，“`0A”替换成“\n”，“`.”替换成“`”。
     *
     * @access  public
     * @param   string  $str  需要恢复 CRLF 的字符串。
     * @return  string
     * @static
     */
    function restore_crlf($str)
    {
        return str_replace(
            array('`0A', '`0D', '`.'),
            array("\r", "\n", "`"),
            $str);
    }   //end function
    
    //  }}}

    //  {{{ get_used_space
    
    /**
     * 获取某目录下的已用空间大小，单位为字节（byte）。
     *
     * @access  public
     * @param   string  $dir    需要计算已用空间的目录物理路径，不要使用“/”结尾。
     * @return  integer
     */
    function get_used_space($dir = '')
    {
        if (is_dir($dir)) {
            $handle = opendir($dir);
            while (FALSE !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..') {
                    $file = $dir.'/'.$entry;
                    if (is_dir($file)) {    //子目录
                        $used_space += CMS_Common :: get_used_space($file);
                    } else if (is_file($file)) {
                        $used_space += filesize($file);
                    }   //end if
                }   //end if
            }   //end while
            closedir($handle); 
        }   //end if
        return $used_space;
    }   //end function
    
    //  }}}
    
}   //end class ZXZX_Common
?>
