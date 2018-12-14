<?php

namespace frontend\controllers;

use yii\web\NotFoundHttpException;

class ProxyController extends \yii\rest\ActiveController
{
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

    protected function proxyRequest($nodePort): string
    {
        $r = \Yii::$app->request;
        if ($r->isPost) {
            $contentType = "application/x-www-form-urlencoded";
            $post     = $r->post();
            $postData = reset($post);
            $postData = json_decode($postData, true);
            $postData = [
                'header' => [
                    "Content-Type: $contentType"
                ],
                'content' => http_build_query($postData)
            ];
        } else {
            $postData = [];
        }

        return $this->httpCallStream(
            $r->method,
            "/{$r->pathInfo}?{$r->queryString}",
            $r->hostName,
            "nginx:$nodePort",
            $postData
        );
    }

    protected function httpCallStream(string $method, string $query, string $host, string $domain, array $extraOptions = []): string
    {
        $headersArray = array_merge([
            'X-Requested-With: XMLHttpRequest',
            "Host: $host",
        ], $extraOptions['header'] ?? []);
        unset($extraOptions['header']);
        $header  = implode("\r\n", $headersArray);
        $opts    = ['http' => array_merge([
            'method' => $method,
            'header' => $header,
        ], $extraOptions)];
        $context = stream_context_create($opts);
        $content = file_get_contents("http://$domain$query", false, $context);

        return $content;
    }
}
