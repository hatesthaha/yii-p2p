<?php

namespace common\models\base\cms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\cms\Link;

/**
 * LinkSearch represents the model behind the search form about `common\models\base\cms\Link`.
 */
class LinkSearch extends Link
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cat_id', 'status'], 'integer'],
            [['intro', 'bannar', 'link', 'created_at', 'updated_at'], 'safe'],
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
        $query = Link::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'cat_id' => $this->cat_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'bannar', $this->bannar])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
