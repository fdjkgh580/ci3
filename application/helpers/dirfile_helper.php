<?
namespace Helper;

/**
 * 快速轉換取得的路徑或檔案權限
 * @param  $dir_file 指定的絕對路徑
 * @return           回傳四位數的權值如0777
 */
function dir_file_perms($dir_file)
{
    clearstatcache();
    $perms = fileperms($dir_file); //10進位的權限
    $perms = decoct($perms); //10轉成8進位
    return substr($perms, -4); //取得最後4位數如0755
}


/**
 * 比對允許的權限值
 * @param  $allow_ary 比對僅可允許的值，如 array("0777", "0775")
 * @return 
 */
function isallow_dir_file_perms($dir_file, $allow_ary)
{
    $target_perms = dir_file_perms($dir_file);
    $exist = 0;
    foreach ($allow_ary as $val)
    {
        if ($target_perms != $val) continue;
        $exist = 1;
        break;
    }
    return $exist;
}

/**
 * 計算資料夾路徑的檔案大小
 * 例如 $result = CalcDirectorySize("C:\AppServ\www\exhibition/10/");
 * 若計算GB則使用如 echo $result / 1024 / 1024 / 1024 ;
 *
 * Calculate the full size of a directory
 *
 * @author      Jonas John
 * @version     0.2
 * @link        http://www.jonasjohn.de/snippets/php/dir-size.htm
 * @param       string   $DirectoryPath    Directory path
 */
function CalcDirectorySize($DirectoryPath) {
 
    // I reccomend using a normalize_path function here
    // to make sure $DirectoryPath contains an ending slash
    // (-> http://www.jonasjohn.de/snippets/php/normalize-path.htm)
 
    // To display a good looking size you can use a readable_filesize
    // function.
    // (-> http://www.jonasjohn.de/snippets/php/readable-filesize.htm)
 
    $Size = 0;
 
    $Dir = opendir($DirectoryPath);
 
    if (!$Dir)
        return -1;
 
    while (($File = readdir($Dir)) !== false) {
 
        // Skip file pointers
        if ($File[0] == '.') continue; 
 
        // Go recursive down, or add the file size
        if (is_dir($DirectoryPath . $File))            
            $Size += CalcDirectorySize($DirectoryPath . $File . DIRECTORY_SEPARATOR);
        else 
            $Size += filesize($DirectoryPath . $File);        
    }
 
    closedir($Dir);
 
    return $Size;
}




/**
 * 聰明的複製檔案或是目錄
 * 例如 smartCopy('test.php', 'test2.php'); 或   smartCopy('dirA', 'dirB');
 * 來自
 * http://php.net/manual/en/function.copy.php
 *
 * Copy file or folder from source to destination, it can do 
 * recursive copy as well and is very smart 
 * It recursively creates the dest file or directory path if there weren't exists 
 * Situtaions : 
 * - Src:/home/test/file.txt ,Dst:/home/test/b ,Result:/home/test/b -> If source was file copy file.txt name with b as name to destination 
 * - Src:/home/test/file.txt ,Dst:/home/test/b/ ,Result:/home/test/b/file.txt -> If source was file Creates b directory if does not exsits and copy file.txt into it 
 * - Src:/home/test ,Dst:/home/ ,Result:/home/test/** -> If source was directory copy test directory and all of its content into dest      
 * - Src:/home/test/ ,Dst:/home/ ,Result:/home/**-> if source was direcotry copy its content to dest 
 * - Src:/home/test ,Dst:/home/test2 ,Result:/home/test2/** -> if source was directoy copy it and its content to dest with test2 as name 
 * - Src:/home/test/ ,Dst:/home/test2 ,Result:->/home/test2/** if source was directoy copy it and its content to dest with test2 as name 
 * @todo 
 *     - Should have rollback technique so it can undo the copy when it wasn't successful 
 *  - Auto destination technique should be possible to turn off 
 *  - Supporting callback function 
 *  - May prevent some issues on shared enviroments : http://us3.php.net/umask 
 * @param $source //file or folder 
 * @param $dest ///file or folder 
 * @param $options //folderPermission,filePermission 
 * @return boolean 
 */ 
function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755)) 
    { 
        $result=false; 
        
        if (is_file($source)) { 
            if ($dest[strlen($dest)-1]=='/') { 
                if (!file_exists($dest)) { 
                    cmfcDirectory::makeAll($dest,$options['folderPermission'],true); 
                } 
                $__dest=$dest."/".basename($source); 
            } else { 
                $__dest=$dest; 
            } 
            $result=copy($source, $__dest); 
            chmod($__dest,$options['filePermission']); 
            
        } elseif(is_dir($source)) { 
            if ($dest[strlen($dest)-1]=='/') { 
                if ($source[strlen($source)-1]=='/') { 
                    //Copy only contents 
                } else { 
                    //Change parent itself and its contents 
                    $dest=$dest.basename($source); 
                    @mkdir($dest); 
                    chmod($dest,$options['filePermission']); 
                } 
            } else { 
                if ($source[strlen($source)-1]=='/') { 
                    //Copy parent directory with new name and all its content 
                    @mkdir($dest,$options['folderPermission']); 
                    chmod($dest,$options['filePermission']); 
                } else { 
                    //Copy parent directory with new name and all its content 
                    @mkdir($dest,$options['folderPermission']); 
                    chmod($dest,$options['filePermission']); 
                } 
            } 

            $dirHandle=opendir($source); 
            while($file=readdir($dirHandle)) 
            { 
                if($file!="." && $file!="..") 
                { 
                     if(!is_dir($source."/".$file)) { 
                        $__dest=$dest."/".$file; 
                    } else { 
                        $__dest=$dest."/".$file; 
                    } 
                    //echo "$source/$file ||| $__dest<br />"; 
                    $result=smartCopy($source."/".$file, $__dest, $options); 
                } 
            } 
            closedir($dirHandle); 
            
        } else { 
            $result=false; 
        } 
        return $result; 
    } 

/**
 * 自動建立連續的路徑或路徑+檔案
 * @param    $type     檔案路徑 file | 路徑 dir
 * @param    $dir_name  路徑位置
 * @param    $mode      權限
 * @return   bool
 */
function smart_create_dir_file($type = "file", $dir_name, $mode = 0777)
{
    $dirs  = explode(DIRECTORY_SEPARATOR, $dir_name);
    $dir   = '';
    $total = count($dirs);

    foreach ($dirs as $key => $part) 
    {

        // 最後一個？
        if ($key + 1 === $total)
        {
            $file = $dir . $part;

            if ($type == "file")
            {
                $result = file_put_contents($file, NULL);
                return $result === false ? false : true;
            }
            else if ($type == "dir")
            {
                // (A錨點)
                $dir .= $part . DIRECTORY_SEPARATOR;

                if (!is_dir($dir) && strlen($dir) > 0)
                {
                    return mkdir($dir, $mode);
                }
            }
        }
        else
        {
            // 同 (A錨點)

            $dir .= $part . DIRECTORY_SEPARATOR;

            if (!is_dir($dir) && strlen($dir) > 0)
            {
                mkdir($dir, $mode);
            }
        }


        
    }
}