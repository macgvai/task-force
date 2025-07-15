<?php

use yii\db\Migration;

class m250715_080429_addUserSettingsTg extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_settings', 'tg', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250715_080429_addUserSettingsTg cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250715_080429_addUserSettingsTg cannot be reverted.\n";

        return false;
    }
    */
}
