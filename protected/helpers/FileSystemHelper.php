<?php

class FileSystemHelper
{
    /**
     * Удаляет папку со всем ее содержимым
     * @param $dir
     * @return bool|void
     */
    public static function delTree($dir) {
        if(!is_dir($dir)) {
            return;
        }
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? static::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
