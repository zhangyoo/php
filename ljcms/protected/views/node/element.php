<link rel="stylesheet" type="text/css"  href="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/css/WdatePicker.css" />    
<script type="text/javascript" src="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/WdatePicker.js"></script>
<div class="sectionTitle-A mb10">
    <h2><?php echo isset($_GET['unbind']) && empty($_GET['unbind'])?"选择解绑元素":"选择元素绑定"; ?></h2>
</div>
<?php
	$form = $this->beginWidget('CActiveForm', array(
	    'id'=>'elements-form',
		//'enableAjaxValidation' => true,
	    'enableClientValidation'=>false,
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
        'focus'=>array($model,'name'),
	));?>
<div class="clear mb10">
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
            <ul class="clear">
                <li>
                    <?php
                        echo CHtml::dropDownList('bindType',$seachData['bindType'],array('bind'=>'未绑元素','unbind'=>'已绑元素'),
                                array('separator'=>'&nbsp;','style'=>'width:auto','onchange'=>'changeBindType(this)'));
                    ?>
                </li>
                <li>
                    <label>名称:</label>
                    <input class="input" type="text" name="name" value="<?php echo !empty($seachData['name'])?$seachData['name']:''; ?>" size="20" style="margin-right: 20px;" />
                </li>
                <li>
                    <label>起始时间:</label>
                    <input class="input inputLen" type="text" name="timeStart" value="<?php echo !empty($seachData['timeStart'])?$seachData['timeStart']:''; ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" size="20" style="margin-right: 20px;" />
                </li>
                <li>
                    <label>截止时间:</label>
                    <input class="input inputLen" type="text" name="timeEnd" value="<?php echo !empty($seachData['timeEnd'])?$seachData['timeEnd']:''; ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" size="20" style="margin-right: 20px;" />
                </li>
                <li class="button">                                    
                    <input class="btn btn-large btn-primary" type="submit" value="查询">
                </li>
            </ul>
    </div>
</div>
<div class="sectionTable-A1 mb10">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="5%">全选</th>
                <th class="col-2" width="15%">缩略图</th>
                <th class="col-3" width="30%">名称</th>
                <th class="col-4" width="15%">创建时间</th>
                <th class="col-5" width="15%">更新时间</th>
                <th class="col-6">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dataProvider as $model): ?>
            <tr align="center" class="sub tr_<?php echo $model['id'];?>" pid="<?php echo $model['id'];?>">
                <td>
                    <input type="checkbox" name="Element[]" value="<?php echo $model['id']; ?>" />
                </td>
                <td class="td-0">
                    <img src="<?php echo ImageHelper::showThumb($model['image'],array("width"=>420,"height"=>330, 'type'=>  ImageHelper::THUMB_TYPE_CROP)); ?>" width="100" alt="图片"/>
                <?php // echo CHtml::image(Yii::app()->params['static'].$model['image'],'图片',array('width'=>'100','height'=>'100')); ?>
                </td>
                <td>
                <?php echo CHtml::encode($model['name']); ?>
                </td>
                <td>
                <?php echo date("Y-m-d H:i:s", $model["create_time"]);?>
                </td>
                <td>
                <?php echo !empty($model["update_time"])?date("Y-m-d H:i:s",$model["update_time"]):'';?>
                </td>
                <td>
                    [ <a href="/space/updateElement/id/<?php echo $model['id']; ?>"><?php echo !empty(Yii::app()->session['update']) ? "编辑":"查看"; ?></a> ]
                    <?php if(!empty(Yii::app()->session['delete'])): ?>
                    [ <a href="javascript:void(0)" class="del" rel="<?php echo $model['id'];?>">删除</a> ]
                    <?php endif; ?><br>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td align="center"><input type="checkbox" class="all_select" /> 全选</td>
                <td colspan="5" align="right"> 
                    <div class="sectionFoot-B1">
                        <div class="sectionFloat-A1 addpage_style">
                            <div class="page_list">
                                <?php $this->widget('CLinkPager', array(
                                    'header'=> '<span>共'. $count. '个</span>',
                                    "maxButtonCount"=>5,
                                    'pages' => $pages,
                                    'firstPageLabel'=>'&lt;&lt; 首页',
                                    'prevPageLabel'=>'&lt; 上一页',
                                    'nextPageLabel'=>'下一页 &gt;',
                                    'lastPageLabel'=>'末页 &gt;&gt;',
                                ))?>  
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="commonBtnArea" style="width: 1174px;">
    <a class="btn submit" onclick="delbatch()">批量删除</a>
    <?php if(isset($_GET['unbind']) && empty($_GET['unbind'])){ ?>
        <?php echo CHtml::submitButton('解除绑定元素',array('class'=>'btn submit'));?>
    <?php }else{ ?>
        <?php echo CHtml::submitButton('绑定元素',array('class'=>'btn submit'));?>
    <?php }?>
</div>       
<?php $this->endWidget(); ?>

<script type="text/javascript">
   //全选
   var i=0;
   $(".all_select").click(function(){
       if(i==0){
       i=1;
       $(".table tr td input[type=checkbox]").attr("checked",true);
   }else if(i==1){
       i=0;
       $(".table tr td input[type=checkbox]").attr("checked",false);
   }
   });
   //批量删除
    function delbatch(){
        var data=new Array();
        $('input:checkbox[name="Element[]"]').each(function (i,n){
            if($(n).attr("checked")=="checked"){
                data.push($(n).val());
            }
        });
        if(data.length > 0){
            if(confirm('确定要删除选择项')){
                $.post('<?php echo CHtml::normalizeUrl(array('/node/deletecate')); ?>',{'id[]':data}, function (dat) {
                    var ret = $.parseJSON(dat);
                    if (ret != null && ret.success != null && ret.success) {
                        $.each(data,function(i,n){
                            $(".tr_"+n).remove();
                       })
                    }
                });
             }

        }else{
            alert("请选择要删除分类!");
        }
    }   
    //删除元素
    $(".del").click(function(){
        var id = $(this).attr('rel').trim();
        popup.confirm('确定删除此数据吗？','删除提示',function(e){
            if('ok'===e){
                $.ajax({
                    url:"/space/delys",
                    data:"id="+id,
                    dataType:"json",
                    type:"POST",
                    success:function(data) {
                        if(data.status==1){
                            popup.error(data.info);
                            setTimeout(function(){
                                popup.close("asyncbox_error");
                            },2000);
                        }else{
                            popup.success(data.info);
                            setTimeout(function(){
                                popup.close("asyncbox_success");
                            },2000);
                            $("[rel="+id+"]").parent().parent().remove();
                        }
                    }
                }); 
            }
            popup.close("asyncbox_confirm");
        });

    })
    
    //图片出现缩略图
    $(".table-hover .td-0 img").hover(function(){
        var srcImg = $(this).attr('src');
        var img="<div id='img_msg' ><img alt='' src='"+srcImg+"' /></div>";
        $(this).parent().find("#img_msg").remove();
        $(this).parent().append(img);	
        $("#img_msg").css("display","block");		
    },function(){
        $(this).parent().find("#img_msg").remove();
    });
    
    //切换已绑定元素和未绑定元素
    function changeBindType(cbt){
        var $t = $(cbt).val();
        if($t == 'unbind'){
            window.location.href = '/node/element/id/'+<?php echo isset($_GET['id']) ? $_GET['id'] :""; ?> +"/unbind";
        }else{
            window.location.href = '/node/element/id/'+<?php echo isset($_GET['id']) ? $_GET['id'] :""; ?>;
        }
    }
</script>
