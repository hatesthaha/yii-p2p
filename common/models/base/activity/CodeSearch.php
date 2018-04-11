<?php

namespace common\models\base\activity;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\activity\Code;

/**
 * CodeSearch represents the model behind the search form about `common\models\base\activity\Code`.
 */
class CodeSearch extends Code
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'display'], 'integer'],
            [['name', 'validity_time', 'rate', 'use_end_time', 'created_at', 'updated_at'], 'safe'],
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
        $query = Code::find()->orderBy('id DESC');

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
            'status' => $this->status,
            'display' => $this->display,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'validity_time', $this->validity_time])
            ->andFilterWhere(['like', 'rate', $this->rate])
            ->andFilterWhere(['like', 'use_end_time', $this->use_end_time])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
