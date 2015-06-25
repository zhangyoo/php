//收件箱和其它的选项卡变化
function displayR(obj)
{
    $(obj).removeClass("gray");
    $(obj).next().removeClass("sel");
    $(obj).parent().parent().next().find(".InboxCon").show();
    $(obj).parent().parent().next().find(".otherCon").hide();                 
}
function displayO(obm)
{
    $(obm).addClass("sel");
    $(obm).prev().addClass("gray");
    $(obm).parent().parent().next().find(".otherCon").show();
    $(obm).parent().parent().next().find(".InboxCon").hide();          
}
//选中某条信息
function selMessage(osm)
{
    $(osm).addClass("selected").siblings().removeClass("selected");
}

//发送站内信
function sendMessage()
{
    //提交发送内容
    var str = $("#saytext").val();
    var content=replace_em(str);
    //获取收件人id
    var rids=new Array();
    var textVal=$("input#recieverBox").val();
    if(textVal!=''){
        textVal=textVal.replace(/(^\s*)|(\s*$)/g,'');
        textVal=textVal.replace(/\s+/g,';');
        while(textVal.indexOf("，")!=-1)//寻找每一个中文逗号，并替换
        {
            textVal=textVal.replace("，",";");
        }
        while(textVal.indexOf(",")!=-1)//寻找每一个英文逗号，并替换
        {
            textVal=textVal.replace(",",";");
        }
        while(textVal.indexOf("；")!=-1)//寻找每一个中文分号，并替换
        {
            textVal=textVal.replace("；",";");
        }
        while(textVal.indexOf("、")!=-1)//寻找每一个中文顿号，并替换
        {
            textVal=textVal.replace("、",";");
        }
        rids=textVal.split(";"); //字符分割 
    }
    if(content!='' && rids.length!=0){
        $.ajax({
             type: "POST",
             url: "/message/create",
             data:{rids:rids,content:content} ,
             dataType: "json",
             success: function(data){
                 alert(data.info);
                 if(data.status){
                     $("#show").html(replace_em(str));
                     $("#saytext").val("");
                 }
             }
         })
    }else{
        alert("收件人或消息内容不能为空，请重新输入！");
    }
}
 
//查看消息界面发送消息
function sendM(oso)
{
    var strrs = $("#saytext").val();
    var temp=replace_em(strrs);
    var nsid=$(oso).attr("sid");
    var nrid=$(oso).attr("rid");
    var nmid=$(oso).attr("mid");
    var dialogid=$(oso).attr("dialogId");
    if(nsid!='' && nrid!='' && nmid!='' && dialogid!='' && temp!=''){
        $.ajax({
             type: "POST",
             url: "/message/viewCreateMessage",
             data:{nsid:nsid,nrid:nrid,nmid:nmid,dialogid:dialogid,temp:temp} ,
             dataType: "html",
             success: function(html){
                 $(".showBox").append(html);
                 $("#saytext").val("");
             }
         })
    }else{
        alert('信息不全，请确认后再发送！');
    }
}

//搜索消息
function searchKeyword()
{
    var keyVal=$("#searchKeyword").val();
    if(keyVal!=''){
        $.ajax({
            type:"POST",
            url:"/message/searchMessage",
            data:{keyword:keyVal},
            datatype:"html",
            success:function(html){
                $(".M_list").html(html);
            }
        })
    }
}

//删除消息
//待开发
function delMessage(odt)
{
    var dMid=$(odt).attr("mid");
    $.ajax({
        type:"POST",
        url:"/message/delMessage",
        data:{mid:dMid},
        datatype:"json",
        success:function(data){
            
        }
    })
}

//查看结果
function replace_em(str){
    str = str.replace(/\</g,'&lt;');
    str = str.replace(/\>/g,'&gt;');
    str = str.replace(/\n/g,'<br/>');
    str = str.replace(/\[em_([0-9]*)\]/g,'<img src="/common/arclist/$1.gif" border="0" />');
    return str;
}

//标记为已读
function readMail(ard){
    var mid=$(ard).attr('mid');
    var uid=$(ard).attr('uid');
    if(mid!='' && uid!=''){
        $.post("/message/readMail",{mid:mid,uid:uid},function(data){
            if(data.status){
                popup.success(data.info);
            }else{
                popup.error(data.info);
            }
            setTimeout(function(){
                popup.close("asyncbox_success");
            },2000);
        },'json')
    }
}


