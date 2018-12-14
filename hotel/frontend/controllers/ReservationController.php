<?php

namespace frontend\controllers;

use frontend\domain\BookARoom;
use frontend\domain\BookARoom\Params;
use frontend\models\Reservation;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ReservationController extends \yii\rest\ActiveController
{
    public $modelClass = Reservation::class;

    public function actions()
    {
        $actions = parent::actions();
        unset(
            $actions['index'],
            $actions['create'],
            $actions['update'],
            $actions['delete']
        );

        return $actions;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionStatus($id)
    {
        if (!$reservation = Reservation::findOne($id)) {
            throw new NotFoundHttpException("Nie znaleziono rezerwacji $id");
        }

        return ['status' => $reservation->status];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionCancel($id)
    {
        if (!$reservation = Reservation::findOne($id)) {
            throw new NotFoundHttpException("Nie znaleziono rezerwacji $id");
        }
        $reservation->status = Reservation::STATUS_CANCELED;
        $reservation->save();

        return $reservation;
    }

    public function actionCreate()
    {
        $booking = new BookARoom(new Params(Yii::$app->getRequest()->getBodyParams()));
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $reservation = $booking->handle();
            if ($reservation) {
                $transaction->commit();
                return $reservation;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        Yii::$app->getResponse()->setStatusCode(400);
        return $booking->errors;
    }
}
