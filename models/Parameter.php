<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "parameters".
 *
 * @property int $id
 * @property string $title
 *
 * @property Icon $icon
 * @property Icon $grayIcon
 */
class Parameter extends \yii\db\ActiveRecord
{
    private const URL = 'http://127.0.0.1:8080/img';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'parameters';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['type'], 'integer', 'in', 'range', [1, 2], 'message' => 'Неверный тип'],
            [['title'], 'string', 'max' => 255],
            [['title', 'type'], 'unique', 'targetAttribute' => ['title', 'type'], 'message' => 'Такой параметр уже есть в системе'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'type' => 'Номер типа',
        ];
    }

    public function fields(): array
    {
        $fields = parent::fields();

        // append icon data if present
        if ($this->icon) {
            $fields['icon_image'] = fn() => [
                'id' => $this->icon['id'],
                'url' => self::URL . DIRECTORY_SEPARATOR . $this->icon['image_name'],
                'original_name' => $this->icon['original_name'],
                'saved_name' => $this->icon['image_name'],
            ];
        }

        // append gray icon data if present
        if ($this->grayIcon) {
            $fields['icon_gray'] = fn() => [
                'id' => $this->grayIcon['id'],
                'url' => self::URL . DIRECTORY_SEPARATOR . $this->grayIcon['image_name'],
                'original_name' => $this->grayIcon['original_name'],
                'saved_name' => $this->grayIcon['image_name'],
            ];
        }

        return $fields;
    }

    /**
     * Gets query for [[Icon]].
     *
     * @return ActiveQuery
     */
    public function getIcon()
    {
        return $this->hasOne(Icon::class, ['param_id' => 'id'])->andOnCondition(['type' => 'icon']);
    }

    /**
     * Gets query for [[Icon]].
     *
     * @return ActiveQuery
     */
    public function getGrayIcon()
    {
        return $this->hasOne(Icon::class, ['param_id' => 'id'])->andOnCondition(['type' => 'icon_gray']);
    }

    /**
     * {@inheritdoc}
     * @return ParameterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ParameterQuery(get_called_class());
    }
}
