//绑定商品/模型弹框
function showBindPM(osp)
{
    var body_bg="<div class='big_bg_brand'> </div>";
    var id = $(osp).attr("rel");
    $("#chooseType").attr("choosevalue",$(osp).attr("type"))
    if($("#chooseType").attr("choosevalue") == 'product'){
        $("#chooseType").text("绑定商品(只能绑定一个)");
        $(".search_product_name").attr("placeholder","商品名称(或商品货号)");
    }else{
        $("#chooseType").text("绑定模型(可批量绑定)");
        $(".search_product_name").attr("placeholder","模型名称(或模型型号)");
    }
    $(".search_product_name").val("");
    $("body").prepend(body_bg);
    $(".psBox").html('');
    $("#saveSecDia").attr("rel",id);
    $("#selectBindType option[value='unbind']").attr("selected", true);
    $("#saveSecDia").attr("bind","unbind");
    $(".pop_dialog_top").show();
    ResSearchProduct(this);
}

//切换绑定和解绑---素材
$("#selectBindType").live('change',function(){
    if($("#selectBindType").val() == 'bind'){
        $("#saveSecDia").val("绑定");
        $("#saveSecDia").attr("bind","bind");
    }else{
        $("#saveSecDia").val("解除绑定");
        $("#saveSecDia").attr("bind","unbind");
    }
    $(".search_product_name").val("");
    $(".psBox").html(loadImg);
    ResSearchProduct(this);
})

//搜索商品
function ResSearchProduct(rsp)
{
    var pName = $(".search_product_name").val();
    var bType = $("#selectBindType").val();
    var rid = $("#saveSecDia").attr("rel");
    var pm = $("#chooseType").attr("choosevalue");
    $.ajax({
        type:"post",
        dataType:"html",
        url:"/label/resSearchProduct",
        data:{name:pName,type:bType,rid:rid,pm:pm},
        success:function(html){
            $(".psBox").html(html);
        }
    })
}

//关联商品id/模型id
function ResSProduct(rs)
{
    var rid = $(rs).attr("rel");
    var type = $(rs).attr("bind");
    var selectArray = new Array();
    var psLen=$(".psBox li[isselect=1]").length;
    for(var j=0;j<psLen;j++){
        selectArray.push($(".psBox li[isselect=1]").eq(j).attr("objId"));
    }
    var pm = $("#chooseType").attr("choosevalue");
    if(selectArray.length > 0){
        $.post("/label/rProduct",{selectArray:selectArray,rid:rid,type:type,pm:pm},function(data){
            if(data.status){
                popup.success(data.info);
                setTimeout(function(){
                    popup.close("asyncbox_success");
                },2000);
                closeDia();
                window.location.reload();
            }else{
                popup.error(data.info);
                setTimeout(function(){
                    popup.close("asyncbox_error");
                },2000);
            }
        },'json')
    }else{
        if(pm == 'product'){
            popup.error("请选择要绑定的商品！");
        }else{
            popup.error("请选择要绑定的模型！");
        }
    }
}

//删除标签
function delLabel(dl)
{
    var laid = $(dl).attr("rel");
    popup.confirm('请先确认完全转移该标签下的物品！确定删除此标签吗？','删除提示',function(e){
        if('ok'===e){
            $.post("/label/delLabel",{laid:laid},function(data){
                if(data.status){
                    popup.success(data.info);
                    setTimeout(function(){
                        popup.close("asyncbox_success");
                    },2000);
                    window.location.reload();
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

//弹出真实分类框
function disDiaCat(ddc)
{
    var $ddc = $(ddc);
    var lid = $ddc.attr("rel");
    var body_bg="<div class='big_bg_brand'> </div>";
    $("body").prepend(body_bg);
    $(".dialog_bind_cat").show();
    $.post("/label/getCategory",{lid:lid},function(html){
        $(".dialog_bind_cat_content").html(html);
    },'html')
    
}

//关闭真实分类弹框
function closeBindCat()
{
    $(".dialog_bind_cat_content").html("");
    $(".dialog_bind_cat").hide();
    $(".big_bg_brand").remove();
}

//标签绑定真实商品分类
function saveLabelCat(sll)
{
    var lid = $(sll).attr("rel");
    var idArray=new Array();
    $('input:checkbox[name="Cat[]"]').each(function (i,n){
        if($(n).attr("checked")=="checked"){
            idArray.push($(n).val());
        }
    });
    $.post("/label/saveLabelCat",{lid:lid,temp:idArray},function(data){
        alert(data.info);
        if(data.status){
            window.location.reload();
        }
    },'json')
}