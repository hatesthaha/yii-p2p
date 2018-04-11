<?php
/**
 * Created by PhpStorm.
 * User: wanhu
 * Date: 2015/7/8
 * Time: 9:11
 */

namespace common\models\fund;
use yii\behaviors\TimestampBehavior;

class Thirdproduct extends \common\models\base\fund\thirdproduct
{
	const PROCESS_STATUS_INACTIVE = 0;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = -1;
    const STATUS_REGECT = 3;
    const STATUS_SOLD = 4;
    private $_statusLabel;
    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::labels();
            $this->_statusLabel = $statuses[$this->status];
        }
        return $this->_statusLabel;
    }
    public static function labels()
    {
        return [
            self::STATUS_ACTIVE => "已审核",
            self::STATUS_INACTIVE => "未审核",
            self::STATUS_REGECT => "驳回",
            self::STATUS_SOLD => "已完成初次投资",
            self::STATUS_DELETED => "删除",
        ];
    }
    public static function getlink(){

    }
    public function getLabel($id)
    {
        $labels = self::labels();
        return isset($labels[$id]) ? $labels[$id] : null;
    }

}