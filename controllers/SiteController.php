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
        $model = new \app\models\Tests();
        if ($model->load(Yii::$app->request->post())) {
            
            Yii::$app->session->setFlash('enviadoImportar');
            
            $model->fecha = \Yii::$app->formatter->asDatetime("now", "php:Y-m-d H:i:s");
            $model->insert();
                      
            $model->fichero = UploadedFile::getInstance($model, 'fichero');

            $modelPreguntas = new \app\models\Preguntas();
            $modelRespuestas = new \app\models\Respuestas();
            
            // Importar fichero
            $handle = fopen($model->fichero->tempName, "r");
            if ($handle) {
              while (($line = fgets($handle)) !== false) {
                if (is_numeric($line[0])) {
                  $modelPreguntas->pregunta = $line;
                  $modelPreguntas->insert();
                } else if (is_alpha($line[0])) {
                  $modelRespuestas->respuesta = $line;
                }
              }

              fclose($handle);
            } else {
              echo "ERROR abriendo el fichero:" . $model->fichero;
              exit();
            }
            
            return $this->refresh();
        }
      return $this->render('importar', [
                    'model' => $model
             ]);
    }
}
