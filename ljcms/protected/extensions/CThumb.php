<?php 
class CThumb extends CApplicationComponent 
{

	public $srcImage;
	private $srcWidth;
	private $srcHeight;
	private $srcType;
	private $srcExt;
	
	public $thumbImage;
	public $width=150;
	public $height=150;
	
	public $mode;//模式：为以后加入剪切功能预留
	public $directory;
	public $defaultName="thumb";
	// @var string 后缀名称
	public $suffix;
    // @var string 前缀名称
    public $prefix;
    // @var int 图像质量
    public $quality = 75;
    // @var int PNG文件转化率
    public $compression = 6;
	
	private $image; //临时图像
	
	
	public function init() 
	{
        if (!function_exists("imagecreatetruecolor")) {
            throw new Exception("使用这个类,需要启用GD库", 500);
        }
        parent::init();
    }
	
	public function createThumb()
	{
		$this->loadImage();
		if(!empty($this->width) && !empty($this->height))
		{
			$this->_createThumb();
		}
		elseif(!empty($this->width) && empty($this->height))
		{//限宽
			$this->_createThumbByWidth();
		}
		elseif(empty($this->width) && !empty($this->height))
		{//限高
			$this->_createThumbByHeight();
		}
	}
	
	private function _createThumb()
	{
		$ratio_w = 1.0 * $this->width / $this->srcWidth;
        $ratio_h = 1.0 * $this->height / $this->srcHeight;
        $ratio = 1.0;
        
		if ($ratio_w > 1 && $ratio_h > 1) 
		{
            $this->thumbImage = imagecreatetruecolor($this->srcWidth, $this->srcHeight);
	        if('png'==$this->srcExt)
			{
			    imagesavealpha($this->thumbImage, true);
				$bg=imagecolorallocatealpha($this->thumbImage, 0, 0, 0, 127);
			}
			else 
			{
				$bg=imagecolorallocate($this->thumbImage, 0, 0, 0);
			}
			imagefill($this->thumbImage, 0, 0, $bg);
            imagecopy($this->thumbImage, $this->image, 0, 0, 0, 0, $this->srcWidth, $this->srcHeight);
         } 
         else 
         {
         	$ratio = $ratio_w > $ratio_h ? $ratio_h : $ratio_w;
         	$tmp_w = (int) ($this->srcWidth * $ratio);
            $tmp_h = (int) ($this->srcHeight * $ratio);
            //$this->log($this->srcImage.":$this->srcWidth X $this->srcHeight ".$tmp_w."X".$tmp_h.":w:".$ratio_w.":h:".$ratio_h.":r:".$ratio."\r\n");
            $this->thumbImage = imagecreatetruecolor($tmp_w, $tmp_h);
            if('png'==$this->srcExt)
            {
                imagesavealpha($this->thumbImage, true);
				$bg=imagecolorallocatealpha($this->thumbImage, 0, 0, 0, 127);
            }
            else 
            {
            	$bg=imagecolorallocate($this->thumbImage, 0, 0, 0);
            }
            imagefill($this->thumbImage, 0, 0, $bg);
            imagecopyresampled($this->thumbImage, $this->image, 0, 0, 0, 0, $tmp_w, $tmp_h, $this->srcWidth, $this->srcHeight);
         }
	}
	private function _createThumbByWidth()
	{
		$ratio_w = 1.0 * $this->width / $this->srcWidth;
        $ratio = 1.0;
        
		if ($ratio_w > 1) 
		{
            $this->thumbImage = imagecreatetruecolor($this->srcWidth, $this->srcHeight);
	        if('png'==$this->srcExt)
			{
			    imagesavealpha($this->thumbImage, true);
				$bg=imagecolorallocatealpha($this->thumbImage, 0, 0, 0, 127);
			}
			else 
			{
				$bg=imagecolorallocate($this->thumbImage, 0, 0, 0);
			}
			imagefill($this->thumbImage, 0, 0, $bg);
            imagecopy($this->thumbImage, $this->image, 0, 0, 0, 0, $this->srcWidth, $this->srcHeight);
         } 
         else 
         {
         	$ratio = $ratio_w;
         	$tmp_w = (int) ($this->srcWidth * $ratio);
            $tmp_h = (int) ($this->srcHeight * $ratio);
            //$this->log($this->srcImage.":$this->srcWidth X $this->srcHeight ".$tmp_w."X".$tmp_h.":w:".$ratio_w.":h:".$ratio_h.":r:".$ratio."\r\n");
            $this->thumbImage = imagecreatetruecolor($tmp_w, $tmp_h);
            if('png'==$this->srcExt)
            {
                imagesavealpha($this->thumbImage, true);
				$bg=imagecolorallocatealpha($this->thumbImage, 0, 0, 0, 127);
            }
            else 
            {
            	$bg=imagecolorallocate($this->thumbImage, 0, 0, 0);
            }
            imagefill($this->thumbImage, 0, 0, $bg);
            imagecopyresampled($this->thumbImage, $this->image, 0, 0, 0, 0, $tmp_w, $tmp_h, $this->srcWidth, $this->srcHeight);
         }
	}
	private function _createThumbByHeight()
	{
        $ratio_h = 1.0 * $this->height / $this->srcHeight;
        $ratio = 1.0;
        
		if ($ratio_h > 1) 
		{
            $this->thumbImage = imagecreatetruecolor($this->srcWidth, $this->srcHeight);
	        if('png'==$this->srcExt)
			{
			    imagesavealpha($this->thumbImage, true);
				$bg=imagecolorallocatealpha($this->thumbImage, 0, 0, 0, 127);
			}
			else 
			{
				$bg=imagecolorallocate($this->thumbImage, 0, 0, 0);
			}
			imagefill($this->thumbImage, 0, 0, $bg);
            imagecopy($this->thumbImage, $this->image, 0, 0, 0, 0, $this->srcWidth, $this->srcHeight);
         } 
         else 
         {
         	$ratio = $ratio_h ;
         	$tmp_w = (int) ($this->srcWidth * $ratio);
            $tmp_h = (int) ($this->srcHeight * $ratio);
            //$this->log($this->srcImage.":$this->srcWidth X $this->srcHeight ".$tmp_w."X".$tmp_h.":w:".$ratio_w.":h:".$ratio_h.":r:".$ratio."\r\n");
            $this->thumbImage = imagecreatetruecolor($tmp_w, $tmp_h);
            if('png'==$this->srcExt)
            {
                imagesavealpha($this->thumbImage, true);
				$bg=imagecolorallocatealpha($this->thumbImage, 0, 0, 0, 127);
            }
            else 
            {
            	$bg=imagecolorallocate($this->thumbImage, 0, 0, 0);
            }
            imagefill($this->thumbImage, 0, 0, $bg);
            imagecopyresampled($this->thumbImage, $this->image, 0, 0, 0, 0, $tmp_w, $tmp_h, $this->srcWidth, $this->srcHeight);
         }
	}
	
	public function save() 
	{
        if (!$this->directory)
            throw new Exception("输入保存缩略图目录", 500);
 
        switch ($this->srcType) 
        {
          case IMAGETYPE_JPEG:
	          imagejpeg($this->thumbImage, $this->directory . $this->prefix . $this->defaultName . $this->suffix . "." . $this->srcExt, $this->quality);
	          break;
          case IMAGETYPE_GIF:
	          imagegif($this->thumbImage, $this->directory . $this->prefix . $this->defaultName . $this->suffix . "." . $this->srcExt, $this->quality);
	          break;
          case IMAGETYPE_PNG:
	          imagepng($this->thumbImage, $this->directory . $this->prefix . $this->defaultName . $this->suffix . "." . $this->srcExt, $this->compression);
	          break;
        }
    }
	
	/**
	 * 
	 * 获取图片信息
	 */
	public function getImageSize()
	{
        $this->loadImage();
		$imageSize=array('width'=>$this->srcWidth,'height'=>$this->srcHeight,'ext'=>$this->srcExt);
		return $imageSize;
	}
	
	/**
	 * 
	 * 载入图片信息
	 * @param string $image
	 * @throws Exception
	 */
	private function loadImage()
	{
		if (empty($this->srcImage))
			throw new Exception('请设置原图！', 500);

        list($this->srcWidth, $this->srcHeight, $this->srcType) = getimagesize($this->srcImage);
        
        switch ($this->srcType) {
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromjpeg($this->srcImage);
                $this->srcExt = 'jpg';
                break;
            case IMAGETYPE_GIF:
                $this->image = imagecreatefromgif($this->srcImage);
                $this->srcExt = 'gif';
                break;
            case IMAGETYPE_PNG:
                $this->image = imagecreatefrompng($this->srcImage);
                $this->srcExt = 'png';
                break;
            default:
                throw new Exception("不支持的图像类型", 500);
        }
	}
	
	public function __destruct() {
		!empty($this->image) &&
       		 imagedestroy($this->image);
       	!empty($this->thumbImage) &&
        	imagedestroy($this->thumbImage);
    }
    
	/**
	 * 显示缩略图
	 * @param array $imageSize
	 * @todo:
	 */
	public function show($image,$imageSize=array())
	{
		//获取图片后缀名
		$ext=substr($image, strrpos($image, '.'));
		if(empty($imageSize))
			$image.='_thumb'.$ext;
		else 
		{
			if(isset($imageSize['width']) && isset($imageSize['height']))
			{
				$image.="_".$imageSize['width']."X".$imageSize['height'].$ext;
			}
			elseif(isset($imageSize['width']) && !isset($imageSize['height']))
			{
				$image.="_w".$imageSize['width'].$ext;
			}
			elseif(!isset($imageSize['width']) && isset($imageSize['height']))
			{
				$image.="_h".$imageSize['height'].$ext;
			}
		}
		return $image;
	}
    
	/**
	 * 
	 * 写日志
	 * @param string $message
	 * @param string $level info warning error
	 * @param string $filename
	 */
	protected function log($message,$level='info',$filename='info.txt')
	{
		$now=date('Y/m/d H:i:s');
		$root=YiiBase::getPathOfAlias('webroot')."/protected/messages/";
		if(!is_dir($root))
		{
			mkdir($root);
		}	
		$filename=$root.$filename;
		$handle=fopen($filename, 'a');//追加方式
		fwrite($handle, $now." [".$level."] : ".$message."\r\n");
		fclose($handle);
	}
}
?>