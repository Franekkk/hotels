<?php

namespace common\models;

use Ramsey\Uuid\UuidInterface;
use thamtech\uuid\validators\UuidValidator;
use Yii;

/**
 * This is the model class for table "hotel".
 *
 * @property UuidInterface|string $id
 * @property string $name
 * @property string $city
 *
 * @property Room[] $rooms
 */
class Hotel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hotel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['id', UuidValidator::class],
            [['name', 'city'], 'required'],
            [['name', 'city'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nazwa',
            'city' => 'Miasto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::className(), ['hotel_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return HotelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new HotelQuery(get_called_class());
    }
}
