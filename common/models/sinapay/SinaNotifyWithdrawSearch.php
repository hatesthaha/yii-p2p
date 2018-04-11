<?php

namespace common\models\sinapay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SinaNotifyWithdrawSearch represents the model behind the search form about `common\models\sinapay\SinaNotifyWithdraw`.
 */
class SinaNotifyWithdrawSearch extends SinaNotifyWithdraw
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['outer_trade_no', 'inner_trade_no', 'withdraw_status', 'card_id'], 'safe'],
            [['withdraw_amount'], 'number'],
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
        $query = SinaNotifyWithdraw::find();

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
            'withdraw_amount' => $this->withdraw_amount,
        ]);

        $query->andFilterWhere(['like', 'outer_trade_no', $this->outer_trade_no])
            ->andFilterWhere(['like', 'inner_trade_no', $this->inner_trade_no])
            ->andFilterWhere(['like', 'withdraw_status', $this->withdraw_status])
            ->andFilterWhere(['like', 'card_id', $this->card_id]);

        return $dataProvider;
    }
}
