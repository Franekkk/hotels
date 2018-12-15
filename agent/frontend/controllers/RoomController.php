<?php

namespace frontend\controllers;

use common\models\Room;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
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
        $result = json_decode($this->proxyRequest($this->hotelPortFromRoomId($id)), true);
        if (isset($result['id'])) {
            $this->sendMessageToQueue('reservation', $result['id']);
        }

        return $result;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $allRooms = array_map(function ($hotel) {
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

    private function sendMessageToQueue(string $queue, $msg): void
    {
        $config     = Yii::$app->params['queue'];
        $connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
        $channel    = $connection->channel();
        $channel->queue_declare($queue, false, false, false, false);
        $channel->basic_publish(new AMQPMessage($msg), '', $queue);
        $channel->close();
        $connection->close();
    }
}
