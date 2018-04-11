<?php

namespace common\models\base\experience;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\experience\Rule;

/**
 * RuleSearch represents the model behind the search form about `common\models\base\experience\Rule`.
 */
class RuleSearch extends Rule
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'status','time'], 'integer'],
            [['money'], 'number'],
            [['start_at', 'end_at', 'title'], 'safe'],
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
        $query = Rule::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'money' => $this->money,
            'time' => $this->time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'start_at', $this->start_at])
            ->andFilterWhere(['like', 'end_at', $this->end_at])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
