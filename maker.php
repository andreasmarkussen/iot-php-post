<?php

$file_content = "file \n";
$subject  = '-';
$AttachmentPublicURL;
/**
* Builds a file path with the appropriate directory separator.
* @param string $segments,... unlimited number of path segments
* @return string Path
*/
function file_build_path($a,$b) {
    return $a.DIRECTORY_SEPARATOR.$b;
}
// function file_build_path($segments) {
//     return join(DIRECTORY_SEPARATOR, $segments);
// }

function pl($message){
    global $file_content;
    echo '<br/>'.$message;
    $file_content .= "\n" . $message; 

}

pl(var_export($_SERVER,true));
$uploaddir = 'uploads';
@mkdir($uploaddir);
//$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

$time = time();

if( ($post_fields_count=count($_POST)) > 0 ){
    pl("This is a post with $post_fields_count elements");
    pl(var_export($_POST,true));
    if(array_key_exists('subject',$_POST))   {
        $subject = '_lc_'.$_POST['subject'];
    }
    elseif(array_key_exists('Subject',$_POST))  { 
        $subject = '_uc_'.$_POST['Subject'];
    }
    else{
        $subject = implode('-',array_keys($_POST));
    }

    if(array_key_exists('AttachmentPublicURL',$_POST)) {
        // http://www.phpliveregex.com/
        $output_array = "";
        preg_match("/.*\/([^?]*)?/", $AttachmentPublicURL, $output_array);
        $url_base_name = $output_array[1];
        $AttachmentPublicURL = $_POST['AttachmentPublicURL'];
        exec("wget -N $AttachmentPublicURL -P uploads ");
        $output_path = 'uploads'.$ds.$url_base_name;
        exec("wget -N $AttachmentPublicURL -O $output_path ");
        pl("wget $AttachmentPublicURL ");
        pl("output path = $output_path ");
    } 

    if (isset($_FILES)) {
        $file_count = sizeof($_FILES);
        $file_fields = array_keys($_FILES);
        if ($file_count == 1) {
            $first_key = array_pop($file_fields);
            pl("OK - Files received - form key - '$first_key' ");
            $ar_file = $_FILES[$first_key];
            pl("  File name is " . $ar_file['name']);
            $uploadfile = file_build_path($uploaddir,basename($ar_file['name']));
            pl(" uplaod file = $uploadfile ");
            if (move_uploaded_file($ar_file['tmp_name'], $uploadfile)) {
                pl( "File is valid, and was successfully uploaded.\n");
            } else {
                pl("Possible file upload attack!\n");
            }
        }
        elseif($file_count == 0){
            pl("Hmm - No files attached?");
        }
        else{
            pl("Hmm - more files attached $file_count ");
            pl(var_export($_FILES));
        }
    
    $uploadOk = 0;
    
    }
    else{
        pl("Files array is empty nor not set");
    }

}
else{
    ?>
    <form method='POST' enctype="multipart/form-data" >
        submit somthing<br/>
        <input name="subject" value='TestValue' >TestSubject</input><br/>
        <input type="file" name="upfil" /><br/>
        <input type='input' name='AttachmentPublicURL' value='http://www.dr.dk/Forside/drdk/DR.Frontpage/Content/img/ultra-button_small.png?sharingkey=1234'/><br/>
        <button name="submit" type="submit"   >submit</button> 
    </form>
    <?php 


}
@mkdir('logs');
$subject.="_unspecified_";
$ds = DIRECTORY_SEPARATOR;
$base_name = "logs".$ds."log_maker_php_$subject-";
$file_name = $base_name.".txt";
pl("Log File name to save $file_name ");
file_put_contents($file_name,$file_content);
file_put_contents($base_name.'_latest.html',$file_content);
file_put_contents($base_name.'_latest.txt',$file_content);
phpinfo();
shell_exec('rm log*.html');
shell_exec('rm log'.$ds.'*.html');