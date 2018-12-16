<?php

namespace common\models;

use Ramsey\Uuid\UuidInterface;
use thamtech\uuid\validators\UuidValidator;

/**
 * This is the model class for table "room".
 *
 * @property UuidInterface|string $id
 * @property string $name
 * @property UuidInterface|string $hotel_id
 * @property int $price
 * @property int $capacity
 * @property string $photo
 *
 * @property Reservation[] $reservations
 * @property Hotel $hotel
 */
class Room extends \yii\db\ActiveRecord
{
    /**
     * @param UuidInterface|string $id
     */
    public static function create(string $id): self
    {
        $obj = new self();
        $obj->id = $id;
        return $obj;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'room';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'hotel_id'], UuidValidator::class],
            [['name', 'hotel_id', 'price', 'capacity'], 'required'],
            [['price', 'capacity'], 'integer', 'min' => 1],
            [['name', 'photo'], 'string', 'max' => 255],
            [['hotel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hotel::className(), 'targetAttribute' => ['hotel_id' => 'id']],
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
            'hotel_id' => 'Hotel',
            'price' => 'Cena',
            'capacity' => 'Liczba łóżek',
            'photo' => 'Zdjęcie',
            'reservations' => 'Rezerwacje',
            'hotel' => 'Hotel',
            'availability' => 'Dostępny od'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReservations()
    {
        return $this->hasMany(Reservation::className(), ['room_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotel()
    {
        return $this->hasOne(Hotel::className(), ['id' => 'hotel_id']);
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'hotel',
            'availability' => function ($model) {
                return "2018-12-14";
            }
        ]);
    }

    /**
     * {@inheritdoc}
     * @return RoomQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RoomQuery(get_called_class());
    }
}
