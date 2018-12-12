<?php

use yii\db\Migration;

/**
 * Handles the creation of table `hotel`.
 */
class m181211_175243_create_hotel_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('hotel', [
            'id' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'city' => $this->string()->notNull()
        ]);
        $this->addPrimaryKey('hotel-id', 'hotel', 'id');

        $this->insert('hotel', [
            'id' => Yii::$app->params['hotel']['id'],
            'name' => Yii::$app->params['hotel']['name'],
            'city' => Yii::$app->params['hotel']['city'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('hotel');
    }
}
