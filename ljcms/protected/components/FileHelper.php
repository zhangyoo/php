<?php
/**
 * 
 * 文件工具类
 * @author fengchuan
 * create_time 2014/07/15
 * update_time 2014/07/18
 * 
 */
class FileHelper extends CFileHelper
{
	//array 文件路径
	public $files;	
	//array 文件类型
	public $types=array('zip', 'rar', 'jpg', 'jpeg', 'png', 'gif', 'max','obj','md5','fbx','3ds','awd');	
	//文件保存的根目录
	public $rootFolder;
	//文件保存的子目录
	public $subFolder='default';
	//保存的文件名是否唯一(只写)
	public $uniqid=true;
    //记录上传的文件绝对路径，用于回滚时删除图片
    public $filename=array();
    //params.php里的配置信息
    private $params;

    public function __construct()
	{
        $this->params=Yii::app()->params;
		$this->rootFolder=$this->params['realPathOfStatic'];
	}
    
    /**
     * 检验文件后缀名
     * @param string $ext
     */
    private function checkExtension($ext)
    {
        $ext=  strtolower($ext);//转为小写
        if(!in_array($ext, $this->types))
            throw new FileException('上传文件类型错误');
    }
	
	/**
     * 上传单个对象单个属性的单个文件
	 * upload single file
	 * @param CActiveRecord $model
	 * @param string $attribute
	 * @param array $options 如果$options['upyun']=true，则同步上传文件到又拍云 
	 * @throws CException
	 * @return string 文件相对
     * @author fengchuan
     * 
	 */
	public function saveFile($model, $attribute, $options=array())
	{
		$uploadFile=CUploadedFile::getInstance($model,$attribute);
		if(empty($uploadFile))
			throw new FileException('没有上传文件');
        $ext=$uploadFile->getExtensionName();//文件后缀
        $this->checkExtension($ext);//检查文件类型
		$folder='/upload/'.$this->subFolder.'/'.date('Y/m/d').'/';
		if(!is_dir($this->rootFolder.$folder))
		{
			if(!mkdir($this->rootFolder.$folder, 0777, true))
				throw new FileException('创建文件夹失败');
		}
		if($this->uniqid)
			$filename=uniqid().'.'.$ext;
		else 
			$filename=$uploadFile->getName();	
		if(!$uploadFile->saveAs($this->rootFolder.$folder.$filename))
			throw new FileException('保存文件失败');
        //记录上传成功的文件绝对路径
        $this->filename[]=$this->rootFolder.$folder.$filename;
        //同步上传文件到又拍云
        if(isset($options['upyun']) && false!==$options['upyun'])
            $this->uploadUpYun($folder.$filename);
		return $folder.$filename;//返回单个文件的相对路径
	}
	/**
	 * 
	 * 批量上传单个对象多个属性的单个文件
	 * @param CActiveRecord $model
	 * @param array $attributes
	 * @param array $options 如果$options['upyun']=true，则同步上传文件到又拍云 allowEmpty 允许不上传文件的属性值
     * @return array 文件相对路径
     * @author fengchuan
     * 
	 */
	public function saveFilesByAttributes($model, $attributes, $options=array())
	{
		$retFiles=array();
		foreach ($attributes as $attribute)
		{
            if(!$this->hasUploadFile($model, $attribute))//处理没有选择上传的文件
            {
                if(!isset($options['allowEmpty']) || !in_array($attribute, $options['allowEmpty']))
                    throw new FileException('缺少上传文件');
                continue;
            }
			$retFiles[$attribute]=$this->saveFile($model, $attribute, $options);
		}
		return $retFiles;
	}
	/**
	 * 批量上传单个对象单个属性的一批文件
	 * @param CActiveRecord $model
	 * @param string $attribute
	 * @param array $options 如果$options['upyun']=true，则同步上传文件到又拍云
	 * @throws CException
	 * @return array
     * @author fengchuan
     * 
	 */
	public function saveBatchFiles($model, $attribute, $options=array())
	{
		$ret=array();
		$uploadFiles=CUploadedFile::getInstances($model, $attribute);
		if(empty($uploadFiles))
			throw new FileException('没有上传文件');
		$folder='/upload/'.$this->subFolder.'/'.date('Y/m/d').'/';
		if(!is_dir($this->rootFolder.$folder))
		{
			if(!mkdir($this->rootFolder.$folder, 0777, true))
				throw new FileException('创建文件夹失败');
		}
		foreach ($uploadFiles as $uploadFile)
		{
            $ext=$uploadFile->getExtensionName();
            $this->checkExtension($ext);//检查文件类型
			if($this->uniqid)
				$filename=uniqid().'.'.$ext;
			else
				$filename=$uploadFile->getName();
			if(!$uploadFile->saveAs($this->rootFolder.$folder.$filename))
				throw new FileException('保存文件失败');
			$ret[]=$folder.$filename;//返回单个文件的相对路径
            //记录上传成功的文件绝对路径
            $this->filename[]=$this->rootFolder.$folder.$filename;
            //同步上传文件到又拍云
            if(isset($options['upyun']) && false!==$options['upyun'])
                $this->uploadUpYun($folder.$filename);
		}
		return $ret;
	}
	/**
	 * 上传单个对象多个属性的多批文件
	 * @param CModel $model
	 * @param array $attributes
	 * @param array $options 如果$options['upyun']=true，则同步上传文件到又拍云
	 * @return array
     * @author fengchuan
     * 
	 */
	public function saveBatchFilesByAttributes($model, $attributes, $options=array())
	{
		$retFiles=array();
		foreach ($attributes as $attribute)
		{
			$retFiles[$attribute]=$this->saveBatchFiles($model, $attribute, $options);
		}
		return $retFiles;
	}
    /**
	 * 
	 * 保存文件流
	 * @param string $fileStream 文件流
	 * @param array $options 如果$options['upyun']=true，则同步上传文件到又拍云
     * @author fengchuan
     * 
	 */
	public function saveFileStream($fileStream, $options=array())
	{
		//文件名（不包含文件后缀） 
		$filename=isset($params['filename'])?$params['filename']:uniqid();
		$ext=$this->getStreamExtension($fileStream);
		if(false===$ext)
			throw new FileException('不支持的文件类型');
        $this->checkExtension($ext);//检查文件类型
		$filename.='.'.$ext;
		$folder='/upload/'.$this->subFolder.'/'.date('Y/m/d').'/';
		if(!is_dir($this->rootFolder.$folder))
		{
			if(!mkdir($this->rootFolder.$folder, 0777, true))
				throw new FileException('创建文件夹失败');
		}
		$flag=file_put_contents($this->rootFolder.$folder.$filename, $fileStream); 
		if(false===$flag)
			throw new FileException('保存文件失败');
        //记录上传成功的文件绝对路径
        $this->filename[]=$this->rootFolder.$folder.$filename;
        //同步上传文件到又拍云
        if(isset($options['upyun']) && false!==$options['upyun'])
            $this->uploadUpYun($folder.$filename);
		return $folder.$filename;//返回单个文件的相对路径
	}
	/**
	 * 
	 * 获取文件流的文件类型
	 * @param string $fileStream 文件流
     * @author fengchuan
	 * 
	 */
	public function getStreamExtension($fileStream)
	{
		$fileType = false;
        $bin = substr($fileStream,0,2);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
        switch ($typeCode)
        {
            case 8075:
            	$fileType = 'zip';
            	break;
            case 8297:
                $fileType = 'rar';
                break;
            case 255216:
                $fileType = 'jpg';
                break;
            case 7173:
                $fileType = 'gif';
                break;
            case 6677:
                $fileType = 'bmp';
                break;
            case 13780:
                $fileType = 'png';
                break;
            default:
        }
        return $fileType;
    }
    /**
     * 获取文件流的mime类型
     * @param string $fileStream 文件流
     * mime application/octet-stream 任意的二进制流
     * @author fengchuan
     */
    public function getStreamMimeType($fileStream)
    {
    	$mime = false;
        $bin = substr($fileStream,0,2);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
        switch ($typeCode)
        {
            case 8075:
            	$mime = 'application/zip';
            	break;
            case 8297:
                $mime = 'application/x-rar-compressed';
                break;
            case 255216:
                $mime = 'image/jpeg';
                break;
            case 7173:
                $mime = 'image/gif';
                break;
            case 6677:
                $mime = 'image/bmp';
                break;
            case 13780:
                $mime = 'image/png';
                break;
            default:
        }
        return $mime;
    }
	
    /**
     * 下载单个文件
     * @param string $filename 文件的相对/绝对路径
     * @param array $options 
     * @author fengchuan
     */
    public function sendFile($filename, $options=array())
    {
        $flag=strpos($filename, $this->rootFolder);
        if(false===$flag)
        {//转为绝对路径
            $filename=$this->rootFolder.$filename;
        }
        if(!file_exists($filename))
            throw new FileException('下载的资源不存在');
        $content=  file_get_contents($filename);
        $mimeType=self::getMimeType($filename);
        if(!isset($options['saveName']))
            $options['saveName']=  pathinfo($filename, PATHINFO_FILENAME);
        $filename= $options['saveName'].'.'.self::getExtension($filename);
        Yii::app()->request->sendFile($filename, $content, $mimeType, false);
    }
    
    /**
     * 删除文件
     * @param string/array $filename 文件绝对路径
     * @param array $options 如果$options['upyun']=true，则同步删除又拍云中的文件
     * @author fengchuan
     * 
     */
    public function deleteFile($filename=null, $options=array())
    {
        if(null===$filename)
            $filename=$this->filename;
        if(is_string($filename))
            $filename=array($filename);
        foreach ($filename as $file)
        {
            if(file_exists($file))
            {
                unlink ($file);
                //删除upyun文件
                if(isset($options['upyun']) && false!==$options['upyun'])
                    $this->deleteUpYun($filename);
            }
        }
    }
    /**
	 * 
	 * 上传文件到又拍云（只传原文件）
	 * @param string $filename 文件的路径
     * @author fengchuan
	 */
    public function uploadUpYun($filename)
	{
        //本地切记不要配置upyun参数
        if(isset($this->params['upyun']) && !empty($this->params['upyun']))
        {
            $flag=strpos($filename, $this->rootFolder);
            if(false!==$flag)
            {//转为相对路径
                $filename=substr($filename, strlen($this->rootFolder)+1);
            }
            $upyun = new UpYun($this->params['upyun']['bucketname'], $this->params['upyun']['username'], $this->params['upyun']['password']);
            $fh=fopen($this->rootFolder.$filename, 'r');
            $rsp=$upyun->writeFile($filename, $fh, true);
            fclose($fh);
        }
	}
    /**
     * 删除又拍云文件
     * @param string $filename 文件的路径
     */
    private function deleteUpYun($filename)
    {
        //本地切记不要配置upyun参数
        if(isset($this->params['upyun']) && !empty($this->params['upyun']))
        {
            $flag=strpos($filename, $this->rootFolder);
            if(false!==$flag)
            {//转为相对路径
                $filename=substr($filename, strlen($this->rootFolder)+1);
            }
            $upyun = new UpYun($this->params['upyun']['bucketname'], $this->params['upyun']['username'], $this->params['upyun']['password']);
            if(is_file($filename) && file_exists($filename))
                $upyun->delete($filename);
        }
    }
    
    //以下方法待整理
    /**
	 * 
	 * 判断上传文件是否为图片
	 * @param CModel $model
	 * @param string $attribute
	 */
	protected function isImageFile($model,$attribute)
	{
		if(!$this->hasUploadFile($model,$attribute))
			return false;//没有上传文件
		$uploadFile=CUploadedFile::getInstance($model,$attribute);
		$ext=strtolower($uploadFile->getExtensionName());
		return in_array($ext, array('jpg', 'png', 'jpeg', 'gif'));
	}
	/**
	 * 
	 * 判断一批上传文件是否为图片
	 * @param unknown $model
	 * @param unknown $attribute
	 * @return boolean
	 */ 
	protected function isImageFiles($model,$attribute)
	{
		$uploadFiles=CUploadedFile::getInstances($model, $attribute);
		foreach ($uploadFiles as $uploadFile)
		{
			$ext=strtolower($uploadFile->getExtensionName());
			if(!in_array($ext, array('jpg', 'png', 'jpeg', 'gif')))
				return false;
		}
		return true;
	}
	/**
	 * 
	 * 判断上传文件是否为max文件
	 * @param CModel $model
	 * @param string $attribute
	 */
	protected function isMaxFile($model,$attribute)
	{
		$uploadFile=CUploadedFile::getInstance($model,$attribute);
		if(!$this->hasUploadFile($model,$attribute))
			return false;//没有上传文件
		$ext=strtolower($uploadFile->getExtensionName());
		return in_array($ext, array('max'));
	}
	/**
	 * 
	 * 判断上传文件是否为压缩文件
	 * @param CModel $model
	 * @param string $attribute
	 */
	protected function isCompressFile($model,$attribute)
	{
		$uploadFile=CUploadedFile::getInstance($model,$attribute);
		if(!$this->hasUploadFile($model,$attribute))
			return false;//没有上传文件
		$ext=strtolower($uploadFile->getExtensionName());
		return in_array($ext, array('zip', 'rar', '7z'));
	}
	/**
	 * 
	 * 判断是否有上传对应的文件
	 * @param CModel $model
	 * @param string $attribute
	 */
	public function hasUploadFile($model,$attribute)
	{
		$uploadFile=CUploadedFile::getInstance($model,$attribute);
		if(null===$uploadFile)//没有上传文件
			return false;
		return true;
	}
	/**
	 * 
	 * 判断是否有上传对应的文件
	 * @param CModel $model
	 * @param array $attributes
	 */
	protected function hasUploadFiles($model,$attributes)
	{
		foreach ($attributes as $attribute)
		{
			$hasUploadFile=$this->hasUploadFile($model, $attribute);
			if(!$hasUploadFile)
				return false;
		}
		return true;
	}
	/**
	 *
	 * 判断是否有上传对应的一批文件
	 * @param CModel $model
	 * @param string $attribute
	 */
	public function hasUploadBatchFiles($model,$attribute)
	{
		$uploadFiles=CUploadedFile::getInstances($model,$attribute);
        if(empty($uploadFiles)){
            return false;
        }else{
            return true;
        }
	}
	/**
	 *
	 * 判断是否有上传对应的多批文件
	 * @param CModel $model
	 * @param array $attributes
	 */
	protected function hasUploadBatchesFiles($model,$attributes)
	{
		foreach ($attributes as $attribute)
		{
			$hasUploadFile=$this->hasUploadBatchFiles($model, $attribute);
			if(!$hasUploadFile)
				return false;
		}
		return true;
	}
}
