<?php

namespace common\models\base\activity;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\activity\HoldActivity;

/**
 * HoldActivityQuery represents the model behind the search form about `common\models\base\activity\HoldActivity`.
 */
class HoldActivityQuery extends HoldActivity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'activity_begin', 'activity_end', 'gold_day', 'red_bothway', 'status', 'create_at', 'update_at'], 'integer'],
            [['activity_name', 'red_money_rang'], 'safe'],
            [['gold_money', 'activity_rate'], 'number'],
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
        $query = HoldActivity::find();

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
            'activity_begin' => $this->activity_begin,
            'activity_end' => $this->activity_end,
            'gold_money' => $this->gold_money,
            'activity_rate' => $this->activity_rate,
            'gold_day' => $this->gold_day,
            'red_bothway' => $this->red_bothway,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'activity_name', $this->activity_name])
            ->andFilterWhere(['like', 'red_money_rang', $this->red_money_rang]);

        return $dataProvider;
    }
}
