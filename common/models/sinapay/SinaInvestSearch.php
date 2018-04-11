<?php

namespace common\models\sinapay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sinapay\SinaInvest;

/**
 * SinaInvestSearch represents the model behind the search form about `common\models\sinapay\SinaInvest`.
 */
class SinaInvestSearch extends SinaInvest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['identity_id', 'out_trade_no', 'summary', 'trade_close_time', 'payer_ip', 'pay_type', 'account_type', 'goods_id', 'money', 'msg', 'payee_out_trade_no', 'payee_identity_id', 'payee_account_type', 'payee_amount', 'payee_summary'], 'safe'],
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
        $query = SinaInvest::find()->orderBy('id DESC');

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
            'uid' => $this->uid,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'identity_id', $this->identity_id])
            ->andFilterWhere(['like', 'out_trade_no', $this->out_trade_no])
            ->andFilterWhere(['like', 'summary', $this->summary])
            ->andFilterWhere(['like', 'trade_close_time', $this->trade_close_time])
            ->andFilterWhere(['like', 'payer_ip', $this->payer_ip])
            ->andFilterWhere(['like', 'pay_type', $this->pay_type])
            ->andFilterWhere(['like', 'account_type', $this->account_type])
            ->andFilterWhere(['like', 'goods_id', $this->goods_id])
            ->andFilterWhere(['like', 'money', $this->money])
            ->andFilterWhere(['like', 'msg', $this->msg])
            ->andFilterWhere(['like', 'payee_out_trade_no', $this->payee_out_trade_no])
            ->andFilterWhere(['like', 'payee_identity_id', $this->payee_identity_id])
            ->andFilterWhere(['like', 'payee_account_type', $this->payee_account_type])
            ->andFilterWhere(['like', 'payee_amount', $this->payee_amount])
            ->andFilterWhere(['like', 'payee_summary', $this->payee_summary]);

        return $dataProvider;
    }
}
