<?php

namespace app\models;

use victor\logic\actions\AbstractAction;
use victor\logic\AvailableActions;
use Yii;
use yii\web\IdentityInterface;
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
 * @property Category $category
 * @property Status $status
 */
class Task extends \yii\db\ActiveRecord
{
    public $noResponse;
    public $noLocation;
    public $filterPeriod;
    /**
     * @var mixed|null
     */
    public $replies;

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
            [['status_id'], 'default', 'value' => function () {
                return Status::find()->where(['id' => '1'])->scalar();
            }],
            ['expire_dt', 'date', 'format' => 'php:Y-m-d', 'min' => date('Y-m-d'), 'minString' => 'чем текущий день'],
            [['name', 'category_id', 'description', 'client_id', 'status_id'], 'required'],
            [['category_id', 'budget', 'client_id', 'performer_id'], 'default', 'value' => null],
            [['category_id', 'budget', 'client_id', 'performer_id', 'status_id'], 'integer'],
            [['description'], 'string'],
            [['expire_dt', 'dt_add', 'noResponse', 'noLocation'], 'safe'],
            [['name', 'location'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['noResponse', 'noLocation'], 'boolean'],
            [['filterPeriod'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'category_id' => 'Категория',
            'description' => 'Описание',
            'location' => 'Локация',
            'budget' => 'Стоимость',
            'expire_dt' => 'Дата окончания',
            'dt_add' => 'Дата добавления',
            'client_id' => 'Клиент',
            'performer_id' => 'Исполнитель',
            'status_id' => 'Статус',
            'noResponse' => 'Без откликов',
            'noLocation' => 'Удалённая работа',
            'filterPeriod' => 'Период',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery|StatusQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    public function getFilters($status_id = null): TaskQuery
    {
        $query = self::find();

        // Фильтрация по статусу (новые задания)
        if ($status_id) {
            $query->where(['status_id' => $status_id]);
        }

        // Фильтрация по категории, если она указана
        if ($this->category_id) {
            $query->andWhere(['category_id' => $this->category_id]);
        }

        if ($this->noResponse) {
            $query->joinWith('replies r')->andWhere('r.id IS NULL');
        }

        if ($this->noLocation) {
            $query->andWhere('location IS NULL');
        }

        if ($this->filterPeriod) {
            $query->andWhere('UNIX_TIMESTAMP(tasks.dt_add) > UNIX_TIMESTAMP() - :period', [':period' => $this->filterPeriod]);
        }

        return $query->orderBy('dt_add DESC');
    }

    /**
     * {@inheritdoc}
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }

    /**
     * Gets query for [[Reply]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies(?IdentityInterface $user = null)
    {
        $allRepliesQuery = $this->hasMany(Reply::class, ['task_id' => 'id']);

        if ($user && $user->getId() !== $this->client_id) {
            $allRepliesQuery->andWhere(['replies.user_id' => $user->getId()]);
        }

        return $allRepliesQuery;
    }

    public function getFiles()
    {
        return $this->hasMany(File::class, ['task_id' => 'uid']);
    }


    public function goToNextStatus(AbstractAction $action)
    {
        $actionManager = new AvailableActions($this->status->slug, $this->performer_id, $this->client_id);
        $nextStatusName = $actionManager->getNextStatus($action);

        $status = Status::findOne(['slug' => $nextStatusName]);
        $this->link('status', $status);
        $this->save();
    }
}
