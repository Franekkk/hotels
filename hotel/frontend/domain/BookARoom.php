<?php
declare(strict_types=1);
/**
 * @author: Piotr Rozmarynowski, Codete
 * Project: photele
 * Date: 12.12.18
 */

namespace frontend\domain;

use common\models\Reservation;
use frontend\domain\BookARoom\Params;
use frontend\domain\BookARoom\ReservationPreparer;
use frontend\domain\BookARoom\RoomAvailability;
use frontend\models\Room;
use yii\base\Model;

class BookARoom extends Model
{
    /** @var Params */
    public $params;
    /** @var Reservation|null */
    public $reservation;

    public function __construct(Params $params)
    {
        parent::__construct();
        $this->params = $params;
    }

    public function validateReservation(): ?Reservation
    {
        if (
            $this->params->validate()
            AND $wantedRoom = $this->findRoom()
            AND $this->checkIfCapacityFits($wantedRoom)
            AND $this->checkAvailability($wantedRoom)
            AND $reservation = $this->prepareReservation($wantedRoom)
            AND $reservation->validate()
        ) {
            return $reservation;
        }
        $this->addErrors(array_merge(
            $reservation->errors ?? [],
            $this->params->errors)
        );

        return null;
    }

    public function handle(): ?Reservation
    {
        if (
            $reservation = $this->validateReservation()
            AND $reservation->save()
        ) {
            return $reservation;
        }
        return null;
    }

    private function findRoom(): ?Room
    {
        return Room::findOne($this->params->room_id);
    }

    private function checkIfCapacityFits(Room $room): bool
    {
        $result = $room->capacity >= $this->params->persons;
        if ($result) return $result;

        $this->addError('persons', 'Ilość osób przekracza pojemność pokoju');
        return false;
    }

    private function checkAvailability(Room $room): bool
    {
        $availability = new RoomAvailability($room, $this->params);
        if ($availability->isAvailable()) {
            return true;
        }
        $this->addError('checkin_date', $availability->error());
        return false;
    }

    private function prepareReservation(Room $room): Reservation
    {
        return ReservationPreparer::prepare($room, $this->params);
    }
}