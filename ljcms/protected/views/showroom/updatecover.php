<div class="sectionTitle-A mb10">
    <h2>设置封面</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
       <a class="btn btn-primary" href="/showroom/index/sid/<?php echo $model['id']; ?>">样板间列表</a>
    </div>
</div>
<?php $form = $this->beginWidget('CActiveForm', array(
	    'id'=>'showroom-form',
		//'enableAjaxValidation' => true,
	    'enableClientValidation'=>true,
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
//	    'focus'=>array($model,'name'),
	)); ?>
<div class="sectionTable-A1 mb10">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="10%">操作</th>
                <th class="col-2" width="40%">封面图</th>
                <th class="col-3">名称</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($planlist as $k=>$v){ ?>
            <tr>
                <td>
                    <?php
                        if($v['id'] == $model['coverpic_id']){
                            echo '<input type="radio" value="'.$v['id'].'" name="Showroom[coverpic_id]" checked />';
                        }else{
                            echo '<input type="radio" value="'.$v['id'].'" name="Showroom[coverpic_id]" />';
                        }
                    ?>
                </td>
                <td>
                    <img width="260" height="170" src="<?php echo Yii::app()->params['static'].$v['image'];?>" />
                </td>
                <td align="center">
                    <a style="color:#000000;text-decoration:underline;" target="_blank" href="<?php echo Yii::app()->params['flash']."?init=plan&pid=".$v['id']."&&srid=".$model['id'];?>"><?php echo $v['name'];?></a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="commonBtnArea" style="width: 1174px;">
    <?php echo CHtml::submitButton('设置封面',array('class'=>'btn submit')); ?>
    <input type="button" id="cancelCoverpic" sid="<?php echo $model['id']; ?>" onclick="rCoverpic(this)" value="取消设置封面" class="btn submit" name="cancelCoverpic" />
</div> 
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //取消设置封面
    function rCoverpic(obj){
        var sid = $(obj).attr("sid");
        if(confirm('确定取消设置封面？')){
            $.post("/showroom/CancelCover",{sid:sid},function(data){
                alert(data.info);
                if(data.status){
                    window.location.reload();
                }
            },'json')
        }
    }
</script>