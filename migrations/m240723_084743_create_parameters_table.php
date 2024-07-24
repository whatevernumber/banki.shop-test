<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%parameters}}`.
 */
class m240723_084743_create_parameters_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%parameters}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'type' => $this->tinyInteger()->notNull(), // todo: type
        ]);


        $this->createIndex(
            'idx-params-type',
            'parameters',
            ['title', 'type'],
            true,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-params-type',
            'parameters'
        );

        $this->dropTable('{{%parameters}}');
    }
}
