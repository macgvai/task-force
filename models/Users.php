<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property int $city_id
 * @property string $password
 * @property string $dt_add
 * @property int $fail_count
 *
 * @property Cities $city
 * @property UserCategories[] $userCategories
 * @property UserSettings $userSettings
 */
class Users extends \yii\db\ActiveRecord
{
    public $password_repeat;

    public $old_password;
    public $new_password;
    public $new_password_repeat;
    public $hide_contacts;

    /**
     * @var UploadedFile
     */
    public $avatarFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'city_id', 'password'], 'required'],
            [['city_id'], 'default', 'value' => null],
            [['city_id'], 'integer'],
            [['dt_add'], 'safe'],
            [['email', 'name'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['hide_contacts'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Name',
            'city_id' => 'City ID',
            'password' => 'Password',
            'dt_add' => 'Dt Add',
            'hide_contacts' => 'Показывать контакты только заказчику    ',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|CitiesQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery|UserCategoriesQuery
     */
    public function getUserCategories()
    {
//        $sql = 'SELECT c.name FROM user_categories as u LEFT JOIN categories as c ON u.category_id = c.id WHERE u.user_id = :id';
//
//        $res = Yii::$app->db->createCommand($sql)
//            ->bindValue(':id', $this->id)
//            ->queryAll(\PDO::FETCH_OBJ);
//
//            return $res;

        return $this->hasMany(Categories::class, ['id' => 'category_id'])->viaTable('user_categories', ['user_id' => 'id']);
    }


    /**
     * Gets query for [[UserSettings]].
     *
     * @return \yii\db\ActiveQuery|UserSettingsQuery
     */
    public function getUserSettings()
    {
        return $this->hasOne(UserSettings::className(), ['user_id' => 'id']);
    }

    Public function getOpinions()
    {
        return $this->hasMany(Opinions::className(), ['performer_id' => 'id']);
    }


    public function getRating()
    {
        $rating = null;

        $opinionsCount = $this->getOpinions()->count();

        if ($opinionsCount) {
            $ratingSum = $this->getOpinions()->sum('rate');
            $failCount = $this->fail_count;
            $rating = round(intdiv($ratingSum, $opinionsCount + $failCount), 2);
        }

        return $rating;
    }



    Public function getReplies()
    {
        return $this->hasMany(Replies::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Assigned Tasks]].
     *
     * @return \yii\db\ActiveQuery|TasksQuery
     */
    public function getAssignedTasks($user = null)
    {
        $query = $this->hasMany(Tasks::className(), ['client_id' => 'id']);

        if ($user !== null) {
            // Дополнительный фильтр, если нужен, например, для проверки выполненных задач
            $query->andWhere(['user_id' => $user->id]);
        }

        return $query;
    }

    public function isBusy()
    {
        return $this->getAssignedTasks()
            ->joinWith('status')
            ->andWhere(['statuses.id' => Statuses::STATUS_IN_PROGRESS])
            ->exists();
    }

    public function isContactsAllowed($user)
    {
        $result = true;

        if ($this->hide_contacts) {
            $result = $this->getAssignedTasks($user)->exists();
        }

        return $result;
    }

    public function getRatingPosition()
    {
        $result = null;

        $sql = "SELECT u.id, (SUM(o.rate) / (COUNT(o.id) + u.fail_count)) as rate FROM users u
                LEFT JOIN opinions o on u.id = o.performer_id
                GROUP BY u.id
                ORDER BY rate DESC";

        $records = Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);
        $index = array_search($this->id, array_column($records, 'id'));

        if ($index !== false) {
            $result = $index + 1;
        }

        return $result;
    }



    /**
     * {@inheritdoc}
     * @return UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }
}
