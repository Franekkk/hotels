<?php

namespace common\models;

use frontend\domain\BookARoom\RoomAvailability;
use Ramsey\Uuid\UuidInterface;
use thamtech\uuid\validators\UuidValidator;

/**
 * This is the model class for table "reservation".
 *
 * @property UuidInterface|string $id
 * @property UuidInterface|string $room_id
 * @property int $persons
 * @property string $checkin_date
 * @property string $checkin_time
 * @property int $duration
 * @property string|Json $dates
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $comment
 * @property int $status
 * @property int $price
 *
 * @property Room $room
 */
class Reservation extends \yii\db\ActiveRecord
{
    public const STATUS_NEW = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_DECLINED = 2;
    public const STATUS_CANCELED = 3;

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
        return 'reservation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'room_id'], UuidValidator::class],
            [['room_id', 'persons', 'checkin_date', 'checkin_time', 'duration', 'first_name', 'last_name', 'email'], 'required'],
            [['persons', 'duration', 'status', 'price'], 'integer'],
            [['status'], 'default', 'value' => self::STATUS_NEW],
            [['status'], 'in', 'range' => [self::STATUS_NEW, self::STATUS_ACCEPTED, self::STATUS_DECLINED, self::STATUS_CANCELED]],
            [['checkin_date'], 'date', 'format' => 'yyyy-MM-dd'],
            ['checkin_date', function ($attribute, $params, $validator) {
                if (
                    $this->isAttributeChanged($attribute)
                    && !RoomAvailability::isAvailableForPeriod($this->room_id, $this->checkin_date, $this->duration)
                ) {
                    $this->addError($attribute, "Pokój w podanym okresie jest zajęty.");
                }
            }],
            [['dates'], 'string'],
            [['checkin_time'], 'time', 'format' => 'HH:mm:ss'],
            [['first_name', 'last_name', 'email', 'comment'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['room_id'], 'exist', 'skipOnError' => false, 'targetClass' => Room::class, 'targetAttribute' => ['room_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'room_id' => 'Room ID',
            'room' => 'Pokój',
            'persons' => 'Ilość osób',
            'checkin_date' => 'Data zameldowania',
            'checkin_time' => 'Godzina zameldowania',
            'duration' => 'Noclegów',
            'dates' => 'Daty',
            'first_name' => 'Imię',
            'last_name' => 'Nazwisko',
            'email' => 'Email',
            'comment' => 'Uwagi',
            'price' => 'Cena za nocleg',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::class, ['id' => 'room_id']);
    }

    /**
     * {@inheritdoc}
     * @return ReservationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReservationQuery(static::class);
    }
}
