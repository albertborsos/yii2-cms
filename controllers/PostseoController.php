<?php

namespace albertborsos\yii2cms\controllers;

use Yii;
use albertborsos\yii2cms\models\PostSeo;
use yii\data\ActiveDataProvider;
use albertborsos\yii2lib\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PostseoController implements the CRUD actions for PostSeo model.
 */
class PostseoController extends Controller
{
    public function init()
    {
        parent::init();
        $this->defaultAction = 'index';
        $this->name = 'PostSeo';
        $this->layout = '//center';
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['update'], // editor+
                'rules' => [
                    [
                        'actions' => ['update'],
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
     * Updates an existing PostSeo model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param string $id
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
                    Yii::$app->session->setFlash('success', '<h4>SEO beállítások sikeresen módosítva!</h4>');
                    return $this->redirect(['/cms/posts/update', 'id' => $model->post_id]);
                }else{
                    Yii::$app->session->setFlash('error', '<h4>SEO beállítások módosítása nem sikerült!</h4>');
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
     * Finds the PostSeo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PostSeo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostSeo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
