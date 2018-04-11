<?php

namespace common\models\sinapay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sinapay\SinaWithdraw;

/**
 * SinaWithdrawSearch represents the model behind the search form about `common\models\sinapay\SinaWithdraw`.
 */
class SinaWithdrawSearch extends SinaWithdraw
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'type', 'status', 'create_at', 'update_at'], 'integer'],
            [['out_trade_no', 'identity_id', 'card_id', 'msg'], 'safe'],
            [['site_balance', 'sina_balance', 'money', 'money_fund', 'money_site', 'money_sina'], 'number'],
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
        $query = SinaWithdraw::find()->orderBy('id DESC');

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
            'site_balance' => $this->site_balance,
            'sina_balance' => $this->sina_balance,
            'money' => $this->money,
            'money_fund' => $this->money_fund,
            'money_site' => $this->money_site,
            'money_sina' => $this->money_sina,
            'type' => $this->type,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'out_trade_no', $this->out_trade_no])
            ->andFilterWhere(['like', 'identity_id', $this->identity_id])
            ->andFilterWhere(['like', 'card_id', $this->card_id])
            ->andFilterWhere(['like', 'msg', $this->msg]);

        return $dataProvider;
    }
}
