<?php
/**
 * 
 * 图片工具类
 * @author fengchuan
 * create_time 2014/07/18
 * update_time 2014/08/24
 */
class ImageHelper
{
    const THUMB_TYPE_CROP=0;  //裁剪产生缩略图
    const THUMB_TYPE_SCALE=1; //缩放产生缩略图
    /**
     * 检验文件后缀名
     * @param string $ext
     * @author fengchuan
     */
    private static function checkExtension($ext)
    {
        // array 文件类型
        $types=array('jpg','jpeg','png','gif');	
        if(!in_array($ext, $types))
            throw new FileException('上传图片类型错误');
    }
    /**
     * 获取图片信息
     * @param string $image
     * @return boolean
     * @author fengchuan
     */
    public static function getImageInfo( $image )
    {
        /*
         * Array ( [0] => 1800 [1] => 1200 [2] => 3 [3] => width="1800" height="1200" [bits] => 8 [mime] => image/png )
         */
        $imageInfo = getimagesize($image);
        if(false!==$imageInfo) 
        {
            $imageType = strtolower(image_type_to_extension($imageInfo[2],false));
            $imageInfo = array(
                "width"		=>$imageInfo[0],
                "height"	=>$imageInfo[1],
                "type"		=>$imageType,
                "mime"		=>$imageInfo['mime'],
            );
        }
        return $imageInfo;
    }
    /**
     * 根据长宽生成缩略图后缀
     * @param string $image
     * @param int $width
     * @param int $height
     * @author fengchuan
     */
    public static function thumbSuffix($image, $width=0, $height=0)
    {
        $name='_thumb';//缩略图名称后缀
        $ext=  pathinfo($image, PATHINFO_EXTENSION);//图片类型后缀
        //根据width和height生成不同name
        if($width>0 && $height>0)
        {
            $name="_".$width."X".$height;
        }
        elseif($width>0 && 0==$height)
        {
            $name="_w".$width;
        }
        elseif(0==$width && $height>0)
        {
            $name="_h".$height;
        }    
        return $name.'.'.$ext;
    }
    /**
     * 缩略图目录
     * @param string $subFolder 子目录
     * @return string
     * @author fengchuan
     */
    public static function thumbPath($subFolder=null)
    {
        $thumbPath=Yii::app()->params['realPathOfStatic'].'/thumb';
        if(null!==$subFolder)
            $thumbPath.=$subFolder;
        if(!is_dir($thumbPath))
        {
            mkdir($thumbPath, 0777, true);
        }
        return $thumbPath;
    }
    /**
     * 返回缩略图的名称(绝对路径)
     * @param string $image
     * @param array $options
     * @return string
     */
    public static function thumbName($image, $options=array())
    {
        //拼接缩略图的绝对路径
        $width=isset($options['width'])?$options['width']:0;
        $height=isset($options['height'])?$options['height']:0;
        $image=  self::relativePath($image);//转为相对路径
        $pathinfo=  pathinfo($image);
        $suffix=  self::thumbSuffix($image, $width, $height);
        $thumbName=  self::thumbPath($pathinfo['dirname'].'/').$pathinfo['basename'].$suffix;
        return $thumbName;
    }
    /**
     * 返回相对地址
     * @param string $image
     * @author fengchuan
     */
    public static function relativePath($image)
    {
        $rootFolder=Yii::app()->params['realPathOfStatic'];
        $flag=strpos($image, $rootFolder);
        if(false!==$flag)
        {//转为相对路径
            $image=substr($image, strlen($rootFolder));
        }
        return $image;
    }
    /**
     * 返回绝对路径
     * @param string $image 
     * @author fengchuan
     */
    public static function absolutePath($image)
    {
        $rootFolder=Yii::app()->params['realPathOfStatic'];
        $flag=strpos($image, $rootFolder);
        if(false===$flag)
        {//转为相对路径
            $image=$rootFolder.$image;
        }
        return $image;
    }
    /**
     * 显示缩略图(会自动生成缩略图)
     * @param string $image 原图绝对/相对路径
     * @param array $options =array(
     *                              'relative'=>true,  //返回相对路径
     *                              'type'=>ImageHelper::THUMB_TYPE_CROP, //处理方式，裁剪，等比缩放 默认是等比缩放
     *                          )
     * @return string 缩略图的绝对/相对路径
     * @author fengchuan
     */
    public static function showThumb($image, $options=array())
    {
        if(empty($image) || ""==  trim($image))
            return '';
        $image=self::absolutePath($image);
        //判断图片是否存在
        if(!file_exists($image))
        {
            $filename=  self::relativePath ($image);
            if(!isset($options['relative']))
                $filename=Yii::app()->params['static'].$filename;
            return $filename;
            //throw new FileException('图片文件不存在');
        }
        $thumbName= self::thumbName($image, $options);
        if(file_exists($thumbName))//如果缩略图已存在直接返回
        {
            $filename=  self::relativePath ($thumbName);
            if(!isset($options['relative']))
                $filename=Yii::app()->params['static'].$filename;
            return $filename;
        }
        !isset($options['type']) && $options['type']=  self::THUMB_TYPE_SCALE;//默认等比缩放
        //返回缩略图的绝对路径
        switch ($options['type'])
        {
            case self::THUMB_TYPE_CROP:
                $filename=self::crop($image, $options);
                break;
            case self::THUMB_TYPE_SCALE:
                $filename=self::scale($image, $options);
                break;
            default:
                $filename=$image;
        }
        $filename=  self::relativePath ($filename);
        if(!isset($options['relative']))
            $filename=Yii::app()->params['static'].$filename;
        return $filename;
    }
    /**
     * 生成特定尺寸缩略图 解决原版缩略图不能满足特定尺寸的问题
     * @param string $image 原图绝对/相对路径
     * @param int $targetWidth 缩略图的宽
     * @param int $targetHeight 缩略图的高
     * @param array $options 用于设置缩略图的后缀 如果没有设置$options,则后缀为_thumb.ext
     *                  array('width'=>200, 'height'=>200) _200x200.ext
     *                  array('width'=>200) _w200.ext
     *                  array('height'=>200) _h200.ext
     * @return string 缩略图绝对路径
     * @author fengchuan
     */
    public static function createThumb($image, $targetWidth, $targetHeight, $options=array())
    {
        $image=  self::absolutePath($image);//转为绝对路径
        $info=  self::getImageInfo($image);
        if(FALSE===$info)
            return false;
        $srcWidth = isset($options['srcWidth'])?$options['srcWidth']:$info['width'];
        $srcHeight = isset($options['srcHeight'])?$options['srcHeight']:$info['height'];
        $srcX=  isset($options['srcX'])?$options['srcX']:0;
        $srcY=  isset($options['srcY'])?$options['srcY']:0;
        $type=  $info['type'];
        $thumbImage = imagecreatetruecolor($targetWidth, $targetHeight);
        if('png'==$type)
        {
            imagesavealpha($thumbImage, true);
            $bg=imagecolorallocatealpha($thumbImage, 0, 0, 0, 127);
        }
        else 
        {
            $bg=imagecolorallocate($thumbImage, 0, 0, 0);
        }
        imagefill($thumbImage, 0, 0, $bg);
        $imageCreateFun='imagecreatefrom'.($type == 'jpg' ? 'jpeg' : $type);
        $srcImage=$imageCreateFun($image);
        imagecopyresampled($thumbImage, $srcImage, 0, 0, $srcX, $srcY, $targetWidth, $targetHeight, $srcWidth, $srcHeight);
        
        // 生成缩略图图
        $thumbName=  self::thumbName($image, $options);
        $imageFun = 'image' . ($type == 'jpg' ? 'jpeg' : $type);
        $imageFun($thumbImage, $thumbName);
        
        imagedestroy($thumbImage);
        imagedestroy($srcImage);
        
        return $thumbName;
    }
    /**
     * 图片等比缩放
     * @param string $image 图片绝对/相对路径
     * @param array $options
     * @author fengchuan
     */
    public static function scale($image, $options=array())
    {
        $image=  self::absolutePath($image);//转为绝对路径
        $info=  self::getImageInfo($image);
        if(FALSE===$info)
            return false;
        $srcWidth = $info['width'];
        $srcHeight = $info['height'];
        
        $width=  isset($options['width'])?$options['width']:$srcWidth;
        $height=  isset($options['height'])?$options['height']:$srcHeight;
        $ratio = min($width / $srcWidth, $height / $srcHeight); // 计算缩放比例
        if(1==$ratio)
        {
//            //默认缩放1/2  目前不默认缩放
//            $ratio=$ratio/2;
            return $image;//直接返回原图
        }
            
        $targetWidth = (int) ($srcWidth * $ratio);
        $targetHeight = (int) ($srcHeight * $ratio);
        $thumbName=self::createThumb($image, $targetWidth, $targetHeight, $options);
        return $thumbName;
    }
    /**
	 *
	 * 裁剪图片
     * ps:配合js的坐标定位和宽高就能实现前端裁剪功能
     * @param string $image 图片绝对/相对路径
     * @param array $options 必须设置宽高才能进行裁剪
	 * @author fengchuan
	 */
    public static function crop($image, $options=array())
    {
        if(!isset($options['width']) || !isset($options['height']))
            return false;
        $image=  self::absolutePath($image);//转为绝对路径
        $info=  self::getImageInfo($image);
        if(FALSE===$info)
            return false;
        $srcWidth = $info['width'];
        $srcHeight = $info['height'];
        $targetWidth=$options['width'];
        $targetHeight=$options['height'];
        $srcX=0;
        $srcY=0;
        
        $srcRatio=$srcWidth/$srcHeight;
        $targetRatio=$targetWidth/$targetHeight;
        if($srcRatio>$targetRatio)//原图过宽
        {
            $cropHeight=$srcHeight;
            $cropWidth=$srcHeight*$targetRatio;
            $srcX=($srcWidth-$cropWidth)/2;
        }
        elseif($srcRatio<$targetRatio)//原图过高
        {
            $cropWidth=$srcWidth;
            $cropHeight=$srcWidth/$targetRatio;
            $srcY=($srcHeight-$cropHeight)/2;
        }
        
        $options['srcWidth']=isset($cropWidth)?$cropWidth:$srcWidth;
        $options['srcHeight']=isset($cropHeight)?$cropHeight:$srcHeight;
        $options['srcX']=$srcX;
        $options['srcY']=$srcY;
        $thumbName=self::createThumb($image, $targetWidth, $targetHeight, $options);
        return $thumbName;
    }
    
    //以下方法待整理
    /**
	 *
	 * 异步上传图片到临时目录
	 * @todo:待完善
	 */
	public function actionUploadTempPic(){
		if(empty($_FILES) === false){
			$root=Yii::app()->params['realPathOfStatic'];
			$save_path=$root."/upload/temp/".date('Ymd')."/";
			if(!is_dir($save_path)){
				mkdir($save_path,0777,true);
			}
			$save_url=Yii::app()->params['static']."/upload/temp/".date('Ymd')."/";
			$ext_arr = array('jpg','jpeg','png','gif');
			//PHP上传失败
			if (!empty($_FILES['imgFile']['error'])) {
				switch($_FILES['imgFile']['error']){
					case '1':
						$error = '超过php.ini允许的大小。';
						break;
					case '2':
						$error = '超过表单允许的大小。';
						break;
					case '3':
						$error = '图片只有部分被上传。';
						break;
					case '4':
						$error = '请选择图片。';
						break;
					case '6':
						$error = '找不到临时目录。';
						break;
					case '7':
						$error = '写文件到硬盘出错。';
						break;
					case '8':
						$error = 'File upload stopped by extension。';
						break;
					case '999':
					default:
						$error = '未知错误。';
				}
				echo json_encode(array("error"=>1,"message"=>$error));
				exit;
			}
			//原文件名
			$file_name = $_FILES['imgFile']['name'];
			//服务器上临时文件名
			$tmp_name = $_FILES['imgFile']['tmp_name'];
			//文件大小
			$file_size = $_FILES['imgFile']['size'];
			//获得文件扩展名
			$temp_arr = explode(".", $file_name);
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_ext = strtolower($file_ext);
			//检查扩展名
			if (in_array($file_ext, $ext_arr) === false) {
				echo json_encode(array("error"=>1,"message"=>"上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr) . "格式。"));
				exit;
			}
			//新文件名
			$new_file_name = uniqid() . '.' . $file_ext;
			//移动文件
			$file_path = $save_path . $new_file_name;
			if (move_uploaded_file($tmp_name, $file_path) === false) {
				echo json_encode(array("error"=>1,"message"=>"上传文件失败。"));
				exit;
			}
			$file_url = $save_url . $new_file_name;
				
			header('Content-type: text/html; charset=UTF-8');
			echo json_encode(array('error' => 0, 'url' => $file_url));
			exit;
		}
		echo json_encode(array('error'=>1,'message'=>'error'));
	}
    
        
        
        
	/**
	 *
	 * 从临时目录中移动到指定文件夹，
	 * 		修改img的src路径
	 * @param string $content
	 * @param string $subFolder 子目录
	 * @todo:待完善
	 */
	protected function saveTempImg($content,$subFolder='kindeditor'){
		$reg="/\<img src=\"(.*)\"(.*)\>/Ui";//过滤的规则,如果在img和src之间还存在其他的，就会有问题
		//$imgs[1]是图片路径
		$flag=preg_match_all($reg, $content, $imgs);
		if($flag>0)
		{
			$root=Yii::app()->params['realPathOfStatic'];
			$temp=$root."/upload/temp/".date('Ymd')."/";
			$date=date ( "Y/m/d" );
			$savePath=$root."/upload/".$subFolder."/".$date."/";
			$saveUrl="/upload/".$subFolder."/".$date."/";//保存的相对路径
			if(!is_dir($savePath))
			{
				mkdir($savePath,0777,true);
			}
			for ($i=0;$i<count($imgs[1]);$i++)
			{
				$basename=basename($imgs[1][$i]);
				if(is_file($temp.$basename) && file_exists($temp.$basename))
				{
					rename($temp.$basename, $savePath.$basename);
				}
			}
			$pattern="/\/upload\/temp\/".date('Ymd')."\//i";
			$content=preg_replace($pattern, $saveUrl, $content);
		}
		return $content;
	}
	/**
	 * 
	 * 从临时目录中移动单张图片到指定文件夹
	 * 		修改单张图片的路径
	 * @param string $url  临时路径
	 * @param string $subFolder 子目录
	 */
	protected function saveTempUrl($url,$subFolder='kindeditor')
	{
		$root=Yii::app()->params['realPathOfStatic'];
		$temp=$root."/upload/temp/".date('Ymd')."/";
		$date=date ( "Y/m/d" );
		$savePath=$root."/upload/".$subFolder."/".$date."/";
		$saveUrl="/upload/".$subFolder."/".$date."/";//保存的相对路径
		if(!is_dir($savePath))
		{
			mkdir($savePath,0777,true);
		}
		$basename=basename($url);
		if(is_file($temp.$basename) && file_exists($temp.$basename))
		{
			rename($temp.$basename, $savePath.$basename);
			$pattern="/\/upload\/temp\/".date('Ymd')."\//i";
			$url=preg_replace($pattern, $saveUrl, $url);
		}
		return $url;
	}
    
    
    /**
     * 
     * 合成图片
     * getimagesize返回的信息
     * Array ( [0] => 2000 [1] => 1250 [2] => 2 [3] => width="2000" height="1250" [bits] => 8 [channels] => 3 [mime] => image/jpeg )
     * @param array $images 图片绝对路径
     * @author fengchuan
     * @todo:未完成
     */
    public static function merge($images)
    {
        $ret=false;
        if(!is_array($images))
            return $ret;
        $rootFolder=Yii::app()->params['realPathOfStatic'];
        //判断图片类型
            
        //根据图片类型创建图片资源
        $bottom=$images[0];//合成图片的底图资源
        $size = getimagesize($rootFolder . $images[0]);
        unset($images[0]);
        foreach($images as $image)
        {
            //判断图片类型
            
            //根据图片类型创建图片资源
            
        }
        return $ret;
    }
    
    /**
     * 
     * 合并空间和元素的图片
     * getimagesize返回的信息
     * Array ( [0] => 2000 [1] => 1250 [2] => 2 [3] => width="2000" height="1250" [bits] => 8 [channels] => 3 [mime] => image/jpeg )
     * @author fengchuan <gezlife@foxmail.com>
     * @todo:未完成
     */
    private static function _mergeImage($images, $subFolder = 'plan') {
        //$images=array('/merge/1.jpg', '/merge/2.png', '/merge/3.png', '/merge/4.png', '/merge/5.png', '/merge/6.png');//测试数据
        $root = Yii::app()->params['realPathOfStatic'];
        $space = imagecreatefromjpeg($root . $images[0]); //空间图片
        $spaceSize = getimagesize($root . $images[0]);
        unset($images[0]);
        foreach ($images as $image) {
            $element = imagecreatefrompng($root . $image);
            imagecopy($space, $element, 0, 0, 0, 0, $spaceSize[0], $spaceSize[1]);
            imagedestroy($element);
        }
        $folder = '/upload/' . $subFolder . '/' . date('Y/m/d') . '/';
        if (!is_dir($root . $folder)) {
            if (!mkdir($root . $folder, 0777, true))
                throw new CException('创建文件夹失败');
        }
        $filename = uniqid() . '.jpg';
        imagejpeg($space, $root . $folder . $filename);
        imagedestroy($space);
        return $folder . $filename;
    }
    
    /**
	 *
	 * 保存图片   上传模型的贴图时使用
	 * $options['upyun'] true表示上传到云盘，false表示不上传到云盘
	 * @auth zhangyong
	 */
   public function saveImg($src,$option=array())
   {
       $catalog = 'mold';
       if(isset($option['catalog']) && !empty($option['catalog']))
           $catalog = $option['catalog'];
       $root=Yii::app()->params['realPathOfStatic'];
       $tempUrl = dirname(Yii::app()->BasePath).$src;
       $date=date ( "Y/m/d" );
       $savePath=$root."/upload/".$catalog."/".$date;
       $saveUrl="/upload/".$catalog."/".$date."/";
       if(!is_dir($savePath)){
           mkdir($savePath,0777,true);
       }
       if(file_exists($tempUrl)){
           if(copy($tempUrl,$savePath.'/'.basename($tempUrl))){
               unlink($tempUrl);
           }
       }
       if(isset($option['upyun']) && true==$option['upyun']){
            $fileHelper=new FileHelper;
            $fileHelper->uploadUpYun($saveUrl.basename($tempUrl));
        }
       return $saveUrl.basename($tempUrl);
   }
}


