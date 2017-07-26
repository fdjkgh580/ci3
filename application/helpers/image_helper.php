<?
namespace Helper;

/**
 * 轉換圖片為 Data URI scheme
 * 範例: echo base64_encode_image ('images_system/theme/BackEnd/green/01.jpg','jpg'); 
 */
function base64_encode_image ($filename, $filetype) {
	if ($filename) {
		$imgbinary = fread(fopen($filename, "r"), filesize($filename));
		return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
	}
}

?>