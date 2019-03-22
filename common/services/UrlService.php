<?php

namespace app\common\services;

use yii\helpers\Url;

/**
 * 构建链接
 * Class UrlService
 * @package app\common\services
 */
class UrlService
{

    /**
     * 构建会员端所有的链接
     * @param $path
     * @param array $params
     * @return string
     */
    public static function buildMUrl($path, $params = [])
    {
        $domain_config = \Yii::$app->params['domain'];
        $path = Url::toRoute(array_merge([$path], $params));
        return $domain_config['m'] . $path;

    }

    /**
     * 构建web端所有的链接
     * @param $path
     * @param array $params
     * @return string
     */
    public static function buildWebUrl($path, $params = [])
    {
        $domain_config = \Yii::$app->params['domain'];
        $path = Url::toRoute(array_merge([$path], $params));
        return $domain_config['web'] . $path;


    }

    /**
     * 构建官网链接
     * @param $path
     * @param array $params
     * @return string
     */
    public static function buildWwwUrl($path, $params = [])
    {
        $domain_config = \Yii::$app->params['domain'];
        $path = Url::toRoute(array_merge([$path], $params));
        return $domain_config['www'] . $path;
    }

    /**
     * 构建空链接
     * @return string
     */
    public static function buildNullUrl()
    {
        return "javascript:void(0);";
    }


    public static function buildPicUrl($bucket, $file_key)
    {
        $domain_config = \Yii::$app->params['domain'];
        $upload_config = \Yii::$app->params['upload'];
        return $domain_config['www'] . $upload_config[$bucket] . "/" . $file_key;
    }

    public static function buildBlogUrl($path, $params = [])
    {
        $domain_config = \Yii::$app->params['domain'];
        $path = Url::toRoute(array_merge([$path], $params));
        return $domain_config['blog'] . $path;
    }
}