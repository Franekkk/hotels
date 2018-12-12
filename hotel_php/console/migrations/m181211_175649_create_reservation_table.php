<?php

use yii\db\Migration;

/**
 * Handles the creation of table `reservation`.
 */
class m181211_175649_create_reservation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('reservation', [
            'id'           => $this->string()->notNull()->unique(),
            'room_id'      => $this->string()->notNull(),
            'persons'      => $this->smallInteger()->notNull(),
            'checkin_date' => $this->date()->notNull(),
            'checkin_time' => $this->time()->notNull(),
            'duration'     => $this->smallInteger()->notNull(),
            'dates'        => $this->string()->notNull(),
            'price'        => $this->integer()->notNull(),
            'first_name'   => $this->string()->notNull(),
            'last_name'    => $this->string()->notNull(),
            'email'        => $this->string()->notNull(),
            'comment'      => $this->string(),
            'status'       => $this->smallInteger()->notNull(),
        ]);

        $this->addPrimaryKey('reservation-id', 'reservation', 'id');
        $this->addForeignKey('FK_reservation-room', 'reservation', 'room_id', 'room', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_reservation-room', 'reservation');
        $this->dropTable('reservation');
    }
}
