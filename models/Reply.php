<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "replies".
 *
 * @property int $id
 * @property int $user_id
 * @property string $dt_add
 * @property string $description
 * @property int $task_id
 * @property bool|null $is_approved
 */
class Reply extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'replies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'description', 'task_id'], 'required'],
            [['user_id', 'task_id'], 'default', 'value' => null],
            [['user_id', 'task_id'], 'integer'],
            [['dt_add'], 'safe'],
            [['is_approved'], 'boolean'],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'dt_add' => 'Dt Add',
            'description' => 'Description',
            'task_id' => 'Task ID',
            'is_approved' => 'Is Approved',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ReplyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReplyQuery(get_called_class());
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

}
