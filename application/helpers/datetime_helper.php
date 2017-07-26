<?
namespace Helper;

/**
 * 跟時間、日期相關的輔助函式
 */


/**
 * 當前時間，相對於指定時間，是否過期了？
 * @param   $limit_time  date()的格式如 '2014-12-25 00:00:00'
 * @return  bool
 */
function iscurrent_expired($limit_time)
{
    return (time() >= strtotime($limit_time)) ? 1 : 0;
}


/**
 * 快速轉換原本的date()格式
 * @param  $date_format date()的格式
 * @param  $orange_date 原始的日期字串
 *
 * 如     ch_date_format("Y/m/d", "2012-01-01 09:10:00") 
 * 輸出   2012/01/01
 */
function ch_date_format($date_format, $orange_date)
{
	return date($date_format, strtotime($orange_date));
}



/**
 * 比對日期 介於 幾月幾日 至 幾月幾日 之內嗎? (以上 以下)
 * 其中$startdate $chkdate $enddate 為date()的寫法如 2012-12-21 或 2012-02-02 18:00:21
 * 
 * @param  $startdate 從何時
 * @param  $chkdate   比對日期
 * @param  $enddate   到何時
 * @return            1 或 0
 */
function date_range($startdate, $chkdate, $enddate)
{
	//戳記
	$startdate = strtotime($startdate);
	$chkdate = strtotime($chkdate);
	$enddate = strtotime($enddate);
	
	return ($startdate <= $chkdate and $chkdate <= $enddate) ? "1" : "0";
}



/**
 * 比對日期 在 指定的天數之內嗎?(使用以上以下的概念)
 * 如 date_inner("2012-04-23", "+3") 代表在今天在2012-04-23的後三天之內嗎?
 * 
 * @param  $date      		提供要比對的日期字串
 * @param  $limit_num 		限制的天數
 * @param  $type      		可參考strtotime字串，如"day" "week" "month" "hours 30 minutes"
 * @return [type]           1 或 0
 */
function date_inner($date, $limit_num, $type = "day")
{
	//目前戳記
	$now = time();
	
	$limit_time = strtotime("{$date} {$limit_num} {$type}");
	
	return ($now <= $limit_time) ? "1" : "0";
}


/**
 * 取得多少天前後的日期格式
 * 如 date_before_after("Y-m-d H:i:s", "now", +1, "day");
 * 可取得明天的日期
 * 
 * @param  $date_format 日期格式如 "Y-m-d H:i:s"
 * @param  $use_date    基準日期 可用now代表今天, 或使用 date() 字串
 * @param  $limit_num   多少天
 * @param  $type        參考 strtotime 的類型 如天數 day
 * @return       		$date_format 指定的日期字串       
 */
function date_before_after($date_format, $use_date, $limit_num, $type)
{
	return date($date_format, strtotime("{$use_date} {$limit_num} {$type}"));
}


/**
 * 取得語意化的指定時間，距離當前時間多久
 * @param [type] $date_format 日期格式如 "Y-m-d H:i:s"
 */
function datetime_distance($date_format) 
{
	$Time1 = strtotime($date_format);
	$Time = time() - $Time1 ;
	
	if ($Time <= 60) 
	{
		return "1分鐘前" ;	
	} 
	else if ($Time < 60 * 60) 
	{
		return floor($Time/60) . "分鐘前" ;
		
	} 
	else if ($Time < 60 * 60 * 24) 
	{
		return floor($Time/60/60) . "小時前" ;
		
	} 
	else if ($Time < 60 * 60 * 24 * 30) 
	{
		return floor($Time/60/60/24) . "天前" ;
	} 
	else if ($Time >= 60 * 60 * 24 * 30 ) 
	{ 
	if(floor($Time/60/60/24/30) <= 12)
		{return floor($Time/60/60/24/30) . "個月前" ;}
	else if(floor($Time/60/60/24/30) > 12)
		{return floor($Time/60/60/24/30/12) . "年前"; }
	else if(floor($Time/60/60/24/30) > 24)
		{return floor($Time/60/60/24/30/12) . "年前"; }
	else if(floor($Time/60/60/24/30) > 36)
		{return floor($Time/60/60/24/30/12) . "年前"; }
	else if(floor($Time/60/60/24/30) > 48)
		{return floor($Time/60/60/24/30/12) . "年前"; }
	else if(floor($Time/60/60/24/30) > 60)
		{return floor($Time/60/60/24/30/12) . "年前"; }
	}
}

/**
 * 查詢年月的第一天與最後一天
 * @param   $date                指定要查詢的年月如 2016-02
 * @return                       返回 array('first' => '第一天', 'last' => '最後一天');
 */
function month_first_last($date)
{
	// 格式化使用者輸入的年月
	$obj->current->format = ch_date_format("Y-m", $date);

	// 該月第一天
	$obj->current->first = ch_date_format("Y-m-01", $obj->current->format);

	// 下個月
	$obj->next->first = date_before_after("Y-m-d", $obj->current->first, +1, "month");

	// 該月最後一天 = 下個月的前一天
	$obj->current->last = date_before_after("Y-m-d", $obj->next->first, -1, "day");

	$return['first'] = $obj->current->first;
	$return['last']  = $obj->current->last;
	return $return;
}