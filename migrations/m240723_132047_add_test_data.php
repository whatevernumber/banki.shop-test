<?php

use yii\db\Migration;

/**
 * Class m240723_132047_add_test_data
 */
class m240723_132047_add_test_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('parameters',
            [
                'id', 'title', 'type'
            ],
            [
                ['1', 'FirstParam', 1],
                ['2', 'SecondParam', 2],
                ['3', 'ThirdParam', 2],
                ['4', 'AnotherParam', 2],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('types');
        $this->truncateTable('parameters');

        return false;
    }
}
