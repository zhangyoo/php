<?php 
$go=$_GET['go']; 
header("HTTP/1.1 301 Moved Permanently");

header("Location:http://www.feylandsh.com.cn/".$go);

exit();
?>