<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
 * @property City $city
 * @property UserCategory[] $userCategories
 * @property UserSetting $userSettings
 * @property is_contractor $is_contractor
 */
class User  extends ActiveRecord implements IdentityInterface
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
            [['email', 'name', 'city_id', 'password', 'password_repeat'], 'required'],
            [['city_id'], 'default', 'value' => null],
            [['city_id'], 'integer'],
            [['dt_add', 'password_repeat'], 'safe'],
            [['email', 'name'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['hide_contacts', 'is_contractor'], 'boolean'],
            // Новое правило: проверка, что password_repeat совпадает с password
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
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
            'name' => 'Имя',
            'city_id' => 'City ID',
            'password' => 'Password',
            'dt_add' => 'Dt Add',
            'hide_contacts' => 'Показывать контакты только заказчику    ',
            'is_contractor' => 'Я собираюсь откликаться на заказы',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|CityQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[UserCategory]].
     *
     * @return \yii\db\ActiveQuery|UserCategoryQuery
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

        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('user_categories', ['user_id' => 'id']);
    }


    /**
     * Gets query for [[UserSetting]].
     *
     * @return \yii\db\ActiveQuery|UserSettingQuery
     */
    public function getUserSettings()
    {
        return $this->hasOne(UserSetting::className(), ['user_id' => 'id']);
    }

    Public function getOpinions()
    {
        return $this->hasMany(Opinion::className(), ['performer_id' => 'id']);
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
        return $this->hasMany(Reply::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Assigned Task]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getAssignedTasks($user = null)
    {
        $query = $this->hasMany(Task::className(), ['client_id' => 'id']);

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
            ->andWhere(['statuses.id' => Status::STATUS_IN_PROGRESS])
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
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
}
