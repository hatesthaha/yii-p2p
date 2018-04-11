<?php
/**
 * @author: wuwenhan <329576084@qq.com>
 */


namespace framework\helpers;

use yii;
use yii\swiftmailer\Mailer;

class Utils
{
    /**
     * @param $to
     * @param $subject
     * @param $body
     * @return \yii\mail\MessageInterface
     */
    public static function sendEmail($to, $subject, $body)
    {
        $email = \App::$app->mailer->compose();
        $email->setFrom('wanhunet@sina.com')
            ->setTo($to)
            ->setSubject($subject)
            ->setTextBody($body)
            ->send();
        return $email;
    }

    /**
     * @param $startstr
     * @return string
     */
    public static function alldaytostr($startstr) {
        $oneday_count = 3600 * 24;  //一天有多少秒
        //明天
        $tomorrow_s = $startstr + $oneday_count;    //明天开始
        $tomorrow_e = $tomorrow_s + $oneday_count - 1;  //明天结束
        //昨天
        $yesterday_s = $startstr - $oneday_count;  //昨天开始
        $yesterday_e = $startstr - 1;   //昨天结束
        //今天结束
        $today_e = $tomorrow_s - 1;
        //昨天0点和当天23点59分59秒合并成数组
        $allday_array =array($yesterday_s, $yesterday_e);
        return $allday_array;
    }

    public static function count_days($a,$b){
        $a_dt=getdate($a);
        $b_dt=getdate($b);
        $a_new=mktime(12,0,0,$a_dt['mon'],$a_dt['mday'],$a_dt['year']);
        $b_new=mktime(12,0,0,$b_dt['mon'],$b_dt['mday'],$b_dt['year']);
        return round(abs($a_new-$b_new)/86400);
    }
    /**
     * 获取上个季度的开始和结束日期
     * @param int $ts 时间戳
     * @return array 第一个元素为开始日期，第二个元素为结束日期
     */
    public static function lastQuarter($ts) {
        $ts = intval($ts);

        $threeMonthAgo = mktime(0, 0, 0, date('n', $ts) - 3, 1, date('Y', $ts));
        $year = date('Y', $threeMonthAgo);
        $month = date('n', $threeMonthAgo);
        $startMonth = intval(($month - 1)/3)*3 + 1; // 上季度开始月份
        $endMonth = $startMonth + 2; // 上季度结束月份
        return array(
            date('Y-m-1', strtotime($year . "-{$startMonth}-1")),
            date('Y-m-t', strtotime($year . "-{$endMonth}-1"))
        );
    }

    /**
     * 获取上个月的开始和结束
     * @param int $ts 时间戳
     * @return array 第一个元素为开始日期，第二个元素为结束日期
     */
    public static function lastMonth($ts) {
        $ts = intval($ts);

        $oneMonthAgo = mktime(0, 0, 0, date('n', $ts) - 1, 1, date('Y', $ts));
        $year = date('Y', $oneMonthAgo);
        $month = date('n', $oneMonthAgo);
        return array(
            date('Y-m-1', strtotime($year . "-{$month}-1")),
            date('Y-m-t', strtotime($year . "-{$month}-1"))
        );
    }

    /**
     * 获取上n周的开始和结束，每周从周一开始，周日结束日期
     * @param int $ts 时间戳
     * @param int $n 你懂的(前多少周)
     * @param string $format 默认为'%Y-%m-%d',比如"2012-12-18"
     * @return array 第一个元素为开始日期，第二个元素为结束日期
     */
    public static function lastNWeek($ts, $n, $format = '%Y-%m-%d') {
        $ts = intval($ts);
        $n  = abs(intval($n));

        // 周一到周日分别为1-7
        $dayOfWeek = date('w', $ts);
        if (0 == $dayOfWeek)
        {
            $dayOfWeek = 7;
        }

        $lastNMonday = 7 * $n + $dayOfWeek - 1;
        $lastNSunday = 7 * ($n - 1) + $dayOfWeek;
        return array(
            strftime($format, strtotime("-{$lastNMonday} day", $ts)),
            strftime($format, strtotime("-{$lastNSunday} day", $ts))
        );
    }

    public static function createcode($length = 8)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

        // 在 $chars 中随机取 $length 个数组元素键名
        $keys = array_rand($chars, $length);

        $password = '';
        for($i = 0; $i < $length; $i++)
        {
            // 将 $length 个数组元素连接成字符串
            $password .= $chars[$keys[$i]];
        }

        return $password;
    }
    public static function moneyFormat($money)
    {
        return sprintf("%.5f", $money);
    }

    /**
     * 导出数据为excel表格
     * @param array $data 一个二维数组,结构如同从数据库查出来的数组
     * @param array $title excel的第一行标题,一个数组,如果为空则没有标题
     * @param string $filename 下载的文件名
     * @examlpe
     * $stu = M ('User');
     * $arr = $stu -> select();
     * exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
     */
    public static function exportExcel($data = array(), $title = array(), $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key] = implode("\t", $data[$key]);

            }
            echo implode("\n", $data);
        }
    }

    public static function ensureOpenId()
    {
        $wechat = Yii::$app->wechat;
        $request = Yii::$app->request;
        if (
            $request->post('open_id') == null
            &&
            $request->get('open_id') == null
        ) {
            $result = $wechat->getOauth2AccessToken($request->get('code'));
            $_GET['open_id'] = $result['openid'];
        }
    }
}