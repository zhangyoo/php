<div class="sectionTable-A1 mb10">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="10%"><input type="checkbox" onclick="checkall()"></th>
                <th class="col-2" width="40%">节点描述</th>
                <th class="col-3" width="50%">节点名称</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($child) && !empty($child)): ?>
            <?php foreach ($child as $model):?>
            <tr class="">
                <td class="col-1">
                    <label class="checkbox">
                        <input type="checkbox" name="id[]" value="<?php if(!empty($type) && intval($type)>0){
                            echo $model['name'];
                        }else{echo $model->name;}?>" />
                    </label>
                </td>
                <td class="col-2"><?php if(!empty($type) && intval($type)>0){echo $model['description'];
                }else{echo $model->description;}?></td>
                <td class="col-3"><?php if(!empty($type) && intval($type)>0){
                    echo $model['name'];}else{echo $model->name;}?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="sectionFoot-B1">
    <div class="sectionFloat-A1">
        <ul class="clear">
            <li class="mr10 ml8">
                <label class="checkbox">
                  <input type="checkbox" onclick="checkall()"> 全选
                </label>
            </li>
            <li class="sectionForm-A1 sectionForm-A1-2">                                    
                <span class="button">
                    <?php if(isset($type) && $type>0){ ?>
                    <input class="btn btn-large btn-primary" id="doset" onclick="setCancel(this)" parent="<?php echo $itemname?>" type="button" value="批量设置">
                    <?php }else{ ?>
                    <input class="btn btn-large btn-primary" id="cancel" onclick="setCancel(this)" parent="<?php echo $itemname?>" type="button" value="批量取消">
                    <?php } ?>
                </span>
            </li>
        </ul>
    </div>
</div>