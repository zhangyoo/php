<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/message.css" rel="stylesheet" type="text/css" />
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
        <span class="R top_rig_add"><a href="/message/create">+&nbsp;新消息</a></span>
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
        <div class="message_default_index">
            <p><font color="#AAAAAA" style="font-size: 14px">未选择对话</font></p>
            <p><a href="/message/create">新信息</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);">显示未读信息</a></p>
        </div>
    </div>
</div>