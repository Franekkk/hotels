<?php
declare(strict_types=1);
/**
 * @author: Piotr Rozmarynowski
 * Project: photele
 * Date: 12.12.18
 */

namespace frontend\domain\BookARoom;

use Ramsey\Uuid\UuidInterface;
use thamtech\uuid\validators\UuidValidator;
use yii\base\Model;

class Params extends Model
{
    /** @var UuidInterface|string */
    public $reservation_id;
    /** @var UuidInterface|string */
    public $room_id;
    /** @var int */
    public $persons;
    /** @var string */
    public $checkin_date;
    /** @var string */
    public $checkin_time;
    /** @var int */
    public $duration;
    /** @var string */
    public $first_name;
    /** @var string */
    public $last_name;
    /** @var string */
    public $email;
    /** @var string */
    public $comment;

    public function __construct(array $params)
    {
        parent::__construct();
        $this->load($params, '');
    }

    public function rules()
    {
        return [
            [['reservation_id', 'room_id'], UuidValidator::class],
            [['reservation_id', 'room_id', 'persons', 'duration', 'checkin_date', 'checkin_time', 'first_name', 'last_name', 'email'], 'required'],
            [['persons', 'duration'], 'integer'],
            [['checkin_date', 'checkin_time', 'first_name', 'last_name', 'comment'], 'string'],
            ['email','email']
        ];
    }
}