<?php

namespace common\models\sinapay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sinapay\SinaRansom;

/**
 * SinaRansomSearch represents the model behind the search form about `common\models\sinapay\SinaRansom`.
 */
class SinaRansomSearch extends SinaRansom
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['identity_id', 'out_trade_no', 'summary', 'trade_close_time', 'payer_id', 'payer_ip', 'pay_method', 'payee_out_trade_no', 'msg'], 'safe'],
            [['money_sina'], 'number'],
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
        $query = SinaRansom::find()->orderBy('id DESC');

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
            'money_sina' => $this->money_sina,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'identity_id', $this->identity_id])
            ->andFilterWhere(['like', 'out_trade_no', $this->out_trade_no])
            ->andFilterWhere(['like', 'summary', $this->summary])
            ->andFilterWhere(['like', 'trade_close_time', $this->trade_close_time])
            ->andFilterWhere(['like', 'payer_id', $this->payer_id])
            ->andFilterWhere(['like', 'payer_ip', $this->payer_ip])
            ->andFilterWhere(['like', 'pay_method', $this->pay_method])
            ->andFilterWhere(['like', 'payee_out_trade_no', $this->payee_out_trade_no])
            ->andFilterWhere(['like', 'msg', $this->msg]);

        return $dataProvider;
    }
}
