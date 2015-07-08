<?php 
use yii\helpers\Html; 

$this->title = 'Category Update';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="/public/js/alg.common.js"></script>
<?php $form = yii\widgets\ActiveForm::begin(['id' => 'login-form']); ?>
<input type="hidden" name="form_submit" value="1"/>
    <div class="panel">
        <div class="panel-head"><strong>创建栏目</strong></div>
        <div class="padding border-bottom">
            <a href="javascript:history.back(-1);" class="button button-small border-yellow">返回</a>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="label">
                    <?= Html::label('栏目标题','category-name'); ?>
                </div>
                <div class="field">
                    <?= Html::activeInput('text',$model,'name',array('class'=>'input','placeholder'=>'栏目标题','data-validate'=>'required:请填写栏目标题')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('父级分类','parent_id'); ?>
                </div>
                <div class="field">
                    <?= Html::activeDropDownList($model,'parent_id',array(0=>'请选择'),array('class'=>'input','style'=>'width:auto','placeholder'=>'父级分类')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('关键字','keywords'); ?>
                </div>
                <div class="field">
                    <?= Html::activeInput('text',$model,'keywords',array('class'=>'input','placeholder'=>'栏目关键字')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('描述','description'); ?>
                </div>
                <div class="field">
                    <?= Html::activeTextarea($model,'description',array('class'=>'input','placeholder'=>'栏目描述')); ?>
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
<script type="text/javascript">
    $(function(){
        //获取分类
        var sub = new Array();
        sub['_csrf'] = '<?php echo yii::$app->request->csrfToken; ?>';
        getCategory(sub);
    })
</script>
