<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%icons}}`.
 */
class m240723_084751_create_icons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%icons}}', [
            'id' => $this->primaryKey(),
            'original_name' => $this->string()->notNull(),
            'image_name' => $this->string()->notNull(),
            'type' => "ENUM('icon', 'icon_gray')",
            'param_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-param-icon',
            'icons',
            'param_id',
            'parameters',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-param-icon',
            'icons',
            ['type', 'param_id'],
            true,
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-image-param',
            'icons'
        );

        $this->dropIndex(
            'idx-param-icon',
            'icons'
        );

        $this->dropTable('{{%icons}}');
    }
}
