<?php

namespace common\models\base\asset;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\asset\Log;
use common\models\UcenterMember;
/**
 * LogSearch represents the model behind the search form about `common\models\base\asset\Log`.
 */
class LogSearch extends Log
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'status', 'create_at', 'update_at'], 'integer'],
            [['step'], 'number'],
            [['action', 'bankcard', 'remark'], 'safe'],
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
        $query = Log::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);
        if(\App::$app->request->post()){
            $time = \App::$app->request->post()['time'] ;
            if($time){
                $arr = explode('至',\App::$app->request->post()['time']);
                //var_dump($arr);
//            var_dump(strtotime($arr[0].' 00:00:00'));
//            var_dump(strtotime($arr[1].' 23:59:59'));
                $query->andWhere(['between', 'create_at',strtotime($arr[0].' 00:00:00'),strtotime($arr[1].' 23:59:59') ]);
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
            'step' => $this->step,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'bankcard', $this->bankcard])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
