<div class="sectionTitle-A mb10">
    <h2>分组列表</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        <a href="/authority/create" class="btn btn-primary">创建分组</a>
    </div>
</div>
<div class="sectionTable-A1 mb10">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="15%">分组名称</th>
                <th class="col-2" width="10%">模块类型</th>
                <th class="col-3" >描述</th>
                <th class="col-4" width="15%">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($itemlist) && !empty($itemlist)): ?>
            <?php foreach ($itemlist as $item):?>
            <tr class="">
                <td class="col-1">
                    <label class="checkbox">
                        <?php echo $item->name; ?>
                    </label>
                </td>
                <td class="col-2">
                    <?php 
                        switch ($item->type) {
                            case 0:
                                echo '操作';
                                break;
                            case 1:
                                echo '任务';
                                break;
                            case 2:
                                echo '角色';
                                break;
                        }
                    ?>
                </td>
                <td class="col-3"><?php echo $item->description; ?></td>
                <td class="col-4">
                    <?php if(isset($item->name) && $item->name==='administrator'):?>
                    ---
                    <?php else :?>
                    <a href="/authority/update/name/<?php echo $item->name;?>"><img class="mr10 icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/update.png" width="24" height="24" alt=""/></a>
                    <a href="javascript:void(0)" class="del" rel="<?php echo $item->name?>"><img class="icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/delete.png" width="24" height="24" alt=""/></a>
                    &nbsp;&nbsp;<a class="operate_one" href="/authority/setrole/name/<?php echo $item->name;?>">设置节点</a>
                    <?php endif;?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    jQuery(function(){
        
        //删除分组操作
        $(".del").click(function(){
            var itemname = $(this).attr('rel').trim();
            popup.confirm('确定删除此分组吗？','删除提示',function(e){
                if('ok'===e){
                    $.ajax({
                        url:"/authority/delete",
                        data:"itemname="+itemname,
                        dataType:"json",
                        type:"POST",
                        success:function(data) {
                            if(data.status==1){
                                popup.success(data.info);
                                setTimeout(function(){
                                    popup.close("asyncbox_success");
                                },2000);
                                $("[rel="+itemname+"]").parent().parent().remove();
                            }else{
                                popup.error(data.info);
                                setTimeout(function(){
                                    popup.close("asyncbox_error");
                                },2000);
                            }
                        }
                    }); 
                }
            })
        })
    });
</script>