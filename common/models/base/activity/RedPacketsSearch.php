<?php

namespace common\models\base\activity;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\activity\RedPackets;

/**
 * RedPacketsSearch represents the model behind the search form about `common\models\base\activity\RedPackets`.
 */
class RedPacketsSearch extends RedPackets
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'start_at', 'end_at', 'create_at', 'update_at'], 'integer'],
            [['amount', 'each_max', 'each_min'], 'number'],
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
        $query = RedPackets::find()->orderBy('id DESC');

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
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'amount' => $this->amount,
            'each_max' => $this->each_max,
            'each_min' => $this->each_min,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        return $dataProvider;
    }
}
