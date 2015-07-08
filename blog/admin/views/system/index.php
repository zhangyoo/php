<?php
use yii\helpers\Html;

$this->title = 'System Index';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="/public/js/alg.common.js"></script>
<?php $form = yii\widgets\ActiveForm::begin(['id' => 'login-form']); ?>
<input type="hidden" name="form_submit" value="1"/>
    <div class="panel">
        <div class="panel-head"><strong>系统设置</strong></div>
        <div class="panel-body">
            <div class="form-group">
                <div class="label">
                    <?= Html::label('网站标题','title'); ?>
                </div>
                <div class="field">
                    <?= Html::activeInput('text',$model,'title',array('class'=>'input','placeholder'=>'网站标题','data-validate'=>'required:请填写网站标题')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('网站logo','image'); ?>
                </div>
                <div class="field">
                    <a href="javascript:void(0);" class="button input-file">+ 浏览文件
                        <?= Html::activeFileInput($model,'image',array('class'=>'input','size'=>'100')); ?>
                    </a>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('关键字','keywords'); ?>
                </div>
                <div class="field">
                    <?= Html::activeInput('text',$model,'keywords',array('class'=>'input','placeholder'=>'关键字')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('描述','description'); ?>
                </div>
                <div class="field">
                    <?= Html::activeTextarea($model,'description',array('class'=>'input','placeholder'=>'描述')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('备案号','record'); ?>
                </div>
                <div class="field">
                    <?= Html::activeInput('text',$model,'record',array('class'=>'input','placeholder'=>'备案号')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('版权信息','powerby'); ?>
                </div>
                <div class="field">
                    <?= Html::activeInput('text',$model,'powerby',array('class'=>'input','placeholder'=>'版权信息')); ?>
                </div>
            </div>
            <div class="form-button">
                <?= Html::button('提交',array('type'=>'submit','class'=>'button bg-main')); ?>
            </div>
        </div>
    </div>
<?php yii\widgets\ActiveForm::end(); ?>
