<?php 
include('extension/connect.php');
//where the files are
$downloads_folder = '../_uploads/';

//has a file name been passed?
if(!empty($_GET['file'])){
	//protect from people getting other files
	$file = basename($_GET['file']);

	//does the file exist?
	if(file_exists($downloads_folder.$file)){

		//update counter - add if dont exist
		$update = mysqli_query($con,"update application SET download_count=download_count+1 WHERE filename='$file'");

		//set force download headers
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$file.'"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: Keep-Alive');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . sprintf("%u", filesize($downloads_folder.$file)));

		//open and output file contents
		$fh = fopen($downloads_folder.$file, "rb");
		while (!feof($fh)) {
			echo fgets($fh);
			flush();
		}
		fclose($fh);
		exit;
	}else{
		header("location:login");
		exit('File not found!');
	}
}else{
	exit(header("Location: ./index.php"));
}
?>