<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%iot24}}`.
 */
class m211110_203816_create_iot24_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%iot24}}', [
            'id' => $this->primaryKey(),
            'system_id' => $this->integer(),
            'device_id' => $this->string(512),
            'increments' => $this->text(),
            'values' => $this->text(),
            'status' => $this->string(10)->defaultValue("1"),
            "created_at" => $this->string(20),
            "updated_at" => $this->string(20),
            'downloaded_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%iot24}}');
    }
}