<?php
namespace admin\widgets;

/**
 * 后台导航
 */
class AdminNav extends \yii\bootstrap\Widget
{
    public function init()
    {
        parent::init();
        echo $this->render('adminNav');
    }
}
?>
