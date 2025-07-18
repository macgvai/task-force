<?php

use yii\db\Migration;

class m250718_101152_addUserIs_contractor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'is_contractor', $this->boolean()->defaultValue(false));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250718_101152_addUserIs_contractor cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250718_101152_addUserIs_contractor cannot be reverted.\n";

        return false;
    }
    */
}
