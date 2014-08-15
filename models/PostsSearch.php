<?php

namespace albertborsos\yii2cms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use albertborsos\yii2cms\models\Posts;

/**
 * PostsSearch represents the model behind the search form about `albertborsos\yii2cms\models\Posts`.
 */
class PostsSearch extends Posts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language_id', 'order_num', 'created_at', 'created_user', 'updated_at', 'updated_user'], 'integer'],
            [['post_type', 'name', 'content_preview', 'content_main', 'commentable', 'date_show', 'status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Posts::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => 'order_num ASC',
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'language_id' => $this->language_id,
            'order_num' => $this->order_num,
            'date_show' => $this->date_show,
            'created_at' => $this->created_at,
            'created_user' => $this->created_user,
            'updated_at' => $this->updated_at,
            'updated_user' => $this->updated_user,
        ]);

        if ($this->post_type == 'DROP' || $this->post_type == 'MENU'){
            $query->andFilterWhere(['like', 'post_type', 'DROP'])->orFilterWhere(['like', 'post_type', 'MENU']);
        }else{
            $query->andFilterWhere(['like', 'post_type', 'BLOG']);
        }
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'content_preview', $this->content_preview])
            ->andFilterWhere(['like', 'content_main', $this->content_main])
            ->andFilterWhere(['like', 'commentable', $this->commentable])
            ->andFilterWhere(['like', 'status', $this->status]);

        $query->orderBy([
            'order_num' => SORT_ASC,
        ]);

        return $dataProvider;
    }
}
