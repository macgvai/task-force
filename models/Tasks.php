<?php

namespace app\models;

use Yii;
use function PHPUnit\Framework\isNull;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string $description
 * @property string|null $location
 * @property int|null $budget
 * @property string|null $expire_dt
 * @property string|null $dt_add
 * @property int $client_id
 * @property int|null $performer_id
 * @property int $status_id
 *
 * @property Categories $category
 * @property Statuses $status
 */
class Tasks extends \yii\db\ActiveRecord
{
    public $noResponse;
    public $noLocation;
    public $filterPeriod;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'category_id', 'description', 'client_id', 'status_id'], 'required'],
            [['category_id', 'budget', 'client_id', 'performer_id', 'status_id'], 'default', 'value' => null],
            [['category_id', 'budget', 'client_id', 'performer_id', 'status_id'], 'integer'],
            [['description'], 'string'],
            [['expire_dt', 'dt_add'], 'safe'],
            [['name', 'location'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statuses::className(), 'targetAttribute' => ['status_id' => 'id']],
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
            'category_id' => 'Category ID',
            'description' => 'Description',
            'location' => 'Location',
            'budget' => 'Budget',
            'expire_dt' => 'Expire Dt',
            'dt_add' => 'Dt Add',
            'client_id' => 'Client ID',
            'performer_id' => 'Исполнитель',
            'status_id' => 'Status ID',
            'noResponse' => 'Без откликов',
            'noLocation' => 'Удалённая работа',
            'filterPeriod' => 'Период',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|CategoriesQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery|StatusesQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Statuses::className(), ['id' => 'status_id']);
    }

    public function getFilters(): TasksQuery
    {
        $query = self::find();

        // Фильтрация по статусу (новые задания)
        $query->where(['status_id' => 1]);

        // Фильтрация по категории, если она указана
        if ($this->category_id) {
            $query->andWhere(['category_id' => $this->category_id]);
        }

        if ($this->noResponse) {
            print_r($this);

            $query->joinWith('replies r')->andWhere(['r.id IS NULL']);
        }

        if ($this->noLocation) {
            print_r($this);
            $query->andWhere('location IS NULL');
        }

        if ($this->filterPeriod) {
            $query->andWhere('UNIX_TIMESTAMP(tasks.dt_add) > UNIX_TIMESTAMP() - :period', [':period' => $this->filterPeriod]);
        }

        return $query->orderBy('dt_add DESC');
    }

    /**
     * {@inheritdoc}
     * @return TasksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TasksQuery(get_called_class());
    }
}
