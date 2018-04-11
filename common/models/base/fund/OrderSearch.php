<?php

namespace common\models\base\fund;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\fund\Order;
use common\models\UcenterMember;
/**
 * OrderSearch represents the model behind the search form about `common\models\base\fund\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'product_id', 'status', 'start_at', 'end_at', 'create_at', 'update_at'], 'integer'],
            [['money'], 'number'],
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
        $query = Order::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);
        if(\App::$app->request->post()) {
            $time = \App::$app->request->post()['time'];
            if ($time) {
                $arr = explode('至', \App::$app->request->post()['time']);
                //var_dump($arr);
//            var_dump(strtotime($arr[0].' 00:00:00'));
//            var_dump(strtotime($arr[1].' 23:59:59'));
                $query->andWhere(['between', 'create_at', strtotime($arr[0] . ' 00:00:00'), strtotime($arr[1] . ' 23:59:59')]);
            }
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $ucmember = new UcenterMember();

        $ucmember = $ucmember->find()->andWhere(['username'=>$this->member_id])->one();
        $uid = $ucmember? $ucmember->id :'';
        $query->andFilterWhere([
            'id' => $this->id,
            'member_id' => $uid,
            'product_id' => $this->product_id,
            'money' => $this->money,
            'status' => $this->status,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        return $dataProvider;
    }
}
