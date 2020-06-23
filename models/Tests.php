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
 * @property Preguntas[] $preguntas
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
            'descripcion' => 'Descripcion',
            'materia' => 'Materia',
            'fecha' => 'Fecha',
            'titulo' => 'Titulo',
            'titulo_impreso' => 'Titulo Impreso',
            'fichero' => 'Selecciona el test a importar',
        ];
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
