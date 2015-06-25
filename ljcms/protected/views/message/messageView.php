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
        <span class="L newMLef"><?php echo $friend['username']; ?></span>
        <span class="R top_rig_add">
            <a href="">+&nbsp;操作</a>
        </span>
        <span class="R top_rig_add" style="margin-right:10px">
            <a href="/message/create">+&nbsp;新消息</a>
        </span>
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
            <div class="list_single_box <?php echo $k+1==count($med) ? 'noBorder':''; ?> <?php echo $_GET['fid']==$m['uid'] ? 'selected':''; ?>" onclick="selMessage(this)">
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
                        <span class="L"><b><a class="<?php echo $m['flag']==1 || $m['suid']==$uid ? 'sel_a':''; ?>" href="/message/messageView/mid/<?php echo empty($m['main_id']) ? $m['id']:$m['main_id']; ?>/fid/<?php echo $m['uid'];?>"><?php echo $m['username']; ?></a></b></span>
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
        <div class="dialogBox">
            <div id="show" class="showView">
                <div class="showBox">
                    <p class="startTime">对话开始于<?php echo date("Y-m-d h:i:s",$mainSiteMail['addtime']); ?></p>
                    <?php if(!empty($megs)): ?>
                    <?php foreach ($megs as $mg): ?>
                    <?php if($mg['uid']==$mine['id']){ ?>
                    <div class="dialogBoxWrap clearfix">
                        <div class="dialogBox1 mb20 fl">
                            <div class="dialogBox1-top clearfix mb5">
                                <div class="dateTime fl"><?php echo $mg['flag']==0 ? date("Y-m-d h:i:s",$mg['addtime']):date("Y-m-d h:i:s",$mg['read_time']); ?></div>
                                <div class="contact fr"><?php echo $mine['username']; ?></div>
                            </div>
                            <div class="dialogBox1-info">
                                <?php echo $mg['content']; ?>
                            </div>                           
                            <i class="trigon"></i>
                        </div>
                        <div class="contactPic fr mr15">
                            <?php
                                $imgright=  empty($mine['image']) ? '/common/images/headPortrait.png' : Yii::app()->params['static'].$mine['image'];
                                echo CHtml::image($imgright,$mine['username'],array('width'=>'50px','height'=>'50px')); 
                            ?>
                        </div>
                    </div>
                    <?php }else{ ?>
                    <div class="dialogBoxWrap clearfix">
                        <div class="dialogBox1 dialogBox2 fr mr15 mb20">
                            <div class="dialogBox1-top clearfix mb5">
                                <div class="contact fl"><?php echo $friend['username']; ?></div>
                                <div class="dateTime fr"><?php echo $mg['flag']==0 ? date("Y-m-d h:i:s",$mg['addtime']):date("Y-m-d h:i:s",$mg['read_time']); ?></div>
                            </div>
                            <div class="dialogBox1-info">
                                <?php echo $mg['content']; ?>
                            </div>                               
                            <i class="trigon"></i>
                        </div>
                         <div class="contactPic fl">
                             <?php
                                $imgright2=  empty($friend['image']) ? '/common/images/headPortrait.png' : Yii::app()->params['static'].$friend['image'];
                                echo CHtml::image($imgright2,$friend['username'],array('width'=>'50px','height'=>'50px')); 
                            ?>
                         </div>
                    </div>
                    <?php } ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <!--写信息-->
            <form id="commentWrap" class="commentWrap">
                <div class="comment">
                   <div class="com_form">
                       <textarea placeholder="写消息..." name="saytext" id="saytext" class="input"></textarea>
                        <p>
<!--                            <span class="addFile"><i></i>&nbsp;添加文件</span>
                            <span class="addPic"><i></i>&nbsp;添加照片</span>
                            <span class="sendEnter">按Enter发送 <input type="checkbox" name="checkbox" id="checkbox"/></span>-->
                            <input type="button" value="发送" class="sub_btn" onclick="sendM(this)" dialogId="<?php echo $dialog_id; ?>" sid="<?php echo $mine['id']; ?>" rid="<?php echo $friend['id']; ?>" mid="<?php echo $mid; ?>">                      
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