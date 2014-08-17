<?php

namespace albertborsos\yii2cms\controllers;

use albertborsos\yii2cms\components\DataProvider;
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
        $this->actionName = ArrayHelper::merge($this->actionName, [
            'themeeditor' => 'Téma Szerkesztő',
        ]);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['themeeditor'], // editor+
                'rules' => [
                    [
                        'actions' => ['themeeditor'],
                        'allow'   => true,
                        'matchCallback' => function(){
                                return Yii::$app->user->can('editor');
                            }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete'            => ['post']
                ],
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
            if (!is_null($post)){
                $post->checkUrlIsCorrect();
                // set SEO values @todo
                $content = $post->setContent();
                // $content .= disqus

                $content = Galleries::insertGallery($content);

                return $this->render('index', [
                    'content' => $content,
                ]);
            }else{
                Yii::$app->session->setFlash('error', '<h4>Ilyen bejegyzés nem létezik!</h4>');
                return $this->goBack(['/']);
            }
        }else{
            return $this->goHome();
        }
    }

    /**
     * Listázza az összes aktív blogbejegyzést
     */
    public function actionBlog(){
        $this->setTheme('page');

        $this->breadcrumbs = ['Blog'];
        $posts = Posts::findBySql('SELECT * FROM '.Posts::tableName().' WHERE post_type=:type_blog AND status=:status_a ORDER BY date_show DESC', [
            ':type_blog' => 'BLOG',
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

    public function actionThemeeditor($fileName = null, $theme = 'page'){

        $fileContent = null;
        $path = Yii::$app->getBasePath().'/../common/themes/'.$theme.'/views/layouts';

        if (Yii::$app->request->isPost){
            $newContent = Yii::$app->request->post('file-editor');
            $result = File::setContent($path.'/'.$fileName, $newContent);
            if ($result === true){
                Yii::$app->session->setFlash('success', '<h4>Fájl tartalma sikeresen módosítva!</h4>');
            }else{
                Yii::$app->session->setFlash('error', '<h4>'.$result.'</h4>');
            }
        }

        if (!is_null($fileName)){
            // ha választott ki filet
            $options = ['only' => [$fileName]];

            $editedFile = $path . '/' . $fileName;

            if (is_file($editedFile)) {
                $fileContent = file_get_contents($editedFile);
            }
        }
        $options = ['only' => ['tpl_footer*', 'tpl_sidebar*']];
        $filesDataProvider = File::dirContentToDataProvider($path, $options);


        return $this->render('themeeditor', [
            'filesDataProvider' => $filesDataProvider,
            'fileContent' => $fileContent,
        ]);
    }
}
