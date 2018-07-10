<?php
class Cache
{
    /*检查文件是否超过某个时间
     * @string $cacheid 文件路径
     * @integer $expire 过期时间 秒
     * @@boolean
     */
	public static function cache_isvalid($cacheid,$expire=300)
	{
		@clearstatcache();
		if (!@file_exists($cacheid)) return false;
		if (!($mtime=@filemtime($cacheid))) return false;
			$nowtime=mktime();
		if (($mtime+$expire)<$nowtime)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
    /*把内容写入文件
     * @string $cacheid 文件路径
     * @string $cachecontent 内容
     * @@boolean
     */
	public static function cache_write($cacheid,$cachecontent)
	{
		$retry=100;
		for ($i=0;$i<$retry;$i++) {
			$ft=@fopen($cacheid,"wb");
			if ($ft!=false) break;
			if ($i==($retry-1)) return false;
		}
		@flock($ft,LOCK_UN);
		@flock($ft,LOCK_EX|LOCK_NB);
		for ($i=0;$i<$retry;$i++) {
			$tmp=@fwrite($ft,$cachecontent);
			if ($tmp!=false) break;
			if ($i==($retry-1)) return false;
		}
		@flock($ft,LOCK_UN);
		@fclose($ft);
		@chmod($cacheid,0666);
		return true;
	}
    /*读取文件内容
     * @string $cacheid 文件路径
     * @@string
     */
	public static function cache_fetch($cacheid)
	{
		$retry=100;
		for ($i=0;$i<$retry;$i++)
		{
			$ft=@fopen($cacheid,"rb");
			if ($ft!=false) break;
			if ($i==($retry-1)) return false;
		}
		$cachecontent='';
		while (!@feof($ft))
		{
			$cachecontent.=@fread($ft,4096);
		}
		@fclose($ft);
		return $cachecontent;
	}
    /*清除过期的文件
     * @string $cachedirname 文件名
     * @inteter $expire 过期时间
     * @@void
     */
	public static function cache_clear_expired($cachedirname,$expire=300)
	{
		$cachedir=@opendir($cachedirname);
		while (false!==($userfile=@readdir($cachedir)))
		{
			if ($userfile!="." and $userfile!=".." and substr($userfile,-4,4)=='.htm')
			{
				$cacheid=$cachedirname.'/'.$userfile;
				if (!cache_isvalid($cacheid,$expire)) @unlink($cacheid);
			}
		}
		@closedir($cachedir);
	}
}
?>