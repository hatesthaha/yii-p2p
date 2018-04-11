<?php

namespace common\models\sinapay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sinapay\SinaFreeze;

/**
 * SinaFreezeSearch represents the model behind the search form about `common\models\sinapay\SinaFreeze`.
 */
class SinaFreezeSearch extends SinaFreeze
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['identity_id', 'account_type', 'out_freeze_no', 'freeze_summary', 'msg', 'out_unfreeze_no', 'unfreeze_summary'], 'safe'],
            [['freeze_money', 'unfreeze_money'], 'number'],
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
        $query = SinaFreeze::find();

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
            'freeze_money' => $this->freeze_money,
            'status' => $this->status,
            'unfreeze_money' => $this->unfreeze_money,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'identity_id', $this->identity_id])
            ->andFilterWhere(['like', 'account_type', $this->account_type])
            ->andFilterWhere(['like', 'out_freeze_no', $this->out_freeze_no])
            ->andFilterWhere(['like', 'freeze_summary', $this->freeze_summary])
            ->andFilterWhere(['like', 'msg', $this->msg])
            ->andFilterWhere(['like', 'out_unfreeze_no', $this->out_unfreeze_no])
            ->andFilterWhere(['like', 'unfreeze_summary', $this->unfreeze_summary]);

        return $dataProvider;
    }
}
