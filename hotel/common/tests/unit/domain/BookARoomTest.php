<?php

namespace common\tests\unit\models;

use common\models\Reservation;
use common\models\Room;
use frontend\domain\BookARoom;
use frontend\domain\BookARoom\Params;

class BookARoomTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected        $tester;
    protected static $correctParams = [
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
    ];

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

    public function testCreateApiValidation()
    {
        $validation = (new Params([
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
        ]))->validate();
        self::assertTrue($validation);
    }

    public function testCreateReservation()
    {
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

        $reservation = Reservation::findOne('b2a1749c-1105-4311-aaf3-5112aef69b50');

        self::assertEquals($reservation_id, $reservation->id);
        self::assertEquals($room_id, $reservation->room_id);
        self::assertEquals($persons, $reservation->persons);
        self::assertEquals($checkin_date, $reservation->checkin_date);
        self::assertEquals($checkin_time, $reservation->checkin_time);
        self::assertEquals($duration, $reservation->duration);
        self::assertEquals($first_name, $reservation->first_name);
        self::assertEquals($last_name, $reservation->last_name);
        self::assertEquals($email, $reservation->email);
        self::assertEquals($comment, $reservation->comment);
    }

    public function testCreateReservationOnUnavailableDateShouldFail()
    {
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

        $reservationOK = Reservation::findOne('b2a1749c-1105-4311-aaf3-5112aef69b50');

        self::assertNotNull($reservationOK);

        $bookingOnUnavailableDate = new BookARoom(new Params([
            'reservation_id' => $reservation_id = 'fbb05461-3425-40e3-98fb-c3629d2b1c4f',
            'room_id'        => $room_id = 'ebb05461-3425-40e3-98fb-c3629d2b1c45',
            'persons'        => $persons = '2',
            'checkin_date'   => $checkin_date = '2019-02-14',
            'checkin_time'   => $checkin_time = '10:00:00',
            'duration'       => $duration = '3',
            'first_name'     => $first_name = 'Jan',
            'last_name'      => $last_name = 'Kowalski',
            'email'          => $email = 'marek@nowak.com',
        ]));
        $bookingOnUnavailableDate->handle();

        # reservation should not be created
        $reservationFAIL = Reservation::findOne('fbb05461-3425-40e3-98fb-c3629d2b1c4f');
        self::assertNull($reservationFAIL);

        # cannot create two reservations for the room on the interfered date
        $validationErrors = $bookingOnUnavailableDate->errors;
        self::assertArrayHasKey('checkin_date', $validationErrors);
    }

//    public function testCreateApiInvalidInput()
//    {
//        $badInput = [
//            'reservation_id' => 'niepoprawne id rezerwacji',
//            'room_id'        => -99999999999999,
//            'persons'        => 0,
//            'checkin_date'   => '1999-02-11',
//            'checkin_time'   => '13:13:00',
//            'duration'       => 0,
//            'first_name'     => 'Jan',
//            'last_name'      => 'Kowalski',
//            'email'          => 'to nie jest email',
//        ];
//
//        $validation = ($params = new Params($badInput))->validate();
//        $validationErrors = $params->errors;
//
//        self::assertFalse($validation);
//        self::assertArrayHasKey('reservation_id', $validationErrors);
//        self::assertArrayHasKey('room_id', $validationErrors);
//        self::assertArrayHasKey('persons', $validationErrors);
//        self::assertArrayHasKey('checkin_date', $validationErrors);
//        self::assertArrayHasKey('duration', $validationErrors);
//        self::assertArrayHasKey('email', $validationErrors);
//    }

}
