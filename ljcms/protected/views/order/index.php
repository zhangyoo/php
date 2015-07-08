<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/order.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/WdatePicker.js"></script>
<div class="sectionTitle-A mb10">
    <h2>订单列表</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        <?php if(!empty(Yii::app()->session['update'])): ?>
        <a href="/order/create" class="btn btn-primary">创建订单</a>
        <?php endif; ?>
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <?php $form = $this->beginWidget('CActiveForm', array(
                    'id'=>'user-form',
                    'method'=>'get',
                    'action'=>'/order/index',
                    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
          )); ?>
            <ul class="clear">
                <li>
                    <label> </label>
                    <?php
                        echo CHtml::dropDownList('brandhall',$seachData['brandhall'],$brandhalls,
                                array('separator'=>'&nbsp;','empty'=>'品牌馆','style'=>'width:auto'));
                    ?>
                </li>
                <li>
                    <label> </label>
                    <?php
                        echo CHtml::dropDownList('type',$seachData['type'],Yii::app()->params['orderType'],
                                array('separator'=>'&nbsp;','empty'=>'订单类型','style'=>'width:auto'));
                    ?>
                </li>
                <li>
                    <label> </label>
                    <?php
                        echo CHtml::dropDownList('status',$seachData['status'],Yii::app()->params['orderStatus'],
                                array('separator'=>'&nbsp;','empty'=>'订单状态','style'=>'width:auto'));
                    ?>
                </li>
                <li>
                    <label> </label>
                    <input type="text" value="<?php echo !empty($seachData['name']) ? $seachData['name'] :''; ?>" name="name" class="text" placeholder="订单名称/编号">
                </li>
                <li>
                    <label> </label>
                    <input type="text" value="<?php echo !empty($seachData['infoName']) ? $seachData['infoName'] :''; ?>" name="infoName" class="text" placeholder=" 素材编号">
                </li>
                <li>
                    <input class="input" type="text" placeholder=" 起始时间" name="timeStart" value="<?php echo !empty($seachData['timeStart'])?$seachData['timeStart']:''; ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" size="20" style="margin-right: 20px;width: 100px" />
                </li>
                <li>
                    <input class="input" type="text" placeholder=" 截止时间" name="timeEnd" value="<?php echo !empty($seachData['timeEnd'])?$seachData['timeEnd']:''; ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" size="20" style="margin-right: 20px;width: 100px" />
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
            <?php if(isset($ul) && !empty($ul)): ?>
            <?php foreach ($ul as $model):?>
            <tr id="tr_<?php echo $model['id']; ?>">
                <td class="col-2"><?php echo $model['number']; ?></td>
                <td class="col-1"><?php echo Yii::app()->params['orderType'][$model['type']]; ?></td>
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
                    <?php endif; ?><br>
                    <?php if(in_array($model['type'], array(0,3))): ?>
                    [<a href="/order/info/oid/<?php echo $model['id']; ?>">查看详情</a>]
                    <?php else: ?>
                    [<a href="/order/info/oid/<?php echo $model['id']; ?>/bind/">关联素材</a>]<br>
                    [<a href="/order/info/oid/<?php echo $model['id']; ?>/unbind/">解除关联素材</a>]
                    <?php endif; ?>
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
    </div>
</div>