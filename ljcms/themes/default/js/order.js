//切换订单类型
function changeOType(co)
{
    var $obj = $(co);
    var typeVal = $obj.val();
    $.ajax({
        type:"post",
        dataType:"html",
        url:"/order/changeOType",
        data:{type:typeVal},
        success:function(html){
            $("#changeOrderType").html(html);
            if(typeVal==0 || typeVal==3){
                $("#submitOrder").val("保存订单，下一步创建素材信息");
            }else{
                $("#submitOrder").val("保存订单，下一步选择素材信息");
            }
        }
    })
}

//显示绑定空间弹框
function showBind()
{
    var body_bg="<div class='big_bg_brand'> </div>";
    $("body").prepend(body_bg);
    $(".pop_dialog_top").show();
}

//搜索某个功能空间下的空间
function searchSpace(oss)
{
    var rc = $("#search_room_category").val();
    var sv = $(".search_space_lw").val();
    var cs = $(oss).attr("rel");
    if(rc == ''){
        popup.error("请选择一个功能空间！");
    }else{
        if(cs != 0 && rc != cs){
            if(confirm('确定选择不同的功能空间吗？确定则会删除已绑定的空间数据！')){
                $(".sBind").html('');
            }else{
                return false;
            }
        }
        $.ajax({
            type:"post",
            dataType:"html",
            url:"/order/searchSpace",
            data:{rc:rc,name:sv},
            success:function(html){
                $(".psBox").html(html);
            }
        })
    }
}

//选择空间
function choosePS(objz){
    var chooseValue=$("#chooseType").attr("chooseValue");
    if(chooseValue=='product' && $(objz).attr('isselect')!=1){
        var chooseLen=$(".psBox li[isselect=1]").length;
        if(chooseLen>0){
            $(".psBox li[isselect=1]").find(".deleteImg").hide();
            $(".psBox li[isselect=1]").attr("isselect","0");
        }
    }
    var checkImg = $(objz).find('.deleteImg:first'),
        isDisplay = $(checkImg).css('display');
    $(checkImg).css('display',isDisplay==='none' ? 'block' : 'none');
    isDisplay = $(checkImg).css('display');
    $(objz).attr('isselect',isDisplay==='none' ? '0' : '1');
}

//临时添加空间数据
function saveSpace(osn)
{
    //已绑定的空间id
    var oldArr = new Array();
    var oldLen = $(".sBind input").length;
    for(var ii=0;ii<oldLen;ii++){
        oldArr.push($(".sBind input").eq(ii).val());
    }
    //新绑定的空间id
    var selectArray = new Array();
    var psLen=$(".psBox li[isselect=1]").length;
    for(var j=0;j<psLen;j++){
        selectArray.push($(".psBox li[isselect=1]").eq(j).attr("objId"));
    }
    
    if(selectArray.length>0){
        $.post("/order/saveSpace",{temp:selectArray,oldArr:oldArr},function(html){
            $(".info_psearch_button").attr("rel",$("#search_room_category").val());
            closeDia();
            $(".sBind").append(html);
        },'html')
    }
}

//关闭绑定商品/空间弹框
function closeDia()
{
    $(".pop_dialog_top").hide();
    $(".big_bg_brand").remove();
}

//删除临时绑定的空间
function delSbind(oam)
{
    $(oam).parent().parent().remove();
}

//删除图片
$("#sc_ys").live('click',function(e){
     var dom = $(this);
     if(confirm('确定删除图片?'))
     {
         var url = dom.attr('url');
         var tempUrl = dom.parent().parent().find("img.cc").attr('src');
         $.post(url,{tempUrl:tempUrl},function(rs){
             if(rs){
                  dom.parent().parent().remove();
             }else{
                 alert('删除失败！');
             }
         })
     }
     e.stopPropagation();
});

//删除绑定的空间参考图或者360度图片
function delSImg(oam)
{
    var $si = $(oam);
    var aid = $si.attr("rel");
    if(confirm('确定删除此图吗？')){
        $.post("/order/delSImg",{aid:aid},function(data){
            alert(data.info);
            if(data.status){
                $si.parent().parent().remove();
            }
        },'json')
    }
}

//删除绑定的空间
function delPSbind(oam)
{
    $(oam).parent().parent().remove();
}