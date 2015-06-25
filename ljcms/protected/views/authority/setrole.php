<div class="sectionTitle-A mb10">
    <h2>设置节点</h2>
</div>
<div class="clear mb10" id="changeChildS">
    <div class="sectionBun-A2 L">
        <a href="/authority/addrole/name/<?php echo $itemname;?>" class="btn btn-primary">添加节点</a>
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <select id="catalogId" onchange="changeChild(this)">
            <option value="0">已设置节点</option>
            <option value="1">未设置节点</option>
        </select>
    </div>
</div>
<div id="changeContent">
    <div class="sectionTable-A1 mb10">
        <table class="table table table-hover">
            <thead>
                <tr>
                    <th class="col-1" width="10%"><input type="checkbox" onclick="checkall()"></th>
                    <th class="col-2" width="40%">节点描述</th>
                    <th class="col-3" width="50%">节点名称</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($child) && !empty($child)): ?>
                <?php foreach ($child as $model):?>
                <tr class="">
                    <td class="col-1">
                        <label class="checkbox">
                          <input type="checkbox" name="id[]" value="<?php echo $model->name;?>" />
                        </label>
                    </td>
                    <td class="col-2"><?php echo $model->description;?></td>
                    <td class="col-3"><?php echo $model->name;?></td>
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
                      <input type="checkbox" onclick="checkall()"> 全选
                    </label>
                </li>
                <li class="sectionForm-A1 sectionForm-A1-2">                                    
                    <span class="button">
                        <input class="btn btn-large btn-primary" id="cancel" onclick="setCancel(this)" type="button" parent="<?php echo $itemname?>" value="批量取消">
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    //异步刷新已设置节点/未设置节点
    function changeChild(obm){
        var $obj=$(obm);
        var val=$obj.val();
        var gName='<?php echo $_GET['name'] ?>';
        $.ajax({
            type: "GET",
            url: "/authority/setroles/name/"+gName+"",
            data: {val:val},
            dataType: "html",
            success: function(html){
                $("#changeContent").html(html);
            }
        })
    }
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
     
     //批量设置/取消节点
     function setCancel(objj){
        var itemname = $(objj).attr('parent').trim();
        var idVal = $(objj).attr('id');
        var child = [];
        $('input:checkbox[name="id[]"]:checked').each(function (i,n){
            child.push($(n).val());
       });
       if(child=='' || child==null){
           popup.alert("请选择设置项!");
           return false;
       }
       var url=null;
       if(idVal=='cancel'){
           url='/authority/batchcancel';//批量取消节点方法
       }else{
           url='/authority/batchset';//批量设置节点方法
       }
       $.post(url,{itemname:itemname,child:child},function(data){
           if(data){
               popup.success("修改成功！");
               setTimeout(function(){
                   popup.close("asyncbox_success");
               },2000);
                $.each(child,function(i,n){
                   $("#"+n).fadeOut();
               });
           }else{
               popup.error("批量设置失败！");
               setTimeout(function(){
                   popup.close("asyncbox_error");
               },2000);
           }
       })
    }
</script>