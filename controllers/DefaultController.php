<?php

namespace albertborsos\yii2cms\controllers;

use albertborsos\yii2cms\components\DataProvider;
use albertborsos\yii2cms\models\Posts;
use albertborsos\yii2lib\helpers\S;
use albertborsos\yii2lib\helpers\Seo;
use albertborsos\yii2lib\web\Controller;
use albertborsos\yii2tagger\models\Tags;
use Yii;
use yii\filters\AccessControl;

class DefaultController extends Controller
{
    public function init()
    {
        parent::init();
        $this->defaultAction = 'index';
        $this->name          = 'Alap';
        $this->layout        = '//center';
        $this->setTheme('page');
        $this->addActionNames(['migrate-up' => 'Migráció']);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'blog', 'redirecttohome', 'error', 'migrate-up'],
                        'allow'   => true,
                        'matchCallback' => function(){
                            return Yii::$app->user->can('guest');
                        }
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionMigrateUp(){
        $content = DataProvider::migrateUp();
        Seo::noIndex();
        Yii::$app->cache->flush();
        return $this->render('index', [
            'content' => $content,
        ]);
    }

    public function actionIndex($title = null, $id = null)
    {
        $this->setTheme('page');
        if (!is_null($id)){
            // menu or blog posts
            $post = Posts::findOne([
                'id' => $id,
                'status' => [
                    DataProvider::STATUS_ACTIVE,
                    DataProvider::STATUS_INACTIVE,
                ],
            ]);
        }else{
            // ha nincs ID, akkor a kezdőlap az első oldal
            $post = Posts::find([
                'lang' => 'hu',
                'status' => DataProvider::STATUS_ACTIVE,
            ])->orderBy(['order_num' => 'ASC'])->one();
        }
        if (!is_null($post)){ /** @var $post Posts */
            if(is_null($id)){
                $this->breadcrumbs = false;
            }else{
                $this->breadcrumbs = [$post->name];
            }

            $post->checkUrlIsCorrect();
            $post->setSEOValues();
            $content = $post->setContent();

            $content = $this->module->replaceItems($content);

            if($content === true){
                return $this->redirect(Yii::$app->request->url);
            }else{
                return $this->render('index', [
                    'content' => $content,
                ]);
            }
        }else{
            Yii::$app->session->setFlash('error', '<h4>Ilyen bejegyzés nem létezik!</h4>');
            return $this->goBack(['/']);
        }
    }

    /**
     * Listázza az összes aktív blogbejegyzést
     */
    public function actionBlog(){
        $this->setTheme('page');
        Yii::$app->getView()->title = 'Blog | '.Yii::$app->name;

        $this->breadcrumbs = ['Blog'];

        $posts = Posts::find()->where([
            'post_type' => Posts::TYPE_BLOG,
            'status' => DataProvider::STATUS_ACTIVE,
        ])->orderBy([
            'date_show' => SORT_ASC,
        ])->all();

        $content = '';
        if (empty($posts)){
            $content .= '<legend>Egyelőre nincsenek bejegyzések!</legend>';
        }else{
            foreach($posts as $post){
                $content .= $this->renderPartial('_post_preview', [
                    'post' => $post,
                    'tags' => Tags::getAssignedTags($post, true, 'link'),
                ]);
            }
        }

        return $this->render('index', [
            'content' => $content,
        ]);
    }

    public function actionRedirecttohome(){
        Yii::$app->session->setFlash('info','<h4>Szia! A régi weboldalam megszűnt!</h4><p>Az a tartalom, amit kerestél már nem létezik. De azért nézz körbe hátha találsz néhány hasznos információt!</p>');
        return $this->redirect('/', 301);
    }
}
