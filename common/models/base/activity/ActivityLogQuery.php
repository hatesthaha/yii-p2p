<?php

namespace common\models\base\activity;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\activity\ActivityLog;

/**
 * ActivityLogQuery represents the model behind the search form about `common\models\base\activity\ActivityLog`.
 */
class ActivityLogQuery extends ActivityLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'invite_id', 'inviter_draw', 'invitee_draw', 'status', 'create_at', 'update_at', 'end_at'], 'integer'],
            [['phone', 'invite_phone', 'actibity_source'], 'safe'],
            [['experience_money', 'red_packet'], 'number'],
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
        $query = ActivityLog::find()->orderBy('update_at desc');

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
            'invite_id' => $this->invite_id,
            'experience_money' => $this->experience_money,
            'red_packet' => $this->red_packet,
            'inviter_draw' => $this->inviter_draw,
            'invitee_draw' => $this->invitee_draw,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'end_at' => $this->end_at,
        ]);

        $query->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'invite_phone', $this->invite_phone])
            ->andFilterWhere(['like', 'actibity_source', $this->actibity_source]);

        return $dataProvider;
    }
}
