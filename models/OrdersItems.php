<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "OrderItems".
 *
 * @property int $order_num
 * @property int $order_item
 * @property string $prod_id
 * @property int $quantity
 * @property float $item_price
 */
class OrderItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'OrderItems';
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
            [['order_num', 'order_item', 'prod_id', 'quantity', 'item_price'], 'required'],
            [['order_num', 'order_item', 'quantity'], 'integer'],
            [['item_price'], 'number'],
            [['prod_id'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_num' => 'Order Num',
            'order_item' => 'Order Item',
            'prod_id' => 'Prod ID',
            'quantity' => 'Quantity',
            'item_price' => 'Item Price',
        ];
    }
}
