<?php

namespace common\models\base\ucenter;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\ucenter\Log;
use common\models\UcenterMember;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * LogSearch represents the model behind the search form about `common\models\base\ucenter\Log`.
 */
class LogSearch extends Log
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'user_id', 'login_time', 'status', 'create_at', 'update_at'], 'integer'],
            [['login_ip', 'login_area'], 'safe'],
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
            'user_id' => $this->user_id,
            'login_time' => $this->login_time,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'login_ip', $this->login_ip])
            ->andFilterWhere(['like', 'login_area', $this->login_area]);

        return $dataProvider;
    }
}
