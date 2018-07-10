<?php
/**
 * 验证码类。
 */
class Captcha
{
	/**
	 * 生成并输出验证图片。
	 *
	 * @param string $key 用于标识验证图片的唯一值，获取验证图片字符串的时候需要用到。
	 * @param string $word 验证图片中显示的字符串。
	 * @param integer $width 图片宽度。
	 * @param integer $height 图片搞定。
	 */
	public function generateImage($key, $word = '', $width = 80, $height = 30)
	{
		if (!extension_loaded('gd')) {
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				dl('php_gd.dll');
			} else {
				dl('gd.so');
			}			
			if (!extension_loaded('gd')) throw new Exception('加载 GD 库失败。');
		}
		//是否设置了显示于图片中的文字，若未设置，则随机生成 4 个字符。	
		if ($word == '') {
			$pool = '345678abcdefhjkmnprstuvwxy';
			$str = '';
			for ($i = 0; $i < 4; $i++) {
				mt_srand(((int)((double)microtime()*1000003)));
				$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
			}
			$word = $str;
		}
		//将验证图片中的字符串保存到 SESSION 中。
		$key = $this->_getFixedKey($key);
		$_SESSION[$key] = $word;
		//设置角度与位置
		srand(((int)((double)microtime()*1000003)));
		$length	= strlen($word);
		$angle	= ($length >= 6) ? rand(-($length-6), ($length-6)) : 0;
		$x_axis	= rand(6, (360/$length)-16);		
		$y_axis = ($angle >= 0 ) ? rand($height, $width) : rand(6, $height);
		//生成图像
		if (function_exists('imagecreatetruecolor')) {
			$im = imagecreatetruecolor($width, $height);
		} else {
			$im = imagecreate($width, $height);
		}
		//设置颜色				
		$bg_color		= imagecolorallocate($im, 255, 255, 255);
		$border_color	= imagecolorallocate($im, 153, 102, 102);
		$text_color		= imagecolorallocate($im, 255, 0, 0); //imagecolorallocate($im, 204, 153, 153);
		$grid_color		= imagecolorallocate($im, 255, 182, 182);
		$shadow_color	= imagecolorallocate($im, 255, 240, 240);
		//生成矩形框
		ImageFilledRectangle($im, 0, 0, $width, $height, $bg_color);
		//生成干扰图像
		$theta		= 1;
		$thetac		= 7;
		$radius		= 16;
		$circles	= 20;
		$points		= 32;	
		for ($i = 0; $i < ($circles * $points) - 1; $i++) {
			$theta = $theta + $thetac;
			$rad = $radius * ($i / $points );
			$x = ($rad * cos($theta)) + $x_axis;
			$y = ($rad * sin($theta)) + $y_axis;
			$theta = $theta + $thetac;
			$rad1 = $radius * (($i + 1) / $points);
			$x1 = ($rad1 * cos($theta)) + $x_axis;
			$y1 = ($rad1 * sin($theta )) + $y_axis;
			imageline($im, $x, $y, $x1, $y1, $grid_color);
			$theta = $theta - $thetac;
		}
		//写入字符
		$font_size = 8;
		$x = rand(0, $width/$font_size+2);
		$y = 0;	
		for ($i = 0; $i < strlen($word); $i++) {
			$y = rand(0 , $height/2);
			imagestring($im, $font_size, $x, $y, substr($word, $i, 1), $text_color);
			$x += ($font_size*2);
		}
		//图像边框
		imagerectangle($im, 0, 0, $width-1, $height-1, $border_color);
		//输出		
		header("Content-type: image/jpeg"); 
		imagejpeg($im, null, 100);
		imagedestroy($im);
		exit; 
	}
	
	/**
	 * 获取验证图片中的字符串。
	 *
	 * @param string $key
	 * @return string
	 */
	public function getCaptchaValue($key)
	{
		$key = $this->_getFixedKey($key);
		if (isset($_SESSION[$key])) {
			$value = $_SESSION[$key];
			unset($_SESSION[$key]);
			return $value;
		} else {
			return null;
		}
	}
	
	/**
	 * 获取真正用于 SESSION 的 key。
	 *
	 * @param string $key
	 * @return string
	 */
	private function _getFixedKey($key)
	{
		return md5($key.'Snoopy');
	}
}
