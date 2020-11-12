<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Vendors".
 *
 * @property string $vend_id
 * @property string $vend_name
 * @property string|null $vend_address
 * @property string|null $vend_city
 * @property string|null $vend_state
 * @property string|null $vend_zip
 * @property string|null $vend_country
 */
class Vendors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Vendors';
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
            [['vend_id', 'vend_name'], 'required'],
            [['vend_id', 'vend_zip'], 'string', 'max' => 10],
            [['vend_name', 'vend_address', 'vend_city', 'vend_country'], 'string', 'max' => 50],
            [['vend_state'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'vend_id' => 'Vend ID',
            'vend_name' => 'Vend Name',
            'vend_address' => 'Vend Address',
            'vend_city' => 'Vend City',
            'vend_state' => 'Vend State',
            'vend_zip' => 'Vend Zip',
            'vend_country' => 'Vend Country',
        ];
    }
}
