<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\widgets\ListView;
use yii\helpers\ArrayHelper;
?>

<div class="row">
    <div class="col-lg">
      <?php
        if (Yii::$app->session->hasFlash('enviadoGenerarCategoria')) {
            echo '<div id="eGenerarCategoria" class="alert alert-success">';
            echo 'Test generado';
            echo '</div>';
        }
      ?>
      
      <div class="alert alert-info">
        <p>Debeis tener en cuenta que mediante esta opcion te importará todas las preguntas del test que le coloques. Además te generará un test para que después puedas imprimir cuando quieras</p>
      </div>

      <?php $form = ActiveForm::begin(['id' => 'importar-form']); ?>

          <?= $form->field($modelTests, 'npreguntas')->input(['autofocus' => true]) ?>
          
          <?= $form->field($modelTests, 'descripcion')->textarea(['rows' => 6]) ?>
          
          <?= $form->field($modelTests, 'materia') ?>

          <div class="form-group">
               <?= Html::submitButton('Previsualizar Test',
               ['class' => 'btn btn-primary', 'name' => 'previsualizar-button', 'onclick' => 'ocultarGenerado()']) ?>
               <?= Html::submitButton('Generar Test',
               ['class' => 'btn btn-primary', 'name' => 'generar-button', 'onclick' => 'ocultarGenerado()']) ?>
          </div>

          <?php echo $form->field($modelTests, 'categoria[]')
              ->checkboxList( $modelCategoriasArraySoloCampos ); ?>

          <?php echo $form->field($modelTests, 'pregunta[]')
              ->checkboxList( $modelPreguntasArraySoloCampos ); ?>

          <?php ActiveForm::end(); ?>

    </div>

</div>

<script type="text/javascript">
  function ocultarGenerado() {
    var x = document.getElementById("eGenerarCategoria");
    x.style.display = "none";
  }
</script>
