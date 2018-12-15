<?php
declare(strict_types=1);
/**
 * @author: Piotr Rozmarynowski
 * Project: photele
 * Date: 12.12.18
 */

namespace frontend\domain\BookARoom;

use common\models\Reservation;
use frontend\models\Room;
use yii\helpers\Json;

class ReservationPreparer
{
    public static function prepare(Room $room, Params $params): Reservation
    {
        $reservation               = Reservation::create($params->reservationId);
        $reservation->room_id      = $room->id;
        $reservation->persons      = $params->persons;
        $reservation->checkin_date = $params->checkinDate;
        $reservation->checkin_time = $params->checkinTime;
        $reservation->first_name   = $params->firstName;
        $reservation->last_name    = $params->lastName;
        $reservation->email        = $params->email;
        $reservation->comment      = $params->comment;
        $reservation->status       = Reservation::STATUS_NEW;
        $reservation->duration     = $params->duration;
        $reservation->price        = $room->price;
        $reservation->dates        = Json::encode(self::dates($params->checkinDate, $params->duration));

        return $reservation;
    }

    /**
     * @return string[]
     */
    public static function dates(string $checkinDate, int $duration): array
    {
        $dates = [];
        for ($i = 0; $i < $duration; $i++) {
            $dates[] = date('Y-m-d', strtotime("$checkinDate +$i days"));
        }

        return $dates;
    }
}