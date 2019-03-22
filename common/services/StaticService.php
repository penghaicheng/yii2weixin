<?php

namespace app\common\services;

use Yii;

/**
 * 用于加载应用本身的资源文件
 * Class StaticService
 * @package app\common\services
 */
class StaticService
{

    /**
     * 加载静态文件
     * @param $type
     * @param $path
     * @param $depend
     */
    public static function includeAppStatic($type, $path, $depend)
    {
        $release_version = defined("RELEASE_VERSION") ? RELEASE_VERSION : "20150731141600";
        $path = $path . "?ver={$release_version}";
        if ($type == "css") {
            Yii::$app->getView()->registerCssFile($path, ['depends' => $depend]);
        } else {
            Yii::$app->getView()->registerJsFile($path, ['depends' => $depend]);
        }
    }

    /**
     * 加载js文件
     * @param $path
     * @param $depend
     */
    public static function includeAppJsStatic($path, $depend)
    {
        self::includeAppStatic("js", $path, $depend);
    }

    /**
     * 加载css文件
     * @param $path
     * @param $depend
     */
    public static function includeAppCssStatic($path, $depend)
    {
        self::includeAppStatic("css", $path, $depend);
    }
}