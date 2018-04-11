<?php

/**
 * @author: wuwenhan <329576084@qq.com>
 */
class App extends Yii
{
    /**
     * @return \yii\console\Application|\yii\web\Application|\framework\base\Application
     */
    public static function me()
    {
        return self::$app;
    }

    /**
     * @param      $name
     * @param null $default
     * @return null
     */
    public static function getParam($name, $default = null)
    {
        return isset(self::$app->params[$name]) ? self::$app->params[$name] : $default;
    }

    /**
     * @param      $name
     * @param      $value
     * @param bool $cover
     * @return bool
     */
    public static function setParam($name, $value, $cover = true)
    {
        if ($cover) {
            self::$app->params[$name] = $value;
        } else {
            if (isset(self::$app->params[$name])) {
                return false;
            } else {
                self::$app->params[$name] = $value;
            }
        }
        return true;
    }
}