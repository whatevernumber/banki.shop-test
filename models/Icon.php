<?php

namespace app\models;

use app\helpers\StringHelper;
use Exception;
use yii\web\UploadedFile;

/**
 * This is the model class for table "images".
 *
 * @property int $id
 * @property string $original_name
 * @property string $image_name
 *
 * @property Parameter $param;
 */
class Icon extends \yii\db\ActiveRecord
{
    private const PATH = 'img';

    public UploadedFile $icon;

    public ?string $url = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'icons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['original_name', 'image_name', 'type'], 'string', 'max' => 255],
            ['type', 'in', 'range' => ['icon', 'icon_gray'], 'message' => 'Неверный тип иконки'],
            ['icon', 'image',
                'extensions' => 'jpg, jpeg, svg, png',
                'mimeTypes' => 'image/*',
                'wrongExtension' => 'Неверный формат файла. Принимаются только картинки с расширением JPG, PNG, SVG',
                'wrongMimeType' => 'Неверный формат файла. Принимаются только картинки с расширением JPG, PNG, SVG',
                'tooBig' => 'Файл слишком большой',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'original_name' => 'Оригинальное название',
            'icon_name' => 'Сохранённое название',
            'type' => 'Тип иконки',
        ];
    }

    /**
     * Handles all operations with the given file
     * @return void
     * @throws Exception
     */
    public function handlePhoto(): void
    {
        $baseName = $this->icon->baseName;
        $ext = $this->icon->extension;

        // removes not allowed characters and extra whitespaces
        $baseName = preg_replace('/(?<=\s)\s+|[^-\w\s]/u', '', $baseName);

        $newName = strtolower(StringHelper::str_translit($baseName));
        $name = $this->getUniqueName($newName, $ext, self::PATH);

        $this->original_name = $this->icon->name;
        $this->store($this->icon, self::PATH, $name);
    }

    /**
     * Generates name with unique prefix
     *
     * @param string $name
     * @param string $ext
     * @param string $path
     * @return string
     */
    public function getUniqueName(string $name, string $ext, string $path): string
    {
        if (file_exists(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . "$name.$ext")) {
            $name .= '_' . uniqid();
            $this->getUniqueName($name, $ext, $path);
        }

        return "$name.$ext";
    }

    /**
     * Saves file to disk
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string $name
     * @return void
     * @throws Exception
     */
    public function store(UploadedFile $file, string $path, string $name): void
    {
        if ($file->saveAs(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $name)) {
            $this->image_name = $name;
        } else {
            throw new Exception('Не удалось записать файл');
        }
    }

    /**
     * Removes file
     * @return void
     */
    public function unlinkIcon(): void
    {
        unlink(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . self::PATH . DIRECTORY_SEPARATOR . $this->image_name);
    }

    /**
     * Gets query for [[Parameter]].
     *
     * @return \yii\db\ActiveQuery|ParameterQuery
     */
    public function getParam()
    {
        return $this->hasOne(Parameter::class, ['id' => 'param_id']);
    }

    /**
     * {@inheritdoc}
     * @return IconQuery the active query used by this AR class.
     */
    public static function find(): IconQuery
    {
        return new IconQuery(get_called_class());
    }
}
