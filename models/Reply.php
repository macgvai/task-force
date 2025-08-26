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
            [['description', 'budget'], 'required'],
            [['budget'], 'integer', 'min' => 1],
            [['description'], 'string', 'max' => 255],
            [['description'], 'unique', 'targetAttribute' => ['task_id', 'user_id'], 'message' => 'Вы уже оставляли отклик к этому заданию'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
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
            'description' => 'Комментариий',
            'budget' => 'Стоимость',
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
