<?php


require_once "Connection.php";

class Logo{

private $refToMainDirectory;
private $conn;
private $thumbpath = 'thumb';
private $thumbPre = 't_';

    function __construct( $refToDirectory="") {
        $this->conn = New Connection();
        $this->refToMainDirectory = $refToDirectory;
    }

function showThumbs(){
//    https://stackoverflow.com/questions/9223593/display-image-without-showing-its-file-path
$sql = "SELECT I.imageID, I.path, I.filename, I.name, I.description, P.firstName, P.lastName FROM image as I LEFT JOIN person as P ON P.personID = I.creatorPersonID";
    $out = "";
	foreach( $this->conn->listMultiple( $sql ) AS $index=>$row ){
        $out .= "<p>";
        $out .= "<a href='?action=getImage&imageID=" . $row[0] . "'>";
        $out .= "<img src='" . $this->refToMainDirectory . $row[1] . "/" . $this->thumbpath . "/" .  $this->thumbPre . $row[2] . "'>";
        $out .= $row[3] . " " . $row[5] . " " . $row[6] ;
        $out .= "</a>";
        $out .= "</p>";
        }
    return $out;
}


function getLogo( $logoID = -1){
//    https://stackoverflow.com/questions/9223593/display-image-without-showing-its-file-path
$sql = "SELECT I.imageID, I.path, I.filename, I.filetype FROM image as I LEFT JOIN person as P ON P.personID = I.creatorPersonID WHERE I.imageID = " . $logoID;
	foreach( $this->conn->listMultiple( $sql ) AS $index=>$row ){
        $image = $this->refToMainDirectory . $row[1] . '/' . $row[2];
        $content = file_get_contents($image);
        $type = $row[3];
        if( 'JPEG' == $type){
            header('Content-Type: image/jpeg');
        } else {
            header('Content-Type: image/png');
        }
        echo $content; exit();
    	}
}

function makeThumbnails( $width, $height ){
//    https://stackoverflow.com/questions/9223593/display-image-without-showing-its-file-path
$sql = "SELECT I.imageID, I.path, I.filename, I.filetype FROM image as I   " ;
	foreach( $this->conn->listMultiple( $sql ) AS $index=>$row ){
	    $src = $this->refToMainDirectory . $row[1] . '/' . $row[2];
	    $dest = $this->refToMainDirectory . $row[1] . '/' . $this->thumbpath . '/' . $this->thumbPre .  $row[2];
	    $type = $row[3];
//        $this->make_thumb($src, $dest, $width, $type);
        $this->image_resize($src, $dest, $width, $height, 0);
    	}
}


function make_thumb($src, $dest, $desired_width, $type) {
//echo "src" . $src . "\n";
//echo "dest" . $dest . "\n";
//echo "type" . $type . "\n";

    /* read the source image */
    if( 'JPEG' == $type){
        $source_image = imagecreatefromjpeg($src);
    } else { // lazy !
        $source_image = imagecreatefrompng($src);
    }
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_height = floor($height * ($desired_width / $width));

    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    /* copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
//    header('Content-Type: image/png');

    /* create the physical thumbnail image to its destination */
    if( 'JPEG' == $type){
        imagejpeg($virtual_image, $dest);
    } else {
        imagepng($virtual_image, $dest, 9);
    }
}

// http://php.net/manual/en/function.imagecopyresampled.php
function image_resize($src, $dst, $width, $height, $crop=0){

  if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

  $type = strtolower(substr(strrchr($src,"."),1));
  if($type == 'jpeg') $type = 'jpg';
  switch($type){
    case 'bmp': $img = imagecreatefromwbmp($src); break;
    case 'gif': $img = imagecreatefromgif($src); break;
    case 'jpg': $img = imagecreatefromjpeg($src); break;
    case 'png': $img = imagecreatefrompng($src); break;
    default : return "Unsupported picture type!";
  }

  // resize
  if($crop){
    if($w < $width or $h < $height) return "Picture is too small!";
    $ratio = max($width/$w, $height/$h);
    $h = $height / $ratio;
    $x = ($w - $width / $ratio) / 2;
    $w = $width / $ratio;
  }
  else{
    if($w < $width and $h < $height) return "Picture is too small!";
    $ratio = min($width/$w, $height/$h);
    $width = $w * $ratio;
    $height = $h * $ratio;
    $x = 0;
  }

  $new = imagecreatetruecolor($width, $height);

  // preserve transparency
  if($type == "gif" or $type == "png"){
    imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
    imagealphablending($new, false);
    imagesavealpha($new, true);
  }

  imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

  switch($type){
    case 'bmp': imagewbmp($new, $dst); break;
    case 'gif': imagegif($new, $dst); break;
    case 'jpg': imagejpeg($new, $dst); break;
    case 'png': imagepng($new, $dst); break;
  }
  return true;
}



} // end class Logo
//$l = new Logo('../');
//$l->makeThumbnails(100,100);
//echo $l->showThumbs();
//$l->makeThumbnails(200);
//$l->getLogo(14);