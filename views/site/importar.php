<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-lg">
      <?php
        if (Yii::$app->session->hasFlash('enviadoImportar')) {
            echo '<div id="eImportar" class="alert alert-success">';
            echo 'Test importado';
            echo '</div>';
        }
      ?>
      
      <div class="alert alert-info">
        <p>Debeis tener en cuenta que mediante esta opcion te importará todas las preguntas del test que le coloques. Además te generará un test para que después puedas imprimir cuando quieras</p>
      </div>

      <?php $form = ActiveForm::begin(['id' => 'importar-form']); ?>

          <?= $form->field($model, 'descripcion')->textarea(['rows' => 6, 'autofocus' => true]) ?>
          
          <?= $form->field($model, 'materia') ?>

          <?= $form->field($model, 'fichero')->fileInput() ?>
          
          <div class="form-group">
              <?= Html::submitButton('Importar preguntas',
               ['class' => 'btn btn-primary', 'name' => 'importar-button', 'onclick' => 'ocultarImportado()']) ?>
          </div>

      <?php ActiveForm::end(); ?>

    </div>
</div>

<script type="text/javascript">
  function ocultarImportado() {
    var x = document.getElementById("eImportar");
    x.style.display = "none";
  }
</script>







