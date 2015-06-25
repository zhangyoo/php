<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/message.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/common/js/jquery.qqFace.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/message.js"></script>
<div class="sectionTitle-A mb10">
    <h2>站内信列表</h2>
</div>
<div class="M_top_content">
    <div class="left_top_content">
        <span class="L">收件箱</span>
        <span class="R">选项</span>
    </div>
    <div class="right_top_content">
        <span class="L newMLef">新消息</span>
    </div>
</div>
<div class="M_bottom_content">
    <div class="left_bottom_content">
        <div class="bc_box">
            <span><b>未读收件箱</b>中的消息</span>
            <a href="javascript:void(0);">全部标记为已读</a>
        </div>
        <div class="bc_box">
            <div class="M_search">
                <input type="text" name="searchKeyword" id="searchKeyword" size="42" placeholder="搜索"/>
                <a class="search" href="javascript:void(0);" onclick="searchKeyword()"></a>
            </div>
        </div>
        <div class="bc_box M_list">
            <?php if(!empty($med)): ?>
            <?php foreach ($med as $k=>$m): ?>
            <div class="list_single_box <?php echo $k+1==count($med) ? 'noBorder':''; ?>" onclick="selMessage(this)">
                <div class="img_sbx">
                    <a href="/manage/message/messageView/mid/<?php echo empty($m['main_id']) ? $m['id']:$m['main_id']; ?>/fid/<?php echo $m['uid'];?>">
                        <?php
                            $img=  empty($m['image']) ? '/common/images/headPortrait.png' : Yii::app()->params['static'].$m['image'];
                            echo CHtml::image($img,$m['username'],array('width'=>'50px','height'=>'50px')); 
                        ?>
                    </a>
                </div>
                <div class="img_rig_sbx">
                    <p>
                        <span class="L"><b><a style="<?php echo $m['flag']==1 || $m['suid']==$uid ? 'color:#808080;':''; ?>" href="/message/messageView/mid/<?php echo empty($m['main_id']) ? $m['id']:$m['main_id']; ?>/fid/<?php echo $m['uid'];?>"><?php echo $m['username']; ?></a></b></span>
                        <span class="R"><?php echo $m['flag']==0 ? date("Y-m-d",$m['addtime']):date("Y-m-d",$m['read_time']); ?></span>
                    </p>
                    <p>
                        <span class="L"><?php echo mb_substr(strip_tags($m['content']),0,14,'utf8'); ?>...</span>
                        <span class="read_del_m">
                            <?php if($m['flag']==0 && $m['suid']!=$uid): ?>
                            <i class="no_read" onclick="readMail(this)" mid="<?php echo $m['id']; ?>" uid="<?php echo $uid; ?>"><a href="javascript:void(0);">标记为已读</a></i>
                            <?php endif; ?>
                            <i class="no_del"><a href="javascript:void(0);">删 除</a></i>
                        </span>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="right_bottom_content">
        <div class="receiver">
            <label>收件人：</label>
            <div id="input_rid_box">
                <input id="recieverBox" placeholder="输入邮箱号,以英文逗号(;)隔开" name="reciever"/>
                <div class="receiverCon" style="text-align:left"> 
                    <p><b>最近联系人：</b></p>
                    <?php if(!empty($reids)): ?>
                    <?php foreach ($reids as $kr=>$rd): ?>
                    <p><a href="javascript:void(0);" rel="<?php echo $kr; ?>"><?php echo $rd.'('.$kr.')'; ?></a></p>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="dialogBox">
            <div id="show"></div>
            <!--写信息-->
            <form id="commentWrap" class="commentWrap">
                <div class="comment">
                   <div class="com_form">
                       <textarea placeholder="写消息..." name="saytext" id="saytext" class="input"></textarea>
                        <p>
<!--                            <span class="addFile"><i></i>&nbsp;添加文件</span>
                            <span class="addPic"><i></i>&nbsp;添加照片</span>
                            <span class="sendEnter">按Enter发送 <input type="checkbox" name="checkbox" id="checkbox"/></span>-->
                            <input type="button" onclick="sendMessage()" value="发送" class="sub_btn">                      
                            <span class="emotion"></span>
                        </p>
                   </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){      
        //显示收件人列表
        $("#input_rid_box").hover(function(){
            $(".receiverCon").slideDown();
        },function(){
            $(".receiverCon").slideUp();
        });
        
        //添加联系人
        $(".receiverCon p a").click(function(){
           var textValue = $("#recieverBox").val();
           var textEmail=$(this).attr("rel");
           var checkArray=new Array();
           var val='';
           var chc=0;
           if(textValue!=''){
                val=textValue.replace(/(^\s*)|(\s*$)/g,'');
                val=val.replace(/\s+/g,';');//匹配字符串中一个或多个空格为英文分号
                while(val.indexOf("，")!=-1)//寻找每一个中文逗号，并替换
                 {
                     val=val.replace("，",";");
                 }
                 while(val.indexOf(",")!=-1)//寻找每一个中英文逗号，并替换
                 {
                     val=val.replace(",",";");
                 }
                 while(val.indexOf("；")!=-1)//寻找每一个中文分号，并替换
                 {
                     val=val.replace("；",";");
                 }
                 while(val.indexOf("、")!=-1)//寻找每一个中文顿号，并替换
                 {
                     val=val.replace("、",";");
                 }
                 checkArray=val.split(";"); //字符分割 
                 $.each(checkArray,function(i,n){
                     if(n==textEmail)
                         chc=1;
                 })
                 textEmail=";"+textEmail;
           }
           if(chc==0)
               $("#recieverBox").val(val+textEmail);
        });
        
        //载入qq表情
        $('.emotion').qqFace({
            id : 'facebox', 
            assign:'saytext', 
            path:'/common/arclist/'	//表情存放的路径
        });
            
        //按Enter键发送（后期考虑需加上）
//        $(".sendEnter input").click(function(){
//           var str = $("#saytext").val();
//            $("#show").html(replace_em(str));
//        });
    }) 
</script>