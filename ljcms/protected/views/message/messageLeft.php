                        <div class="searchMessage mt25">
                        <div class="result"><b>搜索信息</b><br>显示名称为<?php echo $keyword; ?>的结果</div>
                        <?php if(empty($model)): ?>
                        <p class="noResult">没有名为<?php echo $keyword; ?>的人或对话</p>    
                        <?php endif; ?>
                        </div>
                        <?php if(!empty($model)): ?>
                        <?php foreach ($model as $k=>$m): ?>
                        <div class="list_single_box <?php echo $k+1==count($model) ? 'noBorder':''; ?>" onclick="selMessage(this)">
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