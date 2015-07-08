    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="8%">编号</th>
                <th class="col-2" width="8%">品牌型号</th>
                <th class="col-3" width="14%">素材名称</th>
                <th class="col-5" width="15%">素材标签</th>
                <th class="col-6" width="15%">模型情况</th>
                <th class="col-7" width="9%">图片情况</th>
                <th class="col-7" width="9%">是否绑定商品</th>
                <th class="col-7" width="9%">是否绑定模型</th>
                <th class="col-7" width="5%">状态</th>
                <th class="col-8">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($ul) && !empty($ul)): ?>
            <?php foreach ($ul as $info):?>
            <tr id="tr_<?php echo $info['id']; ?>">
                <td class="col-1">
                    <?php echo $info['number']; ?>
                </td>
                <td class="col-2">
                    <?php echo $info['brandName']; ?><br>
                    <a href="javascript:void(0);" title="<?php echo $info['item']; ?>"><?php echo mb_substr(strip_tags($info['item']),0,10,'utf8'); ?></a>
                </td>
                <td class="col-3">
                    <div class="L" style="width:40%;">
                        <?php echo CHtml::image(Yii::app()->params['static'].$info['image'],$info['title'],array('width'=>'80')); ?>
                    </div>
                    <div class="L ml5 list_more_detail" style="width:56%;">
                        <p><font title="<?php echo $info['title']; ?>"><?php echo mb_substr(strip_tags($info['title']),0,10,'utf8'); ?></font></p>
                        <p><?php echo Yii::app()->params['productType'][$info['type']]; ?></p>
                    </div>
                </td>
                <td class="col-5">
                    <p>风格：<?php echo $info['style']; ?></p>
                    <p>
                        颜色：
                        <?php if(!empty($info['color'])): ?>
                        <?php foreach ($info['color'] as $kco=>$co): ?>
                        <?php echo CHtml::image(Yii::app()->request->BaseUrl.$co,$kco,array('width'=>'15','title'=>$kco))." ".$colorsSN[$kco.'|'.$co]; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </p>
                    <p>尺寸: 长<?php echo $info['length'];?>×宽<?php echo $info['width'];?>×高<?php echo $info['height'];?></p>
                    <p>材质：<?php echo $info['material']; ?></p>
                    <p>标签：<?php echo $info['label']; ?></p>
                </td>
                <td class="col-6 one_select">
                    <?php if($order['type'] == 3): ?>
                    无
                    <?php else: ?>
                    <?php $mCon = json_decode($info['mold_condition'],true); ?>
                    <?php foreach (Yii::app()->params['moldType'] as $kic=>$ic): ?>
                    <span class="mr10 moldSpanStyle">
                        <a class="<?php echo !empty($mCon) && isset($mCon[$kic]) ? "upMoldColor":""; ?>" <?php echo !empty($mCon)&&isset($mCon[$kic])&&intval($mCon[$kic])>0 ? "href='/mold/update/id/".$mCon[$kic]."' target='_blank'" : "href='javascript:void(0);'"; ?>><?php echo $ic; ?></a>
                    </span>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </td>
                <td class="col-7 one_select">
                    <?php $imgCon = json_decode($info['img_condition'],true); ?>
                    <?php foreach (Yii::app()->params['imgCondition'] as $kimg=>$vimg): ?>
                        <?php if($order['type'] == 3): ?>
                        <?php if($kimg != 5): ?>
                        <span>
                            <?php echo $vimg; ?>
                            ( <?php echo !empty($imgCon)&&isset($imgCon[$kimg])&&intval($imgCon[$kimg])>0 ? intval($imgCon[$kimg]) : "0"; ?> )
                        </span><br>
                        <?php endif; ?>
                        <?php else: ?>
                        <span>
                            <?php echo $vimg; ?>
                            ( <?php echo !empty($imgCon)&&isset($imgCon[$kimg])&&intval($imgCon[$kimg])>0 ? intval($imgCon[$kimg]) : "0"; ?> )
                        </span><br>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </td>
                <td class="col-7 one_select"><?php echo !empty($info['product_id']) ? "已绑定" :"未绑定"; ?></td>
                <td class="col-7 one_select"><?php if($order['type'] != 3){echo !empty($info['mold']) ? "已绑定" :"未绑定";}else{ echo '无';} ?></td>
                <td class="col-7 one_select"><?php echo Yii::app()->params['infoStatus'][$info['status']]; ?></td>
                <td class="col-8">
                    [<a href="/info/update/id/<?php echo $info['id']; ?>/oid/<?php 
                    echo isset($oid) ? $oid:''; ?><?php echo isset($_GET['unbind']) ? '/unbind':''; ?><?php echo isset($_GET['bind']) ? '/bind':''; ?>"><?php echo !empty(Yii::app()->session['update']) ? "编辑":"查看"; ?></a>]
                    <?php if(!empty(Yii::app()->session['delete'])): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $info['id'];?>" oid="<?php echo $oid; ?>" onclick="delInfo(this)">删除</a>]
                    <?php endif; ?><br>
                    <?php if(!empty(Yii::app()->session['bindProduct'])): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $info['id']; ?>" type="product" onclick="showBindPM(this)">绑定商品</a>]<br>
                    <?php endif; ?>
                    <?php if(!empty(Yii::app()->session['upMold']) && $order['type'] !=3): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $info['id']; ?>" type="mold" onclick="showBindPM(this)">绑定模型</a>]<br>
                    <?php endif; ?>
                    <?php if($order['type'] ==3): ?>
                    [<a href="/info/texture/id/<?php echo $info['id']; ?>">修改物品贴图</a>]
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>