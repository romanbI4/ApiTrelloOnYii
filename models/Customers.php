<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Customers".
 *
 * @property string $cust_id
 * @property string $cust_name
 * @property string|null $cust_address
 * @property string|null $cust_city
 * @property string|null $cust_state
 * @property string|null $cust_zip
 * @property string|null $cust_country
 * @property string|null $cust_contact
 * @property string|null $cust_email
 */
class Customers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cust_id', 'cust_name'], 'required'],
            [['cust_id', 'cust_zip'], 'string', 'max' => 10],
            [['cust_name', 'cust_address', 'cust_city', 'cust_country', 'cust_contact'], 'string', 'max' => 50],
            [['cust_state'], 'string', 'max' => 5],
            [['cust_email'], 'string', 'max' => 255],
            [['cust_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cust_id' => 'Cust ID',
            'cust_name' => 'Cust Name',
            'cust_address' => 'Cust Address',
            'cust_city' => 'Cust City',
            'cust_state' => 'Cust State',
            'cust_zip' => 'Cust Zip',
            'cust_country' => 'Cust Country',
            'cust_contact' => 'Cust Contact',
            'cust_email' => 'Cust Email',
        ];
    }
}
