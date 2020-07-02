<?php
use yii\bootstrap\Carousel;

echo Carousel::widget([
    'items' => [
        [
            'content' => \yii\helpers\Html::img('@web/imgs/test3.jpg'),
            'caption' => '<div><h4>Crear un archivo de texto (formato txt)</h4><p>Este fichero de texto debe estar en formato ANSI</p></div>',
        ],
        [
            'content' => \yii\helpers\Html::img('@web/imgs/test2.jpg'),
            'caption' => '<div><h4>Colocar cada pregunta y las posibles respuestas</h4><p>La respuesta correcta debe terminar con XXXXX</p></div>',
        ],
        [
            'content' => \yii\helpers\Html::img('@web/imgs/test1.jpg'),
            'caption' => '<div><h4>En cada pregunta y entre dos corchetes debeis colocar las categorias de las preguntas<h4><p>Si hay mas de una categoria colocar todas separadas por comas</p></div>',
        ],
        [
            'content' => \yii\helpers\Html::img('@web/imgs/test3.jpg'),
            'caption' => '<div><h4>Las preguntas pueden tener fotos asociadas</h4><p>Para ello debeis subir primero la foto y colocar su numero en la pregunta detras de las categorias entre llaves</p></div>',
        ],
    ],
]);