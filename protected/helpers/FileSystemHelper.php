<?php

class FileSystemHelper
{
    /**
     * Удаляет папку со всем ее содержимым
     * @param string $dir
     * @param bool $deleteFolder Удалять ли саму папку
     * @return bool|void
     */
    public static function delTree($dir, $deleteFolder = true)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? static::delTree("$dir/$file") : unlink("$dir/$file");
        }

        return ($deleteFolder == true) ? rmdir($dir) : null;
    }

    public static function delFolderContent($dir)
    {
        return self::delTree($dir, false);
    }
}
