<?php

/**
 * Класс для работы с прикрепляемыми к заявкам файлами.
 *
 * @property int $id
 * @property string $name
 * @property string $filename
 * @property int $itemId
 * @property int $itemType
 */
class File extends CActiveRecord
{
    // путь к папке с файлами относительно корня сайта (Document root)
    const ATTACHMENTS_FOLDER = 'upload/files';

    // типы объектов, к которым привязываются файлы
    const ITEM_TYPE_OBJECT_CATEGORY = 1;

    const TYPE_PDF = 1; //Файл pdf
    const TYPE_WORD = 2; //Файл word

    /**
     * Возвращает имя таблицы БД, в которой хранятся записи модели
     * {@inheritdoc}
     */
    public function tableName(): string
    {
        return '{{file}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName(): string
    {
        return Yii::app()->db->tablePrefix . 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'filename', 'objectId', 'objectType'], 'required'],
            [['objectId', 'objectType', 'type'], 'numerical', 'integerOnly' => true],
            [['name', 'filename'], 'length', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название элемента',
            'filename' => 'Имя файла на сервере',
            'objectId' => 'id объекта, связанного с файлом',
            'objectType' => 'Тип объекта, связанного с файлом',
            'type' => 'тип файла (к чему привязан)',
        ];
    }

    /**
     * Создает имя файла для хранения на сервере.
     *
     * @param CUploadedFile $file
     *
     * @return string
     */
    public function createFileName($file):string
    {
        return uniqid() . '_' . CustomFuncs::translit($file->getName());
    }

    /**
     * Создает имя файла из пути существующего файла на диске.
     *
     * @param string $path Путь к существующему файлу
     *
     * @return string Имя файла для хранения на диске
     */
    public static function createFileNameFromPath($path): string
    {
        $fileInfo = pathinfo($path);
        $baseName = $fileInfo['basename'];
        $extension = $fileInfo['extension'];

        return md5($baseName . $extension . time() . mt_rand(10000, 100000)) . '.' . $extension;
    }

    /**
     * Относительный путь к файлу от корня веб сервера.
     *
     * @return string
     */
    public function getRelativePath()
    {
        return $this->createFolderFromFileName() . '/' . $this->filename;
    }

    /**
     * Возвращает имя папки для хранения файла на сервере.
     *
     * @return bool|string
     */
    public function createFolderFromFileName()
    {
        if (!$this->filename) {
            return false;
        }

        // файл будем хранить в подпапке вида 5d/b8/5db8ausdfuafduafud6uwuff6f6ada.doc, чтобы не держать в одной папке море файлов
        $folderName = mb_substr($this->filename, 0, 2, 'utf-8');
        $folderName2 = mb_substr($this->filename, 2, 2, 'utf-8');

        return '/' . self::ATTACHMENTS_FOLDER . '/' . $folderName . '/' . $folderName2;
    }

    /**
     * Метод, вызываемый после удаления объекта. Удаляет загруженный файл с диска.
     */
    public function afterDelete()
    {
        parent::afterDelete();

        $directory = Yii::getAlias('@webroot') . $this->createFolderFromFileName();
        $filePath = $directory . '/' . $this->filename;

        // удаляем файл
        if (unlink($filePath)) {
            // удалим директорию хранения файла, если там нет других файлов (есть только . и ..)
            if (2 == sizeof(scandir($directory))) {
                rmdir($directory);

                // если директория уровнем выше тоже пуста, удалим и ее
                $parentDirectory = substr($directory, 0, strlen($directory) - 3);
                if (is_dir($parentDirectory) && 2 == sizeof(scandir($parentDirectory))) {
                    rmdir($parentDirectory);
                }
            }
        }
    }
}
