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

class RoomAvailability
{
    /** @var Room */
    public $room;
    /** @var Params */
    public $params;
    /** @var string|null  */
    private $error = null;

    public function __construct(Room $room, Params $params)
    {
        $this->room   = $room;
        $this->params = $params;
    }

    public function isAvailable(): bool
    {
        $isAvailable = self::isAvailableForPeriod(
            $this->room->id,
            $this->params->checkin_date,
            (int) $this->params->duration
        );
        if (!$isAvailable) {
            $this->error = "Pokój w podanym okresie jest zajęty.";
        }
        return $isAvailable;
    }

    public function error(): ?string
    {
        return $this->error;
    }

    public static function isAvailableForPeriod(string $roomId, string $checkinDate, int $duration): bool
    {
        $orDates = ['or'];
        foreach (ReservationPreparer::dates($checkinDate, $duration) as $key => $date) {
            $orDates[] = ['like', 'dates', $date];
        }

        $reservation = Reservation::find()
            ->where(['room_id' => $roomId])
            ->andWhere($orDates);

        return !$reservation->exists();
    }

    public static function closestAvailability(string $roomId, string $checkinDate, int $duration)
    {

    }
}