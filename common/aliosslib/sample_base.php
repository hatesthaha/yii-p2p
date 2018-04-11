<?php
require_once 'sdk.class.php';
require_once 'util/oss_util.class.php';

//关于endpoint的介绍见, endpoint就是OSS访问的域名
//http://docs.aliyun.com/?spm=5176.7114037.1996646101.11.XMMlZa&pos=6#/oss/product-documentation/domain-region
class SampleUtil 
{
    const endpoint = OSS_ENDPOINT;
    const accessKeyId = OSS_ACCESS_ID;
    const accesKeySecret = OSS_ACCESS_KEY;
    const bucket = OSS_STATIC_BUCKET;

    public static function get_oss_client() {
        $oss = new ALIOSS(self::accessKeyId, self::accesKeySecret, self::endpoint);
        return $oss;
    }

    public static function my_echo($msg) {
        $new_line = " \n";
        echo $msg . $new_line;
    }

    public static function get_bucket_name() {
        return self::bucket;
    }

    public static function create_bucket() {
        $oss = self::get_oss_client();
        $bucket = self::get_bucket_name();
        $acl = ALIOSS::OSS_ACL_TYPE_PUBLIC_READ;
        $res = $oss->create_bucket($bucket, $acl);
        $msg = "创建bucket " . $bucket;
        OSSUtil::print_res($res, $msg);
    }

    public static function upload_image($obj_path, $file_path){
        $bucket = SampleUtil::get_bucket_name();
        $oss = SampleUtil::get_oss_client();
        SampleUtil::create_bucket();
        $object = $obj_path;

        /*%**************************************************************************************************************%*/
        // Multipart 相关的示例
        /**
         *通过multipart上传文件
         *如果上传的文件小于partSize,则直接使用普通方式上传
         */
        $filepath = $file_path;
        $options = array(
            ALIOSS::OSS_FILE_UPLOAD => $filepath,
            'partSize' => 5242880,
        );
        $res = $oss->create_mpu_object($bucket, $object,$options);
        $msg = "通过multipart上传文件";
        OSSUtil::print_res($res, $msg);


    }
}
