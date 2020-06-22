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
 * @property Preguntastest[] $preguntastests
 */
class Tests extends \yii\db\ActiveRecord
{
    public $fichero;
    
    
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
            [['fichero'], 'file'],
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
            'descripcion' => 'Escribe una pequeña descripción del test',
            'materia' => 'Indica el nombre del programa que es el test',
            'fecha' => 'Fecha',
            'titulo' => 'Titulo',
            'titulo_impreso' => 'Titulo Impreso',
        ];
    }

    /**
     * Gets query for [[Preguntastests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntastests()
    {
        return $this->hasMany(Preguntastest::className(), ['test_id' => 'id']);
    }
}
