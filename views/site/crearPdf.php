<?php

/* @var $this yii\web\View */

use yii\helpers\Html;


$this->title = $t->titulo;
?>

<div style="margin-top:50px;"> </div>
<div class="site-about pdfSalida">
    <div class="row">
        <div style="float:none" class="col-lg-8 center-block">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h2 class="panel-title">
                        <?= $this->title ?>
                    </h2>
                </div>
                <div style="height: 600px" class="panel-body">
                    <?= $t->descripcion ?>
                    <div style="margin:30px;float:none" class="col-lg-6 center-block">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-info">Las preguntas de este test estan relacionadas con los siguientes temas:</div>

                            <?php
                            foreach ($n as $categoria){
                                if (!is_null($categoria["numero"])) {
                                    echo '<div class="list-group-item"><span stype="display:block;float:left">' . $categoria["categoria"] . '</span> - <span style="line-height:20px;height:20px;border-radius:5px;width:60px;display:block;float:left" class="bg-info">' . "&nbsp;&nbsp;" . $categoria["numero"] . "&nbsp;&nbsp;" . '</span></div>';
                                }
                            }
                            ?>
                         </div>
                    </div>
                </div>
                <div class="panel-footer bg-info text-right">
                    <?= 'ID' . $t->id ?>
                </div>
            </div>
        </div>

    </div>

    <div style="display: block; page-break-before: always;"> </div>

    <div class="test1" style="line-height: 1.5em;font-size: .9em;font-family: Bodoni MT">
      <ol>
    <?php
        $fotos=[];
        $contadorPreguntas=0;
        foreach ($p as $v) {
            $contadorPreguntas++;
    ?>

        <li style="margin-bottom:10px; margin-top:10px"><?= $v->pregunta ?></li>
        <?php

          if ($v->getImagen()->one()){
              echo '<div style="text-align:center;margin:10px">';
              /** compruebo que la foto no este en otra pregunta del mismo test para solo mostrarla una vez **/
              if(!in_array($v->getImagen()->one()->nombre,$fotos)){
                  $fotos=[$contadorPreguntas => $v->getImagen()->one()->nombre];
                  echo Html::img('@web/imgs/pdf/' . $v->getImagen()->one()->nombre);
              }else{
                  $numeroFoto=array_search($v->getImagen()->one()->nombre,$fotos);
                  echo "Mirar imagen de la pregunta $numeroFoto";
              }
              echo "</div>";
          }

        ?>

        <ol type="a">
          <?php
            foreach ($v->getRespuestas()->all() as $r){
                echo "<li>" . $r->respuesta . "</li>";
            }
          ?>
        </ol>

    <?php
        }
    ?>
      </ol>
    </div>

    <div style="display: block; page-break-before: always;"> </div>

    <table class="tablas">
    <?php

        for($numero=0;$numero<count($p); $numero=$numero+3){
            $respuestas = $p[$numero]->getRespuestas()->all();
            $correcta = 'a';
            foreach ($respuestas as $r) {
              if ($r->correcta == 1) break;
              $correcta++;
            }
            echo '<tr><td>' . ($numero+1) . ':</td><td>' .  $correcta . "</td>";

            if($numero+1<count($p)){
              $respuestas = $p[$numero+1]->getRespuestas()->all();
              $correcta = 'a';
              foreach ($respuestas as $r) {
                if ($r->correcta == 1) break;
                $correcta++;
              }
            }
            if($numero+1>=count($p)){
                echo '<td>' . ($numero+2) . ':</td><td> - </td>';
            }else{
                echo '<td>' . ($numero+2) . ':</td><td>' .  $correcta . "</td>";
            }

            if($numero+2<count($p)){
              $respuestas = $p[$numero+2]->getRespuestas()->all();
              $correcta = 'a';
              foreach ($respuestas as $r) {
                if ($r->correcta == 1) break;
                $correcta++;
              }
            }
            if($numero+2>=count($p)){
                echo '<td>' . ($numero+3) . ':</td><td> - </td></tr>';
            }else{
                echo '<td>' . ($numero+3) . ':</td><td>' .  $correcta . "</td></tr>";
            }

        }

    ?>
    </table>
</div>
