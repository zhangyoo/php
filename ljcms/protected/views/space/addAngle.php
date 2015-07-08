<div class="angle_image">
    <i class="del_angle_icon"> </i>
    <p class="angleVal_space">
        视角*：
        <?php echo CHtml::textField('angle[]','',array('size'=>'25','class'=>'')); ?>
    </p>
    <div class="anglePics">
        空间空模图*:<br>
        <?php echo $form->FileField($model,'pics[]',array('class'=>'')); ?>
    </div>
    <div class="anglePics">
        空间效果展示图*:<br>
        <?php echo $form->FileField($model,'showpics[]',array('class'=>'')); ?>
    </div>
    <div class="anglePics">
        空间平面布局图*:<br>
        <?php echo $form->FileField($model,'floorplan[]',array('class'=>'')); ?>
    </div>
</div>