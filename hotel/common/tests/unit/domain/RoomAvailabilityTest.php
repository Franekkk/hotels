<?php

namespace common\tests\unit\models;

use common\models\Room;
use frontend\domain\BookARoom;
use frontend\domain\BookARoom\Params;
use frontend\domain\BookARoom\RoomAvailability;

class RoomAvailabilityTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected        $tester;

    protected function setUp()
    {
        parent::setUp();
        (new Room([
            'id'       => "ebb05461-3425-40e3-98fb-c3629d2b1c45",
            'name'     => 'Dwójka',
            'hotel_id' => '201bf54b-b23d-4c01-8cf9-fe8b5f47d74c',
            'price'    => 123,
            'capacity' => 2,
            'photo'    => '/static/img/room2.jpg',
        ]))->save();
    }

    public function testAvailableDate()
    {
        (new BookARoom(new Params([
            'reservation_id' => 'b2a1749c-1105-4311-aaf3-5112aef69b50',
            'room_id'        => 'ebb05461-3425-40e3-98fb-c3629d2b1c45',
            'persons'        => '2',
            'checkin_date'   => '2019-02-11',
            'checkin_time'   => '13:13:00',
            'duration'       => '5',
            'first_name'     => 'Jan',
            'last_name'      => 'Kowalski',
            'email'          => 'jan@kowalski.com',
            'comment'        => 'Jakaś uwaga',
        ])))->handle();

        $room_id = "ebb05461-3425-40e3-98fb-c3629d2b1c45";

        $afterLastReservation = RoomAvailability::isAvailableForPeriod($room_id, '2019-02-16', 3);
        $monthEarlier = RoomAvailability::isAvailableForPeriod($room_id, '2019-01-16', 3);
        $oneDayUnavailable = RoomAvailability::isAvailableForPeriod($room_id, '2019-02-15', 3);
        $onlyOneDayAvailable = RoomAvailability::isAvailableForPeriod($room_id, '2019-02-13', 4);

        expect('New dates are after previous reserevation', $afterLastReservation)->true();
        expect('Dates are month before next reservation', $monthEarlier)->true();
        expect('One day should be unavailable', $oneDayUnavailable)->false();
        expect('Only one day should be available', $onlyOneDayAvailable)->false();
    }

}
