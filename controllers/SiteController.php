<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Logs;
use app\models\Search;
use app\models\Customers;
use app\models\Orders;
use Unirest\Request;
use linslin\yii2\curl;
use yii\db\Query;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $modelSearch = new Search();
        if ($modelSearch->load(Yii::$app->request->post()) && $modelSearch->validate()) {
            $arr = self::getNameColumnsTrello();
            $arrayNames = [];
            for ($i = 0; $i < count($arr); $i++) {
                $arrayNames[$i]['name'] = $arr[$i]->name;
                if (strpos($arrayNames[$i]['name'], $modelSearch->textForSearchBoard)) {
                    $responseId[$i] = self::CreateBoardTrello($arrayNames[$i]['name']);
                    if (isset($responseId[$i]->name)) {
                        $modelLogs = new Logs();
                        $modelLogs->board_old_id = null;
                        $modelLogs->board_new_id = $responseId[$i]->id;
                        $modelLogs->message = 'Создана Доска с именем: ' . $responseId[$i]->name;
                        $modelLogs->date = date('Y-m-d H:i:s');
                        $modelLogs->save();
                    }
                    if (isset($responseId[$i]->id) && isset($arr[$i]->id)) {
                        $responseMove[$i] = self::MoveColumnTrello($responseId[$i]->id, $arr[$i]->id);
                    }
                    if (isset($responseMove[$i]->name) && isset($responseId[$i]->name)) {
                        $modelLogs = new Logs();
                        $modelLogs->board_old_id = $arr[$i]->id;
                        $modelLogs->board_new_id = $responseId[$i]->id;
                        $modelLogs->message = 'Колонка с именем ' . $responseId[$i]->name . ' перемещена в доску с именем ' . $responseMove[$i]->name;
                        $modelLogs->date = date('Y-m-d H:i:s');
                        $modelLogs->save();
                    }
                }
            }
        }

        $query = (new Query())
           ->select('Customers.cust_name, Customers.cust_address, Customers.cust_city, Orders.order_date, Orders.cust_id, OrderItems.order_item, OrderItems.quantity, OrderItems.item_price, Products.prod_name, Products.prod_price, Products.prod_desc, Vendors.vend_name, Vendors.vend_address, Vendors.vend_city, Vendors.vend_state, Vendors.vend_zip, Vendors.vend_country')
             ->from(Customers::tableName())
             ->leftJoin('Orders', 'Customers.cust_id = Orders.cust_id')
             ->leftJoin('OrderItems', 'Orders.order_num = OrderItems.order_num')
             ->leftJoin('Products', 'OrderItems.prod_id = Products.prod_id')
             ->leftJoin('Vendors', 'Orders.order_num = Products.vend_id = Vendors.vend_id')
             ->where(['>', 'Customers.cust_id', '1000000001'])
             ->andWhere(['<', 'Customers.cust_id', '1000000004'])
             ->andWhere(['=', 'Orders.order_num', '20006'])
             ->groupBy('Customers.cust_id');
        
        return $this->render('index', [
            'model' => $modelSearch,
            'modelQueryHard' => $query 
        ]);
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
            'https://api.trello.com/1/boards/5faad17de5e461462d0d8b3d/lists',
            $headers,
            $query
        );
        return $response->body;
    }

    public function CreateBoardTrello($name) {
        $headers = array(
            'Accept' => 'application/json'
        );
        $query = array(
            'key' => '9d93d011ade3c5c4f4533e50cb55293b',
            'token' => '9a1576e541779c18463ef55b73ca77f0cd9f68c3fbfcfa527912f73b17318dd5',
            'name' => $name,
            'prefs_permissionLevel' => 'public'
        );        
        $response = Request::post(
            'https://api.trello.com/1/boards/',
            $headers,
            $query
          );
        return $response->body;
    }

    public function MoveColumnTrello($MoveToId, $FromWhereId) {
        $curl = new curl\Curl();
        $response = $curl->setPostParams([
            'key' => '9d93d011ade3c5c4f4533e50cb55293b',
            'token' => '9a1576e541779c18463ef55b73ca77f0cd9f68c3fbfcfa527912f73b17318dd5',
            'value' => $MoveToId,
        ])->put("https://api.trello.com/1/lists/$FromWhereId/idBoard");
        return json_decode($response);
    }
}
//     /**
//      * Login action.
//      *
//      * @return Response|string
//      */
//     public function actionLogin()
//     {
//         if (!Yii::$app->user->isGuest) {
//             return $this->goHome();
//         }

//         $model = new LoginForm();
//         if ($model->load(Yii::$app->request->post()) && $model->login()) {
//             return $this->goBack();
//         }

//         $model->password = '';
//         return $this->render('login', [
//             'model' => $model,
//         ]);
//     }

//     /**
//      * Logout action.
//      *
//      * @return Response
//      */
//     public function actionLogout()
//     {
//         Yii::$app->user->logout();

//         return $this->goHome();
//     }

//     /**
//      * Displays contact page.
//      *
//      * @return Response|string
//      */
//     public function actionContact()
//     {
//         $model = new ContactForm();
//         if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
//             Yii::$app->session->setFlash('contactFormSubmitted');

//             return $this->refresh();
//         }
//         return $this->render('contact', [
//             'model' => $model,
//         ]);
//     }

//     /**
//      * Displays about page.
//      *
//      * @return string
//      */
//     public function actionAbout()
//     {
//         return $this->render('about');
//     }
// }
