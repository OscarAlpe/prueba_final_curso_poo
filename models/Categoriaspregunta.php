<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categoriaspregunta".
 *
 * @property int $id
 * @property int $pregunta_id
 * @property int $categoria_id
 *
 * @property Categorias $categoria
 * @property Preguntas $pregunta
 */
class Categoriaspregunta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categoriaspregunta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pregunta_id', 'categoria_id'], 'required'],
            [['pregunta_id', 'categoria_id'], 'integer'],
            [['pregunta_id', 'categoria_id'], 'unique', 'targetAttribute' => ['pregunta_id', 'categoria_id']],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorias::className(), 'targetAttribute' => ['categoria_id' => 'id']],
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
            'categoria_id' => 'Categoria ID',
        ];
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categorias::className(), ['id' => 'categoria_id']);
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
}
