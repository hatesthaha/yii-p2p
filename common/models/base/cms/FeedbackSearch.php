<?php

namespace common\models\base\cms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\cms\Feedback;
use common\models\UcenterMember;
/**
 * FeedbackSearch represents the model behind the search form about `common\models\base\cms\Feedback`.
 */
class FeedbackSearch extends Feedback
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid'], 'integer'],
            [['feedback', 'created_at', 'updated_at'], 'safe'],
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
        $query = Feedback::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $ucmember = new UcenterMember();
        $ucmember = $ucmember->find()->andWhere(['username'=>$this->uid])->one();
        $uid = $ucmember? $ucmember->id :'';
        $query->andFilterWhere([
            'id' => $this->id,
            'uid' => $uid,
        ]);

        $query->andFilterWhere(['like', 'feedback', $this->feedback])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
