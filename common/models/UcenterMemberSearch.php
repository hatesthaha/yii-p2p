<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UcenterMember;

/**
 * UcenterMemberSearch represents the model behind the search form about `common\models\UcenterMember`.
 */
class UcenterMemberSearch extends UcenterMember
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status','lock', 'type','created_at', 'updated_at', 'create_channel', 'error_num', 'parent_member_id', 'vip'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'phone', 'email', 'idcard', 'real_name', 'create_ip', 'create_area', 'login_ip', 'login_area'], 'safe'],
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
        $query = UcenterMember::find()->orderBy('id DESC');

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
                $query->andWhere(['between', 'created_at', strtotime($arr[0] . ' 00:00:00'), strtotime($arr[1] . ' 23:59:59')]);
            }
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'lock' => $this->lock,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'create_channel' => $this->create_channel,
            'error_num' => $this->error_num,
            'parent_member_id' => $this->parent_member_id,
            'vip' => $this->vip,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'idcard', $this->idcard])
            ->andFilterWhere(['like', 'real_name', $this->real_name])
            ->andFilterWhere(['like', 'create_ip', $this->create_ip])
            ->andFilterWhere(['like', 'create_area', $this->create_area])
            ->andFilterWhere(['like', 'login_ip', $this->login_ip])
            ->andFilterWhere(['like', 'login_area', $this->login_area]);

        return $dataProvider;
    }
}
