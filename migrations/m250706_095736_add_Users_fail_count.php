<?php

use yii\db\Migration;

class m250706_095736_add_Users_fail_count extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'fail_count', $this->integer()->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250706_095736_add_Users_fail_count cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250706_095736_add_Users_fail_count cannot be reverted.\n";

        return false;
    }
    */
}
