<?php 
namespace Helper;

/**
 * 驗證文字圖片
 * @fonturl 文字路徑
 * 範例:
 		$this->load->helper('tool/captcha');
		captcha_img(_BASEPATH . "plugin/FONT/CONSOLA.TTF");

 */
function captcha_img($fonturl)
{
	session_start() ;
	
	//設定列印出錯誤
	//error_reporting(0);
	ini_set("display_errors","Off") ;
	
	Header("Content-type: image/PNG"); 
	srand((double)microtime()*1000000); 
	unset($_SESSION['captcha']); 
	unset($captcha); 
	
	$im = imagecreate(90,24) or die("Cant’s initialize new GD image stream!");  
	$red = ImageColorAllocate($im, 255,0,0); 
	$white = ImageColorAllocate($im, 255,255,255); 
	$gray = ImageColorAllocate($im, 200,200,200); 
	imagefill($im,0,0,$white); 
	
	// 定義顯示在圖片上的文字，可以再加上大寫字母
	$str = 'ABCDEFGHJKMPQRSTUVWXYZ1234567890';
	
	$l = strlen($str); //取得字串長度
	
	//隨機取出 6 個字
	for($i=0; $i<6; $i++){
	   $num=rand(0,$l-1);
	   $captcha.= $str[$num];
	}
	
	/*
	$ychar="0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z"; 
	$list=explode(",",$ychar); 
	for($i=0;$i<4;$i++){ 
	  $randnum=rand(0,35); 
	  $captcha.=$list[$randnum]." "; 
	} 
	*/
	
	//while(($captcha=rand()%100000)<10000); 
	$_SESSION['captcha']=$captcha; 
	imagettftext($im, 16, 2, 7, 18, $red, $fonturl, $captcha);
	//imagestring($im, 5, 10, 3 , $captcha, $red); 
	
	for($i=0;$i<400;$i++){ 
	$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255)); 
	// imagesetpixel($im, rand()%90 , rand()%30 , $randcolor); 
	imagesetpixel($im, rand()%90 , rand()%30 , $gray); 
	} //加入干擾像素 
	
	ImagePNG($im); 
	ImageDestroy($im); 
}


?>