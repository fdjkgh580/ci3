<?php 
require_once '../vendor/autoload.php';

// 4. 初始化
$checksum = new \Jsnlib\Checksum(
[
    'key' => hash('sha512', 'Jsn')
]);

// 5. service 端，將比對校驗碼
$result = $checksum->check($_GET);
print_r($result);

// 6. 選) 測試若修改參數，就會 status == false
// $_GET['A'] = '100'; 
// $result = $checksum->check($_GET);
// print_r($result);