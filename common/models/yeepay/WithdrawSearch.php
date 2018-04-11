<?php

namespace common\models\yeepay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\yeepay\Withdraw;

/**
 * WithdrawSearch represents the model behind the search form about `common\models\yeepay\Withdraw`.
 */
class WithdrawSearch extends Withdraw
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'amount', 'status', 'create_at', 'update_at'], 'integer'],
            [['identityid', 'identitytype', 'card_top', 'card_last', 'userip', 'ybdrawflowid', 'msg'], 'safe'],
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
        $query = Withdraw::find()->orderBy('id DESC');

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
            'amount' => $this->amount,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'identityid', $this->identityid])
            ->andFilterWhere(['like', 'identitytype', $this->identitytype])
            ->andFilterWhere(['like', 'card_top', $this->card_top])
            ->andFilterWhere(['like', 'card_last', $this->card_last])
            ->andFilterWhere(['like', 'userip', $this->userip])
            ->andFilterWhere(['like', 'ybdrawflowid', $this->ybdrawflowid])
            ->andFilterWhere(['like', 'msg', $this->msg]);

        return $dataProvider;
    }
}
