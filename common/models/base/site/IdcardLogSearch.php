<?php

namespace common\models\base\site;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\site\IdcardLog;

/**
 * IdcardLogSearch represents the model behind the search form about `common\models\base\site\IdcardLog`.
 */
class IdcardLogSearch extends IdcardLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['name', 'idcard', 'address', 'sex', 'birthday', 'remark'], 'safe'],
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
        $query = IdcardLog::find()->orderBy('id DESC');

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
            'uid' => $this->uid,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'idcard', $this->idcard])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'birthday', $this->birthday])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
