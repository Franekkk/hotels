<?php

use yii\db\Migration;

/**
 * Handles the creation of table `room`.
 */
class m181211_175358_create_room_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('room', [
            'id'           => $this->string()->notNull()->unique(),
            'name'         => $this->string()->notNull(),
            'hotel_id'     => $this->string()->notNull(),
            'price'        => $this->integer()->notNull(),
            'capacity'     => $this->smallInteger()->notNull(),
            'photo'        => $this->string(),
        ]);

        $this->addPrimaryKey('room-id', 'room', 'id');
        $this->addForeignKey('FK_room-hotel', 'room', 'hotel_id', 'hotel', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_room-hotel', 'room');
        $this->dropTable('room');
    }
}
