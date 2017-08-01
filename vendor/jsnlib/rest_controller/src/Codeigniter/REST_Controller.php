<?php
namespace Jsnlib\Codeigniter;
/**
 * 擴充 REST_Controller.php
 */
class REST_Controller extends \REST_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    protected function respary($result)
    {
        if (is_object($result->data))
        {
            $result->data = $result->data->toArray();
        }
        elseif (empty($result))
        {
            $result = false;
        }
        parent::response($result);
    }

    /**
     * 輸出格式
     * @param    $result  mix          結果
     * @param    $message string       語意訊息
     * @param    $param   \Jsnlib\Ao   參數   
     */
    protected function output($result, $message = false, $param = false)
    {
        // 有夾帶參數
        if ($param !== false) 
        {
            $param_ary = $param->toArray();

            // 只取出 data 的部分提供顯示
            $obj       = new \Jsnlib\Ao($param_ary['data']);
        }
        // 沒有就空陣列
        else 
        {
            $obj     = new \Jsnlib\Ao([]);
        }

        // 添加額外參數
        $obj->result  = $result;
        $obj->message = $message;

        parent::response($obj);
    }
    

}

