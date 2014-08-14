<?php

namespace albertborsos\yii2cms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use albertborsos\yii2cms\models\GalleryPhotos;

/**
 * GalleryPhotosSearch represents the model behind the search form about `albertborsos\yii2cms\models\GalleryPhotos`.
 */
class GalleryPhotosSearch extends GalleryPhotos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gallery_id', 'created_at', 'created_user', 'updated_at', 'updated_user'], 'integer'],
            [['filename', 'title', 'description', 'status'], 'safe'],
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
        $query = GalleryPhotos::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'gallery_id' => $this->gallery_id,
            'created_at' => $this->created_at,
            'created_user' => $this->created_user,
            'updated_at' => $this->updated_at,
            'updated_user' => $this->updated_user,
        ]);

        $query->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
