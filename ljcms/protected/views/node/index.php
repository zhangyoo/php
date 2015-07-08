<div class="sectionTitle-A mb10">
    <h2>层级列表</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        <?php if(!empty(Yii::app()->session['update'])): ?>
        <a href="/node/create/sid/<?php echo $_GET['sid']; ?>" class="btn btn-primary">创建层级</a>
        <?php endif; ?>
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <?php $form = $this->beginWidget('CActiveForm', array(
                    'id'=>'user-form',
                    'method'=>'get',
                    'action'=>'/node/index/sid/'.$_GET['sid'],
                    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
          )); ?>
            <ul class="clear">
                <li>
                    <label>名称</label>
                    <input type="text" value="<?php if(isset($_GET['name'])) echo $_GET['name']; ?>" name="name" class="text">
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
                <th class="col-1" width="15%" colspan="2">层级名称</th>
                <th class="col-2" width="20%">层级</th>
                <th class="col-4" width="20%">创建时间</th>
                <th class="col-5" width="20%">更新时间</th>
                <th class="col-6">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($dataProvider) && !empty($dataProvider)): ?>
            <?php foreach ($dataProvider as $model):?>
            <tr id="tr_<?php echo $model['id']; ?>">
                <td class="col-1"><span class="edit_<?php echo $model['id']; ?>"><?php echo $model["name"];?></span></td>
                <td class="col-2 edit">
                    <i id="n" rel="<?php echo $model['id']; ?>" onclick="editli(this)"></i>
                    <a id="a_nav" style=" display: none;" href="javascript::" onclick="dosave(this)" >保存</a>
                    <a id="a1_nav"  style=" display: none;" href="javascript::" onclick="dosave1(this)" >取消</a>
                </td>
                <td class="col-1"><?php echo $model['layer'];?></td>
                <td class="col-3"><?php echo date("Y-m-d H:i:s", $model["create_time"]);?></td>
                <td class="col-4"><?php echo !empty($model["update_time"])?date("Y-m-d H:i:s",$model["update_time"]):'';?></td>
                <td class="col-5">
                    [ <a href="/node/update/id/<?php echo $model['id']; ?>/sid/<?php echo $sid; ?>"><?php echo !empty(Yii::app()->session['update']) ? "编辑":"查看"; ?></a> ]
                    <?php if(!empty(Yii::app()->session['delete'])): ?>
                    [ <a href="javascript:void(0)" onclick="delel(this)" rel="<?php echo $model['id'];?>">删除</a> ]<br>
                    <?php endif; ?>
                    <!--[ <a href="/node/element/id/<?php // echo $model['id']; ?>">绑定元素</a> ]<br>-->
                    [ <a href="/node/element/id/<?php echo $model['id']; ?>/unbind/">层级元素管理</a> ]
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="sectionFoot-B1">
    <div class="sectionFloat-A1 addpage_style">
        <div class="page_list">
        <?php $this->widget('CLinkPager', array(
            'header'=> '<span>共'. $count. '个</span>',
            "maxButtonCount"=>5,
            'pages' => $pages,
            'firstPageLabel'=>'&lt;&lt; 首页',
            'prevPageLabel'=>'&lt; 上一页',
            'nextPageLabel'=>'下一页 &gt;',
            'lastPageLabel'=>'末页 &gt;&gt;',
        ))?> 
        </div>
    </div>
</div>
<script type="text/javascript">
    //删除层级
    function delel(dom){
            var id = $(dom).attr('rel');
            var $delpar=$(dom).parent().parent();
               if(confirm('是否确定删除')){
                  $.post('/node/del',{id:id},function(data){
                     if(data.status){
                         alert("删除成功!");
                         $delpar.remove();
                      }else{
                      alert("删除失败!");
                  }
                  },'json');
            }  
    }
    //编辑层级名称
    function editli(dom){
		var id = $(dom).attr('rel');
		var val = $(".edit_"+id).html();
		$(".edit_"+id).after('<input id="name" rel="'+id+'" style="width:50px;" class="w150" type="text" value="'+val+'">');
		$(".edit_"+id).remove();
        $(dom).hide();
        $(dom).siblings('#a_nav').show();
        $(dom).siblings('#a1_nav').show();
	}
    //小铅笔更新层级名称
    $(function(){
		$("#name").live('blur',function(){
			var id = $(this).attr('rel');
			var val = $(this).val().trim();
			$.post('/node/updateview',{id:id,type:'name',val:val},function(data){
			});
		});
        
	});
    //保存层级名称js操作页面
    function dosave(dom)
    {
        var save = $(dom).parent().prev().find("input");
        if(save.val()!=undefined)
        {
            var va = save.val();
            var id = save.attr('rel');
            save.remove();
            $(dom).parent().prev().append('<span class="edit_'+id+'">'+va+'</span>');
        }
        $(dom).siblings('#n').show();
        $(dom).hide();
        $(dom).siblings('#a1_nav').hide();
    }
    //取消保存层级名称js操作页面
    function dosave1(dom)
    {
        var save = $(dom).parent().prev().find("input");
        if(save.val()!=undefined)
        {
            var va = save.val();
            var id = save.attr('rel');
            save.remove();
            $(dom).parent().prev().append('<span class="edit_'+id+'">'+va+'</span>');
        }
        $(dom).siblings('#n').show();
        $(dom).siblings('#a_nav').hide();
        $(dom).hide();
    }
    
</script>