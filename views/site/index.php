<?php

/* @var $this yii\web\View */

Use yii\helpers\Html;

$this->title = 'Gestion de Test';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Gestion de Test</h1>

        <p class="lead">Podemos crear test de forma automatica y de forma manual</p>

        <p><?= Html::a('Instrucciones', ['/site/ayuda'], ['class'=>'btn btn-primary']) ?></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Importar preguntas</h2>

                <p>Desde esta opcion podeis importar las preguntas escritas en un documento de texto</p>

                <p><?= Html::a('importar preguntas', ['/site/importar'], ['class'=>'btn btn-primary']) ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Generar un test directamente</h2>

                <p>Esta opcion os permite crear un test en formato PDF indicando unicamente el numero de test</p>

                <p><?= Html::a('Generar test', ['/test'], ['class'=>'btn btn-primary']) ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Generar un test por categoria</h2>

                <p>Desde esta opcion podeis crear un test en formato pdf indicando las categorias de las preguntas</p>

                <p><?= Html::a('Generar test', ['/site/generarcategoria'], ['class'=>'btn btn-primary']) ?></p>
            </div>
        </div>

    </div>
</div>
