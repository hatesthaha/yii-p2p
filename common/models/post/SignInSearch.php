<?php

namespace common\models\post;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\post\SignIn;

/**
 * SignInSearch represents the model behind the search form about `common\models\post\SignIn`.
 */
class SignInSearch extends SignIn
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'sign_in_time', 'create_at', 'update_at'], 'integer'],
            [['sign_in_money'], 'number'],
            [['sign_in_ip', 'sign_in_from'], 'safe'],
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
        $query = SignIn::find();

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
            'uid' => $this->uid,
            'sign_in_time' => $this->sign_in_time,
            'sign_in_money' => $this->sign_in_money,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'sign_in_ip', $this->sign_in_ip])
            ->andFilterWhere(['like', 'sign_in_from', $this->sign_in_from]);

        return $dataProvider;
    }
}
