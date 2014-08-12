<?php

namespace albertborsos\yii2cms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use albertborsos\yii2cms\models\Galleries;

/**
 * GalleriesSearch represents the model behind the search form about `albertborsos\yii2cms\models\Galleries`.
 */
class GalleriesSearch extends Galleries
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'created_user', 'updated_at', 'updated_user'], 'integer'],
            [['replace_id', 'name', 'status'], 'safe'],
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
        $query = Galleries::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'created_user' => $this->created_user,
            'updated_at' => $this->updated_at,
            'updated_user' => $this->updated_user,
        ]);

        $query->andFilterWhere(['like', 'replace_id', $this->replace_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
