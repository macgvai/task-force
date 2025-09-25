<?php

use yii\db\Migration;

class m250924_130157_add_user_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'bd_date', $this->date());
        $this->addColumn('users', 'phone', $this->char(16));
        $this->addColumn('users', 'tg', $this->char(255));
        $this->addColumn('users', 'hide_contacts', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250924_130157_add_user_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250924_130157_add_user_fields cannot be reverted.\n";

        return false;
    }
    */
}
