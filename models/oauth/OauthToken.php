<?php

namespace app\models\oauth;

use Yii;

/**
 * This is the model class for table "oauth_token".
 *
 * @property integer $id
 * @property string $client_type
 * @property string $token
 * @property string $note
 * @property string $valid_to
 * @property string $updated_time
 * @property string $createdt_time
 */
class OauthToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oauth_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['valid_to', 'updated_time', 'createdt_time'], 'safe'],
            [['client_type'], 'string', 'max' => 20],
            [['token'], 'string', 'max' => 500],
            [['note'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_type' => 'Client Type',
            'token' => 'Token',
            'note' => 'Note',
            'valid_to' => 'Valid To',
            'updated_time' => 'Updated Time',
            'createdt_time' => 'Createdt Time',
        ];
    }
}
