<?php

namespace albertborsos\yii2cms\controllers;

use albertborsos\yii2cms\components\Gallery;
use albertborsos\yii2cms\models\GalleryPhotos;
use Exception;
use Yii;
use albertborsos\yii2cms\models\Galleries;
use albertborsos\yii2cms\models\GalleriesSearch;
use albertborsos\yii2lib\web\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * GalleriesController implements the CRUD actions for Galleries model.
 */
class GalleriesController extends Controller
{
    public function init()
    {
        parent::init();
        $this->defaultAction = 'index';
        $this->name = 'Galériák';
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
                        'actions' => ['create', 'update', 'delete', 'updatebyeditable'],
                        'allow' => true,
                        'matchCallback' => function(){
                                return Yii::$app->user->can('editor');
                            }
                    ],
                    [
                        'actions' => ['loadotherimages', 'renderajaxgallery'],
                        'allow'   => true,
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete'            => ['post'],
                    'updatebyeditable'  => ['post'],
                    'renderajaxgallery' => ['post'],
                    //'loadotherimages'   => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Galleries models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->redirect('create');
        $searchModel = new GalleriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Galleries model.
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
     * Creates a new Galleries model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Galleries();
        $model->getReplaceID();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if ($model->save()){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '<h4>Galleries sikeresen létrehozva!</h4>');
                    return $this->redirect(['index']);
                }else{
                    Yii::$app->session->setFlash('error', '<h4>Galleries mentése nem sikerült!</h4>');
                }
            }catch (\Exception $e){
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        $searchModel = new GalleriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Galleries model.
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
                    Yii::$app->session->setFlash('success', '<h4>Galleries sikeresen módosítva!</h4>');
                    return $this->redirect(['update', 'id' => $model->id]);
                }else{
                    Yii::$app->session->setFlash('error', '<h4>Galleries módosítása nem sikerült!</h4>');
                }
            }catch (\Exception $e){
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        $searchModel = new GalleriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Galleries model.
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
     * Finds the Galleries model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Galleries the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Galleries::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdatebyeditable(){
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

    public function actionRenderajaxgallery($id, $page = null){
        $model = $this->findModel($id);

        return $model->generate($page);
    }

    public function actionLoadotherimages($id, $activePage){
        Yii::$app->response->getHeaders()->set('Vary', 'Accept');
        Yii::$app->response->format = Response::FORMAT_JSON;

        $gallery = $this->findModel($id);

        $query = GalleryPhotos::find()->where(['status' => 'a', 'gallery_id' => $id]);
        $totalNum = $query->count();

        $dataProvider = new ActiveDataProvider([
            'query' => $query->limit($activePage*$gallery->pagesize)->offset(0),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => 'id '.$gallery->order,
            ],
        ]);

        $links['before'] = Gallery::renderLinksOnly($dataProvider);
        // lekérdezem azokat a képeket, amik az aktuális oldal után vannak
        $dataProvider = new ActiveDataProvider([
            'query' => $query->limit(null)->offset(($activePage+1)*$gallery->pagesize),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => 'id '.$gallery->order,
            ],
        ]);

        $links['after'] = Gallery::renderLinksOnly($dataProvider);

        return Json::encode($links);

    }
}
