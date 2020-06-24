<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "respuestas".
 *
 * @property int $id
 * @property int $pregunta_id
 * @property string|null $respuesta
 * @property int|null $correcta
 *
 * @property Preguntas $pregunta
 * @property Respuestaspregunta[] $respuestaspreguntas
 * @property Preguntas[] $preguntas
 */
class Respuestas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'respuestas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pregunta_id'], 'required'],
            [['pregunta_id', 'correcta'], 'integer'],
            [['respuesta'], 'string', 'max' => 255],
            [['pregunta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Preguntas::className(), 'targetAttribute' => ['pregunta_id' => 'id']],
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
            'respuesta' => 'Respuesta',
            'correcta' => 'Correcta',
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
     * Gets query for [[Respuestaspreguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestaspreguntas()
    {
        return $this->hasMany(Respuestaspregunta::className(), ['respuesta_id' => 'id']);
    }

    /**
     * Gets query for [[Preguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntas()
    {
        return $this->hasMany(Preguntas::className(), ['id' => 'pregunta_id'])->viaTable('respuestaspregunta', ['respuesta_id' => 'id']);
    }
}
