<?php
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\Logs;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">
    
    <?php $form = ActiveForm::begin([
        'id' => 'search-form',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-0\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-6 control-label'],
        ],
    ]);
    echo $form->field($model, 'textForSearchBoard');?>
    <div class="form-group">
        <?= Html::submitButton('Выполнить поиск', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end();

    if (!empty($model->textForSearchBoard)) {
        $dataProvider = new ActiveDataProvider([
            'query' => Logs::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        if (!empty($dataProvider)) {
            echo GridView::widget([
                'dataProvider' => $dataProvider,
            ]);
        }
    } 
    
    // if (!empty($sql)) {
    //     $dataProvider = new ActiveDataProvider([
    //         'query' => $sql,
    //         'pagination' => [
    //             'pageSize' => 20,
    //         ],
    //     ]);
    //     if (!empty($dataProvider)) {
    //         echo GridView::widget([
    //             'dataProvider' => $dataProvider,
    //         ]);
    //     }
    // }
    ?>
    </div>
</div>
