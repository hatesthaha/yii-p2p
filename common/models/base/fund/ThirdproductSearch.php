<?php

namespace common\models\base\fund;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\fund\Thirdproduct;
use backend\models\user;
use common\models\UcenterMember;

/**
 * ThirdproductSearch represents the model behind the search form about `common\models\base\fund\Thirdproduct`.
 */
class ThirdproductSearch extends Thirdproduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'start_at', 'end_at', 'invest_people', 'invest_sum', 'create_at', 'update_at', 'status', 'create_user_id', 'check_user_id'], 'integer'],
            [['title', 'intro', 'source', 'creditor', 'remarks','maxcreditor'], 'safe'],
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
        $user = User::find()->where(['id'=>\App::$app->user->identity->getId()])->one();
        if($user->role == 'admin'){
            $query = Thirdproduct::find()->andWhere(['status'=>0])->orderBy('id DESC');
        }else{
            $query = Thirdproduct::find()->andWhere(['status'=>0])->andWhere(['create_user_id' => Yii::$app->user->identity->getId()])->orderBy('id DESC');

        }

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
        $creditor = $ucmember->find()->andWhere(['username'=>$this->creditor])->one();
        $oid = $creditor? $creditor->id :'';
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
            'create_user_id' => $uid,
            'check_user_id' => $this->check_user_id,
            'ocreditor' =>$oid,
            'maxcreditor' =>$mid,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'creditor', $this->creditor])
            ->andFilterWhere(['like', 'contract', $this->contract])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);
        return $dataProvider;
    }

    public function unsearch($params)
    {
        $user = User::find()->where(['id'=>\App::$app->user->identity->getId()])->one();
        if($user->role == 'admin'){
            $query = Thirdproduct::find()->andWhere(['status'=>1])->orderBy('id DESC');
        }else{
            $query = Thirdproduct::find()->andWhere(['status'=>1])->andWhere(['create_user_id' => Yii::$app->user->identity->getId()])->orderBy('id DESC');

        }

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
        $creditor = $ucmember->find()->andWhere(['username'=>$this->creditor])->one();
        $maxcreditor = $ucmember->find()->andWhere(['username'=>$this->maxcreditor])->one();
        $user = $user->find()->andWhere(['username'=>$this->create_user_id])->one();
        $oid = $creditor? $creditor->id :'';
        $mid = $maxcreditor? $maxcreditor->id :'';
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
            'create_user_id' => $uid,
            'check_user_id' => $this->check_user_id,
            'ocreditor' =>$oid,
            'maxcreditor' =>$mid ,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'creditor', $this->creditor])
            ->andFilterWhere(['like', 'contract', $this->contract])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);
        return $dataProvider;
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function addsearch($params)
    {
        $user = User::find()->where(['id'=>\App::$app->user->identity->getId()])->one();

        if($user->role == 'admin'){
            $query = Thirdproduct::find()->orderBy('id DESC');
        }else{
            $query = Thirdproduct::find()->andWhere(['create_user_id' => Yii::$app->user->identity->getId()])->orderBy('id DESC');
        }


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
        $creditor = $ucmember->find()->andWhere(['username'=>$this->creditor])->one();
        $maxcreditor = $ucmember->find()->andWhere(['username'=>$this->maxcreditor])->one();
        $user = $user->find()->andWhere(['username'=>$this->create_user_id])->one();
        $oid = $creditor? $creditor->id :'';
        $mid = $maxcreditor? $maxcreditor->id :'';
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
            'create_user_id' => $uid,
            'check_user_id' => $this->check_user_id,
            'ocreditor' =>$oid,
            'maxcreditor' =>$mid,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'creditor', $this->creditor])
            ->andFilterWhere(['like', 'contract', $this->contract])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);
        return $dataProvider;
    }
}
