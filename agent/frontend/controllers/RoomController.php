<?php

namespace frontend\controllers;

use common\models\Room;
use common\models\RoomSearch;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

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

    public static function allowedDomains() {
        return ['*',];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [

            // For cross-domain AJAX request
            'corsFilter'  => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    // restrict access to domains:
                    'Origin'                           => static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['POST', 'GET'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
                ],
            ],

        ]);
    }

    public function actionView($id)
    {
        $hodelsNodes = \Yii::$app->params['hotelsNodes'];
        foreach ($hodelsNodes as $hotel) {
            $room = $this->inSpecificDb(function () use($id) {
               return Room::findOne($id);
            }, "db_{$hotel['name']}");
            if ($room) {
                $this->proxyRequest($hotel['port']);
            }
        }
    }

    public function actionIndex()
    {
        $hotelsNodes = \Yii::$app->params['hotelsNodes'];

        $findRooms = function($db) {
            $filter = new ActiveDataFilter([
                'searchModel' => RoomSearch::class
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
            return (new ActiveDataProvider([
                'query' => $query,
                'db' => $db
            ]));
        };

        $allRooms = array_map(function($hotel) use ($findRooms) {
            return $this->inSpecificDb($findRooms, "db_{$hotel['name']}")->getModels();
        }, $hotelsNodes);

        return new ArrayDataProvider(['allModels' => array_merge(...array_values($allRooms))]);
    }

    private function inSpecificDb($do, $dbHotel)
    {
        \Yii::$app->set('db', \Yii::$app->$dbHotel);
        return $do(\Yii::$app->$dbHotel);
        \Yii::$app->set('db', \Yii::$app->db_main);
        return $x;
    }

    private function proxyRequest($nodePort)
    {
        $r = \Yii::$app->request;
        $this->httpCallStream(
            $r->method,
            "/{$r->pathInfo}{$r->queryString}",
            $r->hostName,
            "nginx:$nodePort"
        );
    }

    protected function httpCallStream(string $method, string $query, string $host, string $domain, array $extraOptions = []): void
    {
        $header  = implode("\r\n", [
            'X-Requested-With: XMLHttpRequest',
            "Host: $host",
        ]);
        $opts    = array_merge(['http' => [
            'method' => $method,
            'header' => $header,
        ]], $extraOptions);
        $context = stream_context_create($opts);
        readfile("http://$domain$query", false, $context);
    }
}
