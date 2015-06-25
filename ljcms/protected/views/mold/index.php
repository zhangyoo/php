<link rel="stylesheet" type="text/css"  href="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/css/WdatePicker.css" />    
<script type="text/javascript" src="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/WdatePicker.js"></script>
<div class="sectionTitle-A mb10">
    <h2>模型列表</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        <a href="/mold/autoCreate" target="_blank" class="btn btn-primary">自动上传模型</a>
        <a href="/mold/upOldTexture" target="_blank" class="btn btn-primary">更新旧模型贴图数据</a>
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <?php $form = $this->beginWidget('CActiveForm', array(
                    'id'=>'user-form',
                    'method'=>'get',
                    'action'=>'/mold/index',
                    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
          )); ?>
            <ul class="clear">
                <li>
                    <label>名称或型号</label>
                    <input type="text" value="<?php echo !empty($seachData['name']) ? $seachData['name'] :''; ?>" name="name" class="text">
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
        <?php $this->endWidget(); ?>
    </div>
</div>

<div class="sectionTable-A1 mb10">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="12%">模型缩略图</th>
                <th class="col-2" width="15%">模型名称</th>
                <th class="col-2" width="8%">模型型号</th>
                <th class="col-3" width="7%">类型</th>
                <th class="col-4" width="18%">模型说明</th>
                <th class="col-5" width="15%">创建时间</th>
                <th class="col-6" width="15%">更新时间</th>
                <th class="col-7">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($ul) && !empty($ul)): ?>
            <?php foreach ($ul as $model):?>
            <tr id="tr_<?php echo $model['id']; ?>">
                <td class="col-1">
                    <img src="<?php echo ImageHelper::showThumb($model['image'],array("width"=>420,"height"=>330, 'type'=>  ImageHelper::THUMB_TYPE_CROP)); ?>" width="140" alt="<?php echo $model['name']; ?>"/>
                    <?php // echo CHtml::image(Yii::app()->params['static'].$model['image'],$model['name'],array('width'=>'140')); ?>
                </td>
                <td class="col-2"><?php echo $model['name']; ?></td>
                <td class="col-2"><?php echo $model['item']; ?></td>
                <td class="col-3">
                    <?php echo Yii::app()->params['moldType'][$model['mold_type']]; ?><br>
                    <?php echo Yii::app()->params['productType'][$model['type']]; ?>
                </td>
                <td class="col-4"><?php echo $model["summary"];?></td>
                <td class="col-5"><?php echo date("Y-m-d H:i:s", $model["create_time"]);?></td>
                <td class="col-6"><?php echo !empty($model["update_time"])?date("Y-m-d H:i:s",$model["update_time"]):'';?></td>
                <td class="col-7">
                    [<a href="/mold/update/id/<?php echo $model['id']; ?>"><?php echo !empty(Yii::app()->session['update']) ? "编辑":"查看"; ?></a>]
                    <?php if(!empty(Yii::app()->session['delete'])): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $model['id'];?>" onclick="Delete(this)">删除</a>]
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="sectionFoot-B1">
    <div class="sectionFloat-A1 addpage_style">
        <div class="page_list">
        <?php $this->widget('CLinkPager', array(
            'header'=> ' ',
            "maxButtonCount"=>5,
            'pages' => $pages,
            'firstPageLabel'=>'&lt;&lt; 首页',
            'prevPageLabel'=>'&lt; 前页',
            'nextPageLabel'=>'后页 &gt;',
            'lastPageLabel'=>'末页 &gt;&gt;',
        ))?>
        </div>
        <div class="goToPages">
            跳转到：
            <input type="text" name="pageNum" class="pageInput" /> 页&nbsp;&nbsp;
            <input type="button" id="loadPageNum" onclick="transPages(this)" url="<?php
            echo Yii::app()->request->BaseUrl;?>/mold/index/page/" class="clickPageIcon" value="确定"/>&nbsp;&nbsp;
        </div>
    </div>
</div>
<script type="text/javascript">
//删除用户
function Delete(dom){
    var id = $(dom).attr('rel');
    popup.confirm('确定删除此模型吗？相关的数据都会被删除！','删除提示',function(e){
        if('ok'===e){
            $.post('/mold/delete',{id:id},function(data){
                if(data.status){
                    popup.success(data.info);
                    setTimeout(function(){
                        popup.close("asyncbox_success");
                    },2000);
                    $("#tr_"+id).remove();
                }else{
                    popup.error(data.info);
                    setTimeout(function(){
                        popup.close("asyncbox_error");
                    },2000);
                }
            },'json')
        }
    })
}

    //图片出现缩略图
    $(".table-hover .col-1 img").hover(function(){
        var srcImg = $(this).attr('src');
        var img="<div id='img_msg' ><img alt='' src='"+srcImg+"' /></div>";
        $(this).parent().find("#img_msg").remove();
        $(this).parent().append(img);	
        $("#img_msg").css("display","block");		
    },function(){
        $(this).parent().find("#img_msg").remove();
    });
</script>