<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\widgets\ListView;
use yii\helpers\ArrayHelper;
use yii\web\View;
?>

<?php

if (isset($numero)) {
//No funciona el título
echo "<script>
        window.onload = function openInNewTab() {
          var win = window.open('generardirectamente?numero=' + " . $numero . ", '_blank');
          win.focus();
          win.document.title = '" . $titulo . "';
        }
      </script>";

}

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

          <?= $form->field($modelTests, 'npreguntas') ?>
          
          <?= $form->field($modelTests, 'descripcion')->textarea(['rows' => 6]) ?>
          
          <?= $form->field($modelTests, 'materia') ?>

          <div class="form-group">
               <?= Html::submitButton('Previsualizar Test',
               ['class' => 'btn btn-primary', 'name' => 'previsualizar-button', 'onclick' => 'ocultarGenerado()']) ?>
               <?= Html::submitButton('Generar Test',
               ['class' => 'btn btn-primary', 'name' => 'generar-button', 'onclick' => 'ocultarGenerado()']) ?>
          </div>

          <?= $form->field($modelTests, 'categorias[]')->checkboxList($modelCategoriasArraySoloCampos) ?>

<?php
echo Html::checkbox(null, false, [
    'label' => 'Activar/desactivar todas las preguntas',
    'class' => 'check-all',
]);
?>
          <?= $form->field($modelTests, 'preguntas[]')->checkboxList($modelPreguntasArraySoloCampos,
                [
                  'item' => function ($index, $label, $name, $checked, $value) {
                              $ret = '  <div class="col-lg-12">';
                              $ret .= '    <div class="input-group" style="margin-top: 10px;" >';
                              $ret .= '      <span class="input-group-addon" style="text-align: left;">';
                              $ret .= '        <input type="checkbox" name="Tests[preguntas][]" value="' . $value . '" ">';
                              $ret .= '        <label style="margin: 20px;">' . $label["pregunta"] . '</label>';
                              if ($label["nombre"] !== NULL) {
                                $ret .= '        <div>';
                                $ret .= '          <img src="' . \Yii::getAlias('@web') .
                                                     '/imgs/pdf/' . $label["nombre"] . '" >';
                                $ret .= '        </div>';
                              }
                              $ret .= '      </span>';
                              $ret .= '    </div><!-- /input-group -->';
                              $ret .= '  </div><!-- /.col-lg-6 -->';

                              return $ret;
                            },
                  'separator' => '<hr>']) ?>

          <?php ActiveForm::end(); ?>

    </div>

</div>

<script type="text/javascript">
  function ocultarGenerado() {
    var x = document.getElementById("eGenerarCategoria");
    x.style.display = "none";
  }
</script>

<?php
  $script = <<< JS
    $('.check-all').click(function() {
      var selector = $(this).is(':checked') ? ':not(:checked)' : ':checked';
      $('#tests-preguntas input[type="checkbox"]' + selector).each(function() {
        $(this).trigger('click');
      });
    });
JS;

  $this->registerJs($script, View::POS_END);
?>
