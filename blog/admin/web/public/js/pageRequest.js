$(function(){
    
})

//加载初始类别数据
function getInit(){
    $.ajax({
        type:"POST",
        url:"/category/getCategory",
        data:{},
        dateType:"html",
        success:function(data){

        }
    })
}