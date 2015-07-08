<div class="sectionTitle-A mb10">
    <h2>用户列表</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        <a href="/user/create" class="btn btn-primary">创建用户</a>
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <?php $form = $this->beginWidget('CActiveForm', array(
                    'id'=>'user-form',
                    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
          )); ?>
            <ul class="clear">
                <li>
                    <label>名称</label>
                    <input type="text" value="<?php echo !empty($name) ? $name :''; ?>" name="name" class="text">
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
                <th class="col-1" width="6%">头像</th>
                <th class="col-1" width="15%">用户名</th>
                <th class="col-2" width="15%">昵称</th>
                <th class="col-3" width="15%">邮箱</th>
                <th class="col-4" width="15%">创建时间</th>
                <th class="col-5" width="15%">更新时间</th>
                <th class="col-6" width="15%">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($ul) && !empty($ul)): ?>
            <?php foreach ($ul as $model):?>
            <tr id="tr_<?php echo $model['id']; ?>">
                <td class="col-1">
                    <img src="<?php echo ImageHelper::showThumb($model['image'],array("width"=>420,"height"=>330, 'type'=>  ImageHelper::THUMB_TYPE_CROP)); ?>" width="80" alt="<?php echo $model['username']; ?>"/>
                </td>
                <td class="col-1"><?php echo $model['username']; ?></td>
                <td class="col-2"><?php echo $model['nickname']; ?></td>
                <td class="col-3"><?php echo $model["email"];?></td>
                <td class="col-4"><?php echo date("Y-m-d H:i:s", $model["create_time"]);?></td>
                <td class="col-5"><?php echo !empty($model["update_time"])?date("Y-m-d H:i:s",$model["update_time"]):'';?></td>
                <td class="col-6">
                    <a href="/user/update/id/<?php echo $model['id']; ?>"><img class="mr10 icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/update.png" width="24" height="24" alt=""/></a>
                    <a href="javascript:void(0)" rel="<?php echo $model['id'];?>" onclick="Delete(this)"><img class="icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/delete.png" width="24" height="24" alt=""/></a>
                    &nbsp;&nbsp;<a class="operate_one" href="/authority/setauth/userid/<?php echo $model['id'];?>">设置权限</a>
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
    popup.confirm('确定删除此用户吗？','删除提示',function(e){
        if('ok'===e){
            $.post('/user/delete',{id:id},function(data){
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
</script>