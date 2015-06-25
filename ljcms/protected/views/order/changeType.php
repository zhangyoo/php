<?php if(in_array($type, array(0,3))){ ?>
        <li class="mb5">
            <label class="sectionLabel-A1">客户名称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4 one_select customer_select">
                <?php if(!empty($brandhalls)): ?>
                <?php foreach ($brandhalls as $kbh=>$brandhall): ?>
                <span>
                    <input type="checkbox" name="brandhall[]" value="<?php echo $kbh; ?>" <?php echo in_array($kbh, $default['ob']) ? "checked" : ""; ?> />
                    <label><?php echo $brandhall; ?></label>
                </span>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </li>

<?php }elseif($type == 1){ ?>
        <li class="mb5">
            <label class="sectionLabel-A1">客户名称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4 one_select customer_select">
                <?php if(!empty($brandhalls)): ?>
                <?php foreach ($brandhalls as $kbh=>$brandhall): ?>
                <span>
                    <input type="checkbox" name="brandhall[]" value="<?php echo $kbh; ?>" <?php echo in_array($kbh, $default['ob']) ? "checked" : ""; ?> />
                    <label><?php echo $brandhall; ?></label>
                </span>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">绑定空间(可绑定多个,只能选择同一种功能空间)：</label>
            <div class="clear">
                <input type="button" value="绑定空间" onclick="showBind()" />
            </div>
            <div class="clear">
                <ul class="info_img_ul sBind">
                    <?php if(!empty($default['spaces'])): ?>
                    <?php foreach ($default['spaces'] as $pd): ?>
                    <li>
                        <?php echo CHtml::hiddenField('space[]',$pd['id'],array()); ?>
                        <div class="deleteImg">
                            <img width="22" height="23" onclick="delPSbind(this)" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/del_p.png"/>
                        </div>
                        <?php echo CHtml::image(Yii::app()->params['static'].$pd['image'],$pd['name'],array('style'=>'width:185px;height:185px')); ?>
                        <span class="bind_pro_spa"><?php echo $pd['name'];?><a class="R"><?php echo $pd['length'];?>*<?php echo $pd['width'];?></a></span>
                    </li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </li>
<?php }else{ ?>
        <li class="mb5">
            <label class="sectionLabel-A1">空间功能*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="one_select">
                <?php echo CHtml::radioButtonList('Order[room_category]',$default['room_category'],
                        Yii::app()->params['roomCategories'],array('separator'=>'&nbsp;')); ?>
                </div>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">客户名称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4 one_select customer_select">
                <?php if(!empty($brandhalls)): ?>
                <?php empty($default['ob']) && $default['ob'][0] = ''; ?>
                <?php foreach ($brandhalls as $kbh=>$brandhall): ?>
                <span>
                    <input type="radio" name="brandhall[]" value="<?php echo $kbh; ?>" <?php echo $default['ob'][0]==$kbh ? "checked" : ""; ?> />
                    <label><?php echo $brandhall; ?></label>
                </span>
                <?php endforeach; ?>
                <?php endif; ?>
                <?php
                ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">空间参考图片：</label>
            <div class="clear">
                <input id="J_selectImage" class="btn btn-mini L mr10" type="button" value="上传空间参考图片"/>
            </div>
            <div class="clear">
                <ul class="info_img_ul picBox">
                    <?php if(!empty($default['albums'])): ?>
                    <?php foreach ($default['albums'] as $kp=>$fp): ?>
                    <li num="<?php echo $kp; ?>">
                        <div class="deleteImg">
                            <img width="22" height="23" rel="<?php echo $fp['id']; ?>" onclick="delSImg(this)" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/del_p.png"/>
                        </div>
                        <?php echo CHtml::image(Yii::app()->params['static'].$fp['image'],$fp['summary'],array('style'=>'width:185px;height:185px','class'=>'cc')); ?>
                        <input type="hidden" name="image[<?php echo $kp; ?>]" value="<?php echo $fp['image']; ?>" />
                        <input type="hidden" name="ftId[<?php echo $kp; ?>]" value="<?php echo $fp['id']; ?>" />
                        <span><textarea type="text" name="summary[<?php echo $kp; ?>]" cols="27" rows="1"><?php echo $fp['summary']; ?></textarea></span>
                    </li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </li>
        <script type="text/javascript">
            var preUrl = "<?php echo Yii::app()->theme->BaseUrl;?>";
            var editor = KindEditor.editor({
                    allowFileManager : true,
        //            uploadJson : "/manage/design/uploadTempPic",暂时注销
            });
            //上传家具图片
            KindEditor('#J_selectImage').click(function() {
                if($(".picBox li").length > 0){
                    var picnum = parseInt($(".picBox li:last").attr("num")) + 1;
                }else{
                    var picnum = 0;
                }
                
                    editor.loadPlugin('multiimage', function() {
                        editor.plugin.multiImageDialog({
                            clickFn : function(urlList) {
                                var div = KindEditor('.picBox');
                                KindEditor.each(urlList, function(i, data) {
                                    var num=picnum+i;
                                    var html ='<li num="'+num+'">'
                                        html+='<div class="deleteImg">'
                                        html+='<img id="sc_ys" src="'+preUrl+'/images/del_p.png" width="22" url="/order/delTempImage" height="23" alt=""/>'
                                        html+= '</div>'
                                        html+='<img class="cc" src="'+data.url+'" style="width:185px;height:185px" alt=""/>'
                                        html+= '<input type="hidden" name="image['+num+']" value="'+data.url+'" />'
                                        html+='<input type="hidden" value="0" name="ftId['+num+']">'
                                        html+= '<span><textarea type="text" name="summary['+num+']" cols="27" rows="1"></textarea></span>'
                                        html+= '</li>'
                                    div.append(html);
                                });
                                editor.hideDialog();
                            }
                        });
                    });
            });
        </script>
<?php } ?>
