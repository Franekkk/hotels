<?php

use yii\db\Migration;

/**
 * Class m181215_222327_set_polish_chars
 */
class m181215_222327_set_polish_chars extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand()->checkIntegrity(false)->execute();
        Yii::$app->db->createCommand("
ALTER TABLE hotel CONVERT TO CHARACTER SET utf8 COLLATE utf8_polish_ci;
ALTER TABLE room CONVERT TO CHARACTER SET utf8 COLLATE utf8_polish_ci;
ALTER TABLE reservation CONVERT TO CHARACTER SET utf8 COLLATE utf8_polish_ci;
"
        )->execute();
        $this->db->createCommand()->checkIntegrity(true)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

}
