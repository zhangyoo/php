<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/info.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/order.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/res.js"></script>
<div class="sectionTitle-A mb10">
    <h2>素材列表</h2>
</div>
<?php $form = $this->beginWidget('CActiveForm', array(
            'id'=>'infoForm',
            'method'=>'get',
            'action'=>'/order/info/oid/'.$model['id'].$bind,
            'htmlOptions'=>array('enctype'=>'multipart/form-data'),
  )); ?>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        <a href="/order/index" class="btn btn-primary">订单列表</a>
        <?php if(in_array($model['type'], array(0,3))): ?>
        <?php if(!empty(Yii::app()->session['update'])): ?>
        <a href="/info/create/oid/<?php echo $model['id']; ?>" class="btn btn-primary">添加素材信息</a>
        <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <ul class="clear">
            <li>
                <label>素材标题/型号/编号</label>
                <input type="text" value="<?php echo !empty($name) ? $name :''; ?>" name="name" class="text">
            </li>
            <li class="button">                                    
                <input class="btn btn-large btn-primary" type="submit" value="查询">
            </li>
        </ul>
    </div>
</div>
<?php if(!empty($model)): ?>
<div class="sectionTable-A1 mb10">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="10%">订单编号</th>
                <th class="col-1" width="10%">订单类型</th>
                <th class="col-1" width="10%">订单标题</th>
                <th class="col-2" width="23%">订单内容</th>
                <th class="col-3" width="17%">创建时间</th>
                <th class="col-4" width="14%">更新时间</th>
                <th class="col-4" width="6%">订单状态</th>
                <th class="col-5">操作</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col-2"><?php echo $model['number']; ?></td>
                <td class="col-1">
                    <?php echo Yii::app()->params['orderType'][$model['type']]; ?>
                </td>
                <td class="col-2"><?php echo $model['title']; ?></td>
                <td class="col-3"><?php echo mb_substr(strip_tags($model['content']),0,30,'utf8'); ?></td>
                <td class="col-4">
                    起始：<?php echo date("Y-m-d H:i:s", $model["create_time"]);?><br>
                    预结束：<?php echo date("Y-m-d H:i:s", $model["end_time"]);?>
                </td>
                <td class="col-5"><?php echo !empty($model["update_time"])?date("Y-m-d H:i:s",$model["update_time"]):'';?></td>
                <td class="col-6">
                    <?php echo Yii::app()->params['orderStatus'][$model['status']]; ?>
                </td>
                <td class="col-7">
                    [<a href="/order/create/id/<?php echo $model['id']; ?>"><?php echo !empty(Yii::app()->session['update']) ? "编辑":"查看"; ?></a>]
                    <?php if(!empty(Yii::app()->session['delete'])): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $model['id'];?>" onclick="delOrder(this)">删除</a>]
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php endif; ?>
<div class="sectionTable-A1 mb10">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="9%">编号</th>
                <th class="col-2" width="8%">品牌型号</th>
                <th class="col-3" width="14%">素材名称</th>
                <th class="col-5" width="15%">素材标签</th>
                <th class="col-6" width="15%">模型情况</th>
                <th class="col-7" width="9%">图片情况</th>
                <th class="col-7" width="8%">是否绑定商品</th>
                <th class="col-7" width="8%">是否绑定模型</th>
                <th class="col-7" width="5%">状态</th>
                <th class="col-8">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($ul) && !empty($ul)): ?>
            <?php foreach ($ul as $info):?>
            <tr id="tr_<?php echo $info['id']; ?>">
                <td class="col-1">
                    <?php if(!in_array($model['type'], array(0,3))): ?>
                    <input type="checkbox" name="Info[]" value="<?php echo $info['id']; ?>" />
                    <?php endif; ?>
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
                    <?php if($model['type'] == 3): ?>
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
                        <?php if($model['type'] == 3): ?>
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
                <td class="col-7 one_select"><?php if($model['type'] != 3){echo !empty($info['mold']) ? "已绑定" :"未绑定";}else{ echo '无';} ?></td>
                <td class="col-7 one_select"><?php echo Yii::app()->params['infoStatus'][$info['status']]; ?></td>
                <td class="col-8">
                    [<a href="/info/update/id/<?php echo $info['id']; ?>/oid/<?php 
                    echo isset($_GET['oid']) ? $_GET['oid']:''; ?><?php echo isset($_GET['unbind']) ? '/unbind/':''; ?><?php echo isset($_GET['bind']) ? '/bind/':''; ?><?php echo isset($_GET['page']) ? '/page/'.$_GET['page']:''; ?>"><?php echo !empty(Yii::app()->session['update']) ? "编辑":"查看"; ?></a>]
                    <?php if(!empty(Yii::app()->session['delete'])): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $info['id'];?>" oid="<?php echo $_GET['oid']; ?>" onclick="delInfo(this)">删除</a>]
                    <?php endif; ?><br>
                    <?php if(!empty(Yii::app()->session['bindProduct'])): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $info['id']; ?>" type="product" onclick="showBindPM(this)">绑定商品</a>]<br>
                    <?php endif; ?>
                    <?php if(!empty(Yii::app()->session['upMold']) && $model['type'] !=3): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $info['id']; ?>" type="mold" onclick="showBindPM(this)">绑定模型</a>]<br>
                    <?php endif; ?>
                    <?php if($model['type'] ==3): ?>
                    [<a href="/info/texture/id/<?php echo $info['id']; ?>">查看贴图</a>]
                    <?php endif; ?>
                    <?php if($model['type'] !=3): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $info['id']; ?>" onclick="disPics(this)">查看360度图片</a>]
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            <tr>
                <?php if(!in_array($model['type'], array(0,3))): ?>
                <td align="center">
                    <input type="checkbox" class="all_select" style="margin:0" /> 全选
                </td>
                <?php endif; ?>
                <td colspan="<?php echo !in_array($model['type'], array(0,3)) ? '9' : '10'; ?>" align="right"> 
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
                            <div class="goToPages">
                                跳转到：
                                <input type="text" name="pageNum" class="pageInput" /> 页&nbsp;&nbsp;
                                <input type="button" id="loadPageNum" url="<?php echo Yii::app()->request->BaseUrl."/order/info/oid/".$_GET['oid'];?><?php echo isset($_GET['unbind'])?"/unbind/":""; ?><?php echo isset($_GET['bind'])?"/bind/":""; ?>/page/" onclick="transPages(this)" class="clickPageIcon" value="确定"/>&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php if(!in_array($model['type'], array(0,3))): ?>
<div class="commonBtnArea" style="width: 1174px;">
    <?php if(isset($_GET['unbind']) && empty($_GET['unbind'])){ ?>
        <?php echo CHtml::submitButton('解除关联素材',array('class'=>'btn submit'));?>
    <?php }else{ ?>
        <?php echo CHtml::submitButton('关联素材',array('class'=>'btn submit'));?>
    <?php }?>
</div>
<?php endif; ?>
<?php $this->endWidget(); ?>
<div class="pop_dialog_top" style="display: none;">
    <div class="dialog_two_top">
        <b id="chooseType" chooseValue="product">绑定商品(只能绑定一个)</b>
        <a href="javascript:void(0);" onclick="closeDia()"><img class="icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/cut_icon.png" alt="关闭弹框" title="关闭弹框"/></a>
    </div>
    <div class="dialog_content_top">
        <div class="info_psearch">
            <select class="inputLen" id="selectBindType" style="margin:0">
                <option value="unbind">已绑定</option>
                <option value="bind">未绑定</option>
            </select>
            &nbsp;
            <input type="text" class="search_product_name" placeholder="商品名称(或商品货号)" name="searchName" />
            <input type="button" class="info_psearch_button"  onclick="ResSearchProduct(this)" value="搜索" />
        </div>
        <ul class="info_img_ul psBox">
            
        </ul>
        <div class="dialog_save savePfix">
            <input type="button" value="解除绑定" id="saveSecDia" rel="" bind="unbind" onclick="ResSProduct(this)"/>
        </div>
    </div>
</div>
<div class="pop_dialog_top_two" style="display: none;">
    <div class="dialog_two_top">
        <b id="chooseType" chooseValue="product">360度图片展示</b>
        <a href="javascript:void(0);" onclick="closeDia_two()"><img class="icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/cut_icon.png" alt="关闭弹框" title="关闭弹框"/></a>
    </div>
    <div class="dialog_content_top">
        <ul class="top_two_ul">
            
        </ul>
    </div>
</div>
<script type="text/javascript">
    var i=0;
    $(".all_select").click(function(){
        if(i==0){
        i=1;
        $(".table tr td input[type=checkbox][name='Info[]']").attr("checked",true);
    }else if(i==1){
        i=0;
        $(".table tr td input[type=checkbox][name='Info[]']").attr("checked",false);
    }
    }); 
</script>