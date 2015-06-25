//删除素材
//@PS:建模型单下的素材直接删除，渲染订单下的素材只删除关联关系
function delInfo(obj)
{
    var id = $(obj).attr('rel');
    var oid = $(obj).attr('oid');
    popup.confirm('确定删除此素材吗？','删除提示',function(e){
        if('ok'===e){
            $.post("/info/del",{id:id,oid:oid},function(data){
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

//是否360度
function is_360(sn)
{
    var snVal = $(sn).val();
    var infoId = $(sn).attr("rel");
    if(snVal == 0){
        $("#is_360_Content").html("");
    }else{
        $.ajax({
            type:"post",
            dataType:"html",
            url:"/info/changeOType",
            data:{type:snVal,infoId:infoId},
            success:function(html){
                $("#is_360_Content").html(html);
            }
        })
    }
}

//删除贴图图片
function delTexImg(dt)
{
    var tid = $(dt).attr("tid");
    var column = $(dt).attr("column");
    var imgKey = $(dt).attr("imgKey");
    if(tid == '' || column == '' || imgKey == ''){
        alert("缺少参数！");return false;
    }else{
        if(confirm('确定删除此图吗？')){
            var temp = {"tid":tid,"column":column,"imgKey":imgKey};
            $.post("/mold/delTexImg",{temp:temp},function(data){
                if(data.status){
                    alert(data.info);
                    $(dt).parent().remove();
                }
            },'json')
        }
    }
}

//切换模型的类型
function switchMT(smt)
{
    var mid = $(smt).val();
    if(mid != '')
        window.location = '/mold/update/id/'+mid;
}

//删除颜色帧
function delTexture(dte)
{
    var mmId = $(dte).attr("rel");
    var id = $(dte).attr("obj_id");
    var model = $(dte).attr("type");
    if(confirm('确定删除此贴图吗？')){
        if(mmId !='' && id !=''){
            $.post("/mold/delMoldMap",{mmId:mmId,id:id,model:model},function(data){
                if(data.status){
                    alert(data.info);
                    location.reload(); 
                }
            },'json')
        }
    }
}