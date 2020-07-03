<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tests".
 *
 * @property int $id
 * @property string $descripcion
 * @property string $materia
 * @property string $fecha
 * @property string $titulo
 * @property string $titulo_impreso
 *
 * @property Categoriastest[] $categoriastests
 * @property Categorias[] $categorias
 * @property Testspreguntas[] $testspreguntas
 * @property Preguntas[] $preguntas
 */
class Tests extends \yii\db\ActiveRecord
{
    public $fichero;
    public $npreguntas;
    public $categorias = [];
    public $preguntas = [];

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
        $f = "";
        $required = "";

        if (Yii::$app->controller->action->id == "importar") {
          $f = [['fichero'], 'file', 'skipOnEmpty' => false];
          $required = [['descripcion', 'materia', 'fecha', 'titulo', 'titulo_impreso'], 'required'];
        } else {
          $f = [['fichero'], 'file', 'skipOnEmpty' => true];
          $required = [['descripcion', 'materia', 'fecha', 'titulo', 'titulo_impreso', 'npreguntas'], 'required'];
        }

        return [
            $required,
            [['fecha'], 'safe'],
            $f,
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
            'materia' => 'Materia',
            'fecha' => 'Fecha',
            'titulo' => 'Titulo',
            'titulo_impreso' => 'Titulo Impreso',
            'fichero' => 'Selecciona el test a importar',
            'npreguntas' => 'Indica cuantas preguntas quieres que tenga el test como máximo',
            'categorias' => 'Categorias',
            'preguntas' => 'Listado Preguntas',
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
     * Gets query for [[Testspreguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestspreguntas()
    {
        return $this->hasMany(Testspreguntas::className(), ['test_id' => 'id']);
    }

    /**
     * Gets query for [[Preguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntas()
    {
        return $this->hasMany(Preguntas::className(), ['id' => 'pregunta_id'])->viaTable('testspreguntas', ['test_id' => 'id']);
    }
}
