<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preguntas".
 *
 * @property int $id
 * @property string|null $pregunta
 * @property int $test_id
 * @property int|null $imagen_id
 *
 * @property Categoriaspregunta[] $categoriaspreguntas
 * @property Categorias[] $categorias
 * @property Imagenes $imagen
 * @property Tests $test
 * @property Preguntastest[] $preguntastests
 * @property Tests[] $tests
 * @property Respuestas[] $respuestas
 * @property Respuestaspregunta[] $respuestaspreguntas
 * @property Respuestas[] $respuestas0
 */
class Preguntas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'preguntas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['test_id'], 'required'],
            [['test_id', 'imagen_id'], 'integer'],
            [['pregunta'], 'string', 'max' => 255],
            [['imagen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Imagenes::className(), 'targetAttribute' => ['imagen_id' => 'id']],
            [['test_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tests::className(), 'targetAttribute' => ['test_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pregunta' => 'Pregunta',
            'test_id' => 'Test ID',
            'imagen_id' => 'Imagen ID',
        ];
    }

    /**
     * Gets query for [[Categoriaspreguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriaspreguntas()
    {
        return $this->hasMany(Categoriaspregunta::className(), ['pregunta_id' => 'id']);
    }

    /**
     * Gets query for [[Categorias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategorias()
    {
        return $this->hasMany(Categorias::className(), ['id' => 'categoria_id'])->viaTable('categoriaspregunta', ['pregunta_id' => 'id']);
    }

    /**
     * Gets query for [[Imagen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImagen()
    {
        return $this->hasOne(Imagenes::className(), ['id' => 'imagen_id']);
    }

    /**
     * Gets query for [[Test]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTest()
    {
        return $this->hasOne(Tests::className(), ['id' => 'test_id']);
    }

    /**
     * Gets query for [[Preguntastests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntastests()
    {
        return $this->hasMany(Preguntastest::className(), ['pregunta_id' => 'id']);
    }

    /**
     * Gets query for [[Tests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTests()
    {
        return $this->hasMany(Tests::className(), ['id' => 'test_id'])->viaTable('preguntastest', ['pregunta_id' => 'id']);
    }

    /**
     * Gets query for [[Respuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestas()
    {
        return $this->hasMany(Respuestas::className(), ['pregunta_id' => 'id']);
    }

    /**
     * Gets query for [[Respuestaspreguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestaspreguntas()
    {
        return $this->hasMany(Respuestaspregunta::className(), ['pregunta_id' => 'id']);
    }

    /**
     * Gets query for [[Respuestas0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestas0()
    {
        return $this->hasMany(Respuestas::className(), ['id' => 'respuesta_id'])->viaTable('respuestaspregunta', ['pregunta_id' => 'id']);
    }
}
