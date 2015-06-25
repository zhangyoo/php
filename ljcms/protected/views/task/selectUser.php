<?php if(!empty($taskUser)): ?>
    <?php 
        echo CHtml::dropDownList('taskUser',$rid,$taskUser,array(
            'id'=>'taskUser','empty'=>'指定给谁','style'=>'width:auto','taskId'=>$taskId,'onchange'=>'changeBindTask(this)',
            )
        );
    ?><br>
<?php endif; ?>
