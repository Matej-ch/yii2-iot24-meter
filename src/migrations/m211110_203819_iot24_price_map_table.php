<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%iot24_price_map}}`.
 */
class m211110_203819_iot24_price_map_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%iot24_price_map}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(512),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),

        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%iot24_price_map}}');
    }
}