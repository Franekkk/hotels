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
                return json_decode($this->proxyRequest($hotel['port']), true);
            }
        }
    }

    public function actionIndex()
    {
        $allRooms = array_map(function($hotel) {
            return json_decode($this->proxyRequest($hotel['port']), true);
        }, \Yii::$app->params['hotelsNodes']);

        return new ArrayDataProvider(['allModels' => array_merge(...array_values($allRooms))]);
    }

    private function inSpecificDb($do, $dbHotel)
    {
        \Yii::$app->set('db', \Yii::$app->$dbHotel);
        $x = $do(\Yii::$app->$dbHotel);
        \Yii::$app->set('db', \Yii::$app->db_main);
        return $x;
    }

    private function proxyRequest($nodePort): string
    {
        $r = \Yii::$app->request;
        return $this->httpCallStream(
            $r->method,
            "/{$r->pathInfo}?{$r->queryString}",
            $r->hostName,
            "nginx:$nodePort"
        );
    }

    protected function httpCallStream(string $method, string $query, string $host, string $domain, array $extraOptions = []): string
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
        $content = file_get_contents("http://$domain$query", false, $context);
        return $content;
    }
}
