<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\db\Transaction;
use kartik\mpdf\Pdf;
use app\models\Tests;
use yii\helpers\ArrayHelper;
use app\models\Categorias;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionImportar()
    {
      $modelTests = new \app\models\Tests();

      if ($modelTests->load(Yii::$app->request->post())) {

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {
          Yii::$app->session->setFlash('enviadoImportar');

          // Crear Test
          $modelTests->fecha = \Yii::$app->formatter->asDatetime("now", "php:Y-m-d H:i:s");
          $numeroTests = \app\models\Tests::find()->
            where(['=', 'materia', $modelTests['materia']])->
            count();

          $modelTests->titulo = "Test de " . $modelTests->materia . " número " . ($numeroTests + 1);
          $modelTests->titulo_impreso = $modelTests->titulo;
          $modelTests->fichero = UploadedFile::getInstance($modelTests, 'fichero');

          if (!$modelTests->insert()) {
            $transaction->rollBack();
            return $this->render('error', [
                'name' => 'ERROR al insertar Test',
                'message' => json_encode($modelTests->getErrors(), JSON_UNESCAPED_UNICODE)
            ]);
          }

          $test_id = Yii::$app->db->getLastInsertID();

          // Importar fichero
          $handle = fopen($modelTests->fichero->tempName, "r");
          if ($handle) {
            while (($line = fgets($handle)) !== false) {

              if ((strpos(strtolower($line), "[[") !== false) && (strpos(strtolower($line), "[[") == 0)) {
                $categoriasTest = substr($line, strpos($line, "[[") + 2);
                $categoriasTest = substr($categoriasTest, 0, strpos($categoriasTest, "]]"));
                $arrCategoriasTest = explode(",", $categoriasTest);

                foreach ($arrCategoriasTest as $cTest) {
                  $selectCategoriasTest = \app\models\Categorias::find()->
                    where(['=', 'categoria', utf8_encode($cTest)])->one();
                  if (!$selectCategoriasTest) {
                    $modelCategoriasT = new \app\models\Categorias();
                    $modelCategoriasT->categoria = utf8_encode($cTest);

                    if (!$modelCategoriasT->insert()) {
                      $transaction->rollBack();
                      return $this->render('error', [
                          'name' => 'ERROR al insertar Categorias',
                          'message' => json_encode($modelCategoriasT->getErrors(), JSON_UNESCAPED_UNICODE)
                      ]);
                    }

                    $categoria_idTest = Yii::$app->db->getLastInsertID();
                  } else {
                    $categoria_idTest = $selectCategoriasTest['id'];
                  }
                  
                  $modelCategoriastest = new \app\models\Categoriastest();
                  $modelCategoriastest->categoria_id = $categoria_idTest;
                  $modelCategoriastest->test_id = $test_id;

                  if (!$modelCategoriastest->insert()) {
                    $transaction->rollBack();
                    return $this->render('error', [
                        'name' => 'ERROR al insertar Categoriastest',
                        'message' => json_encode($modelCategoriastest->getErrors(), JSON_UNESCAPED_UNICODE)
                    ]);
                  }
                }
              }

              // Crear preguntas
              if (ctype_digit($line[0])) {
                $modelPreguntas = new \app\models\Preguntas();
                $pregunta = substr($line, strpos($line, " ") + 1);

                if (strpos(strtolower($pregunta), "[[") !== false) {
                  $pregunta = substr($pregunta, 0, strpos($pregunta, "[["));
                }

                if (strpos(strtolower($line), "[[") !== false) {
                  $categorias = substr($line, strpos($line, "[[") + 2);
                  $categorias = substr($categorias, 0, strpos($categorias, "]]"));
                }

                if (strpos(strtolower($pregunta), " {{") !== false) {
                  $pregunta = substr($pregunta, 0, strpos($pregunta, " {{"));
                  $imagen_id = substr($pregunta, strpos($pregunta, "{{") + 2, strpos($pregunta, "}}"));
                  $modelPreguntas->imagen_id = $imagen_id;
                }

                if (strpos(strtolower($line), " {{") !== false) {
                  $imagen_id = substr($line, strpos($line, "{{") + 2,
                                             strpos($line, "{{") + 2 - strpos($line, "}}") - 2);
                  $modelPreguntas->imagen_id = $imagen_id;
                }

                if (strpos($pregunta, "\n") !== false) {
                  $pregunta = substr($pregunta, 0, strpos($pregunta, "\n") - 1);
                }

                $modelPreguntas->pregunta = utf8_encode($pregunta);

                if (!$modelPreguntas->insert()) {
                  $transaction->rollBack();
                  return $this->render('error', [
                      'name' => 'ERROR al insertar Preguntas',
                      'message' => json_encode($modelPreguntas->getErrors(), JSON_UNESCAPED_UNICODE)
                  ]);
                }

                $pregunta_id = Yii::$app->db->getLastInsertID();

                // Crear categorías
                if (isset($categorias)) {
                  $arrCategorias = explode(",", $categorias);

                  foreach ($arrCategorias as $c) {
                    $selectCategorias = \app\models\Categorias::find()->
                      where(['=', 'categoria', utf8_encode($c)])->one();
                    if (!$selectCategorias) {
                      $modelCategorias = new \app\models\Categorias();
                      $modelCategorias->categoria = utf8_encode($c);

                      if (!$modelCategorias->insert()) {
                        return $this->render('error', [
                            'name' => 'ERROR al insertar Categorias',
                            'message' => json_encode($modelCategorias->getErrors(), JSON_UNESCAPED_UNICODE)
                        ]);
                      }

                      $categoria_id = Yii::$app->db->getLastInsertID();
                    } else {
                      $categoria_id = $selectCategorias['id'];
                    }

                    $modelCategoriaspregunta = new \app\models\Categoriaspregunta();
                    $modelCategoriaspregunta->categoria_id = $categoria_id;
                    $modelCategoriaspregunta->pregunta_id = $pregunta_id;

                    if (!$modelCategoriaspregunta->insert()) {
                      $transaction->rollBack();
                      return $this->render('error', [
                          'name' => 'ERROR al insertar Categoriaspregunta',
                          'message' => json_encode($modelCategoriaspregunta->getErrors(), JSON_UNESCAPED_UNICODE)
                      ]);
                    }
                  }
                }

                // Insertar relación en testspreguntas
                $modelTestsPreguntas = new \app\models\Testspreguntas();
                $modelTestsPreguntas->test_id = $test_id;
                $modelTestsPreguntas->pregunta_id = $pregunta_id;
                if (!$modelTestsPreguntas->insert()) {
                  $transaction->rollBack();
                  return $this->render('error', [
                      'name' => 'ERROR al insertar TestsPreguntas',
                      'message' => json_encode($modelTestsPreguntas->getErrors(), JSON_UNESCAPED_UNICODE)
                  ]);
                }
              // Crear respuestas
              } else if (ctype_alpha($line[0])) {
                $modelRespuestas = new \app\models\Respuestas();
                $modelRespuestas->pregunta_id = $pregunta_id;
                $respuesta = substr($line, strpos($line, " ") + 1);
                $modelRespuestas->respuesta = utf8_encode(substr($respuesta, 0, strpos($respuesta, "\n") - 1));

                $modelRespuestas->correcta = 0;
                if (strpos(strtolower($respuesta), "xxx") !== false) {
                  $modelRespuestas->correcta = 1;
                  $modelRespuestas->respuesta = utf8_encode(substr(strtolower($respuesta), 0,
                                                            strpos(strtolower($respuesta), " xxx")));
                }

                if (!$modelRespuestas->insert()) {
                  $transaction->rollBack();
                  return $this->render('error', [
                      'name' => 'ERROR al insertar Respuestas',
                      'message' => json_encode($modelRespuestas->getErrors(), JSON_UNESCAPED_UNICODE)
                  ]);
                }
              }
            }

            fclose($handle);

            $transaction->commit();
          } else {
            $transaction->rollBack();
            return $this->render('error', [
                'name' => 'ERROR abriendo el fichero:',
                'message' => error_get_last()
            ]);
          }
        } catch (\Exception $e) {
          $transaction->rollBack();
          throw $e;
        } catch (\Throwable $e) {
          $transaction->rollBack();
          throw $e;
        }

        return $this->refresh();
      }

      return $this->render('importar', [
                    'model' => $modelTests
             ]);
    }

    public function actionGenerardirectamente($numero) {
        $test = $numero;
        $t = Tests::findOne(['id'=>$test]);
        $p = $t->getPreguntas()->all();

        // consulta directa
        $consulta="SELECT c.categoria, COUNT(*) as numero FROM preguntas p
                     JOIN testspreguntas tp ON p.id = tp.pregunta_id
                     JOIN categoriaspregunta cp ON p.id = cp.pregunta_id
                     JOIN categorias c ON cp.categoria_id = c.id WHERE tp.test_id=$numero
                     GROUP BY cp.categoria_id ORDER BY c.categoria";
        $contadorCategorias=Yii::$app->db->createCommand($consulta)->queryAll();

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('crearPdf',[
            'p'=>$p, // pasando preguntas
            't'=>$t, // pasando el test
            'n'=>ArrayHelper::index($contadorCategorias,'categoria'), // numero de preguntas por categoria
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            'marginTop' => 30,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            //'filename'=>Yii::getAlias('@app') . '/file/pdf/test.pdf',
            //'destination' => Pdf::DEST_FILE,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            // '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css'
            'cssFile' => ['@app/web/css/kv-mpdf-bootstrap.css','@app/web/css/site.css'],
            //'cssFile' => '@app/web/css
            ///site.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => [
                'title' => 'Alpe Formacion',
                'showWatermarkImage'=>true,
                //'defaultheaderfontsize'=>9,
            ],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>[' | ' . $t->titulo_impreso . ' | por Oscar Megía López'],
                'SetFooter'=>[strtoupper($t->materia) . ' | Alpe Formacion | Pagina {PAGENO}'],
                'SetWatermarkImage'=>[Yii::getAlias('@web') . '/imgs/alpe.png',1,[34,38],[10,0]],

            ]
        ]);

        return $pdf->render();

      }

    public function actionGenerarcategoria() {
      $modelTests = new \app\models\Tests();

      $modelPreguntasArraySoloCampos = [];

      $modelCategoriasArray = \app\models\Categorias::find()
        ->distinct()
        ->select(["categorias.id", "categorias.categoria"])
        ->rightjoin("categoriaspregunta", "categorias.id = categoriaspregunta.categoria_id")
        ->asArray()
        ->all();

      $modelCategoriasArraySoloCampos = ArrayHelper::map($modelCategoriasArray, 'id', 'categoria');

      if (Yii::$app->request->isPost) {
        $formData = Yii::$app->request->post();
        $modelTests->load($formData);

        if (isset($formData["generar-button"])) {

          $connection = \Yii::$app->db;
          $transaction = $connection->beginTransaction();

          try {
            Yii::$app->session->setFlash('enviadoGenerarCategoria');

            // Inserta el test
            $modelTests->fecha = \Yii::$app->formatter->asDatetime("now", "php:Y-m-d H:i:s");
            $numeroTests = \app\models\Tests::find()->
              where(['=', 'materia', $modelTests['materia']])->
              count();

            $modelTests->titulo = "Test de " . $modelTests->materia . " número " . ($numeroTests + 1);
            $modelTests->titulo_impreso = $modelTests->titulo;
            $titulo = $modelTests->titulo;;

            if (!$modelTests->insert()) {
              $transaction->rollBack();
              return $this->render('error', [
                  'name' => 'ERROR al insertar Test',
                  'message' => json_encode($modelTests->getErrors(), JSON_UNESCAPED_UNICODE)
              ]);
            }

            $test_id = Yii::$app->db->getLastInsertID();

            // Comprueba que se hayan seleccionado preguntas
            if (!isset($formData["Tests"]["preguntas"]) || ($formData["Tests"]["preguntas"] == "")) {
              $transaction->rollBack();
              return $this->render('error', [
                  'name' => 'ERROR',
                  'message' => 'No se ha seleccionado ninguna pregunta para poder generar el test'
              ]);
            }

            $numeroPreguntasGrabadas = 0;

            // Inserta las preguntas
            foreach ($formData["Tests"]["preguntas"] as $v) {
              $modelTestsPreguntas = new \app\models\Testspreguntas();
              $modelTestsPreguntas->test_id = $test_id;
              $modelTestsPreguntas->pregunta_id = $v;

              if (!$modelTestsPreguntas->insert()) {
                $transaction->rollBack();
                return $this->render('error', [
                    'name' => 'ERROR al insertar Testspreguntas',
                    'message' => json_encode($modelPreguntasNew->getErrors(), JSON_UNESCAPED_UNICODE)
                ]);
              }

              $numeroPreguntasGrabadas++;

              if ($numeroPreguntasGrabadas >= $formData["Tests"]["npreguntas"]) {
                break;
              }
            }

            $transaction->commit();

            return $this->render("generarCategoria", [
              'modelTests' => $modelTests,
              'modelCategoriasArraySoloCampos' => $modelCategoriasArraySoloCampos,
              'modelPreguntasArraySoloCampos' => $modelPreguntasArraySoloCampos,
              'numero' => $test_id,
              'titulo' => $titulo,
            ]);

          } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
          } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
          }

        } else if (isset($formData["previsualizar-button"])) {
          $modelPreguntasArray = \app\models\Preguntas::find()
            ->distinct()
            ->select(["preguntas.id", "preguntas.pregunta", "imagenes.nombre"])
            ->leftjoin("imagenes", "preguntas.imagen_id = imagenes.id")
            ->innerjoin("categoriaspregunta", "preguntas.id = categoriaspregunta.pregunta_id")
            ->asArray()
            ->where(["IN", "categoriaspregunta.categoria_id", $formData["Tests"]["categorias"]])
            ->all();
          $modelPreguntasArraySoloCampos = ArrayHelper::index($modelPreguntasArray, 'id');

          return $this->render("generarCategoria", [
            'modelTests' => $modelTests,
            'modelCategoriasArraySoloCampos' => $modelCategoriasArraySoloCampos,
            'modelPreguntasArraySoloCampos' => $modelPreguntasArraySoloCampos,
          ]);
        }

        return $this->refresh();
      }

      return $this->render("generarCategoria", [
        'modelTests' => $modelTests,
        'modelCategoriasArraySoloCampos' => $modelCategoriasArraySoloCampos,
        'modelPreguntasArraySoloCampos' => $modelPreguntasArraySoloCampos,
      ]);
    }

  public function actionAyuda() {
    return $this->render("ayuda");
  }
}
