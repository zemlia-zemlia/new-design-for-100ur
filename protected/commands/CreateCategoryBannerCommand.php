<?php

use App\models\QuestionCategory;

/**
 * Консольная команда для создания баннеров категорий из заданных картинок.
 */
class CreateCategoryBannerCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        // Выберем из папки ./pics 3 произвольные картинки

        $directoryFiles = array_diff(scandir(__DIR__ . './pics/'), ['.', '..', 'output']);

        $categories = QuestionCategory::model()->findAll();
        foreach ($categories as $cat) {
            $filename = $this->_createImage($directoryFiles);
            if ($filename) {
                $cat->image = $filename;
                if ($cat->saveNode()) {
                    echo 'Категория ' . $cat->id . ' сохранена' . PHP_EOL;
                }
            }
        }
    }

    protected function _createImage($directoryFiles)
    {
        if (!is_array($directoryFiles)) {
            return false;
        }

        shuffle($directoryFiles);

        $first = imagecreatefromjpeg(__DIR__ . '/pics/' . $directoryFiles[0]);
        $second = imagecreatefromjpeg(__DIR__ . '/pics/' . $directoryFiles[1]);
        $third = imagecreatefromjpeg(__DIR__ . '/pics/' . $directoryFiles[2]);
        $forth = imagecreatefromjpeg(__DIR__ . '/pics/' . $directoryFiles[3]);

        $outputImage = imagecreatetruecolor(1200, 300);

        $white = imagecolorallocate($outputImage, 255, 255, 255);
        imagefill($outputImage, 0, 0, $white);

        imagecopyresized($outputImage, $first, 0, 0, 0, 0, 300, 300, 500, 500);
        imagecopyresized($outputImage, $second, 300, 0, 0, 0, 300, 300, 500, 500);
        imagecopyresized($outputImage, $third, 600, 0, 0, 0, 300, 300, 500, 500);
        imagecopyresized($outputImage, $forth, 900, 0, 0, 0, 300, 300, 500, 500);

        // накладываем поверх коллажа полупрозрачный прямоугольник
        $im = imagecreatetruecolor(320, 240);
        $col = imagecolorallocatealpha($im, 255, 255, 255, 50);
        imagefilledrectangle($outputImage, 0, 0, 1200, 300, $col);

        // сверху накладываем лого
        $logo = imagecreatefrompng(__DIR__ . '/../../pics/2017/100_yuristov_logo_transp.png');
        imagecopyresized($outputImage, $logo, 480, 30, 0, 0, 230, 41, 230, 41);

        // Генерируем имя картинки
        $filename = md5(mt_rand(10000, 1000000) . time()) . '.jpg';
        $path = __DIR__ . '/../..' . QuestionCategory::IMAGES_DIRECTORY . $filename;
        //$filename = __DIR__ . '/pics/output/' . 'test' .'.jpg';

        // сохраняем коллаж
        $saveImageResult = imagejpeg($outputImage, $path, 100);

        imagedestroy($outputImage);

        if ($saveImageResult) {
            return $filename;
        } else {
            return false;
        }
    }
}
