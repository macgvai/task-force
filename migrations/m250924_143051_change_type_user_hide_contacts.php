<?php

use yii\db\Migration;

class m250924_143051_change_type_user_hide_contacts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('users', 'hide_contacts', $this->boolean()->defaultValue('0'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250924_143051_change_type_user_hide_contacts cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250924_143051_change_type_user_hide_contacts cannot be reverted.\n";

        return false;
    }
    */
}
