<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property int $id
 * @property string|null $address
 * @property string|null $bd
 * @property string|null $avatar_path
 * @property string|null $about
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $messenger
 * @property bool|null $notify_new_msg
 * @property bool|null $notify_new_action
 * @property bool|null $notify_new_reply
 * @property bool|null $opt_hide_contacts
 * @property bool|null $opt_hide_me
 * @property bool|null $is_performer
 * @property int $user_id
 *
 * @property Users $user
 */
class UserSetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bd'], 'safe'],
            [['about'], 'string'],
            [['notify_new_msg', 'notify_new_action', 'notify_new_reply', 'opt_hide_contacts', 'opt_hide_me', 'is_performer'], 'boolean'],
            [['user_id'], 'required'],
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['address', 'avatar_path'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['skype', 'messenger'], 'string', 'max' => 32],
            [['phone', 'skype', 'messenger'], 'unique', 'targetAttribute' => ['phone', 'skype', 'messenger']],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'bd' => 'Bd',
            'avatar_path' => 'Avatar Path',
            'about' => 'About',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'messenger' => 'Messenger',
            'notify_new_msg' => 'Notify New Msg',
            'notify_new_action' => 'Notify New Action',
            'notify_new_reply' => 'Notify New Reply',
            'opt_hide_contacts' => 'Opt Hide Contacts',
            'opt_hide_me' => 'Opt Hide Me',
            'is_performer' => 'Is Performer',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return UserSettingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserSettingQuery(get_called_class());
    }
}
