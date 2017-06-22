<?php

namespace app\assets;

use yii\web\AssetBundle;

class HighchartsAssets extends AssetBundle
{
    
    public $sourcePath = '@bower/highcharts';
    public $css = [];
    public $js = [
        'highcharts.js',
        'highcharts-more.js',
        'modules/exporting.js',
    ];
    
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    
}
