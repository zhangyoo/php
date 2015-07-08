<?php 
use yii\helpers\Html; 

$this->title = 'Tag Update';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="/public/js/alg.common.js"></script>
<?php $form = yii\widgets\ActiveForm::begin(['id' => 'login-form']); ?>
<input type="hidden" name="form_submit" value="1"/>
    <div class="panel">
        <div class="panel-head"><strong>编辑标签</strong></div>
        <div class="padding border-bottom">
            <a href="javascript:history.back(-1);" class="button button-small border-yellow">返回</a>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="label">
                    <?= Html::label('标签名称','category-name'); ?>
                </div>
                <div class="field">
                    <?= Html::activeInput('text',$model,'name',array('class'=>'input','placeholder'=>'标签名称','data-validate'=>'required:请填写标签名称')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('描述','description'); ?>
                </div>
                <div class="field">
                    <?= Html::activeTextarea($model,'description',array('class'=>'input','placeholder'=>'标签描述')); ?>
                </div>
            </div>
            <div class="form-button">
                <?= Html::button('提交',array('type'=>'submit','class'=>'button bg-main')); ?>
            </div>
        </div>
    </div>
<?php yii\widgets\ActiveForm::end(); ?>
