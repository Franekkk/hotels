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
    public $reservationId;
    /** @var UuidInterface|string */
    public $roomId;
    /** @var int */
    public $persons;
    /** @var string */
    public $checkinDate;
    /** @var string */
    public $checkinTime;
    /** @var int */
    public $duration;
    /** @var string */
    public $firstName;
    /** @var string */
    public $lastName;
    /** @var string */
    public $email;
    /** @var string */
    public $comment;

    public function __construct(array $requestBodyParams)
    {
        parent::__construct();
        $this->load($requestBodyParams, '');
    }

    public function rules()
    {
        return [
            [['reservationId', 'roomId'], UuidValidator::class],
            [['reservationId', 'roomId', 'persons', 'duration', 'checkinDate', 'checkinTime', 'firstName', 'lastName', 'email'], 'required'],
            [['persons', 'duration'], 'integer'],
            [['checkinDate', 'checkinTime', 'firstName', 'lastName', 'comment'], 'string'],
            ['email','email']
        ];
    }
}