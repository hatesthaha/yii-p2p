<?php

namespace common\models\base\setting;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BankListQuery represents the model behind the search form about `common\models\base\setting\BankList`.
 */
class BankListQuery extends BankList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_valid', 'create_at', 'update_at'], 'integer'],
            [['bank_name', 'bank_code', 'card_type', 'card_attribute'], 'safe'],
            [['binding_pay_1time_limit', 'binding_pay_time_limit', 'binding_pay_day_limit', 'binding_pay_time_min_limit'], 'number'],
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
        $query = BankList::find();

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
            'binding_pay_1time_limit' => $this->binding_pay_1time_limit,
            'binding_pay_time_limit' => $this->binding_pay_time_limit,
            'binding_pay_day_limit' => $this->binding_pay_day_limit,
            'binding_pay_time_min_limit' => $this->binding_pay_time_min_limit,
            'is_valid' => $this->is_valid,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'bank_code', $this->bank_code])
            ->andFilterWhere(['like', 'card_type', $this->card_type])
            ->andFilterWhere(['like', 'card_attribute', $this->card_attribute]);

        return $dataProvider;
    }
}
