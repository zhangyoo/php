<?php
require_once(dirname(__FILE__).'/../include/config.inc.php');
//初始化参数检测正确性
$cid = empty($cid) ? 52 : intval($cid);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=9"> 
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<?php echo GetHeader(1,$cid); ?>
<link rel="stylesheet" type="text/css" href="../templates/en/css/style.css">
<script type="text/javascript" src="../templates/en/js/jquery.js"></script>
<script type="text/javascript" src="../templates/en/js/ext.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=96615a201163396081cc18c846fff4e9"></script>
</head>
<body>
    <?php require_once('header.php'); ?>
    <?php require_once('morebanner.php'); ?>
    <div class="main">
        <div class="no_index">
            <div class="nav_left">
                <div class="nav_left_title">
                    <p class="nav_left_title_sub">CONTACT US</p>
                </div>
                <?php require_once('leftnav.php'); ?>
            </div>
            <div class="right_main">
                <div class="right_main_position">
                    Location：
                    <a href="<?php echo $cfg_isreurl=='Y'?'index.html':'index.php'; ?>">Home</a> > 
                    <a href="javascript:void(0);">Link Us</a>
                </div>
                <div class="right_main_content">
                    <div id="allmap"></div>
                    <div class="link_us_more">
                        <?php echo Info($cid); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once('footer.php'); ?>
<script type="text/javascript">
// 百度地图API功能
var map = new BMap.Map("allmap");                        // 创建Map实例
map.centerAndZoom(new BMap.Point(121.719564,31.005506), 11);     // 初始化地图,设置中心点坐标和地图级别
var marker1 = new BMap.Marker(new BMap.Point(121.719564,31.005506));  // 创建标注
map.addOverlay(marker1);              // 将标注添加到地图中
map.addControl(new BMap.NavigationControl());               // 添加平移缩放控件
map.addControl(new BMap.ScaleControl());                    // 添加比例尺控件
map.addControl(new BMap.OverviewMapControl());              //添加缩略地图控件
map.enableScrollWheelZoom();                            //启用滚轮放大缩小
map.addControl(new BMap.MapTypeControl());          //添加地图类型控件
map.setCurrentCity("上海市南汇工业园区南芦公路300号");          // 设置地图显示的城市 此项是必须设置的
</script>
</body>
</html>