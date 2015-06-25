<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/res.js"></script>
<div class="sectionTitle-A mb10">
    <h2>标签列表</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        <a href="/label/create" class="btn btn-primary">创建标签</a>
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <?php $form = $this->beginWidget('CActiveForm', array(
                    'id'=>'user-form',
                    'method'=>'get',
                    'action'=>'/label/index',
                    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
          )); ?>
            <ul class="clear">
                <li>
                    <label>标签名称</label>
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
                <th class="col-1" width="15%">标签名称</th>
                <th class="col-2" width="15%">对应分类名称</th>
                <th class="col-2" width="15%">标签类型</th>
                <th class="col-3" width="10%">排序</th>
                <th class="col-4" width="15%">创建时间</th>
                <th class="col-5" width="15%">更新时间</th>
                <th class="col-6">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($parent) && !empty($parent)): ?>
            <?php foreach ($parent as $model):?>
            <tr id="tr_<?php echo $model['id']; ?>">
                <td class="col-1">
                    | <?php echo $model['name']; ?>
                </td>
                <td class="col-2">
                    <?php 
                        $cids = json_decode($model['category_id'],true);
                        $catName = '';
                        if(!empty($model['category_id']) && !empty($cids)){
                            foreach ($cids as $cid){
                                if(isset($categorys[$cid]))
                                    $catName .= $categorys[$cid]."&nbsp;&nbsp;";
                            }
                        }
                        echo $catName;
                    ?>
                </td>
                <td class="col-2"><?php echo Yii::app()->params['labelType'][$model['type']]; ?></td>
                <td class="col-3"><?php echo $model["sort_num"]; ?></td>
                <td class="col-4"><?php echo date("Y-m-d H:i:s", $model["create_time"]);?></td>
                <td class="col-5"><?php echo !empty($model["update_time"])?date("Y-m-d H:i:s",$model["update_time"]):'';?></td>
                <td class="col-6">
                    [<a href="javascript:void(0)" class="iconsTrans" onclick="disDiaCat(this)" rel="<?php echo $model['id'];?>"></a>]
                    [<a href="/label/create/id/<?php echo $model['id']; ?>"><?php echo !empty(Yii::app()->session['update']) ? "编辑":"查看"; ?></a>]
                    <?php if(!empty(Yii::app()->session['delete'])): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $model['id'];?>" onclick="delLabel(this)">删除</a>]
                    <?php endif; ?>
                </td>
            </tr>
                <?php if(!empty($model['children'])): ?>
                <?php foreach ($model['children'] as $child): ?>
                <tr id="tr_<?php echo $child['id']; ?>">
                    <td class="col-1">
                        || <?php echo $child['name']; ?>
                    </td>
                    <td class="col-2">
                        <?php 
                            $cids = json_decode($child['category_id'],true);
                            $catName = '';
                            if(!empty($child['category_id']) && !empty($cids)){
                                foreach ($cids as $cid){
                                    if(isset($categorys[$cid]))
                                        $catName .= $categorys[$cid]."&nbsp;&nbsp;";
                                }
                            }
                            echo $catName;
                        ?>
                    </td>
                    <td class="col-2"><?php echo Yii::app()->params['labelType'][$child['type']]; ?></td>
                    <td class="col-3"><?php echo $child["sort_num"]; ?></td>
                    <td class="col-4"><?php echo date("Y-m-d H:i:s", $child["create_time"]);?></td>
                    <td class="col-5"><?php echo !empty($child["update_time"])?date("Y-m-d H:i:s",$child["update_time"]):'';?></td>
                    <td class="col-4">
                        [<a href="javascript:void(0)" class="iconsTrans" onclick="disDiaCat(this)" rel="<?php echo $child['id'];?>"></a>]
                        [<a href="/label/create/id/<?php echo $child['id']; ?>"><?php echo !empty(Yii::app()->session['update']) ? "编辑":"查看"; ?></a>]
                        <?php if(!empty(Yii::app()->session['delete'])): ?>
                        [<a href="javascript:void(0)" rel="<?php echo $child['id'];?>" onclick="delLabel(this)">删除</a>]
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div  class="dialog_bind_cat" style="display:none;">
    <div class="dialog_bind_cat_top">
        <font color="#15428B"><b>转移分类</b></font>
        <a href="javascript:void(0);" onclick="closeBindCat()"><img class="icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/iccccon_06.gif" alt="关闭弹框"/></a>
    </div>
    <div class="dialog_bind_cat_content">
        
    </div>
</div>

<script type="text/javascript">
    //菜单展示收缩
    $(".dbcc_con span").live('click',function(){
        var $obj = $(this);
        $obj.next("ul").toggle();
    })
    //选中分类
    $(".dbcc_con input").live('click',function(){
        var data=new Array();
        $('input:checkbox[name="Cat[]"]').each(function (i,n){
            if($(n).attr("checked")=="checked"){
                data.push($(n).val());
            }
        });
        if(data.length>0){
            $(".dbcc_save font").html(data.length);
        }else{
            $(".dbcc_save font").html('0');
        }
        
    })
</script>