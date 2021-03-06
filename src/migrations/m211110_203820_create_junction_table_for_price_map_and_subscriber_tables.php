<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%iot24_price_map_subscriber}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%iot24_price_map}}`
 * - `{{%iot24_subscriber}}`
 */
class m211110_203820_create_junction_table_for_price_map_and_subscriber_tables extends Migration
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

        $this->createTable('{{%iot24_price_map_subscriber}}', [
            'price_map_id' => $this->integer(),
            'subscriber_id' => $this->integer(),
            'PRIMARY KEY(price_map_id, subscriber_id)',
        ], $tableOptions);

        $this->createIndex(
            '{{%idx-price_map_subs-price_map_id}}',
            '{{%iot24_price_map_subscriber}}',
            'price_map_id'
        );

        $this->createIndex(
            '{{%idx-price_map_subs-subscriber_id}}',
            '{{%iot24_price_map_subscriber}}',
            'subscriber_id'
        );

        $this->addForeignKey(
            '{{%fk-price_map_subs-price_map_id}}',
            '{{%iot24_price_map_subscriber}}',
            'price_map_id',
            '{{%iot24_price_map}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-price_map_subs-subscriber_id}}',
            '{{%iot24_price_map_subscriber}}',
            'subscriber_id',
            '{{%iot24_subscriber}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-price_map_subs-price_map_id}}',
            '{{%iot24_price_map_subscriber}}'
        );

        $this->dropForeignKey(
            '{{%fk-price_map_subs-subscriber_id}}',
            '{{%iot24_price_map_subscriber}}'
        );

        $this->dropIndex(
            '{{%idx-price_map_subs-price_map_id}}',
            '{{%iot24_price_map_subscriber}}'
        );

        $this->dropIndex(
            '{{%idx-price_map_subs-subscriber_id}}',
            '{{%iot24_price_map_subscriber}}'
        );

        $this->dropTable('{{%iot24_price_map_subscriber}}');
    }
}
