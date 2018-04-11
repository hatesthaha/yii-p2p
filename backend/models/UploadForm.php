<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $contract;

    public function rules()
    {
        return [
            [['contract'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->contract->saveAs('uploads/' . $this->contract->baseName . '.' . $this->contract->extension);
            return true;
        } else {
            return false;
        }
    }
}