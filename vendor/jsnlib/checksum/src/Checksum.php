<?php 
namespace Jsnlib;
/**
* 校驗碼
*/
class Checksum
{
    // 金鑰
    protected $key;

    public function __construct(array $param = [])
    {
        if (empty($param['key'])) {
            print_r($param);
            throw new \Exception("The key does not exist.");
            die;
        }
        
        $this->key = $param['key'];
    }

    /**
     * 快速模式
     * 1. 內部取得校驗碼
     * 2. 混和至來源參數
     */
    public function quick($param = []): array
    {
        $checksum = $this->create_hash($param);
        $ary = $this->mix($param, $checksum);
        $ary = $this->sort($ary);
        return $ary;
    }

    /**
     * 建立校驗碼流程
     * 1. 先加入 checksum
     * 2. 將參數依序 "符號 - 大寫 - 小寫 - 數字" 排列
     * 3. 產生 hash
     * 
     * @param  array  $param 參數
     */
    public function create_hash($param = []): string
    {
        $param = $this->add_key($param);
        $param = $this->sort($param);
        $hash = $this->hash($param);
        return $hash;
    }

    /**
     * 將校驗碼合併到參數
     * @param  array  $param    參數
     * @param  string $checksum 校驗碼
     */
    public function mix($param = [], string $checksum): array
    {
        
        $param['checksum'] = $checksum;
        return $param;
    }

    /**
     * 比對
     * @param  array  $param 要比對的參數
     * @return array        
     */
    public function check($param = []): array
    {
        // 若沒有 checksum 將返回錯誤
        if (empty($param['checksum'])) return 
        [
            'status' => false,
        ];

        // list(來源校驗碼, 其餘參數)
        list($checksum_from, $else_param) = $this->split_checksum($param);

        // 依據來源的參數，產生本地的校驗碼
        $checksum_local = $this->create_hash($else_param);

        $status = ($checksum_from === $checksum_local) ? true : false;
        
        return 
        [
            'status' => $status,
            'checksum' => 
            [
                'from' => $checksum_from,
                'local' => $checksum_local
            ],
            'data' => $else_param
        ];
    }

    /**
     * 分離參數
     * @param   $param 
     * @return  array        [來源的校驗碼, 來扣除校驗碼的其餘參數]
     */
    protected function split_checksum($param): array
    {
        if (empty($param['checksum'])) return [false, false];

        $checksum_from = $param['checksum'];
        unset($param['checksum']);
        return [$checksum_from, $param];
    }

    // 排序
    protected function sort($param = [])
    {
        ksort($param);
        return $param;
    }

    // 產生校驗碼
    protected function hash($param)
    {
        $result = json_encode($param);
        return hash('sha512', $result);
    }

    // 在陣列插入 key
    protected function add_key($param)
    {
        $param['__APP__KEY__'] = $this->key;
        return $param;
    }
}