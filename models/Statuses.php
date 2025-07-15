<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "statuses".
 *
 * @property int $id
 * @property string $name
 *
 * @property Tasks[] $tasks
 */
class Statuses extends ActiveRecord
{


    const STATUS_NEW = 1;
    const STATUS_CANCEL = 2;
    const STATUS_IN_PROGRESS = 3;
    const STATUS_COMPLETE = 4;
    const STATUS_FAIL = 5;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery|TasksQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::className(), ['status_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return StatusesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StatusesQuery(get_called_class());
    }
}
