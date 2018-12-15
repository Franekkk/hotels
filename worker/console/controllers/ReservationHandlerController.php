<?php
declare(strict_types=1);
/**
 * @author: Piotr Rozmarynowski
 * Project: hotels
 * Date: 15.12.18
 */

namespace console\controllers;

use common\models\Reservation;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\web\NotFoundHttpException;

class ReservationHandlerController extends Controller
{
    public function actionIndex()
    {
        try {
            $this->onIncomingReservations(function (string $reservationId) {
                $this->handleReservation($reservationId);
            });
        } catch (\Exception $e){
            error_log($e->getMessage());
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    private function onIncomingReservations(\Closure $callback)
    {
        $this->listenOnQueue('reservation', $callback);
    }

    private function handleReservation(string $reservationId)
    {
        ob_start();
        $statuses = [Reservation::STATUS_ACCEPTED, Reservation::STATUS_DECLINED];

        ob_end_flush();
        $this->stdout(" [x] Handling reservation: $reservationId ... ");
        ob_start();

        sleep(12);
        $acceptanceDecision = array_combine($statuses, $statuses)[(int) \random_int(1,2)];
        $this->updateReservationStatus(
            $this->hotelPortFromReservationId($reservationId),
            $reservationId,
            $acceptanceDecision
        );

        ob_end_flush();
        $this->stdout(Reservation::statusText($acceptanceDecision) . "\n", Console::BOLD);
        ob_start();
    }

    private function updateReservationStatus(int $hotelPort, string $reservationId, int $status)
    {
        $host = Yii::$app->params['nginx']['host'];
        file_get_contents("http://$host:$hotelPort/reservation/$reservationId/update-status/$status");
    }

    private function listenOnQueue(string $queue, \Closure $callback)
    {
        $config     = Yii::$app->params['queue'];
        $connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
        $channel    = $connection->channel();
        $channel->queue_declare($queue, false, false, false, false);

        $callback_ = function ($msg) use ($callback) {
            $callback((string) $msg->body);
        };

        $channel->basic_consume($queue, '', false, true, false, false, $callback_);

        while (count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function hotelPortFromReservationId(string $id): int
    {
        return $this->hotelPortFromModelId(Reservation::class, $id);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function hotelPortFromModelId(string $modelClass, string $id): int
    {
        $hodelsNodes = \Yii::$app->params['hotelsNodes'];
        foreach ($hodelsNodes as $hotel) {
            $room = $this->inSpecificDb(function () use ($id, $modelClass) {
                return $modelClass::findOne($id);
            }, "db_{$hotel['name']}");
            if ($room) {
                return (int)$hotel['port'];
            }
        }

        throw new NotFoundHttpException("Nie znaleziono pokoju $id");
    }

    protected function inSpecificDb($do, $dbHotel)
    {
        \Yii::$app->set('db', \Yii::$app->$dbHotel);
        $x = $do(\Yii::$app->$dbHotel);
        \Yii::$app->set('db', \Yii::$app->db_main);

        return $x;
    }
}