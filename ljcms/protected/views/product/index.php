<script type="text/javascript" src="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/WdatePicker.js"></script>
<div class="sectionTitle-A mb10">
    <h2>商品列表</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <?php $form = $this->beginWidget('CActiveForm', array(
                    'id'=>'user-form',
                    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
          )); ?>
            <ul class="clear">
                <li>
                    <label> </label>
                    <?php
                        echo CHtml::dropDownList('brandhall',$seachData['brandhall'],$brandhalls,
                                array('separator'=>'&nbsp;','empty'=>'品牌馆搜索','style'=>'width:auto'));
                    ?>
                </li>
                <li>
                    <label> </label>
                    <input type="text" value="<?php echo !empty($seachData['name']) ? $seachData['name'] :''; ?>" name="name" class="text" placeholder="商品名称或商品编号">
                </li>
                <li>
                    <label>起始时间:</label>
                    <input class="input" type="text" name="timeStart" value="<?php echo !empty($seachData['timeStart'])?$seachData['timeStart']:''; ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" size="20" style="margin-right: 20px;width: 100px" />
                </li>
                <li>
                    <label>截止时间:</label>
                    <input class="input" type="text" name="timeEnd" value="<?php echo !empty($seachData['timeEnd'])?$seachData['timeEnd']:''; ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" size="20" style="margin-right: 20px;width: 100px" />
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
                <th class="col-1" width="2%">&nbsp;</th>
                <th class="col-2" width="25%">商品信息</th>
                <th class="col-3" width="13%">市场/店铺/促销（￥）</th>
                <th class="col-4" width="5%">总库存</th>
                <th class="col-5" width="5%">总销量</th>
                <th class="col-6" width="5%">状态</th>
                <th class="col-7" width="9%">是否创建贴图</th>
                <th class="col-8" width="12%">发布时间</th>
                <th class="col-9" width="12%">更新时间</th>
                <th class="col-10">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($ul) && !empty($ul)): ?>
            <?php foreach ($ul as $model):?>
            <tr id="tr_<?php echo $model['product_id']; ?>">
                <td class="col-1"> </td>
                <td class="col-2">
                    <div class="L" style="width:30%;">
                        <?php echo CHtml::image(Yii::app()->params['static'].$model['product_img'],$model['product_name'],array('width'=>'80')); ?>
                    </div>
                    <div class="L ml5 list_more_detail" style="width:66%;">
                        <p><?php echo $model['product_name']; ?></p>
                        <p>品牌/系列：<?php echo $model['brandName']; ?></p>
                        <p>商品编号：<?php echo $model['product_sn']; ?></p>
                    </div>
                </td>
                <td class="col-3">
                    <?php echo $model['market_price']; ?><br>
                    <?php echo $model['shop_price']; ?><br>
                    <?php echo $model['promote_price']; ?>
                </td>
                <td class="col-4"><?php echo $model['product_number']; ?></td>
                <td class="col-5"><?php echo $model['sales_total']; ?></td>
                <td class="col-6"><?php $onSale = array('0'=>'下架','1'=>'上架'); echo $onSale[$model['is_on_sale']]; ?></td>
                <td class="col-7">
                    <?php echo !empty($model['texture_id']) && count(json_decode($model['texture_id'],true))>0 ? '是':'否'; ?>
                </td>
                <td class="col-8"><?php echo !empty($model["add_time"])?date("Y-m-d H:i:s",$model["add_time"]):'';?></td>
                <td class="col-9">
                    <?php echo !empty($model["update_time"])?date("Y-m-d H:i:s",$model["update_time"]):'';?>
                </td>
                <td class="col-10">
                    [<a href="javascript:void(0);" rel="<?php echo $model['product_id']; ?>">绑定模型</a>]
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
            'header'=> ' ',
            "maxButtonCount"=>5,
            'pages' => $pages,
            'firstPageLabel'=>'&lt;&lt; 首页',
            'prevPageLabel'=>'&lt; 前页',
            'nextPageLabel'=>'后页 &gt;',
            'lastPageLabel'=>'末页 &gt;&gt;',
        ))?>
        </div>
        <div class="goToPages">
            跳转到：
            <input type="text" name="pageNum" class="pageInput" /> 页&nbsp;&nbsp;
            <input type="button" id="loadPageNum" onclick="transPages(this)" url="<?php
            echo Yii::app()->request->BaseUrl;?>/product/index/page/" class="clickPageIcon" value="确定"/>&nbsp;&nbsp;
        </div>
    </div>
</div>