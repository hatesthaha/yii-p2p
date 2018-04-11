<?php

namespace common\models\invation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\invation\InvitationCode;

/**
 * InvitationCodeSearch represents the model behind the search form about `common\models\invation\InvitationCode`.
 */
class InvitationCodeSearch extends InvitationCode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'code', 'use_id', 'status', 'create_at', 'update_at'], 'integer'],
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
        $query = InvitationCode::find();

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
            'code' => $this->code,
            'use_id' => $this->use_id,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        return $dataProvider;
    }
}
