<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property string|null $board_old_id
 * @property string $board_new_id
 * @property string $message
 * @property string $date
 */
class Logs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['board_new_id', 'message', 'date'], 'required'],
            [['date'], 'safe'],
            [['message', 'board_old_id', 'board_new_id'], 'string', 'max' => 255],
        ];
    }

}
