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

    public function handle(): ?Reservation
    {
        if (
            $this->params->validate()
            AND $wantedRoom = $this->findRoom()
            AND $this->checkIfCapacityFits($wantedRoom)
            AND $this->checkAvailability($wantedRoom)
        ) {
            $reservation = $this->prepareReservation($wantedRoom);
            if ($reservation->save()) {
                return $reservation;
            }
            $this->addErrors($reservation->errors);
        }
        $this->addErrors($this->params->errors);

        return null;
    }

    private function findRoom(): ?Room
    {
        return Room::findOne($this->params->roomId);
    }

    private function checkIfCapacityFits(Room $room): bool
    {
        $result = $room->capacity >= $this->params->persons;
        if ($result) return $result;

        $this->addError('reservation', 'Ilość osób przekracza pojemność pokoju');
        return false;
    }

    private function checkAvailability(Room $room): bool
    {
        $availability = new RoomAvailability($room, $this->params);
        if ($availability->isAvailable()) {
            return true;
        }
        $this->addError('reservation', $availability->error());
        return false;
    }

    private function prepareReservation(Room $room): Reservation
    {
        return ReservationPreparer::prepare($room, $this->params);
    }
}