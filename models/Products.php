<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Products".
 *
 * @property string $prod_id
 * @property string $vend_id
 * @property string $prod_name
 * @property float $prod_price
 * @property string|null $prod_desc
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Products';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prod_id', 'vend_id', 'prod_name', 'prod_price'], 'required'],
            [['prod_price'], 'number'],
            [['prod_desc'], 'string'],
            [['prod_id', 'vend_id'], 'string', 'max' => 10],
            [['prod_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prod_id' => 'Prod ID',
            'vend_id' => 'Vend ID',
            'prod_name' => 'Prod Name',
            'prod_price' => 'Prod Price',
            'prod_desc' => 'Prod Desc',
        ];
    }
}
