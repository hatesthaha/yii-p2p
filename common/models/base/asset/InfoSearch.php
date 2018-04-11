<?php

namespace common\models\base\asset;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\asset\Info;
use common\models\UcenterMember;
/**
 * InfoSearch represents the model behind the search form about `common\models\base\asset\Info`.
 */
class InfoSearch extends Info
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'create_at', 'update_at'], 'integer'],
            [['bank_card', 'bank_card_phone'], 'safe'],
            [['balance', 'freeze'], 'number'],
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
    public function search($params,$order = 'member_id DESC')
    {
        $query = Info::find()->orderBy($order);

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
        $ucmember = new UcenterMember();

        $ucmember = $ucmember->find()->andWhere(['username'=>$this->member_id])->one();
        $uid = $ucmember? $ucmember->id :'';
        $query->andFilterWhere([
            'member_id' =>$uid,
            'balance' => $this->balance,
            'freeze' => $this->freeze,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'bank_card', $this->bank_card])
            ->andFilterWhere(['like', 'bank_card_phone', $this->bank_card_phone]);

        return $dataProvider;
    }
}
