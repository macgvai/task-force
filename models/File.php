<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property int $task_id
 * @property int $user_id
 * @property string $dt_add
 */
class File extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @var mixed|null
     */
    private $size;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'path', 'user_id', 'task_uid'], 'required'],
            [['task_id', 'user_id'], 'default', 'value' => null],
            [['task_id', 'user_id'], 'integer'],
            [[ 'task_uid'], 'string'],
            [['dt_add', 'task_uid', 'task_id'], 'safe'],
            [['name', 'path'], 'string', 'max' => 255],
            [['path'], 'unique'],
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
            'path' => 'Path',
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
            'dt_add' => 'Dt Add',
        ];
    }


    public function upload()
    {
        $this->name = $this->file->name;
        $newname = uniqid() . '.' . $this->file->getExtension();
        $this->path = '/uploads/' . $newname;
        $this->size = $this->file->size;
        $this->task_uid = $this->task_uid;

        if ($this->save()) {
            return $this->file->saveAs('@webroot/uploads/' . $newname);
        }

        return false;
    }

    public function getTask()
    {
        return $this->hasOne(Task::class, ['uid' => 'task_uid']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileQuery(get_called_class());
    }
}
