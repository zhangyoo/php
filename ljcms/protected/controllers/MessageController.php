<?php

/* 
 * 站内信模块
 * 此模块与社区公用两张表 cms_sitemail，cms_sitemail_member
 * @author:zhangyong   2014/09/28
 */
class MessageController extends CmsController{
    
    /*
     * 消息列表
     * 创建消息的时候必须以个人为单位创建，即每人一条对话
     * 对于已存在的收件人，新的消息会继承与收件人最新的消息，而不是单纯地重新建一个消息（如果收件人的消息被删除，则保留收件人，但是消息则是重新创建）
     * 而对于新的收件人，发信息的时候就是新建一条消息，之后的对话都是从这条消息一直延续下去
     * 删除消息的时候只是删除单个对话，而且只能删除作为发件人发送的消息
     * author zhangyong
     */
    public function actionIndex(){
        $connect=Yii::app()->db;
        $uid=Yii::app()->user->getId();
        if(empty($uid))
            throw new CHttpException(404,'您未登录，请先登录！');
        $con='';
        $reids=array();
        //好友最新消息
        $med=  Sitemail::model()->_getMessage($uid,$connect);
        $this->render('index',array('med'=>$med,'uid'=>$uid));
    }
    
    /*
     * 创建站内信消息
     * author zhangyong
     */
    public function actionCreate(){
        $data=array('status'=>false,'info'=>'发送消息失败！');
        $params=$_POST;
        $connect=Yii::app()->db;
        $uid=Yii::app()->user->getId();
        if(empty($uid))
            throw new CHttpException(404,'您未登录，请先登录！');
        $con='';
        $reids=array();//常用收件人
        $users=array();//未使用的收件人
        //好友最新消息
        $med=Sitemail::model()->_getMessage($uid,$connect);
        $recievers=Sitemail::model()->_getRecievers($uid,$connect);
        if(!empty($recievers))
            $reids=CHtml::listData($recievers,'email','username');
        if(isset($params['rids']) && !empty($params['rids']) && isset($params['content'])){
            $rids=array();
            if(!empty($params['rids'])){
                foreach ($params['rids'] as $rd){
                    if(!empty($rd)){
                        $uData=  User::model()->find("is_del=0 and email like '%".trim($rd)."%'");
                        if(empty($uData)){
                            echo CJSON::encode($data=array('status'=>false,'info'=>"邮箱号为".$rd."的用户不存在或已被删除！"));
                            exit;
                        }else{
                            $rids[]=$uData['id'];
                        }
                    }
                }
            }
            $content=$params['content'];
            $newIds=array();
            foreach ($rids as $rid){
                $sql="select ss.id,ss.uid as sid,ss.main_id,ssm.uid as rid from cms_sitemail as ss 
                    left join cms_sitemail_member as ssm on ss.id=ssm.mail_id 
                    where ((ss.uid=".$uid." and ssm.uid=".$rid.") or (ss.uid=".$rid." and ssm.uid=".$uid.")) and ss.is_del=0 and ssm.is_del=0 order by ss.addtime desc limit 1";
                $existMe=$connect->createCommand($sql)->queryAll();
                $sitemail=new Sitemail();
                $sitemailMember=new SitemailMember();
                if(!empty($existMe)){//之前已发送过消息的人，并且消息没被删除
                    $sitemail->dialog_id=$existMe[0]['id'];
                    if(empty($existMe[0]['main_id'])){
                        $sitemail->main_id=$existMe[0]['id'];
                    }else{
                        $sitemail->main_id=$existMe[0]['main_id'];
                    }
                    if($uid==1)
                        $sitemail->type=1;//超管群发
                    $sitemail->uid=$uid;
                    $sitemail->cmt_id=0;
                    $sitemail->cmt_name='0';
                    $sitemail->title='0';
                    $sitemail->content=$content;
                    $sitemail->addtime=time();
                    if($sitemail->save()){
                        $sitemailMember->uid=$rid;
                        $sitemailMember->mail_id=$sitemail->id;
                        if($sitemailMember->save()){
                            $recId = User::model()->findByPk($rid);
                            if(isset($recId['email']) && !empty($recId['email'])){
                                $email = new Mail();
                                $subject = '来自乐居网络科技邮件！';
                                $email->mail_php($recId['email'], $subject, $content);
                            }
                        }
                    }
                }else{
                    $newIds[]=$rid;//记录第一次发送的联系人
                }
            }
            //处理第一次发送消息的人
            if(!empty($newIds)){
                $sitemail=new Sitemail();
                if($uid==1)
                    $sitemail->type=1;//超管群发
                $sitemail->uid=$uid;
                $sitemail->cmt_id=0;
                $sitemail->cmt_name='0';
                $sitemail->title='0';
                $sitemail->content=$content;
                $sitemail->addtime=time();
                if($sitemail->save()){
                    foreach ($newIds as $newid){
                        $sitemailMember=new SitemailMember();
                        $sitemailMember->uid=$newid;
                        $sitemailMember->mail_id=$sitemail->id;
                        if($sitemailMember->save()){
                            $recId = User::model()->findByPk($newid);
                            if(isset($recId['email']) && !empty($recId['email'])){
                                $email = new Mail();
                                $subject = '来自乐居网络科技邮件！';
                                $email->mail_php($recId['email'], $subject, $content);
                            }
                        }
                    }
                    
                }
                
            }
            $data=array('status'=>true,'info'=>'发送消息成功！');
            echo CJSON::encode($data);
            exit;
        }
        $this->render('create',array('med'=>$med,'reids'=>$reids,'uid'=>$uid));
    }
    
    /*
     * 查看消息
     * author zhangyong
     */
    public function actionMessageView($mid,$fid){
        if(!isset($mid) || empty($mid))
            throw new CHttpException(404,'该消息不存在或者已被删除！');
        $connect=Yii::app()->db;
        $uid=Yii::app()->user->getId();
        if(empty($uid))
            throw new CHttpException(404,'您未登录，请先登录！');
        $mine=  User::model()->find(array('select'=>'id,username,image','condition'=>'id='.$uid));
        $friend= User::model()->find(array('select'=>'id,username,image','condition'=>'id='.$fid));
        $mainSiteMail=  Sitemail::model()->find(array('select'=>'addtime','condition'=>'id='.$mid));
        $reids=array();//常用收件人
        //好友最新消息
        $med=Sitemail::model()->_getMessage($uid,$connect);
        $recievers=Sitemail::model()->_getRecievers($uid,$connect);
        if(!empty($recievers))
            $reids=CHtml::listData($recievers,'id','username');
        //获取该条信息(未读信息或最新的几条信息)
        $sql="select ss.*,ssm.read_time,ssm.flag from cms_sitemail as ss "
                . "left join cms_sitemail_member as ssm on ss.id=ssm.mail_id "
                . "where (ss.main_id=".$mid." or ss.id=".$mid.") and ((ss.uid=".$uid." and ssm.uid=".$fid.") or (ss.uid=".$fid." and ssm.uid=".$uid.")) and ss.is_del=0 and ssm.is_del=0 order by ss.addtime asc";
        $megs=$connect->createCommand($sql)->queryAll();
        $dialog_id=$megs[count($megs)-1]['id'];//最新消息的消息id
        //标记此消息已经读过
        $sql="update cms_sitemail_member set flag=1,read_time=".time()." "
                . "where uid=".$uid." and flag=0 and is_del=0 and mail_id in (select id from cms_sitemail where (main_id=".$mid." or id=".$mid.") and uid=".$fid." and is_del=0)";
        $connect->createCommand($sql)->execute();
        $this->render('messageView',array(
            'mid'=>$mid,'med'=>$med,'megs'=>$megs,'mainSiteMail'=>$mainSiteMail,
            'mine'=>$mine,'friend'=>$friend,'dialog_id'=>$dialog_id,'uid'=>$uid));
    }
    
    /*
     * 查看消息界面发送信息
     * author zhangyong
     */
    public function actionViewCreateMessage(){
        $params=$_POST;
        if(isset($params['nmid']) && isset($params['dialogid'])){
            $nsid=$params['nsid'];
            $nrid=$params['nrid'];
            $nmid=$params['nmid'];
            $dialogid=$params['dialogid'];
            $temp=$params['temp'];
            $mine=  User::model()->find(array('select'=>'id,username,image','condition'=>'id='.$nsid));
            $model=new Sitemail();
            $sitemailMember=new SitemailMember();
            $model->uid=$nsid;
            $model->dialog_id=$dialogid;
            if($nsid==1)
                    $model->type=1;//超管群发
            $model->cmt_id=0;
            $model->cmt_name='0';
            $model->title='0';
            $model->content=$temp;
            $model->addtime=time();
            $model->main_id=$nmid;
            if($model->save()){
                $sitemailMember->uid=$nrid;
                $sitemailMember->mail_id=$model->id;
                if($sitemailMember->save()){
                    $recId = User::model()->findByPk($nrid);
                    if(isset($recId['email']) && !empty($recId['email'])){
                        $email = new Mail();
                        $subject = '来自乐居网络科技邮件！';
                        $email->mail_php($recId['email'], $subject, $temp);
                    }
                }
            }
            if(!empty($model->id) && !empty($sitemailMember->id))
                $this->renderPartial('loadMessage',array('model'=>$model,'sitemailMember'=>$sitemailMember,'mine'=>$mine));
        }
    }
    
    /*
     * 搜索消息消息
     * author zhangyong
     */
    public function actionSearchMessage(){
        $connect=Yii::app()->db;
        $uid=Yii::app()->user->getId();
        if(isset($_POST['keyword']) && !empty($_POST['keyword'])){
            $keyword=$_POST['keyword'];
            $data=Sitemail::model()->_getMessage($uid, $connect,array('like'=>$keyword));
            $this->renderPartial('messageLeft',array('model'=>$data,'keyword'=>$keyword));
        }
    }
    
    /*
     * 删除消息
     * @to do 考虑只能删除单条对话，不能删除整条信息,待后期确定再开发
     * author zhangyong
     */
    public function actionDelMessage(){
        
    }
    
    /*
     * 标记邮件为已读
     * author zhangyong
     */
    public function actionReadMail()
    {
        $data=array('status'=>false,'info'=>'操作失败！');
        if(isset($_POST['mid']) && isset($_POST['uid'])){
            $mid=$_POST['mid'];
            $uid=$_POST['uid'];
            $mail=  SitemailMember::model()->find('is_del=0 and uid='.$uid.' and mail_id='.$mid);
            if(!empty($mail)){
                $mail->flag=1;
                $mail->read_time=time();
                $mail->save();
                $data=array('status'=>true,'info'=>'操作成功！');
            }
        }
        echo CJSON::encode($data);
    }
    
}
