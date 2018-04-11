<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use backend\models;
use yii\base\Model;


/**
 * This is the model class for table "cms_category".
 *
 * @property integer $id
 * @property string $title
 * @property string $intro
 * @property integer $parent_id
 * @property integer $create_at
 * @property integer $update_at
 *
 * @property CmsArticle[] $cmsArticles
 */
class Category extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = -1;
    private $_statusLabel;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'create_at', 'update_at','status'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['intro'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'intro' => '简介',
            'parent_id' => '父ID',
            'create_at' => '创建时间',
            'update_at' => '开始时间',
            'status' =>'状态',
            'link' => '链接',
            'pai' => '排序',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsArticles()
    {
        return $this->hasMany(CmsArticle::className(), ['category_id' => 'id']);
    }

    public static function get($parentId = 0, $array = array(), $level = 0, $add = 2, $repeat = '　')
    {
        $strRepeat = '';
        // add some spaces or symbols for non top level categories
        if ($level>1) {
            for($j = 0; $j < $level; $j ++)
            {
                $strRepeat .= $repeat;
            }
        }

        // i feel this is useless
        if($level>0)
            $strRepeat .= '';

        $newArray = array ();
        $tempArray = array ();

        //performance is not very good here
        foreach ( ( array ) $array as $v )
        {
            if ($v['parent_id'] == $parentId)
            {
                $newArray [] = array ('id' => $v['id'], 'title' => $v['title'], 'parent_id' => $v['parent_id'], 'create_at' => $v['create_at'], 'update_at' => $v['update_at'], 'str_label' => $strRepeat.$v['title'],'intro'=>$v['intro'],'link'=>$v['link'],'pai'=>$v['pai'],'status'=>$v['status']);

                $tempArray = self::get ( $v['id'], $array, ($level + $add), $add, $repeat);
                if ($tempArray)
                {
                    $newArray = array_merge ( $newArray, $tempArray );
                }
            }
        }
        return $newArray;
    }
    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::getArrayStatus();
            $this->_statusLabel = $statuses[$this->status];
        }
        return $this->_statusLabel;
    }
    /**
     * @inheritdoc
     */
    public static function getArrayStatus()
    {
        return [

            self::STATUS_ACTIVE => Yii::t('app', '正常'),
            self::STATUS_INACTIVE => Yii::t('app', '前台不显示'),
            self::STATUS_DELETED => Yii::t('app', '删除'),
        ];
    }
}
