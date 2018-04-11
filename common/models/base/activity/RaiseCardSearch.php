<?php

namespace common\models\base\activity;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\activity\RaiseCard;

/**
 * RaiseCardSearch represents the model behind the search form about `common\models\base\activity\RaiseCard`.
 */
class RaiseCardSearch extends RaiseCard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['id', 'member_id', 'fund_order_id', 'use_start_at', 'use_at', 'use_out_at', 'validity_time', 'status', 'create_at', 'update_at'], 'integer'],
           // [['rate'], 'number'],
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
        $query = RaiseCard::find()->orderBy('id DESC');
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
            'member_id' => $this->member_id,
            'coupon_id' => $this->coupon_id,
            'validity_start_at' => $this->validity_start_at,
            'use_at' => $this->use_at,
            'validity_out_at' => $this->validity_out_at,
            'use_end_time' => $this->use_end_time,
            'rate' => $this->rate,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        return $dataProvider;
    }
}
