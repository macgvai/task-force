<?php
//
namespace app\assets;

use yii\web\AssetBundle;
//
//class DropZoneAsset extends AssetBundle
//{
//    public $basePath = '@webroot/vendor/enyo/dropzone/dist';
//    public $baseUrl = '@web/vendor/enyo/dropzone/dist';
//
//    public $css = [
//        'basic.css',
//        'dropzone.css',
//    ];
//    public $js = [
//        'dropzone.js'
//    ];
//}
class DropZoneAsset extends AssetBundle
{
    public $sourcePath = '@vendor/enyo/dropzone/dist';

    public $css = [
        'basic.css',
        'dropzone.css'
    ];

    public $js = [
        'dropzone.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'  // Dropzone требует jQuery
    ];
}
