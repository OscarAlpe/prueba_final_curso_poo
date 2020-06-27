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
 * @property Preguntas[] $preguntas
 * @property Categoriastest[] $categoriastests
 * @property Tests[] $tests
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

    /**
     * Gets query for [[Preguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntas()
    {
        return $this->hasMany(Preguntas::className(), ['id' => 'pregunta_id'])->viaTable('categoriaspregunta', ['categoria_id' => 'id']);
    }

    /**
     * Gets query for [[Categoriastests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriastests()
    {
        return $this->hasMany(Categoriastest::className(), ['categoria_id' => 'id']);
    }

    /**
     * Gets query for [[Tests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTests()
    {
        return $this->hasMany(Tests::className(), ['id' => 'test_id'])->viaTable('categoriastest', ['categoria_id' => 'id']);
    }
}
