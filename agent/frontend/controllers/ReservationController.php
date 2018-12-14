<?php

namespace frontend\controllers;

use common\models\Reservation;
use yii\web\NotFoundHttpException;

class ReservationController extends ProxyController
{
    public $modelClass = Reservation::class;

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
    public function actionView($id)
    {
        return json_decode($this->proxyRequest($this->hotelPortFromReservationId($id)), true);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionStatus($id)
    {
        return json_decode($this->proxyRequest($this->hotelPortFromReservationId($id)), true);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionCancel($id)
    {
        return json_decode($this->proxyRequest($this->hotelPortFromReservationId($id)), true);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionValidate($id)
    {
        return json_decode($this->proxyRequest($this->hotelPortFromReservationId($id)), true);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function hotelPortFromReservationId(string $id): int
    {
        return $this->hotelPortFromModelId(Reservation::class, $id);
    }
}
