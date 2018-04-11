<?php

namespace common\models\cms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\cms\Lunbo;

/**
 * LunboSearch represents the model behind the search form about `common\models\cms\Lunbo`.
 */
class LunboSearch extends Lunbo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_at', 'update_at'], 'integer'],
            [['title', 'url', 'status', 'order'], 'safe'],
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
        $query = Lunbo::find()->orderBy('status desc,id desc');

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
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'order', $this->order]);

        return $dataProvider;
    }
}
