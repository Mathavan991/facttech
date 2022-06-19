<?php include('header.php'); ?>
    <div class="main_content_iner ">
        <div class="container-fluid plr_30 body_white_bg pt_30">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="white_box mb_30">
                        <div class="box_header ">
                            <div class="main-title">
                                <h3 class="mb-0" >My Account</h3>
                            </div>
                        </div>
                        <div >
                            <br><br>
                            <?php
                        include('db_config.php');	
                       $sql = "SELECT * FROM tb_user WHERE username='$login_session'";
					  $result = mysqli_query($conn,$sql);
					 if($fetch = mysqli_fetch_array($result)){	?>
                             <form action="" method="post">
                            <div class="form-group">
                              <label for="firstname">Frist Name :</label>
                              <input type="text" class="form-control" id="firstname" name="fristname" required value="<?php echo $fetch['fristname']; ?>" placeholder="First Name">
                            </div>
                            <div class="form-group">
                              <label for="lastname">Last Name :</label>
                              <input type="text" class="form-control" id="lastname" name="lastname" required value="<?php echo $fetch['lastname']; ?>" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                              <label for="email">Email address</label>
                              <input type="email" class="form-control" id="email" name="email" required value="<?php echo $login_session; ?>" readonly placeholder="Email">
                            </div>
                            <div class="form-group">
                              <label for="phonenumber">Phone Number :</label>
                              <input type="text" class="form-control" id="phonenumber" name="phonenumber" maxlength="10" required value="<?php echo $fetch['phonenumber']; }?>" placeholder="Phone Number">
                            </div>     
                            
                            <button type="submit" value="Save" name="save" class="btn_1 full_width text-center"> Save</button>     
                          </form>
                           <?php 
                            include('db_config.php');							
                                  extract($_POST);
                                if (isset($_POST['save'])){
                               // echo "<script>alert('$login_session');</script>";
                                  $fristname = $_POST['fristname'];
                                  $lastname = $_POST['lastname'];
                                  $email = $_POST['email'];
                                  $phonenumber = $_POST['phonenumber'];
                                  
                                    //echo "<script>alert('$address');</script>";
                $sql = "UPDATE tb_user SET fristname='$fristname', lastname='$lastname', email='$email',                 phonenumber='$phonenumber'   WHERE  username= '$login_session'";
                                    
                                  //echo "<script>alert('$address');</script>"; 
                                 if (mysqli_query($conn, $sql)) {
                                   ?>
                                  <script>
                                    swal({
                                      title: 'Profile Upload successfully!',
                                      type: 'success'
                                    }).then(function() {
                                        window.location.href = "profilebox.php";
                                    });
                            </script>

                            <?php
                                 }
                                    else{
                                        //echo "Not Saved";
                                      //echo $login_session;

                                    ?>
                                          <script>
                                           swal({
                                          title: 'Profile Upload Failed',
                                          type: 'warning'
                                        }).then(function() {
                                            window.location.href = "profilebox.php";
                                        });
                                      </script>
                                          <?php

                                }	
                                mysqli_close($conn); 
                                    
                            }
?> 
                        </div>
                    </div>
                </div>
                
                
                <div class="col-md-6">
                    <div class="white_box mb_30">
                        <div class="box_header ">
                            <div class="main-title">
                                <h3 class="mb-0" >profile image</h3>
                            </div>
                        </div>
                        <div class="">
                            <?php 
						include('db_config.php');	
                     
                      $sql = "SELECT * FROM profile_images ORDER BY id DESC limit 1 ";
                      $result = mysqli_query($conn,$sql);
                      if($fetch = mysqli_fetch_array($result)){
                      $image=$fetch['profile_image'];
					  $image='<img class="rounded " src="data:image/jpeg;base64,' . base64_encode($image).'" width="300" height="300"/>'; 
                       
					     echo $image;
					  }
                                else{ ?>
                          <p>Add Your Profile image...!</p>
                      <?php } ?> 
                            
                            
                        </div>
                            <form action="" method="post" enctype="multipart/form-data">
                           <div class="form-group">
                              <div class="crm-profile-img-edit position-relative">
						      </div>
							  <br>
							   <input class="file" type="file" name="image"><br>
                             <div class="img-extension mt-3">
                              <button type="submit" class="btn btn-primary" name="upload">upload</button>
                           </div>
                           </div>
						 	</form>
                            <?php 
                                    $status = $statusMsg = ''; 
                                        if(isset($_POST["upload"])){ 
                                            $status = 'error'; 
                                            if(!empty($_FILES["image"]["name"])) { 
                                                // Get file info 
                                                $fileName = basename($_FILES["image"]["name"]); 
                                                $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 

                                                // Allow certain file formats 
                                                $allowTypes = array('jpg','png','jpeg','gif'); 
                                                if(in_array($fileType, $allowTypes)){ 
                                                    $image = $_FILES['image']['tmp_name']; 
                                                    $imgContent = addslashes(file_get_contents($image)); 

                                                    // Insert image content into database 
                                        $insert = $conn->query("INSERT into profile_images (Profile_image, uploaded) VALUES ('$imgContent', NOW())"); 

                                                    if($insert){ 
                                                        //$status = 'success'; 
                                                       // $statusMsg = "File uploaded successfully."; 
                                                        //header("location: image_success_page.php");
                                                        ?>
                                              <script>
                                                swal({
                                                  title: 'Image Upload successfully',
                                                  type: 'success'
                                                }).then(function() {
                                                    window.location.href = "profilebox.php";
                                                });
                                        </script>

                                        <?php

                                                    }else{ 
                                                       ?>
                                              <script>
                                                swal({
                                                  title: 'File upload failed, please try again.',
                                                  type: 'info'
                                                }).then(function() {
                                                    window.location.href = "profilebox.php";
                                                });
                                        </script>

                                        <?php
                                                    }  
                                                }else{ 
                                                    ?>
                                              <script>
                                                swal({
                                                  title: 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.',
                                                  type: 'warning'
                                                }).then(function() {
                                                    window.location.href = "profilebox.php";
                                                });
                                        </script>

                                        <?php
                                                } 
                                            }else{ 
                                                ?>
                                              <script>
                                                swal({
                                                  title: 'Please select an image file to upload.',
                                                  type: 'warning'
                                                }).then(function() {
                                                    window.location.href = "profilebox.php";
                                                });
                                        </script>

                                        <?php
                                            } 
                                        } 

                                        // Display status message 
                                        echo $statusMsg;


                                          ?>
                    </div>
                </div>
                
                
                
            </div>
          </div>
<?php include('footer.php'); ?>
