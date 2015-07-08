//载入中图片
var loadImg = "<img style='margin:100px 0 0 250px' src='/common/images/loading.gif' />";
//页面跳转
function transPages(opg)
{
    var pageNum = $("input[name=pageNum]").val();
    var purl = $(opg).attr("url");
    pageNum=pageNum.trim();
    if(!pageNum){
        alert("请输入跳转的页面！");
    }else{
        window.location.href=purl+pageNum;
    }
}
function trim(mystr){
    while ((mystr.indexOf(" ")==0) && (mystr.length>1)){
        mystr=mystr.substring(1,mystr.length);
    }//去除前面空格
    while ((mystr.lastIndexOf(" ")==mystr.length-1)&&(mystr.length>1)){
        mystr=mystr.substring(0,mystr.length-1);
    }//去除后面空格
    if (mystr==" "){
        mystr="";
    }
    return mystr;
}

//添加空间视角
function addAngle()
{
    $.ajax({
             type: "POST",
             url: "/space/addAngle",
             data:{} ,
             dataType: "html",
             success: function(html){
                 $("#addAngles").append(html);
             }
         })
}

//元素绑定模型
function bindmMold(hbm)
{
    var $obj=$(hbm);
    var deal=$obj.attr("deal");
    var eles=new Array();
    if(deal==1){
        eles.push($obj.attr("rel"));
    }else{
        $('input:checkbox[name="Element[]"]').each(function (i,n){
            if($(n).attr("checked")=="checked"){
                eles.push($(n).val());
            }
        })
    }
    if(eles.length==0){
        alert("请选择需要绑定模型的元素！");
    }else{
        $(".psBox").html('');
        var body_bg="<div class='big_bg_brand'> </div>";
        $("body").prepend(body_bg);
        $("#selectBindTypeElement option[value='unbind']").attr("selected", true);
        $("#saveSecDia").attr("bind","unbind");
        $(".search_product_name").val("");
        $(".pop_dialog_top").show();
        $.post("/element/dealEids",{eles:eles},function(data){
            $("#saveSecDia").attr("rel",data);
            changeBM(this);
        },'json');
    }
}

//关闭弹框
function closeDia()
{
    $(".pop_dialog_top").hide();
    $(".big_bg_brand").remove();
}

//切换绑定和解绑---元素
$("#selectBindTypeElement").live('change',function(){
    if($("#selectBindTypeElement").val() == 'bind'){
        $("#saveSecDia").val("绑定");
        $("#saveSecDia").attr("bind","bind");
    }else{
        $("#saveSecDia").val("解除绑定");
        $("#saveSecDia").attr("bind","unbind");
    }
    $(".search_product_name").val("");
    $(".psBox").html(loadImg);
    changeBM(this);
})

//检索模型
function changeBM(okk)
{
    var name=$(".mold_search_value").val();
    var eids=$("#saveSecDia").attr("rel");
    var type = $("#saveSecDia").attr("bind");
    $.post("/element/loadMold",{name:name,eids:eids,type:type},function(html){
        $(".psBox").html(html);
    },'html')

}

//绑定模型
function saveBM(sbm)
{
    var eids=$(sbm).attr("rel");
    var type = $("#saveSecDia").attr("bind");
    var mid=$(".psBox li[isselect=1]").attr("objid");
    if(mid==''){
        alert("请选择需要绑定的模型！");
    }else{
        $.post("/element/bindMold",{eids:eids,mid:mid,type:type},function(data){
            alert(data.info);
            if(data.status){
                window.location.reload();
            }
        },'json')
    }
}

//删除订单
function delOrder(yaa)
{
    var $klj=$(yaa);
    var id=$klj.attr("rel");
    popup.confirm('确定删除此订单吗？确认删除则会删除订单下的所有素材！','删除提示',function(e){
        if('ok'===e){
            $.post("/order/del",{id:id},function(data){
                if(data.status){
                    popup.success(data.info);
                    setTimeout(function(){
                        popup.close("asyncbox_success");
                    },2000);
                    $("#tr_"+id).remove();
                }else{
                    popup.error(data.info);
                    setTimeout(function(){
                        popup.close("asyncbox_success");
                    },2000);
                }
            },'json')
        }
    })
}

//指派任务到谁
function bindTask(bt)
{
    var $bt = $(bt);
    var bindData = {"taskType":"","sid":"","allocationType":"","objId":"","rid":""};
    var rid = $bt.val();
    var preTitle = '接单人员：';
    bindData.taskType = $bt.attr("task_type");
    bindData.sid = $bt.attr("sid");
    bindData.allocationType = $bt.attr("allocation_type");
    bindData.objId = $bt.attr("obj_id");
    if(bindData.taskType==1){
        preTitle = '模型：';
    }else if(bindData.taskType==2){
        preTitle = '贴图：';
    }else if(bindData.taskType==3){
        preTitle = 'QC：';
    }
    if(rid != ''){
        bindData.rid = rid;//任务接受者
        $.ajax({
            type:"POST",
            dataType:"json",
            url:"/task/bindTask",
            data:{bindData:bindData},
            success:function(data){
                if(data.status){
                    popup.success(data.info);
                    setTimeout(function(){
                        popup.close("asyncbox_success");
                    },2000);
                    $bt.parent().html(preTitle+data.rname+"<a href='javascript:void(0);' class='reBindTask' onclick='reBindTask(this)' type='"+data.type+"' taskId='"+data.taskId+"' rid='"+data.rid+"'>&nbsp;</a><br>");
                }else{
                    popup.error(data.info);
                    setTimeout(function(){
                        popup.close("asyncbox_error");
                    },2000);
                }
            }
        })
    }

}

//指派任务到谁
function reBindTask(rbt)
{
    var rid = $(rbt).attr("rid");
    var taskId = $(rbt).attr("taskId");
    var type = $(rbt).attr("type");
    if(rid !='' && taskId !='' && type !=''){
        $.post("/task/changePerson",{rid:rid,taskId:taskId,type:type},function(html){
            $(rbt).parent().html(html);
        },'html')
    }
}

//修改指派任务的员工
function changeBindTask(cbt)
{
    var taskId = $(cbt).attr("taskId");
    var selId = $(cbt).val();
    var preTitle = '接单人员：';
    if(taskId !='' && selId !=''){
        $.post("/task/changeBindTask",{selId:selId,taskId:taskId},function(data){
            if(data.status){
                popup.success(data.info);
                setTimeout(function(){
                    popup.close("asyncbox_success");
                },2000);
                if(data.taskType == 1){
                    preTitle = '模型：';
                }else if(data.taskType == 2){
                    preTitle = '贴图：';
                }else if(data.taskType == 3){
                    preTitle = 'QC：';
                }
                $(cbt).parent().html(preTitle+data.rname+"<a href='javascript:void(0);' class='reBindTask' onclick='reBindTask(this)' type='"+data.type+"' taskId='"+data.taskId+"' rid='"+data.rid+"'>&nbsp;</a><br>");
            }else{
                popup.error(data.info);
                setTimeout(function(){
                    popup.close("asyncbox_error");
                },2000);
            }
        },'json')
    }
}

//调出编辑弹框
function editStatusDia(esd)
{
    var body_bg="<div class='big_bg_brand'> </div>";
    var requestData = {"obj_id":"","type":"","sid":""};
    requestData.obj_id = $(esd).attr("obj_id");
    requestData.type = $(esd).attr("type");
    requestData.sid = $(esd).attr("sid");
    $.post("/task/loadStatus",{requestData:requestData},function(html){
        $(".editStatus_content").html(html);
        $("body").prepend(body_bg);
        $(".editStatus").show();
    },'html')
}

//关闭编辑状态弹框
function closeEditDia()
{
    $(".editStatus").hide();
    $(".big_bg_brand").remove();
}

//保存编辑状态

function saveEditStatus(ses)
{
//    var html=editorVal.html();
//    $("textarea[id=task_summary]").html(html);
    var data=$("form#statusForm").serialize();
    $.ajax({
             type: "POST",
             url: "/task/saveEditStatus",
             data:data ,
             dataType: "json",
             success: function(json){
                 if(json.status){
                     popup.success(json.info);
                     setTimeout(function(){
                         popup.close("asyncbox_success");
                     },2000);
                     closeEditDia();
                     window.location.reload();
                 }else{
                     popup.error(json.info);
                     setTimeout(function(){
                         popup.close("asyncbox_error");
                     },2000);
                 }
             }
         })
}

//审核不通过js
function checkTask(ct)
{
    var taVal = $(ct).val();
    if(taVal == 2){
        $("#taskSumm").show();
    }else{
        $("#taskSumm").hide();
    }
}

//展示360度图片
function disPics(dp)
{
    var body_bg="<div class='big_bg_brand'> </div>";
    var fid = $(dp).attr("rel");
    $.post("/info/disPics",{id:fid},function(html){
        $(".top_two_ul").html(html);
        $("body").prepend(body_bg);
        $(".pop_dialog_top_two").show();
    });
}

//关闭360图片对话框
function closeDia_two()
{
    $(".pop_dialog_top_two").hide();
    $(".big_bg_brand").remove();
}

//修改新命名规则下的定时如的尺寸
function changeTSize(cts)
{
    var id = $(cts).attr("rel");
    var type = $(cts).attr("type");
    var length = $(cts).parent().find("input[name='length']").val();
    var width = $(cts).parent().find("input[name='width']").val();
    var height = $(cts).parent().find("input[name='height']").val();
    if(length == '')
        length = '0';
    if(width == '')
        width = '0';
    if(height == '')
        height = '0';
    var temp = {id:id,type:type,length:length,width:width,height:height};
    $.post("/info/changeTSize",{temp:temp},function(data){
        alert(data.info);
        if(data.status)
            window.location.reload();
    },'json');
    
}
