<?php

namespace common\tests\unit\models;

use common\models\Room;

class RoomTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

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

    public function testCreate()
    {
        (new Room([
            'id'       => $id = "5c7f9f7c-5649-495c-8211-bc038d580a7a",
            'name'     => $name = 'Pokój trójka',
            'hotel_id' => $hotel_id = '201bf54b-b23d-4c01-8cf9-fe8b5f47d74c',
            'price'    => $price = 432,
            'capacity' => $capacity = 3,
            'photo'    => $photo = '/static/img/room1.jpg',
        ]))->save();
        $room = Room::findOne($id);

        self::assertEquals($id, $room->id);
        self::assertEquals($name, $room->name);
        self::assertEquals($hotel_id, $room->hotel_id);
        self::assertEquals($price, $room->price);
        self::assertEquals($capacity, $room->capacity);
        self::assertEquals($photo, $room->photo);
    }

    public function testRead()
    {
        $room = Room::findOne("ebb05461-3425-40e3-98fb-c3629d2b1c45");

        self::assertEquals("ebb05461-3425-40e3-98fb-c3629d2b1c45", $room->id);
        self::assertEquals('Dwójka', $room->name);
        self::assertEquals('201bf54b-b23d-4c01-8cf9-fe8b5f47d74c', $room->hotel_id);
        self::assertEquals(123, $room->price);
        self::assertEquals(2, $room->capacity);
        self::assertEquals('/static/img/room2.jpg', $room->photo);
    }

    public function testUpdate()
    {
        $room = Room::findOne("ebb05461-3425-40e3-98fb-c3629d2b1c45");

        $room->name = "Nowa nazwa pokoju";
        $room->price = 999;
        $room->save(true, ['name', 'price']);

        $roomFresh = Room::findOne("ebb05461-3425-40e3-98fb-c3629d2b1c45");

        self::assertEquals("Nowa nazwa pokoju", $roomFresh->name);
        self::assertEquals(999, $roomFresh->price);
    }

    public function testDelete()
    {
        $room = Room::findOne("ebb05461-3425-40e3-98fb-c3629d2b1c45");
        $room->delete();

        $roomFresh = Room::findOne("ebb05461-3425-40e3-98fb-c3629d2b1c45");

        self::assertEquals(null, $roomFresh);
    }

    public function testCreateWithWrongInputShouldFail()
    {
        ($impossibleRoom = new Room([
            'id'       => $id = "5c7f9f7c-5649-495c-8211-bc038d580a7a",
            'name'     => $name = 'Poprawna nazwa pokoju',
            'hotel_id' => $hotel_id = 'NIEPOPRAWNE ID HOTELU',
            'price'    => $price = -999,
            'capacity' => $capacity = 0,
        ]))->save();

        $validationErrors = $impossibleRoom->errors;

        $roomThatShouldNotExist = Room::findOne($id);

        self::assertNull($roomThatShouldNotExist);
        self::assertArrayHasKey('hotel_id', $validationErrors);
        self::assertArrayHasKey('price', $validationErrors);
        self::assertArrayHasKey('capacity', $validationErrors);
    }
}
