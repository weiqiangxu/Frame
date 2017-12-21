<?php
/**
 * @author HzqGhost <admin@whiledo.com> QQ:313143468,xu QQ:435861851 
 * @version 1.0.1
 *
*/
class LibDir{
    public static function FormatDir($dir){
        // 有时候深度路径为空这里就要去除调用
        if($dir!="")
        {
            $dir = str_replace('\\','/',$dir);
            $dir = str_replace('//','/',$dir);
            $dir = rtrim($dir,'/').'/';
        }
        else
        {
            $dir ="";
        }
        return $dir;
    }

    /**
     * @param $dir 待查询的目录
     * @param $files 返回查询的结果
     */
    public static function SearchFile($dir, &$files){
        $dir = self::FormatDir($dir);
        $arr = scandir($dir);
        foreach($arr as $v){
          if($v=='.' || $v=='..') continue;
          if(is_dir($dir.$v)){
            self::SearchFile($dir.$v,$files);
          }else{
            $files[] = $dir.$v;
          }
        }
    }


    public static function CreateDir($dir){
        if(is_dir($dir)) return true;
        $dir = self::FormatDir($dir);
        $arr = explode('/',$dir);
        $path = '';
        foreach($arr as $v){
            $path .= $v.'/';
            if (trim($v)=='' || in_array($v,array('..','.')) || is_dir($path)) continue;
            if (!@mkdir($path, 0777)){
                return false;
            }
        }
        @clearstatcache();
        return true;
    }
    public static function DeleteDir($dir){
        if(!is_dir($dir)) return true;
        if($handle = opendir($dir)){
            while(($file = readdir($handle))!==false){
                if($file!='.' && $file!='..'){
                    if(is_dir($dir.'/'.$file)){
                        self::DeleteDir($dir.'/'.$file);
                    }else{
                        unlink($dir.'/'.$file);
                    }
                }
            }
            closedir($handle);
            @clearstatcache();
            return @rmdir($dir);
        }
        return true;
    }

	public static function ClearDir($dir){
        if(!is_dir($dir)) return true;
        if($handle = opendir($dir)){
            while(($file = readdir($handle))!==false){
                if($file!='.' && $file!='..'){
                    if(is_dir($dir.'/'.$file)){
                        self::DeleteDir($dir.'/'.$file);
                    }else{
                        unlink($dir.'/'.$file);
                    }
                }
            }
            closedir($handle);
            @clearstatcache();
        }
        return true;
    }
	

	/**清除文件修改时间早于时间戳$time的文件**/
	public static function ClearFile($dir, $time){
        if(!is_dir($dir)) return true;
        if($handle = opendir($dir)){
            while(($file = readdir($handle))!==false){
                if($file!='.' && $file!='..'){
                    if(is_dir($dir.'/'.$file)){
                        self::ClearFile($dir.'/'.$file, $time);
                    }else if (filemtime($dir.'/'.$file) < $time){
						unlink($dir.'/'.$file);
                    }
                }
            }
            closedir($handle);
            @clearstatcache();
        }
        return true;
    }

}