<?php
class Mail
{
	function mail_php($to, $subject, $msg) {
		require("phpmailer.php");//下载的文件必须放在该文件所在目录
		$mail = new PHPMailer (); //建立邮件发送类
		$mail->IsSMTP (); // 使用SMTP方式发送
		$mail->IsHTML(true);
		$mail->CharSet="UTF-8";
		
//		$mail->Host = "smtp.exmail.qq.com"; // 您的企业邮局域名

		$mail->Host="smtp.gezlife.com";
		$mail->SMTPAuth = true; // 启用SMTP验证功能
//		$mail->Username = "gezlife@foxmail.com"; // 邮局用户名(请填写完整的email地址)
//		$mail->Password = "gezleju2013"; // 邮局密码
//		$mail->From = "gezlife@foxmail.com"; //邮件发送者email地址
		
		$mail->Username = "leju@gezlife.com"; // 邮局用户名(请填写完整的email地址)
		$mail->Password = "leju2014"; // 邮局密码
		$mail->From = "leju@gezlife.com"; //邮件发送者email地址
		$mail->FromName = "Gezlife";
		$mail->AddAddress ( "$to", "" ); //收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
		//$mail->AddReplyTo("", "");
		//$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
		$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式
		$mail->Subject = $subject; //邮件标题
		$mail->Body = $msg; //邮件内容
		$mail->AltBody = ""; //附加信息，可以省略
		if (! $mail->Send ()) {
//			echo "邮件发送失败. <p>";
//			echo "错误原因: " . $mail->ErrorInfo;
//			exit ();
			
			return $mail->ErrorInfo;
		}
		//echo "邮件发送成功";
	
		return true;
	}
}
?>