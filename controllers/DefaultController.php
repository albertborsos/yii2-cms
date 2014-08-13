<?php

namespace albertborsos\yii2cms\controllers;

use albertborsos\yii2cms\components\DataProvider;
use albertborsos\yii2cms\models\Galleries;
use albertborsos\yii2cms\models\Posts;
use albertborsos\yii2lib\helpers\S;
use albertborsos\yii2lib\web\Controller;
use albertborsos\yii2tagger\models\Tags;
use Yii;

class DefaultController extends Controller
{
    public function init()
    {
        parent::init();
        $this->defaultAction = 'index';
        $this->name          = 'Alap';
        $this->layout        = '//center';
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
}
