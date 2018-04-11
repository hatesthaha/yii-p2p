<?php

namespace common\models\sinapay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sinapay\SinaBank;

/**
 * SinaBankSearch represents the model behind the search form about `common\models\sinapay\SinaBank`.
 */
class SinaBankSearch extends SinaBank
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['identity_id', 'request_no', 'bank_code', 'bank_name', 'bank_account_no', 'card_type', 'card_attribute', 'phone_no', 'province', 'city', 'bank_branch', 'ticket', 'valid_code', 'card_id', 'msg'], 'safe'],
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
        $query = SinaBank::find()->orderBy('id DESC');

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
            ->andFilterWhere(['like', 'request_no', $this->request_no])
            ->andFilterWhere(['like', 'bank_code', $this->bank_code])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
            ->andFilterWhere(['like', 'card_type', $this->card_type])
            ->andFilterWhere(['like', 'card_attribute', $this->card_attribute])
            ->andFilterWhere(['like', 'phone_no', $this->phone_no])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'bank_branch', $this->bank_branch])
            ->andFilterWhere(['like', 'ticket', $this->ticket])
            ->andFilterWhere(['like', 'valid_code', $this->valid_code])
            ->andFilterWhere(['like', 'card_id', $this->card_id])
            ->andFilterWhere(['like', 'msg', $this->msg]);

        return $dataProvider;
    }
}
