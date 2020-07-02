<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tests-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tests', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'descripcion',
            'materia',
            'fecha',
            'titulo',
            //'titulo_impreso',

            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{view}{update}{delete}{generar}',
              'buttons' => [ 'generar' => function ($url, $model) {
                 return Html::a('<span class="glyphicon glyphicon-print"></span>',
                                 [
                                   'site/generardirectamente', 'numero' => $model->id],
                                   ['target' => '_blank'] ); },
                                 ]
            ], 
        ],
    ]); ?>


</div>
