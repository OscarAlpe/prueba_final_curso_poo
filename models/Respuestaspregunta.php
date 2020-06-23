<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "respuestaspregunta".
 *
 * @property int $id
 * @property int $pregunta_id
 * @property int $respuesta_id
 */
class Respuestaspregunta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'respuestaspregunta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pregunta_id', 'respuesta_id'], 'required'],
            [['pregunta_id', 'respuesta_id'], 'integer'],
            [['pregunta_id', 'respuesta_id'], 'unique', 'targetAttribute' => ['pregunta_id', 'respuesta_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pregunta_id' => 'Pregunta ID',
            'respuesta_id' => 'Respuesta ID',
        ];
    }
}
