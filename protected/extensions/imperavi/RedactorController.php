<?php

class RedactorController extends CExtController {
    function run($actionID) {
    	if (!empty($_FILES['file']['name']) /*&& !Yii::app()->user->isGuest*/) {
    		$dir = Yii::app()->getRequest()->getQuery('dir');
        	if(isset($dir)) $dir = str_replace(array('/','.'), '', $dir);
            $dir = Yii::getPathOfAlias('webroot.upload').'/'.$_GET['dir'].'/';//. Yii::app()->user->id . '/'; // директория для загрузки изображений

			// директория модуля
            if (!is_dir($dir)){
            	$old = umask(0);
                @mkdir($dir,0777);
                umask($old);
            }

            // Поддиректория модуля
            $in_dir = date("j_F_Y").'/';
            if (!is_dir($in_dir)){
            	$old = umask(0);
                @mkdir($dir.$in_dir);
                umask($old);
             }
            $image = CUploadedFile::getInstanceByName('file');
            if ($image) {
            	$name = substr($image->Name,0,strlen($image->Name) - 4);
            	$newName = $name.date("_H_i_s").'.'.$image->extensionName;
                $image->saveAs($dir.$in_dir . $newName);
                // создание превью
				$path_mini = $in_dir.'min_'.$name.date("_H_i_s").'.'.$image->extensionName;
					Yii::app()->ih
							    ->load($dir.$in_dir . $newName)
							    ->resize(170, 120, false)
							    ->save($dir.$path_mini);
                echo '/upload/'.$_GET['dir'].'/'.$path_mini;
            }

        }

    }
}