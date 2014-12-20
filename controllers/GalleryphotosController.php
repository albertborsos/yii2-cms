<?php

namespace albertborsos\yii2cms\controllers;

use albertborsos\yii2cms\models\Galleries;
use albertborsos\yii2lib\helpers\S;
use Exception;
use Yii;
use albertborsos\yii2cms\models\GalleryPhotos;
use albertborsos\yii2cms\models\GalleryPhotosSearch;
use albertborsos\yii2lib\web\Controller;
use yii\image\drivers\Image_GD;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * GalleryphotosController implements the CRUD actions for GalleryPhotos model.
 */
class GalleryphotosController extends Controller
{
    public function init()
    {
        parent::init();
        $this->defaultAction = 'index';
        $this->name = 'Galéria Képek';
        $this->layout = '//center';
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
    public function actionIndex($gallery = null)
    {
        $gallery_id = $gallery;
        $this->layout = '//fluid';
        if (!is_null($gallery_id)){
            $gallery = Galleries::findOne(['id' => $gallery_id]);
            if (Yii::$app->request->isPost){
                try{
                    Yii::$app->response->getHeaders()->set('Vary', 'Accept');
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response = [];
                    $uploaded = UploadedFile::getInstanceByName('filename');
                    if (!is_null($uploaded)){
                        $photo = new GalleryPhotos();
                        $extension =  $photo->setExtension($uploaded);
                        $photo->filename = $uploaded->baseName;
                        $photo->generateUniqueName($extension);
                        $photo->gallery_id = $gallery->id;
                        $photo->status = 'a';
                        if ($photo->validate()){
                            $photo->saveUploadedFile($uploaded);
                            $photo->savePhoto($photo->getPathFull(), 1280);
                            $photo->savePhoto($photo->getPathFull(), 320, false, true);
                            //Now we return our json
                            $response['files'][] = [
                                'name' => $photo->filename,
                                'type' => $uploaded->type,
                                'size' => $uploaded->size,
                                'url' => $photo->getUrlFull(),
                                'thumbnailUrl' => $photo->getUrlFull(true),
                                'deleteUrl' => $photo->getUrlDelete(),
                                'deleteType' => 'POST'
                            ];
                        }else{
                            $photo->throwNewException('Hibás kép attribútum!');
                        }
                    }else{
                        throw new Exception('Nem érkezett kép!');
                    }

                }catch (Exception $e){
                    $response[] = ["error" => $e->getMessage()];
                }
                return $response;
            }
        }else{
            return $this->redirect(['/cms/galleries/index']);
        }
        $searchModel = new GalleryPhotosSearch();
        $dataProvider = $searchModel->search(['GalleryPhotosSearch' => ['gallery_id' => (int)$gallery_id]]);

        return $this->render('upload', [
            'gallery' => $gallery,
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
                    Yii::$app->session->setFlash('success', '<h4>Kép sikeresen létrehozva!</h4>');
                    return $this->redirect(['index']);
                }else{
                    Yii::$app->session->setFlash('error', '<h4>Kép mentése nem sikerült!</h4>');
                }
            }catch (Exception $e){
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
                    Yii::$app->session->setFlash('success', '<h4>Kép sikeresen módosítva!</h4>');
                    return $this->redirect(['index', 'gallery' => $model->gallery_id]);
                }else{
                    Yii::$app->session->setFlash('error', '<h4>Kép módosítása nem sikerült!</h4>');
                }
            }catch (Exception $e){
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
        $galleryID = null;
        try{
            $model = $this->findModel($id);
            $galleryID = $model->gallery_id;
            if ($model->deleteFiles()){
                $model->delete();
            }
        }catch (Exception $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        if (!is_null($galleryID)){
            return $this->redirect(['/cms/galleryphotos/index', 'gallery' => $galleryID]);
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

    public function actionUpdatebyeditable(){;
		$key       = Yii::$app->request->post('pk');
		$id        = unserialize(base64_decode($key));
        $attribute = Yii::$app->request->post('name');
        $value     = Yii::$app->request->post('value');

        $model = $this->findModel($id);
        try{
            if (!is_null($model)){
                $model->$attribute = $value;
                if (!$model->save()){
                    throw new Exception('Nem sikerült menteni!');
                }
            }else{
                throw new Exception('Nem létezik ilyen rekord!');
            }
        }catch (Exception $e){
            throw new HttpException(400,$e->getMessage());
        }
    }
}
