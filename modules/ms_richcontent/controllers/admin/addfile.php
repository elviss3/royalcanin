<?php

require_once(dirname(__FILE__).'../../../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../../../init.php');


// $action = (Tools::getValue('action'));
 $file = (Tools::getValue('file'));
  $text = (Tools::getValue('text'));



$rand = rand(10,100);
$streplaceFileName = cleanSpecialCharacters($_FILES['file']['name']);
$zipFile = $rand."-".$streplaceFileName;$ds = DIRECTORY_SEPARATOR;
$storeFolder = '../../../../rich_content';

if((!empty($_FILES)) && !empty($_FILES['file']['name'])) {
if(preg_match('/[.](zip)$/', $_FILES['file']['name'])) {
        $filename = $rand . "-" . $streplaceFileName;
        $tempFile = $_FILES['file']['tmp_name'];
        $targetPath = $storeFolder . $ds;
        $targetFile = $targetPath.$filename;
        $check = move_uploaded_file($tempFile,$targetFile);
        if($check){
            $unzip = new ZipArchive;
            $out = $unzip->open($targetFile);   
            if ($out === TRUE) {
                $unzip->extractTo($storeFolder);
                $unzip->close();
                unlink($targetFile);


                $path = $storeFolder;
                $files = array();
                $dir = new DirectoryIterator($path);

                foreach ($dir as $fileinfo) {
                    $files[$fileinfo->getMTime()] = $fileinfo->getFilename();
                }

                krsort($files);

                $fill=array();
                foreach($files as $file){
                        if ($file != "index.php" && $file != "." && $file != ".." && $file != '__MACOSX'){
                                $fill[] = $file;
                        }
                }
                echo($fill[0]);
            //echo "ZipFile Uploaded Successfully!";
            }
        } 
    }
}

function cleanSpecialCharacters($string) {
    $string = str_replace(' ', '-', $string); 
    return preg_replace('/[^A-Za-z0-9.\-]/', '', $string); 
}



?>