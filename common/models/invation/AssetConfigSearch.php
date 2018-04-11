<?php

namespace common\models\invation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\invation\AssetConfig;

/**
 * AssetConfigSearch represents the model behind the search form about `common\models\invation\AssetConfig`.
 */
class AssetConfigSearch extends AssetConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'deposit_num', 'invest_num', 'withdraw_num', 'ransom_num', 'create_at', 'update_at'], 'integer'],
            [['deposit_min', 'deposit_max', 'invest_min', 'invest_max', 'withdraw_min', 'withdraw_max', 'ransom_min', 'ransom_max'], 'number'],
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
        $query = AssetConfig::find();

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
            'deposit_num' => $this->deposit_num,
            'deposit_min' => $this->deposit_min,
            'deposit_max' => $this->deposit_max,
            'invest_num' => $this->invest_num,
            'invest_min' => $this->invest_min,
            'invest_max' => $this->invest_max,
            'withdraw_num' => $this->withdraw_num,
            'withdraw_min' => $this->withdraw_min,
            'withdraw_max' => $this->withdraw_max,
            'ransom_num' => $this->ransom_num,
            'ransom_min' => $this->ransom_min,
            'ransom_max' => $this->ransom_max,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        return $dataProvider;
    }
}
