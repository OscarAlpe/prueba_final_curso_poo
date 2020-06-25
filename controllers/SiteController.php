<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\db\Transaction;

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
                $modelPreguntas->test_id = $test_id;

                if (!$modelPreguntas->insert()) {
                  $transaction->rollBack();
                  return $this->render('error', [
                      'name' => 'ERROR al insertar Preguntas',
                      'message' => json_encode($modelPreguntas->getErrors(), JSON_UNESCAPED_UNICODE)
                  ]);
                }

                $pregunta_id = Yii::$app->db->getLastInsertID();

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
              } else if (ctype_alpha($line[0])) {
                $modelRespuestas = new \app\models\Respuestas();
                $modelRespuestas->pregunta_id = $pregunta_id;
                $modelRespuestas->respuesta = utf8_encode(substr($line, 0, strpos($line, "\n") - 1));

                $modelRespuestas->correcta = 0;
                if (strpos(strtolower($line), "xxx") !== false) {
                  $modelRespuestas->correcta = 1;
                  $modelRespuestas->respuesta = utf8_encode(substr(strtolower($line), 0,
                                                            strpos(strtolower($line), " xxx")));
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
}
