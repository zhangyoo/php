<?php
namespace apps\widgets;

/**
 * 后台导航
 */
class Message extends \yii\bootstrap\Widget
{
    public function init()
    {
        parent::init();
        echo $this->render('message');
    }
}
?>
