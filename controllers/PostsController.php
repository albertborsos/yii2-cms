<?php

    namespace albertborsos\yii2cms\controllers;

    use albertborsos\yii2cms\components\DataProvider;
    use albertborsos\yii2lib\helpers\S;
    use Yii;
    use albertborsos\yii2cms\models\Posts;
    use albertborsos\yii2cms\models\PostsSearch;
    use albertborsos\yii2lib\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\filters\AccessControl;

    /**
     * PostsController implements the CRUD actions for Posts model.
     */
    class PostsController extends Controller {
        public function init()
        {
            parent::init();
            $this->defaultAction = 'index';
            $this->name          = 'Bejegyzések';
            $this->layout        = '//center';
        }

        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'only'  => [
                        'index',
                        'view', // reader
                        'create',
                        'update',
                        'delete'
                    ], // editor+
                    'rules' => [
                        [
                            'actions'       => ['index', 'view'],
                            'allow'         => true,
                            'matchCallback' => function () {
                                    return Yii::$app->user->can('reader');
                                }
                        ],
                        [
                            'actions'       => ['create', 'update', 'delete'],
                            'allow'         => true,
                            'matchCallback' => function () {
                                    return Yii::$app->user->can('editor');
                                }
                        ],
                    ],
                ],
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post'],
                    ],
                ],
            ];
        }

        /**
         * Lists all Posts models.
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel  = new PostsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        /**
         * Displays a single Posts model.
         * @param string $id
         * @return mixed
         */
        public function actionView($id)
        {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }

        /**
         * Creates a new Posts model.
         * If creation is successful, the browser will be redirected to the 'index' page.
         * @return mixed
         */
        public function actionCreate($type = null)
        {
            $model            = new Posts();
            $model->post_type = $type;
            $model->date_show = date('Y-m-d H:i');

            if ($model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        // create seo setting
                        $seo = $model->createSeo();
                        if ($seo->save()){
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', '<h4>'.DataProvider::items('post_type', $model->post_type, false).' sikeresen létrehozva!</h4>');
                        }else{
                            $seo->throwNewException('SEO beállítások mentése nem sikerült!');
                        }
                        return $this->redirect(['update?id='.$model->id]);
                    } else {
                        Yii::$app->session->setFlash('error', '<h4>'.DataProvider::items('post_type', $model->post_type, false).' mentése nem sikerült!</h4>');
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }

        /**
         * Updates an existing Posts model.
         * If update is successful, the browser will be redirected to the 'update' page.
         * @param string $id
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);

            $transaction = Yii::$app->db->beginTransaction();
            if ($model->load(Yii::$app->request->post())) {
                try {
                    if ($model->save()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', '<h4>'.DataProvider::items('post_type', $model->post_type, false).' sikeresen módosítva!</h4>');

                        return $this->redirect(['update', 'id' => $model->id]);
                    } else {
                        Yii::$app->session->setFlash('error', '<h4>'.DataProvider::items('post_type', $model->post_type, false).' módosítása nem sikerült!</h4>');
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }

        /**
         * Deletes an existing Posts model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param string $id
         * @return mixed
         */
        public function actionDelete($id)
        {
            try{
                $post = $this->findModel($id);
                $post->seo->delete();
                $post->delete();

                return $this->redirect(['index']);
            }catch (\Exception $e){
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(['index']);
            }
        }

        /**
         * Finds the Posts model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param string $id
         * @return Posts the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (($model = Posts::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
