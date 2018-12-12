<?php

namespace frontend\controllers;

use frontend\models\Room;
use frontend\models\RoomSearch;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;

class RoomController extends \yii\rest\ActiveController
{
    public $modelClass = Room::class;

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

    public function actionIndex()
    {
        $filter = new ActiveDataFilter([
            'searchModel' => RoomSearch::class
        ]);

        $filterCondition = null;

        if ($filter->load(\Yii::$app->request->get())) {
            $filterCondition = $filter->build();
            if ($filterCondition === false) {
                // Serializer would get errors out of it
                return $filter;
            }
        }

        $query = Room::find();
        if ($filterCondition !== null) {
            $query->andWhere($filterCondition);
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
