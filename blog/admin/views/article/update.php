<?php 
use yii\helpers\Html; 

$this->title = 'Article Update';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="/public/js/alg.common.js"></script>
<script src="/public/kindeditor/kindeditor-min.js"></script>
<script src="/public/kindeditor/lang/zh_CN.js"></script>
<link rel="stylesheet" href="/public/kindeditor/themes/default/default.css">
<?php $form = yii\widgets\ActiveForm::begin(['id' => 'login-form']); ?>
<input type="hidden" name="form_submit" value="1"/>
    <div class="panel">
        <div class="panel-head"><strong>编辑文章</strong></div>
        <div class="padding border-bottom">
            <a href="javascript:history.back(-1);" class="button button-small border-yellow">返回</a>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="label">
                    <?= Html::label('文章标题','article-title'); ?>
                </div>
                <div class="field">
                    <?= Html::activeInput('text',$model,'title',array('class'=>'input','placeholder'=>'文章标题','data-validate'=>'required:请填写文章标题')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('栏目分类','category_id'); ?>
                </div>
                <div class="field">
                    <?= Html::activeDropDownList($model,'category_id',array(0=>'请选择'),array('class'=>'input','style'=>'width:auto','placeholder'=>'栏目分类')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <?= Html::label('缩略图','article-image'); ?>
                </div>
                <div class="field">
                    <a href="javascript:void(0);" class="button input-file">+ 浏览文件
                        <?= Html::activeFileInput($model,'image',array('class'=>'input','size'=>'100','data-validate'=>'required:请选择上传文件,regexp#.+.(jpg|jpeg|png|gif)$:只能上传jpg|gif|png格式文件')); ?>
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
                    <?= Html::label('内容','article-content'); ?>
                </div>
                <div class="field">
                    <?= Html::activeTextarea($model,'content',array('class'=>'input','style'=>'height:500px;','id'=>'article_content','placeholder'=>'内容')); ?>
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
        //加载编辑器
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#article_content', {
                        allowFileManager : true
                });
        });
    })
</script>
