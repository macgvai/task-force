<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "opinions".
 *
 * @property int $id
 * @property int $owner_id
 * @property int $performer_id
 * @property int $rate
 * @property string $description
 * @property string|null $dt_add
 */
class Opinions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'opinions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['owner_id', 'performer_id', 'rate', 'description'], 'required'],
            [['owner_id', 'performer_id', 'rate'], 'default', 'value' => null],
            [['owner_id', 'performer_id', 'rate'], 'integer'],
            [['description'], 'string'],
            [['dt_add'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'performer_id' => 'Performer ID',
            'rate' => 'Rate',
            'description' => 'Description',
            'dt_add' => 'Dt Add',
        ];
    }

    /**
     * {@inheritdoc}
     * @return OpinionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OpinionsQuery(get_called_class());
    }
}
