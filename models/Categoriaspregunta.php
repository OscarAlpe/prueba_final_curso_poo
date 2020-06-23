<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categoriaspregunta".
 *
 * @property int $id
 * @property int $pregunta_id
 * @property int $categoria_id
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
}
