<?php 
require_once '../vendor/autoload.php';

// 這是測試資料
$ary = 
[
    'A' => '1',
    'D' => '5',
    'E' => '15',
    'a' => '2',
    'c' => '4',
    'e' => '15',
    'C' => '3',
    '1000' => '3',
    '-X' => '3',
];

// 1. 初始化
$checksum = new \Jsnlib\Checksum(
[
    'key' => hash('sha512', 'Jsn')
]);

// 2. client 端傳送前，自動產生校驗碼並加入至參數
$send_param = $checksum->quick($ary);
// print_r($send_param);die;

// 3. 選用工具如 Guzzle，將夾帶 checksum 的參數 GET 到 Server
$client = new GuzzleHttp\Client(
[
    'base_uri' => 'http://localhost/checksum/demo/',
]);

$response = $client->get('server.php', ['query' => $send_param]);
$body = $response->getBody();
echo $body;









