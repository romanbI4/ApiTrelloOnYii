<?php

namespace app\models;

use yii\base\Model;

class Search extends Model
{
    public $textForSearchBoard;
    
    public function rules()
    {
        return [
            [['textForSearchBoard'], 'string'],
            [['textForSearchBoard'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'textForSearchBoard' => 'Текст поиска:',
        ];
    }
}