<?php
	require "random_string.php";
	require "upload_resize.php";

	if(!empty($_POST['Submit'])){
		// เช็คว่าผู้ใช้อัพโหลดมาหรือไม่
	    if(!empty($_FILES['profile']['name']))
	    {
            $inputname = "profile";
            $maxfilesize = "6000000";
            $orgdirectory = "images/original"; // โฟลเดอร์ภาพต้นฉบับ
            $thumbdirectory = "images/thumbnail"; // โฟล์เดอร์ภาพย่อ
            $thumbwidth = "400";
            $thumbheight = "400";
            // Upload รูปเข้า folder
            $path = $_FILES[$inputname]['name'];

            $ext = pathinfo($path, PATHINFO_EXTENSION);

            if ($ext == "jpg" or $ext == "jpeg" or $ext == "png" or $ext == "gif") {
                $filename = time(). "." . pathinfo($_FILES[$inputname]['name'], PATHINFO_EXTENSION);
                $filesize = $_FILES[$inputname]['size'];
                $filetmp = $_FILES[$inputname]['tmp_name'];
                $filetype = $_FILES[$inputname]['type'];

                $upload = genius_uploadimg_with_org(
                	$inputname, 
			    	$filename, 
				    $filesize, 
				    $filetmp, 
				    $filetype, 
				    $maxfilesize, 
				    $orgdirectory, 
				    $thumbdirectory, 
				    $thumbwidth, 
				    $thumbheight);
            }else{
                echo "File type not allow";
                exit();
            }
	    }

	}


	// Read file from directory

	$img_dir = "images/thumbnail/";
	$file_list = array();

	if (is_dir($img_dir)){
	  if ($dh = opendir($img_dir)){
	    while (($file = readdir($dh)) !== false){
	      // echo "filename:" . $file . "<br>";
	      $file_list[] = $file;
	    }
	    closedir($dh);
	  }
	}

	//print_r($file_list);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Form Upload</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	
	<div class="jumbotron">
		<h1 class="display-4 text-center">Upload Image and Resize with PHP</h1>
	</div>

	<div class="container">
		<form action="form_upload.php" method="post" enctype="multipart/form-data">
		<div class="row">
			<div class="col-10">
				<div class="form-group">
					<input type="file" name="profile" class="form-control" placeholder="Please choose file">
				</div>
			</div>
			<div class="col-2 text-right">
				<input type="submit" name="Submit" value="Upload" class="btn btn-primary">
			</div>
		</div>
		</form>

		<div class="row">
			<?php
				foreach ($file_list as $img){
				if(strlen($img) >= 3){
			?>
				<div class="col-md-3">
				  	<img src="<?php echo "images/thumbnail/$img";?>" class="img-fluid" data-img="<?php echo "images/original/$img";?>">
				</div>
			<?php
				}
			}
			?>
		</div>

	</div>


	<div class="modal fade" id="imgmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-body">
	         <img class="img-fluid" src="" id="show-img"> 
	      </div>
	    </div>
	  </div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		
		$(document).ready(function(){
	        $("img").click(function(){
	           var img=$(this).attr('data-img');
	             $("#show-img").attr('src',img);
	             $("#imgmodal").modal('show');
	        });
    	});

	</script>
</body>
</html>