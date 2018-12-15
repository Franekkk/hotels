<?php

namespace frontend\controllers;

use frontend\domain\BookARoom;
use frontend\domain\BookARoom\Params;
use frontend\models\Reservation;
use Yii;
use yii\web\NotFoundHttpException;

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

    /**
     * @throws NotFoundHttpException
     */
    public function actionUpdateStatus($id, $status)
    {
        if ($reservation = Reservation::findOne($id)) {
            $reservation->status = $status;
            if ($reservation->save()) {
                return $reservation;
            }

            return ['errors' => $reservation->errors];
        }
        throw new NotFoundHttpException("Nie znaleziono rezerwacji $id");
    }

    public function actionValidate()
    {
        $booking = new BookARoom(new Params(Yii::$app->getRequest()->getBodyParams()));

        return (bool)$booking->validateReservation();

        Yii::$app->getResponse()->setStatusCode(400);

        return $booking->errors;
    }
}
