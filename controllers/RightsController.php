<?php

    namespace albertborsos\yii2cms\controllers;

    use albertborsos\yii2lib\web\Controller;
    use albertborsos\yii2cms\models\Users;
    use HttpException;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use Yii;

    class RightsController extends Controller {
        public $name = 'Jogosultságok';

        public function init()
        {
            parent::init();
            $this->layout = '//center';
        }

        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['admin', 'modify', 'delete'],
                            'allow'   => true,
                            'matchCallback' => function(){
                                return Yii::$app->user->can('admin');
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

        public function actionAdmin()
        {
            $this->layout = '//center';

            return $this->render('admin');
        }

        public function actionModify()
        {
            $key = Yii::$app->request->post('pk');
            $user_id = unserialize(base64_decode($key));
            $role = Yii::$app->request->post('value');

            $auth = Yii::$app->authManager;
            // 1 felhasználónak csak 1 joga lehet
            $auth->revokeAll($user_id);
            $permission = $auth->getPermission($role);
            if ($permission !== null) {
                $auth->assign($permission, $user_id);
            } else {
                throw new HttpException(400, 'Right not exists!');
            }
        }

        public function actionDelete($id)
        {
            $fullName = Users::findIdentity($id)->getFullname();
            if ($id != Yii::$app->user->id){
                $auth = Yii::$app->authManager;
                if ($auth->revokeAll($id)) {
                    Yii::$app->session->setFlash('success', '<h4><b>"' . $fullName . '"</b>' . Yii::t('cms', 'user_remove_successful') . '</h4>');
                } else {
                    Yii::$app->session->setFlash('error', '<h4><b>"' . $fullName . '"</b>' . Yii::t('cms', 'user_remove_error') . '</h4>');
                }
            }else{
                Yii::$app->session->setFlash('error', '<h4>'.Yii::t('cms', 'user_remove_yourself') . '</h4>');
            }
            return $this->redirect(['/cms/rights/admin']);
        }
    }
