<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categorias".
 *
 * @property int $id
 * @property string|null $categoria
 *
 * @property Categoriaspregunta[] $categoriaspreguntas
 */
class Categorias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categorias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoria'], 'string', 'max' => 255],
            [['categoria'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoria' => 'Categoria',
        ];
    }

    /**
     * Gets query for [[Categoriaspreguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriaspreguntas()
    {
        return $this->hasMany(Categoriaspregunta::className(), ['categoria_id' => 'id']);
    }
}
