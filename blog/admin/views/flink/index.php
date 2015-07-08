<?php 
use yii\widgets\LinkPager; 

$this->title = 'flink List';
$this->params['breadcrumbs'][] = $this->title;
?>

<form method="post">
    <div class="panel admin-panel">
    	<div class="panel-head"><strong>友情链接列表</strong></div>
        <div class="padding border-bottom">
            <input type="button" class="button button-small checkall" name="checkall" checkfor="id" value="全选" />
            <a href="<?php echo yii\helpers\Url::to('/flink/create'); ?>" class="button button-small border-green">创建友情链接</a>
            <input type="button" class="button button-small border-yellow" value="批量删除" />
            <input type="button" class="button button-small border-blue" value="回收站" />
        </div>
        <table class="table table-hover">
            <tr>
                <th width="45">选择</th>
                <th width="120">名称</th>
                <th width="150">logo图片</th>
                <th width="*">url链接</th>
                <th width="100">操作</th>
            </tr>
            <?php if(!empty($list)): ?>
            <?php foreach ($list as $key=>$val): ?>
            <tr>
                <td><input type="checkbox" name="id" value="<?php echo $val['id']; ?>" /></td>
                <td><?php echo $val['name']; ?></td>
                <td><?php echo $val['image']; ?></td>
                <td><?php echo $val['url']; ?></td>
                <td>
                    <a class="button border-blue button-little" href="<?php echo yii\helpers\Url::to(['/flink/update','id'=>$val['id']]); ?>">修改</a>
                    <a class="button border-yellow button-little" href="javascript:void(0);" onclick="{if(confirm('确认删除?')){return true;}return false;}">删除</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>没有符合条件的记录</tr>
            <?php endif; ?>
        </table>
        <div class="panel-foot text-center">
            <?= LinkPager::widget(['pagination' => $pages,'prevPageLabel'=>'上一页','nextPageLabel'=>'下一页']); ?>
        </div>
    </div>
</form>
    <br />
<p class="text-right text-gray">基于<a class="text-gray" target="_blank" href="http://www.pintuer.com">拼图前端框架</a>构建</p>