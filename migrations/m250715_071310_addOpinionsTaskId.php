<?php

use yii\db\Migration;

class m250715_071310_addOpinionsTaskId extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('opinions', 'task_id', $this->integer());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250715_071310_addOpinionsTaskId cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250715_071310_addOpinionsTaskId cannot be reverted.\n";

        return false;
    }
    */
}
