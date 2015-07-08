<div class="sectionTitle-A mb10">
    <h2>设置权限</h2>
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
                <th class="col-1" width="5%">
                    <input type="checkbox" onclick="checkall()"/>
                </th>
                <th class="col-2" width="20%">分组名称</th>
                <th class="col-3" width="20%">模块类型</th>
                <th class="col-4" >描述</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($itemlist) && !empty($itemlist)): ?>
            <?php foreach ($itemlist as $item):?>
            <tr class="">
                <td class="col-1">
                    <label class="checkbox">
                      <input type="checkbox" name="id[]" value="<?php echo $item->name;?>" <?php if(Yii::app()->authManager->isAssigned($item->name,$userid)){ ?>checked<?php } ?> />
                    </label>
                </td>
                <td class="col-2">
                    <label class="checkbox">
                        <?php echo $item->name; ?>
                    </label>
                </td>
                <td class="col-3">
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
                <td class="col-4"><?php echo $item->description; ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="sectionFoot-B1">
    <div class="sectionFloat-A1">
        <ul class="clear">
            <li class="mr10 ml8">
                <label class="checkbox">
                  <input type="checkbox" onclick="checkall()" /> 全选
                </label>
            </li>
            <li class="sectionForm-A1 sectionForm-A1-2">                                    
                <span class="button"><input type="button" value="分配权限" class="btn btn-large btn-primary submit" userid="<?php echo $userid;?>"></span>
                <span class="button"><input type="button" value="取消权限" class="btn btn-large btn-primary cancel" userid="<?php echo $userid;?>"></span>
            </li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    //全选/取消全选
     function checkall(){
         $('input:checkbox[name="id[]"]').each(function (){
             if($(this).attr("checked")=="checked"){
                 $(this).attr('checked',false);
             }else{
                 $(this).attr('checked',true);
             }
         });

     }
  $(function(){
      
         //批量分配授权
         $(".btn.submit").click(function(){
             var userid = $(this).attr('userid');
             var child = [];
             $('input:checkbox[name="id[]"]:checked').each(function (i,n){
                 child.push($(n).val());
            });
            if(child=='' || child==null){
                popup.alert("请选择要分配项!");
                return false;
            }
            $.post("/authority/doSetauth",{itemname:child,userid:userid},function(data){
                if(data){
                    popup.success("分配成功！");
                    setTimeout(function(){
                        popup.close("asyncbox_success");
                    },2000);
                }else{
                    popup.error("批量分配失败！");
                    setTimeout(function(){
                        popup.close("asyncbox_error");
                    },2000);
                }
            })
         });
         
         //批量取消分配授权
         $(".btn.cancel").click(function(){
             var userid = $(this).attr('userid');
             var child = [];
             $('input:checkbox[name="id[]"]:checked').each(function (i,n){
                 child.push($(n).val());
            });
            if(child=='' || child==null){
                popup.alert("请选择要取消分配项!");
                return false;
            }
            $.post("/authority/doSetCancelauth",{itemname:child,userid:userid},function(data){
                if(data){
                    popup.success("取消分配成功！");
                    setTimeout(function(){
                        popup.close("asyncbox_success");
                    },2000);
                }else{
                    popup.error("批量取消分配失败！");
                    setTimeout(function(){
                        popup.close("asyncbox_error");
                    },2000);
                }
            })
         });
     });
</script>