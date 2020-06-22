<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "respuestas".
 *
 * @property int $id
 * @property int|null $pregunta_id
 * @property string|null $respuesta
 * @property int|null $correcta
 *
 * @property Respuestaspregunta[] $respuestaspreguntas
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
            [['pregunta_id', 'correcta'], 'integer'],
            [['respuesta'], 'string', 'max' => 255],
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
     * Gets query for [[Respuestaspreguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestaspreguntas()
    {
        return $this->hasMany(Respuestaspregunta::className(), ['respuesta_id' => 'id']);
    }
}
