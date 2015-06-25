<link rel="stylesheet" type="text/css"  href="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/css/WdatePicker.css" />    
<script type="text/javascript" src="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/WdatePicker.js"></script>
<div class="sectionTitle-A mb10">
    <h2>元素列表</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        <a href="/element/autoCreate" target="_blank" class="btn btn-primary">自动生成元素</a>
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <?php $form = $this->beginWidget('CActiveForm', array(
                    'id'=>'user-form',
                    'method'=>'get',
                    'action'=>'/element/index',
                    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
          )); ?>
            <ul class="clear">
                <li>
                    <select id="catalogId" name='status'>
                        <option value="5" <?php echo $status==5 ? 'selected':''; ?>>已审核</option>
                        <option value="2" <?php echo $status==2 ? 'selected':''; ?>>未审核</option>
                        <option value="3" <?php echo $status==3 ? 'selected':''; ?>>审核不通过</option>
                    </select>
                </li>
                <li>
                    <label>名称</label>
                    <input type="text" value="<?php echo !empty($seachData['name']) ? $seachData['name'] :''; ?>" name="name" class="text">
                </li>
                <li>
                    <label>起始时间:</label>
                    <input class="input inputLen" type="text" name="timeStart" value="<?php echo !empty($seachData['timeStart'])?$seachData['timeStart']:''; ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" size="20" style="margin-right: 20px;" />
                </li>
                <li>
                    <label>截止时间:</label>
                    <input class="input inputLen" type="text" name="timeEnd" value="<?php echo !empty($seachData['timeEnd'])?$seachData['timeEnd']:''; ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" size="20" style="margin-right: 20px;" />
                </li>
                <li class="button">                                    
                    <input class="btn btn-large btn-primary" type="submit" value="查询">
                </li>
            </ul>				      		                   
        <?php $this->endWidget(); ?>
    </div>
</div>

<div class="sectionTable-A1 mb10">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="4%">全选</th>
                <th class="col-1" width="12%">元素封面图</th>
                <th class="col-2" width="15%">元素名称</th>
                <th class="col-3" width="20%">元素说明</th>
                <th class="col-4" width="13%">创建时间</th>
                <th class="col-5" width="13%">更新时间</th>
                <th class="col-5" width="9%">是否绑定模型</th>
                <th class="col-6" width="14%">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($ul) && !empty($ul)): ?>
            <?php foreach ($ul as $model):?>
            <tr id="tr_<?php echo $model['id']; ?>">
                <td>
                    <input type="checkbox" name="Element[]" value="<?php echo $model['id']; ?>" />
                </td>
                <td class="col-1">
                    <?php if($status==2): ?>
                    <?php echo CHtml::image(Yii::app()->params['static'].$model['image'],$model['name'],array('width'=>'140')); ?>
                    <?php else: ?>
                    <img src="<?php echo ImageHelper::showThumb($model['image'],array("width"=>420,"height"=>330, 'type'=>  ImageHelper::THUMB_TYPE_CROP)); ?>" width="140" alt="<?php echo $model['name']; ?>"/>
                    <?php endif; ?>
                </td>
                <td class="col-2"><?php echo $model['name']; ?></td>
                <td class="col-3"><?php echo $model["summary"];?></td>
                <td class="col-4"><?php echo date("Y-m-d H:i:s", $model["create_time"]);?></td>
                <td class="col-5"><?php echo !empty($model["update_time"])?date("Y-m-d H:i:s",$model["update_time"]):'';?></td>
                <td>
                    <?php if($model['type']!=4 && !isset($model['status'])){ echo !empty($model['mold_id']) ? '已绑定' : '未绑定';}else{echo '无';} ?>
                </td>
                <td class="col-6">
                    [<a href="/space/updateElement/id/<?php echo $model['id']; ?>/model/<?php echo $status==2?'ElementTemp':'Element'; ?>"><?php echo !empty(Yii::app()->session['update']) ? "编辑":"查看"; ?></a>]
                    <?php if(!empty(Yii::app()->session['delete'])): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $model['id'];?>" onclick="Delete(this)">删除</a>]
                    <?php endif; ?><br>
                    <?php if($model['type']!=4 && !isset($model['status'])): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $model['id'];?>" onclick="bindmMold(this)" deal="1">绑定模型</a>]
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            <tr>
                <td><input type="checkbox" class="all_select" /> 全选</td>
                <td colspan="7" align="right"> 
                    <div class="">
                        <div class="sectionFloat-A1 addpage_style">
                            <div class="page_list">
                                <?php $this->widget('CLinkPager', array(
                                    'header'=>'<span>共'.$this->page->itemCount. '个</span>',
                                    "maxButtonCount"=>5,
                                    'pages' => $this->page,
                                    'firstPageLabel'=>'&lt;&lt; 首页',
                                    'prevPageLabel'=>'&lt; 上一页',
                                    'nextPageLabel'=>'下一页 &gt;',
                                    'lastPageLabel'=>'末页 &gt;&gt;',
                                ))?>  
                            </div>
                            <div class="goToPages">
                                跳转到：
                                <input type="text" name="pageNum" class="pageInput" /> 页&nbsp;&nbsp;
                                <input type="button" id="loadPageNum" onclick="transPages(this)" url="<?php
                                echo Yii::app()->request->BaseUrl;?>/element/index/page/" class="clickPageIcon" value="确定"/>&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="commonBtnArea" style="width: 1174px; margin: 10px 0">
    <?php if($status==5): ?>
    <a class="btn submit" onclick="bindmMold(this)" deal="0">批量绑定模型</a>
    <?php else: ?>
    <a class="btn submit" onclick="checkAll()">批量审核</a>
    <?php endif; ?>
</div> 
<div class="pop_dialog_top" style="display:none;width:830px">
    <div class="dialog_two_top">
        <b>绑定模型(只能绑定一个)</b>
        <a onclick="closeDia()" href="javascript:void(0);"><img alt="关闭弹框" title="关闭弹框" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/cut_icon.png" class="icon"></a>
    </div>
    <div class="dialog_content_top">
        <div class="info_psearch">
            <select class="inputLen" id="selectBindTypeElement" style="margin:0">
                <option value="unbind">已绑定</option>
                <option value="bind">未绑定</option>
            </select>
            &nbsp;
            <input type="text" class="search_product_name mold_search_value" placeholder="模型名称(或模型型号)" name="searchName" />
            <input type="button" class="info_psearch_button"  onclick="changeBM(this)" value="搜索" />
        </div>
        <ul class="info_img_ul psBox">
            
        </ul>
        <div class="dialog_save savePfix">
            <input type="button" onclick="saveBM(this)" id="saveSecDia" rel="" bind="unbind" value="解除绑定">
        </div>
    </div>
</div>
<script type="text/javascript">
//删除元素
function Delete(dom){
    var id = $(dom).attr('rel');
    var status=<?php echo $status; ?>;
    popup.confirm('确定删除此元素吗？','删除提示',function(e){
        if('ok'===e){
            $.post('/element/delete',{id:id,status:status},function(data){
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
   //全选
   var i=0;
   $(".all_select").click(function(){
       if(i==0){
       i=1;
       $(".table tr td input[type=checkbox]").attr("checked",true);
   }else if(i==1){
       i=0;
       $(".table tr td input[type=checkbox]").attr("checked",false);
   }
   }); 
   //批量审核元素
   function checkAll(){
        var data=new Array();
        $('input:checkbox[name="Element[]"]').each(function (i,n){
            if($(n).attr("checked")=="checked"){
                data.push($(n).val());
            }
        });
        if(data.length > 0){
            $.post('/element/checkElement',{id:data}, function (json) {
                if (json.status) {
                    $.each(data,function(i,n){
                        $("#tr_"+n).remove();
                   })
                }
            },'json');
        }else{
            alert("请选择要审核的元素!");
        }
    }
    
    //选择模型
    function choosePS(objz){
        if($(objz).attr('isselect')!=1){
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
    
    //图片出现缩略图
    $(".table-hover .col-1 img").hover(function(){
        var srcImg = $(this).attr('src');
        var img="<div id='img_msg' ><img alt='' src='"+srcImg+"' /></div>";
        $(this).parent().find("#img_msg").remove();
        $(this).parent().append(img);	
        $("#img_msg").css("display","block");		
    },function(){
        $(this).parent().find("#img_msg").remove();
    });
</script>