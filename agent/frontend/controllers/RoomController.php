<?php

namespace frontend\controllers;

use common\models\Room;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

class RoomController extends ProxyController
{
    public $modelClass = Room::class;

    public function actions()
    {
        $actions = parent::actions();
        unset(
            $actions['index'],
            $actions['view'],
            $actions['create'],
            $actions['update'],
            $actions['delete']
        );

        return $actions;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(string $id)
    {
        return json_decode($this->proxyRequest($this->hotelPortFromRoomId($id)), true);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionBook(string $id)
    {
        return json_decode($this->proxyRequest($this->hotelPortFromRoomId($id)), true);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $allRooms = array_map(function($hotel) {
            return json_decode($this->proxyRequest($hotel['port']), true);
        }, Yii::$app->params['hotelsNodes']);

        return new ArrayDataProvider(['allModels' => array_merge(...array_values($allRooms))]);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function hotelPortFromRoomId(string $id): int
    {
        return $this->hotelPortFromModelId(Room::class, $id);
    }
}
