<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class IndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/common.css',
        'css/index.css',
        'css/form.css',
        'css/card.css',
    ];
    public $js = [
        'js/index.js',
        'js/user-form.js',
        'js/card.js',
    ];
    public $depends = [
        'app\assets\ResetAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\MapAsset',
    ];
}
