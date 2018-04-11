<?php

namespace common\models\lianlian;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\lianlian\payLL;

/**
 * payLLSearch represents the model behind the search form about `common\models\lianlian\payLL`.
 */
class payLLSearch extends payLL
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['idcard', 'real_name', 'user_id', 'busi_partne', 'no_order', 'name_goods', 'money_order', 'card_no', 'from_ip', 'bank_code', 'remark', 'sign_type', 'sign', 'oid_paybill', 'money_lianlian', 'settle_date', 'pay_type'], 'safe'],
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
        $query = payLL::find()->orderBy('id DESC');

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

        $query->andFilterWhere(['like', 'idcard', $this->idcard])
            ->andFilterWhere(['like', 'real_name', $this->real_name])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'busi_partne', $this->busi_partne])
            ->andFilterWhere(['like', 'no_order', $this->no_order])
            ->andFilterWhere(['like', 'name_goods', $this->name_goods])
            ->andFilterWhere(['like', 'money_order', $this->money_order])
            ->andFilterWhere(['like', 'card_no', $this->card_no])
            ->andFilterWhere(['like', 'from_ip', $this->from_ip])
            ->andFilterWhere(['like', 'bank_code', $this->bank_code])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'sign_type', $this->sign_type])
            ->andFilterWhere(['like', 'sign', $this->sign])
            ->andFilterWhere(['like', 'oid_paybill', $this->oid_paybill])
            ->andFilterWhere(['like', 'money_lianlian', $this->money_lianlian])
            ->andFilterWhere(['like', 'settle_date', $this->settle_date])
            ->andFilterWhere(['like', 'pay_type', $this->pay_type]);

        return $dataProvider;
    }
}
