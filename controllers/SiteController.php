<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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
            
            Yii::$app->session->setFlash('enviadoImportar');
            
            $modelTests->fecha = \Yii::$app->formatter->asDatetime("now", "php:Y-m-d H:i:s");
            $selectTests = \app\models\Tests::find()->
              where(['=', 'materia', $modelTests['materia']])->
              orderBy('titulo')->
              all();
            $siguiente = sizeof($selectTests) + 1;
            $modelTests->titulo = "Test de " . $modelTests->materia . " número " . $siguiente;
            $modelTests->titulo_impreso = $modelTests->titulo;
            $modelTests->insert();
            $test_id = Yii::$app->db->getLastInsertID();          
            
            $modelTests->fichero = UploadedFile::getInstance($modelTests, 'fichero');
         
            // Importar fichero
            $handle = fopen($modelTests->fichero->tempName, "r");
            if ($handle) {
              while (($line = fgets($handle)) !== false) {
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
                  $modelPreguntas->insert();
                  $pregunta_id = Yii::$app->db->getLastInsertID();
                  
                  if (isset($categorias) and is_array($categorias)) {
                    $arrCategorias = explode(",", $categorias);

                    foreach ($arrCategorias as $c) {
                      $selectCategorias = \app\models\Categorias::find()->where(['=', 'categoria', $c])->one();
                      if (!$selectCategorias) {
                        $modelCategorias = new \app\models\Categorias();
                        $modelCategorias->categoria = $c;
                        $modelCategorias->insert();
                        $categoria_id = Yii::$app->db->getLastInsertID();
                      } else {
                        $categoria_id = $selectCategorias['id'];
                      }
                      
                      $modelCategoriaspregunta = new \app\models\Categoriaspregunta();
                      $modelCategoriaspregunta->categoria_id = $categoria_id;
                      $modelCategoriaspregunta->pregunta_id = $pregunta_id;
                      $modelCategoriaspregunta->insert();
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

                  $modelRespuestas->insert();
                }
              }

              fclose($handle);
            } else {
              echo "ERROR abriendo el fichero:" . $modelTests->fichero->tempName;
              exit();
            }
            
            return $this->refresh();
        }
      return $this->render('importar', [
                    'model' => $modelTests
             ]);
    }
}
