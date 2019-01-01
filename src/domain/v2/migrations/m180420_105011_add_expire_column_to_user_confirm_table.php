<?php

use yii\db\Migration;

/**
 * Handles adding expire to table `user_confirm`.
 */
class m180420_105011_add_expire_column_to_user_confirm_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_confirm', 'expire', $this->integer()->after('data'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_confirm', 'expire');
    }
}
