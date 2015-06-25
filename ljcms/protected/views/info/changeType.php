        <label class="sectionLabel-A1">360度图片：</label>
        <div class="clear">
            <input id="J_selectImage" class="btn btn-mini L mr10" type="button" value="上传360度图片"/>
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
                    <span><textarea type="text" style="text-align: center;" name="summary[<?php echo $kp; ?>]" cols="27" rows="1"><?php echo $fp['sort_num']; ?></textarea></span>
                </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
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
                                        html+= '<span><textarea type="text" style="text-align: center;" name="summary['+num+']" cols="27" rows="1"></textarea></span>'
                                        html+= '</li>'
                                    div.append(html);
                                });
                                editor.hideDialog();
                            }
                        });
                    });
            });
        </script>