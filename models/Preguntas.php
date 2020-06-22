<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preguntas".
 *
 * @property int $id
 * @property string|null $pregunta
 * @property int|null $imagen_id
 *
 * @property Categoriaspregunta[] $categoriaspreguntas
 * @property Categorias[] $categorias
 * @property Imagenes $imagen
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
            [['imagen_id'], 'integer'],
            [['pregunta'], 'string', 'max' => 255],
            [['imagen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Imagenes::className(), 'targetAttribute' => ['imagen_id' => 'id']],
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
}
