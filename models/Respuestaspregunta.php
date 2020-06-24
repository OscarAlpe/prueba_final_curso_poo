<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "respuestaspregunta".
 *
 * @property int $id
 * @property int $pregunta_id
 * @property int $respuesta_id
 *
 * @property Preguntas $pregunta
 * @property Respuestas $respuesta
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
            [['pregunta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Preguntas::className(), 'targetAttribute' => ['pregunta_id' => 'id']],
            [['respuesta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Respuestas::className(), 'targetAttribute' => ['respuesta_id' => 'id']],
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

    /**
     * Gets query for [[Pregunta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPregunta()
    {
        return $this->hasOne(Preguntas::className(), ['id' => 'pregunta_id']);
    }

    /**
     * Gets query for [[Respuesta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespuesta()
    {
        return $this->hasOne(Respuestas::className(), ['id' => 'respuesta_id']);
    }
}
