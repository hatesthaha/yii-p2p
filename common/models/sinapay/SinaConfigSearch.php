<?php

namespace common\models\sinapay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sinapay\SinaConfig;

/**
 * SinaConfigSearch represents the model behind the search form about `common\models\sinapay\SinaConfig`.
 */
class SinaConfigSearch extends SinaConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sinapay_give_accrual', 'create_at', 'update_at'], 'integer'],
            [['sinapay_site_prefix', 'sinapay_version', 'sinapay_partner_id', 'sign_type', 'sinapay_md5_key', 'sinapay_input_charset', 'sinapay_rsa_sign_private_key', 'sinapay_rsa_sign_public_key', 'sinapay_rsa_public__key', 'sinapay_mgs_url', 'sinapay_mas_url', 'sinapay_site_email'], 'safe'],
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
        $query = SinaConfig::find();

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
            'sinapay_give_accrual' => $this->sinapay_give_accrual,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'sinapay_site_prefix', $this->sinapay_site_prefix])
            ->andFilterWhere(['like', 'sinapay_version', $this->sinapay_version])
            ->andFilterWhere(['like', 'sinapay_partner_id', $this->sinapay_partner_id])
            ->andFilterWhere(['like', 'sign_type', $this->sign_type])
            ->andFilterWhere(['like', 'sinapay_md5_key', $this->sinapay_md5_key])
            ->andFilterWhere(['like', 'sinapay_input_charset', $this->sinapay_input_charset])
            ->andFilterWhere(['like', 'sinapay_rsa_sign_private_key', $this->sinapay_rsa_sign_private_key])
            ->andFilterWhere(['like', 'sinapay_rsa_sign_public_key', $this->sinapay_rsa_sign_public_key])
            ->andFilterWhere(['like', 'sinapay_rsa_public__key', $this->sinapay_rsa_public__key])
            ->andFilterWhere(['like', 'sinapay_mgs_url', $this->sinapay_mgs_url])
            ->andFilterWhere(['like', 'sinapay_mas_url', $this->sinapay_mas_url])
            ->andFilterWhere(['like', 'sinapay_site_email', $this->sinapay_site_email]);

        return $dataProvider;
    }
}
