<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserSetting]].
 *
 * @see UserSetting
 */
class UserSettingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserSetting[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserSetting|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
