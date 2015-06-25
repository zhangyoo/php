<link rel="stylesheet" type="text/css"  href="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/css/WdatePicker.css" />    
<script type="text/javascript" src="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/WdatePicker.js"></script>
<div class="sectionTitle-A mb10">
    <h2>数据同步</h2>
</div>
<div class="sectionTable-A1 mb10">
    <table class="table synctab table-hover">
        <thead>
            <tr>
                <th class="col-1" width="7%"><input type="checkbox" class="all_select" value="" /> 全选</th>
                <th class="col-2">数据表名称</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dbArray as $key=>$val): ?>
            <?php if(!empty($val)): ?>
            <?php foreach($val as $v): ?>
            <tr align="center" class="sub">
                <td>
                    <input type="checkbox" name="Table[]" value="<?php echo $v; ?>" />
                </td>
                <td class="td-0">
                    <?php echo $v; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            <?php endforeach; ?>
            <tr>
                <td align="center" colspan="2"><input type="checkbox" class="all_select" value="" /> 全选</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="commonBtnArea" style="width: 1174px;margin-bottom: 20px ">
    <a class="btn submit" onclick="sync()">批量同步</a>
</div>
<script type="text/javascript">
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
   //批量数据同步
   function sync()
   {
       var tables=new Array();
       $(".synctab input[type=checkbox]:checked").each(function(){
           if($(this).val()!=''){
               tables.push($(this).val());
           }  
       })
       if(tables.length>0){
           $.ajax({
                type:"POST",
                url:"/sync/batchSync",
                data:{tables:tables},
                datatype:"json",
                success:function(result){
                        
                }
            })
       }else{
           alert("未选择数据表！");
       }
   }
</script>