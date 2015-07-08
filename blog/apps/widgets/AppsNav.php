<?php
namespace apps\widgets;

/**
 * 后台导航
 */
class AppsNav extends \yii\bootstrap\Widget
{
    public function init()
    {
        parent::init();
        echo $this->render('appsNav');
    }
}
?>
