<?php

namespace common\models\sinapay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sinapay\SiteSinaBalance;

/**
 * SiteSinaBalanceSearch represents the model behind the search form about `common\models\sinapay\SiteSinaBalance`.
 */
class SiteSinaBalanceSearch extends SiteSinaBalance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['identity_id', 'phone', 'user_name', 'bank_card', 'sina_bonus', 'create_time', 'msg'], 'safe'],
            [['site_balance', 'sina_available_balance', 'user_earnings', 'sina_balance', 'sina_bonus_day', 'sina_bonus_month', 'sina_bonus_sum'], 'number'],
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
        $query = SiteSinaBalance::find()->orderBy('user_earnings asc,id desc');

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
            'uid' => $this->uid,
            'site_balance' => $this->site_balance,
            'sina_available_balance' => $this->sina_available_balance,
            'user_earnings' => $this->user_earnings,
            'sina_balance' => $this->sina_balance,
            'sina_bonus_day' => $this->sina_bonus_day,
            'sina_bonus_month' => $this->sina_bonus_month,
            'sina_bonus_sum' => $this->sina_bonus_sum,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'identity_id', $this->identity_id])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'bank_card', $this->bank_card])
            ->andFilterWhere(['like', 'sina_bonus', $this->sina_bonus])
            ->andFilterWhere(['like', 'create_time', $this->create_time])
            ->andFilterWhere(['like', 'msg', $this->msg]);

        return $dataProvider;
    }
}
