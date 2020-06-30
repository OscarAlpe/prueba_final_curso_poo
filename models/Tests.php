<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tests".
 *
 * @property int $id
 * @property string|null $descripcion
 * @property string|null $materia
 * @property string|null $fecha
 * @property string|null $titulo
 * @property string|null $titulo_impreso
 *
 * @property Categoriastest[] $categoriastests
 * @property Categorias[] $categorias
 * @property Preguntas[] $preguntas
 */
class Tests extends \yii\db\ActiveRecord
{
    public $fichero;
    public $npreguntas;
    public $categoria;
    public $pregunta;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha'], 'safe'],
            [['fichero'], 'file', 'skipOnEmpty' => false],
            [['descripcion', 'materia', 'npreguntas'], 'required'],
            [['npreguntas'], 'number'],
            [['categoria[]'], 'boolean'],
            [['pregunta[]'], 'boolean'],
            [['descripcion', 'materia', 'titulo', 'titulo_impreso'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'materia' => 'Indica el nombre del programa del que es el test',
            'fecha' => 'Fecha',
            'titulo' => 'Titulo',
            'titulo_impreso' => 'Titulo Impreso',
            'fichero' => 'Selecciona el test a importar',
            'npreguntas' => 'Indica cuantas preguntas quieres que tenga el test como máximo',
            'categoria' => 'Categorias',
            'pregunta' => 'Listado Preguntas',
        ];
    }

    /**
     * Gets query for [[Categoriastests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriastests()
    {
        return $this->hasMany(Categoriastest::className(), ['test_id' => 'id']);
    }

    /**
     * Gets query for [[Categorias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategorias()
    {
        return $this->hasMany(Categorias::className(), ['id' => 'categoria_id'])->viaTable('categoriastest', ['test_id' => 'id']);
    }

    /**
     * Gets query for [[Preguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntas()
    {
        return $this->hasMany(Preguntas::className(), ['test_id' => 'id']);
    }
}
