<?php

namespace albertborsos\yii2cms\controllers;

use Yii;
use albertborsos\yii2cms\models\GalleryPhotos;
use albertborsos\yii2cms\models\GalleryPhotosSearch;
use albertborsos\yii2lib\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * GalleryphotosController implements the CRUD actions for GalleryPhotos model.
 */
class GalleryphotosController extends Controller
{
    public function init()
    {
        parent::init();
        $this->defaultAction = 'index';
        $this->name = 'GalleryPhotos';
        $this->layout = '//center';
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'view', // reader
                            'create', 'update', 'delete'], // editor+
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow'   => true,
                        'matchCallback' => function(){
                            return Yii::$app->user->can('reader');
                            }
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'matchCallback' => function(){
                            return Yii::$app->user->can('editor');
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all GalleryPhotos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GalleryPhotosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GalleryPhotos model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GalleryPhotos model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GalleryPhotos();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if ($model->save()){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '<h4>GalleryPhotos sikeresen létrehozva!</h4>');
                    return $this->redirect(['index']);
                }else{
                    Yii::$app->session->setFlash('error', '<h4>GalleryPhotos mentése nem sikerült!</h4>');
                }
            }catch (\Exception $e){
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
        'model' => $model,
        ]);
    }

    /**
     * Updates an existing GalleryPhotos model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $transaction = Yii::$app->db->beginTransaction();
        if ($model->load(Yii::$app->request->post())) {
            try{
                if ($model->save()){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '<h4>GalleryPhotos sikeresen módosítva!</h4>');
                    return $this->redirect(['update', 'id' => $model->id]);
                }else{
                    Yii::$app->session->setFlash('error', '<h4>GalleryPhotos módosítása nem sikerült!</h4>');
                }
            }catch (\Exception $e){
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GalleryPhotos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try{
            $this->findModel($id)
            ->delete();
        }catch (\Exception $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the GalleryPhotos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GalleryPhotos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GalleryPhotos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
