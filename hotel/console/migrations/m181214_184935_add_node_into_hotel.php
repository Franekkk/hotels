<?php

use yii\db\Migration;

/**
 * Class m181214_184935_add_node_into_hotel
 */
class m181214_184935_add_node_into_hotel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('hotel', 'node', 'string NOT NULL');
        $this->update('hotel', [
            'node' => Yii::$app->params['hotel']['node'],
        ], ['id' => Yii::$app->params['hotel']['id']]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('hotel', 'node');
    }
}
