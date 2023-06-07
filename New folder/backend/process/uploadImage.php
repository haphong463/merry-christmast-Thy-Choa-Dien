<?php
//xu ly file image
//xử lý gom dữ liệu vào từng file đã upload
$uploadedImages = $_FILES['image'];

function uploadImages($uploadedImages, $takeid)
{
    $files = array();
    $errors = array();

    foreach ($uploadedImages as $key => $values) {
        foreach ($values as $index => $value) {
            $files[$index][$key] = $value;
        }
    }

    $uploadPath = "../../image/product/";
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath);
    }
    $uploadPathThumb = "../../image/product/thumbnail/";
    if (!is_dir($uploadPathThumb)) {
        mkdir($uploadPathThumb);
    }
    foreach ($files as $file) {
        $file = validateUploadFile($file, $uploadPath);
        if ($file != false) {
            //up file vật lý
            move_uploaded_file($file["tmp_name"], $uploadPath . '/' . $file['name']);
            //lưu đường dẫn vào db
            $image = 'image/product/'  . $file['name'] . '';
            $sql = 'insert into product_image(pid, image_path) values (' . $takeid . ',"' . $image . '")';
            execute($sql);
            //tạo thumbnail
            $thumbnail = "../../$image";
            $resize = new ResizeImage($thumbnail);
            $resize->resizeTo(264, 363, 'exact');
            //up file vật lý thumnail
            $resize->saveImage($uploadPathThumb . '/' . $file['name']);
            //luu đường dẫn vào db
            $thumbnail = 'image/product/thumbnail/' . $file['name'] . '';
            $sql = 'insert into product_thumbnail(pid, thumbnail) values (' . $takeid . ',"' . $thumbnail . '")';
            execute($sql);
        } else {
            $errors[] = " The file " . basename($file["name"]) . "isn't valid";
        }
    }
    return $errors;
}
$errors = uploadImages($uploadedImages, $takeid);
if (!empty($errors)) {
    print_r($errors);
}


//check file hop le
function validateUploadFile($file, $uploadPath)
{
    //kiem tra xem co vuot qua dung luong cho phep hay khong
    if ($file['size'] > 2 * 1024 * 1024) { // max upload = 2mb
        return false;
    }

    //kiem tra xem kieu file co hop le hay khong
    $validTypes = array("jpg", "jpeg", "png", "bmp");
    $fileType = substr($file['name'], strrpos($file['name'], ".") + 1);
    if (!in_array($fileType, $validTypes)) {
        return false;
    }

    //check xem file da ton tai chua, neu ton tai thi doi ten
    $num = 1;
    $fileName = substr($file['name'], 0, strrpos($file['name'], "."));
    while (file_exists($uploadPath . '/' . $fileName . "." . $fileType)) {
        $fileName = $fileName . "(" . $num . ")";
        $num++;
    }
    $file['name'] = $fileName . '.' . $fileType;
    return $file;
}

//tạo thumbnail
class ResizeImage
{
    private $ext;
    private $image;
    private $newImage;
    private $origWidth;
    private $origHeight;
    private $resizeWidth;
    private $resizeHeight;

    public function __construct($filename)
    {
        $this->setImage($filename);
    }
    private function setImage($filename)
    {
        $size = getimagesize($filename);
        $this->ext = $size['mime'];
        switch ($this->ext) {
                // Image is a JPG
            case 'image/jpg':
            case 'image/jpeg':
                // create a jpeg extension
                $this->image = imagecreatefromjpeg($filename);
                break;
                // Image is a GIF
            case 'image/gif':
                $this->image = @imagecreatefromgif($filename);
                break;
                // Image is a PNG
            case 'image/png':
                $this->image = @imagecreatefrompng($filename);
                break;
                // Mime type not found
            default:
                throw new Exception("File is not an image, please use another file type.", 1);
        }
        $this->origWidth = imagesx($this->image);
        $this->origHeight = imagesy($this->image);
    }
    /**
     * Save the image as the image type the original image was
     *
     */
    public function saveImage($savePath, $imageQuality = "100", $download = false)
    {
        switch ($this->ext) {
            case 'image/jpg':
            case 'image/jpeg':
                // Check PHP supports this file type
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->newImage, $savePath, $imageQuality);
                }
                break;
            case 'image/gif':
                // Check PHP supports this file type
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->newImage, $savePath);
                }
                break;
            case 'image/png':
                $invertScaleQuality = 9 - round(($imageQuality / 100) * 9);
                // Check PHP supports this file type
                if (imagetypes() & IMG_PNG) {
                    imagepng($this->newImage, $savePath, $invertScaleQuality);
                }
                break;
        }
        if ($download) {
            header('Content-Description: File Transfer');
            header("Content-type: application/octet-stream");
            header("Content-disposition: attachment; filename= " . $savePath . "");
            readfile($savePath);
        }
        imagedestroy($this->newImage);
    }
    /**
     * Resize the image to these set dimensions
     *
     */
    public function resizeTo($width, $height, $resizeOption = 'default')
    {
        switch (strtolower($resizeOption)) {
            case 'exact':
                $this->resizeWidth = $width;
                $this->resizeHeight = $height;
                break;
            case 'maxwidth':
                $this->resizeWidth  = $width;
                $this->resizeHeight = $this->resizeHeightByWidth($width);
                break;
            case 'maxheight':
                $this->resizeWidth  = $this->resizeWidthByHeight($height);
                $this->resizeHeight = $height;
                break;
            default:
                if ($this->origWidth > $width || $this->origHeight > $height) {
                    if ($this->origWidth > $this->origHeight) {
                        $this->resizeHeight = $this->resizeHeightByWidth($width);
                        $this->resizeWidth  = $width;
                    } else if ($this->origWidth < $this->origHeight) {
                        $this->resizeWidth  = $this->resizeWidthByHeight($height);
                        $this->resizeHeight = $height;
                    }
                } else {
                    $this->resizeWidth = $width;
                    $this->resizeHeight = $height;
                }
                break;
        }
        $this->newImage = imagecreatetruecolor($this->resizeWidth, $this->resizeHeight);
        imagecopyresampled($this->newImage, $this->image, 0, 0, 0, 0, $this->resizeWidth, $this->resizeHeight, $this->origWidth, $this->origHeight);
    }
    /**
     * Get the resized height from the width keeping the aspect ratio
     *
     * @param  int $width - Max image width
     *
     * @return Height keeping aspect ratio
     */
    private function resizeHeightByWidth($width)
    {
        return floor(($this->origHeight / $this->origWidth) * $width);
    }
    /**
     * Get the resized width from the height keeping the aspect ratio
     *
     * @param  int $height - Max image height
     *
     * @return Width keeping aspect ratio
     */
    private function resizeWidthByHeight($height)
    {
        return floor(($this->origWidth / $this->origHeight) * $height);
    }
}


// lay toan bo anh
function getAllFiles()
{
    $allFiles = array();
    $allDirs = glob('image/*');
    foreach ($allDirs as $dir) {
        $allFiles = array_merge($allFiles, glob($dir . "/*"));
    }
    return $allFiles;
}
