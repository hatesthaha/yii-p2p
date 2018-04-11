<?php

namespace common\models\base\asset;

use Yii;

/**
 * This is the model class for table "asset_log".
 *
 * @property integer $id
 * @property integer $member_id
 * @property double $step
 * @property string $action
 * @property integer $status
 * @property string $bankcard
 * @property string $remark
 * @property integer $create_at
 * @property integer $update_at
 *
 * @property UcenterMember $member
 */
class AssetLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asset_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'status', 'create_at', 'update_at'], 'integer'],
            [['step'], 'number'],
            [['action'], 'string', 'max' => 100],
            [['bankcard'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'step' => 'Step',
            'action' => 'Action',
            'status' => 'Status',
            'bankcard' => 'Bankcard',
            'remark' => 'Remark',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'member_id']);
    }
}
