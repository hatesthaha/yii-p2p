<?php

namespace common\models\yeepay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\yeepay\Payment;

/**
 * PaymentSearch represents the model behind the search form about `common\models\yeepay\Payment`.
 */
class PaymentSearch extends Payment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'transtime', 'amount', 'orderexpdate', 'status', 'sendtime', 'ybamount', 'create_at', 'update_at'], 'integer'],
            [['orderid', 'userip', 'productname', 'identityid', 'phone', 'yborderid', 'msg'], 'safe'],
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
        $query = Payment::find()->orderBy('id DESC');

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
            'transtime' => $this->transtime,
            'amount' => $this->amount,
            'orderexpdate' => $this->orderexpdate,
            'status' => $this->status,
            'sendtime' => $this->sendtime,
            'ybamount' => $this->ybamount,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'orderid', $this->orderid])
            ->andFilterWhere(['like', 'userip', $this->userip])
            ->andFilterWhere(['like', 'productname', $this->productname])
            ->andFilterWhere(['like', 'identityid', $this->identityid])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'yborderid', $this->yborderid])
            ->andFilterWhere(['like', 'msg', $this->msg]);

        return $dataProvider;
    }
}
