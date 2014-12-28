<?php

namespace albertborsos\yii2cms\controllers;

use albertborsos\yii2cms\components\DataProvider;
use albertborsos\yii2cms\models\ContactForm;
use albertborsos\yii2cms\models\Galleries;
use albertborsos\yii2cms\models\Posts;
use albertborsos\yii2lib\helpers\File;
use albertborsos\yii2lib\helpers\S;
use albertborsos\yii2lib\web\Controller;
use albertborsos\yii2tagger\models\Tags;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\YiiAsset;

class DefaultController extends Controller
{
    public function init()
    {
        parent::init();
        $this->defaultAction = 'index';
        $this->name          = 'Alap';
        $this->layout        = '//center';
        $this->setTheme('page');
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'blog', 'redirecttohome'],
                        'allow'   => true,
                        'matchCallback' => function(){
                            return Yii::$app->user->can('guest');
                        }
                    ]
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


    public function actionIndex($title = null, $id = null)
    {
        $this->setTheme('page');
        if (!is_null($id)){
            // menu or blog posts
            $post = Posts::findOne([
                'id' => $id,
                'status' => DataProvider::STATUS_ACTIVE,
            ]);
        }else{
            // ha a kezdőlap az első oldal
            $post = Posts::find([
                'lang' => 'hu',
                'status' => DataProvider::STATUS_ACTIVE,
            ])->orderBy(['order_num' => 'ASC'])->one();
        }
        if (!is_null($post)){ /** @var $post Posts */
            $this->breadcrumbs = [$post->name];

            $post->checkUrlIsCorrect();
            $post->setSEOValues();
            // set SEO values @todo
            $content = $post->setContent();
            // $content .= disqus

            $content = $this->module->replaceItems($content);

            return $this->render('index', [
                'content' => $content,
            ]);
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

        $posts = Posts::findBySql('SELECT * FROM '.Posts::tableName().' WHERE post_type=:type_blog AND status=:status_a ORDER BY date_show DESC', [
            ':type_blog' => Posts::TYPE_BLOG,
            ':status_a' => DataProvider::STATUS_ACTIVE,
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
