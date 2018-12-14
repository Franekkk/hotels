<?php
declare(strict_types=1);
/**
 * @author: Piotr Rozmarynowski, Codete
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
        $reservation               = Reservation::create($params->reservation_id);
        $reservation->room_id      = $room->id;
        $reservation->persons      = (int)$params->persons;
        $reservation->checkin_date = $params->checkin_date;
        $reservation->checkin_time = $params->checkin_time;
        $reservation->first_name   = $params->first_name;
        $reservation->last_name    = $params->last_name;
        $reservation->email        = $params->email;
        $reservation->comment      = $params->comment;
        $reservation->status       = Reservation::STATUS_NEW;
        $reservation->duration     = (int)$params->duration;
        $reservation->price        = $room->price;
        $reservation->dates        = Json::encode(self::dates($params->checkin_date, (int)$params->duration));

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