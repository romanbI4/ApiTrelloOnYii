<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Logs;
use app\models\Search;
use app\models\Customers;
use app\models\Orders;
use Unirest\Request;
use linslin\yii2\curl;
use yii2tech\crontab\CronJob;
use yii2tech\crontab\CronTab;

class HelloController extends Controller
{


    public function actionCron($message) {
        $cronJob = new CronJob();
        $cronJob->min = '*/5';
        $cronJob->hour = '*';
        $cronJob->command = "php /var/www/trellotest.com/yii hello/index " . '"'. $message . '"';

        $cronTab = new CronTab();
        $cronTab->setJobs([
            $cronJob
        ]);
        $cronTab->apply();
    }
    
    public function actionIndex($modelSearch)
    {
        if ($modelSearch) {
            $arrNamesColumnTrello = self::getNameColumnsTrello();
            $arrayNames = [];
            $arrayIdLists = self::getIdListsInCardTrello();
            $arrayNameLists = self::getActionsTrello();
            for ($i = 0; $i < count($arrNamesColumnTrello); $i++) {
                $arrayNames[$i]['name'] = $arrNamesColumnTrello[$i]->name;
                if (strpos($arrayNames[$i]['name'], $modelSearch)) {
                    if (isset($arrNamesColumnTrello[$i]->id) && isset($arrNamesColumnTrello[$i+1]->idList)) {
                        $responseMove = self::MoveColumnTrello($arrNamesColumnTrello[$i]->idBoard, $arrNamesColumnTrello[$i+1]->idList, $arrNamesColumnTrello[$i]->idList);
                    }
                }
            }
            for ($j = 0; $j < count((array)$arrayNameLists); $j++) {
                if (isset($arrayNameLists[$j]->data->card->name) && isset($arrayNameLists[$j]->data->listBefore->name) && isset($arrayNameLists[$j]->data->listAfter->name)) {
                    if ($arrayNameLists[$j]->data->listBefore->name < $arrayNameLists[$j]->data->listAfter->name) {
                        $modelLogs = new Logs();
                        $modelLogs->board_old_id = $arrayNameLists[$j]->data->listBefore->id;
                        $modelLogs->board_new_id = $arrayNameLists[$j]->data->listAfter->id;
                        $modelLogs->message = 'Карточка с именем ' . $arrayNameLists[$j]->data->card->name . ' из списка ' . $arrayNameLists[$j]->data->listBefore->name . ' перемещена в cписок с именем ' . $arrayNameLists[$j]->data->listAfter->name;
                        $modelLogs->date = date('Y-m-d H:i:s', strtotime($arrayNameLists[$j]->date));
                        $modelLogs->save();
                    }
                }
            }
        }
    }

    public function getNameColumnsTrello()
    {
        $headers = array(
            'Accept' => 'application/json'
        );
        $query = array(
            'key' => '9d93d011ade3c5c4f4533e50cb55293b',
            'token' => '9a1576e541779c18463ef55b73ca77f0cd9f68c3fbfcfa527912f73b17318dd5'
        );
        $response = Request::get(
            'https://api.trello.com/1/boards/5faad17de5e461462d0d8b3d/cards',
            $headers,
            $query
        );
        return $response->body;
    }

    public function getIdListsInCardTrello() {
        $headers = array(
            'Accept' => 'application/json'
        );
        $query = array(
            'key' => '9d93d011ade3c5c4f4533e50cb55293b',
            'token' => '9a1576e541779c18463ef55b73ca77f0cd9f68c3fbfcfa527912f73b17318dd5'
        );
        $response = Request::get(
            'https://api.trello.com/1/boards/5faad17de5e461462d0d8b3d/lists',
            $headers,
            $query
        );
        return $response->body;
    }

    public function MoveColumnTrello($moveToIdBoard, $moveToIdList, $id) {
        $curl = new curl\Curl();
        $response = $curl->setPostParams([
            'key' => '9d93d011ade3c5c4f4533e50cb55293b',
            'token' => '9a1576e541779c18463ef55b73ca77f0cd9f68c3fbfcfa527912f73b17318dd5',
            'idBoard' => $moveToIdBoard,
            'idList' => $moveToIdList
        ])->post("https://api.trello.com/1/lists/$id/moveAllCards");
        return json_decode($response);
    }

    public function getActionsTrello() {
        $headers = array(
            'Accept' => 'application/json'
        );
        $query = array(
            'key' => '9d93d011ade3c5c4f4533e50cb55293b',
            'token' => '9a1576e541779c18463ef55b73ca77f0cd9f68c3fbfcfa527912f73b17318dd5'
        );
        $response = Request::get(
            'https://api.trello.com/1/boards/5faad17de5e461462d0d8b3d/actions',
            $headers,
            $query
        );
        return $response->body;
    }
}
