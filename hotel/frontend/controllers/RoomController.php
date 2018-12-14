<?php

namespace frontend\controllers;

use common\models\Room;
use common\models\RoomSearch;
use frontend\domain\BookARoom;
use frontend\domain\BookARoom\Params;
use thamtech\uuid\helpers\UuidHelper;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class RoomController extends \yii\rest\ActiveController
//class RoomController extends Controller
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

    public static function allowedDomains()
    {
        return ['*',];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [

            // For cross-domain AJAX request
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    // restrict access to domains:
                    'Origin'                           => static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
                ],
            ],
        ]);
    }

    public function actionView(string $id)
    {
        return Room::findOne($id);
    }

    public function actionIndex()
    {
        $filter = new ActiveDataFilter([
            'searchModel' => RoomSearch::class,
        ]);

        $filterCondition = null;

        if ($filter->load(\Yii::$app->request->get())) {
            $filterCondition = $filter->build();
            if ($filterCondition === false) {
                return new ArrayDataProvider([]);
            }
        }

        $query = Room::find()->with('hotel');
        if ($filterCondition !== null) {
            $query->andWhere($filterCondition);
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionBook(string $id)
    {
        $params = Yii::$app->request->post();
        $params['reservation_id'] = UuidHelper::uuid();
        $params['room_id'] = $id;

        $booking = new BookARoom(new Params($params));
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
//        Yii::$app->getResponse()->setStatusCode(400);
        return $booking->errors;
    }
}
