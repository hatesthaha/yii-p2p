<?php

namespace common\models\base\fund;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\fund\product;
use common\models\UcenterMember;
use common\models\User;

/**
 * productSearch represents the model behind the search form about `common\models\base\fund\product`.
 */
class productSearch extends product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'start_at', 'end_at', 'invest_people', 'invest_sum', 'create_at', 'update_at', 'status', 'type' ,'create_user_id', 'check_user_id'], 'integer'],
            [['title', 'intro', 'each_max', 'each_min' ,'ocreditor','maxcreditor'], 'safe'],
            [['amount', 'rate'], 'number'],
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
        $query = product::find()->orderBy('id DESC');

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
        $user = new User();
        $ocreditor = $ucmember->find()->andWhere(['username'=>$this->ocreditor])->one();
        $oid = $ocreditor? $ocreditor->id :'';
        $maxcreditor = $ucmember->find()->andWhere(['username'=>$this->maxcreditor])->one();
        $mid = $maxcreditor? $maxcreditor->id :'';
        $user = $user->find()->andWhere(['username'=>$this->create_user_id])->one();
        $uid = $user? $user->id :'';
        $query->andFilterWhere([
            'id' => $this->id,
            'amount' => $this->amount,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'rate' => $this->rate,
            'invest_people' => $this->invest_people,
            'invest_sum' => $this->invest_sum,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'status' => $this->status,
            'type' => $this->type,
            'create_user_id' => $uid,
            'check_user_id' => $this->check_user_id,
            'ocreditor' =>$oid,
            'maxcreditor' =>$mid,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'each_max', $this->each_max])
            ->andFilterWhere(['like', 'each_min', $this->each_min]);

        return $dataProvider;
    }
}
