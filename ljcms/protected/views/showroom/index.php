<link rel="stylesheet" type="text/css"  href="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/css/WdatePicker.css" />    
<script type="text/javascript" src="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/WdatePicker.js"></script>
<div class="sectionTitle-A mb10">
    <h2>样板间列表</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        <?php if(!empty(Yii::app()->session['update'])): ?>
        <a href="/showroom/create/sid/<?php echo $sid; ?>" class="btn btn-primary">创建样板间</a>
        <?php endif; ?>
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <?php $form = $this->beginWidget('CActiveForm', array(
                    'id'=>'user-form',
                    'method'=>'get',
                    'action'=>'/showroom/index/sid/'.$_GET['sid'],
                    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
          )); ?>
            <ul class="clear">
                <li>
                    <label>名称</label>
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
<?php $roomcat=Yii::app()->params['roomCategories'];?>
<div class="sectionTable-A1 mb10">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="12%">样板间封面</th>
                <th class="col-2" width="15%">样板间名称</th>
                <th class="col-3" width="10%">视角</th>
                <th class="col-4" width="10%">功能</th>
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
                <td class="col-3"><?php echo $model["angle"];?></td>
                <td class="col-4"><?php echo $roomcat[$model["room_category"]];?></td>
                <td class="col-5"><?php echo date("Y-m-d H:i:s", $model["create_time"]);?></td>
                <td class="col-6"><?php echo !empty($model["update_time"])?date("Y-m-d H:i:s",$model["update_time"]):'';?></td>
                <td class="col-7">
                    [ <a href="/showroom/create/sid/<?php echo $sid; ?>/id/<?php echo $model['id']; ?>"><?php echo !empty(Yii::app()->session['update']) ? "编辑":"查看"; ?></a> ]
                    <?php if(!empty(Yii::app()->session['delete'])): ?>
                    [ <a href="javascript:void(0)" rel="<?php echo $model['id'];?>" onclick="Delete(this)">删除</a> ]<br>
                    <?php endif; ?>
                    <!--[ <a href="/showroom/element/id/<?php // echo $model['id']; ?>">绑定元素</a> ]<br>-->
                    [ <a href="/showroom/element/id/<?php echo $model['id']; ?>/unbind/">样板间元素管理</a> ]<br>
                    [ <a href="<?php echo Yii::app()->params['flash'].'?init=showroom&srid='.$model['id']; ?>" target="_blank" title="">flash搭配</a> ]<br>
                    [ <a href="/showroom/setcover/id/<?php echo $model['id']; ?>">设置封面</a> ]
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
    </div>
</div>
<script type="text/javascript">
//删除用户
function Delete(dom){
    var id = $(dom).attr('rel');
    popup.confirm('确定删除此样板间吗？','删除提示',function(e){
        if('ok'===e){
            $.post('/showroom/delete',{id:id},function(data){
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