$(function(){
    
})

//分类初始数据
function getCategory(params){
    $.ajax({
        type:"POST",
        url:"/category/getcategory",
        data:{_csrf :params['_csrf']},
        dataType:"html",
        success:function(html){
            $("#category-parent_id").html(html);
        }
        
    })
}

