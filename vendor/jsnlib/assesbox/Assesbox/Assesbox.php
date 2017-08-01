<?php
/*

v1.0.1
修改詳見 changelog

透過 Controller 將 CSS 與 JS 的傳入到 View。

config() 修改設定檔，為選用

【Controller】
    $global         = array('jquery');
    $custom         = array('fancybox', 'index'=>'style.css');
    
    $param->asset = $this->assesbox
        ->config([
            'prefix' => 'assesbox-',
            'map_path' => 'assesmap'
        ])
        ->glob($global)
        ->custom($param->custom)
        ->start("Jsnao");

    $this->load->view("view", $param);
    
【View】
    <? foreach ($asset->css as $key => $file) { ?>
        <link class="<?=$key?>" rel="stylesheet" href="<?=$file?>">
    <? } ?>
    <? foreach ($asset->js as $key => $file) { ?>
        <script class="<?=$key?>" src="<?=$file?>"></script>
    <? } ?>

*/
class Assesbox 
{
    // 資源參照地圖
    private $map;

    // 全域載入的資源
    private $global;

    // 自訂載入的資源
    private $custom;

    // 辨識的前綴字元
    private $prefix = "assesbox-";

    // 預設的 config 名稱路徑
    private $map_path = "assesmap";

    // 允許被修改的屬性
    private $allow_replace_setting = array('prefix', 'map_path');

    // 指定設定檔路徑
    public function config(array $config)
    {
        foreach ($config as $key => $val)
        {
            if (!in_array($key, $this->allow_replace_setting)) continue;

            $this->$key = $val;
        }

        return $this;
    }


    /**
     * 開始取得混和的陣列
     * @param  string     $return_type 返回的類型物件名稱
     */
    public function start($return_type)
    {
        // 讓自訂資源繼承全域資源
        $box        =   $this->asset_extend($this->global, $this->custom);

        // 依照文件類型分類
        $ass_ary    =   $this->asset_type_box($box);
        
        return new $return_type($ass_ary);
    }

    /**
     * 全域加載
     * @param   $ary 加載的名稱
     */
    public function glob($ary)
    {
        $this->map();
        $this->global     =   $this->get($ary);
        return $this;
    }

    /**
     * 自訂加載
     * 如果自訂的鍵名與全域的陣列鍵名相同，全域會被取代。
     * 
     * @param   $ary 加載的名稱
     */
    public function custom($ary)
    {
        $this->map();
        $this->custom     =   $this->get($ary);
        return $this;
    }

    // 從設定檔中取得資源的對應的名稱與路徑
    private function get($ary)
    {
        $re = array();
        foreach($ary as $key => $val)
        {
            if (is_int($key))
            {
                if (!isset($this->map[$val])) throw new Exception("呼叫的資源名稱 {$val} 不存在設定檔 config/{$this->map_path} 中。");
                $re[$val] = $this->map[$val];
                continue;
            }
            $newkey = $this->prefix . $key;
            $re[$newkey] = $val;
        }
        return $re;
    }

    // 讀取設定檔
    private function map()
    {
        if (!isset($this->map))
        {
            $ci =& get_instance();
            $ci->config->load($this->map_path);
            $this->map = $ci->config->item('assesmap');
        }
        return $this;
    }

    // 讓自訂資源繼承全域資源
    private function asset_extend($global, $custom)
    {
        $box_global = $this->asset_mix($global);
        $box_page   = $this->asset_mix($custom);
        return array_merge($box_global, $box_page);
    }

    /**
     * 匹配鑑與值到一個陣列
     * @param  array $asset 輸入的資源陣列
     * @return array        
     */
    protected function asset_mix($asset)
    {
        $box = array();
        foreach ($asset as $idkey => $item)
        {
            $new_idkey = $this->prefix . $idkey;
            if (is_string($item))
            {
                $box[$new_idkey] = $item;
            }
            elseif (is_array($item)) foreach ($item as $itemkey => $file)
            {
                $box[$new_idkey . "-" . $itemkey] = $file;
            }
            else throw new Exception("需要指定字串或陣列: {$idkey}");
        }
        return $box;
    }

    /**
     * 依照文件類型如 css 或 js 分類
     * @param  array   $box     一維陣列。 如 array('唯一名稱' => '文件路徑')
     * @return array            二維陣列。 如 array('css' => array('fancybox_css' => 'plugin/JS/fancybox/jquery.fancybox.css'))
     */
    private function asset_type_box($box)
    {
        $ary = array();
        foreach ($box as $key => $file)
        {
            //副檔名
            $subfname = substr(strrchr($file, "."), 1);
            
            if ($subfname == "css") $type = "css";
            else $type = "js";
            
            $ary[$type][$key] = $file;
        }
        return $ary;
    }

}