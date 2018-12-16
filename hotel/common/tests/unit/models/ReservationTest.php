<?php

namespace common\tests\unit\models;

use common\models\Reservation;
use common\models\Room;
use frontend\domain\BookARoom;
use frontend\domain\BookARoom\Params;

class ReservationTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    protected function setUp()
    {
        parent::setUp();

        parent::setUp();
        (new Room([
            'id'       => "ebb05461-3425-40e3-98fb-c3629d2b1c45",
            'name'     => 'Dwójka',
            'hotel_id' => '201bf54b-b23d-4c01-8cf9-fe8b5f47d74c',
            'price'    => 123,
            'capacity' => 2,
            'photo'    => '/static/img/room2.jpg',
        ]))->save();
        $booking = new BookARoom(new Params([
            'reservation_id' => $reservation_id = 'b2a1749c-1105-4311-aaf3-5112aef69b50',
            'room_id'        => $room_id = 'ebb05461-3425-40e3-98fb-c3629d2b1c45',
            'persons'        => $persons = '2',
            'checkin_date'   => $checkin_date = '2019-02-11',
            'checkin_time'   => $checkin_time = '13:13:00',
            'duration'       => $duration = '5',
            'first_name'     => $first_name = 'Jan',
            'last_name'      => $last_name = 'Kowalski',
            'email'          => $email = 'jan@kowalski.com',
            'comment'        => $comment = 'Jakaś uwaga',
        ]));
        $booking->handle();
    }

    public function testAcceptReservation()
    {
        $reservation = Reservation::findOne('b2a1749c-1105-4311-aaf3-5112aef69b50');
        $reservation->accept();
        $reservation->save();

        $freshReservation = Reservation::findOne('b2a1749c-1105-4311-aaf3-5112aef69b50');

        self::assertEquals(Reservation::STATUS_ACCEPTED, $freshReservation->status);
    }

    public function testDeclineReservation()
    {
        $reservation = Reservation::findOne('b2a1749c-1105-4311-aaf3-5112aef69b50');
        $reservation->decline();
        $reservation->save();

        $freshReservation = Reservation::findOne('b2a1749c-1105-4311-aaf3-5112aef69b50');

        self::assertEquals(Reservation::STATUS_DECLINED, $freshReservation->status);
    }

    public function testCancelReservation()
    {
        $reservation = Reservation::findOne('b2a1749c-1105-4311-aaf3-5112aef69b50');
        $reservation->cancel();
        $reservation->save();

        $freshReservation = Reservation::findOne('b2a1749c-1105-4311-aaf3-5112aef69b50');

        self::assertEquals(Reservation::STATUS_CANCELED, $freshReservation->status);
    }
}
