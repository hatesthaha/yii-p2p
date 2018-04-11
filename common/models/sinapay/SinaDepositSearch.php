<?php

namespace common\models\sinapay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sinapay\SinaDeposit;

/**
 * SinaDepositSearch represents the model behind the search form about `common\models\sinapay\SinaDeposit`.
 */
class SinaDepositSearch extends SinaDeposit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['identity_id', 'out_trade_no', 'account_type', 'amount', 'payer_ip', 'pay_method', 'ticket', 'validate_code', 'msg'], 'safe'],
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
        $query = SinaDeposit::find()->orderBy('id DESC');

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
            ->andFilterWhere(['like', 'account_type', $this->account_type])
            ->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'payer_ip', $this->payer_ip])
            ->andFilterWhere(['like', 'pay_method', $this->pay_method])
            ->andFilterWhere(['like', 'ticket', $this->ticket])
            ->andFilterWhere(['like', 'validate_code', $this->validate_code])
            ->andFilterWhere(['like', 'msg', $this->msg]);

        return $dataProvider;
    }
}
