<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Replies]].
 *
 * @see Replies
 */
class RepliesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Replies[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Replies|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
