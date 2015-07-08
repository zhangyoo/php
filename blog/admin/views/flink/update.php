<?php 
use yii\helpers\Html; 

$this->title = 'flink Update';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="/public/js/alg.common.js"></script>
<?php $form = yii\widgets\ActiveForm::begin(['id' => 'login-form']); ?>
<input type="hidden" name="form_submit" value="1"/>
    <div class="panel">
        <div class="panel-head"><strong>编辑友情链接</strong></div>
        <div class="padding border-bottom">
            <a href="javascript:history.back(-1);" class="button button-small border-yellow">返回</a>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="label">
                    <?= Html::label('名称','tag-name'); ?>
                </div>
                <div class="field">
                    <?= Html::activeInput('text',$model,'name',array('class'=>'input','placeholder'=>'名称','data-validate'=>'required:请填写名称')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('网站logo','tag-image'); ?>
                </div>
                <div class="field">
                    <a href="javascript:void(0);" class="button input-file">+ 浏览文件
                        <?= Html::activeFileInput($model,'image',array('class'=>'input','size'=>'100','data-validate'=>'required:请选择上传文件,regexp#.+.(jpg|jpeg|png|gif)$:只能上传jpg|gif|png格式文件')); ?>
                    </a>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('链接','url'); ?>
                </div>
                <div class="field">
                    <?= Html::activeTextarea($model,'url',array('class'=>'input','placeholder'=>'链接')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('排序','sort_num'); ?>
                </div>
                <div class="field">
                    <?= Html::activeInput('text',$model,'sort_num',array('class'=>'input','placeholder'=>'排序')); ?>
                </div>
            </div>
            <div class="form-button">
                <?= Html::button('提交',array('type'=>'submit','class'=>'button bg-main')); ?>
            </div>
        </div>
    </div>
<?php yii\widgets\ActiveForm::end(); ?>
