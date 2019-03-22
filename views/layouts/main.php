<?php

use \app\assets\AppAsset;
use \app\common\services\UrlService;

AppAsset::register($this);
?>

<?= $this->beginPage(); ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>编程浪子微信图书商城</title>
        <?= $this->head(); ?>
    </head>
    <?= $this->beginBody(); ?>
    <body>
    <div class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <div class="navbar-collapse collapse pull-left">
                <ul class="nav navbar-nav ">
                    <li><a href="<?=UrlService::buildWwwUrl('/')?>">首页</a></li>
                    <li><a target="_blank" href="<?=UrlService::buildWwwUrl('/')?>">博客</a></li>
                    <li><a href="<?=UrlService::buildWebUrl('/user/login')?>">管理后台</a></li>
                </ul>
            </div>
        </div>
    </div>

    <?= $content; ?>


    </body>
    <?= $this->endBody(); ?>
    </html>

<?= $this->endPage(); ?>