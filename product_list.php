<?php include('header.php'); ?> 
<?php 
include 'product_functions.php';
?>
<script>
function validateSize(input) {
  const fileSize = input.files[0].size / 1024 / 1024; // in MiB
  if (fileSize > 2) {
	 // alert('File size exceeds 2 MiB');
    alert('The file must be less than 2MB'); window.open('product_list.php','_self');
	  return false;
    // $(file).val(''); //for clearing with Jquery
  } else {
    // Proceed further
  }
}</script>
<div class="main_content_iner ">
        <div class="container-fluid plr_30 body_white_bg pt_30">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="QA_section">
                        <div class="white_box_tittle list_header">
                            <h4>Product Table</h4>
                            <div class="box_right d-flex lms_block">
                                <div class="add_button ml-10">
                                    <a href="#" type="button" class="btn_1" data-toggle="modal" data-target="#exampleModalLong" >Add New Product</a>
                                    <!-- Button trigger modal -->
                                    <!-- Modal -->
                            <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Product</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <form action="" method="post" enctype="multipart/form-data" >
								<div class="row">
                             <div class="form-group col-md-6">
                              <label for="product_name">Product Name:</label>
                              <input type="text" class="form-control" id="product_name" name="product_name" required>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="product_price">Product price:</label>
                              <input type="text" class="form-control" id="product_price" name="product_price" required>
                            </div>
                                    <div class="form-group col-md-12">
                              <label for="product_quantity">Product Quantity:</label>
                              <input type="text" class="form-control" id="product_quantity" name="product_quantity" required>
                            </div>
                                    <div class="form-group col-md-12">
                              <label for="description">Description:</label>
                              <textarea type="text" class="form-control" id="description" name="description" required></textarea>
                            </div>
                          <div class="form-group col-md-12">
                          <label for="product_image"> Choose Product image:</label>
                         <input type="file" class="form-control-file" id="product_image" name="product_image" required accept="image/png, image/gif, image/jpeg, image/jpg"   onchange="validateSize(this)">
                         </div>
                                   
									</div>	
                          <button type="submit" class="btn btn-primary" name="create">Submit</button>
								
                       </form>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                             </div>
                            </div>
                        </div>

                        <div class="QA_table table-responsive ">
                            <!-- table-responsive -->
                            <table class="table lms_table_active ">
                                <thead>
                                    <tr>
                                        <th >#</th>
                                        <th >Product<br>Image</th>
                                        <th >Product<br>Name</th>
                                        <th >Product<br>Quantity</th>
                                        <th >Product<br>Price</th>
                                        <th >Description</th>
                                        <th >Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                 $result = $usr->get_all_users(); 
              while ($row = mysqli_fetch_array($result)){
				   $userid=$row['id'];
              ?>
                                    <tr>
                                        <th><?= $row['id'] ?></th>
                                      <td> <img src="product_details/<?= $row['product_image'] ?>"style="width:50px;
                                        height:50px; border-radius:12%;">  </td>
                                        <td><?= $row['product_name'] ?></td>
                                        <td><?= $row['product_quantity'] ?></td>
                                        <td><?= $row['product_price'] ?></td>
                                        <td><?= $row['description'] ?></td>
                                      <td>
                                         
                                          
                                         <a href="" class="btn btn-primary  " data-toggle="modal" data-target="#update_modal<?php echo $row['id']?>" >Update</a>
                                          
                                          <a href="" class="btn btn-success  " data-toggle="modal" data-target="#view_modal<?php echo $row['id']?>" >View</a>
                                       
                                          
                                        <a href="product_list.php?delete=<?= $row['id'] ?>" class="btn btn-danger ">Delete</a>
                                          
                                      </td>
                                        
                                    </tr>
                                    <div class="modal fade" id="update_modal<?php echo $row['id']?>" aria-hidden="true">
                                          <div class="modal-dialog">
                                            <div class="modal-content">
                                              <form method="POST" action="" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                  <h3 class="modal-title">Update Product </h3>
                                                </div>
                                                <div class="modal-body">
                                                  <div class="col-md-2"></div>
                                                  <div class="col-md-8">
                                                    <div class="form-group">
                                                      <label>Product Name :</label>
                                                      <input type="hidden" name="id" value="<?php echo $row['id']?>"/>
                                                      <input type="text" name="product_name" value="<?php echo $row['product_name']?>" class="form-control" />
                                                    </div>
                                                    <div class="form-group">
                                                      <label>Product Quantity :</label>
                                                      <input type="text" name="product_quantity" value="<?php echo $row['product_quantity']?>" class="form-control"  />
                                                    </div>
                                                    <div class="form-group">
                                                      <label>Product Price :</label>
                                                      <input type="text" name="product_price" value="<?php echo $row['product_price']?>" class="form-control" />
                                                    </div>
                                                  <div class="form-group">
                                                      <label>Description :</label>
                                                      <input type="text" name="description" value="<?php echo $row['description']?>" class="form-control" />
                                                    </div>
                                                      <div class="form-group">
                                                      <label>Update Product Image :</label>
                                                         <input type="file" class="form-control-file" id="product_image" name="product_image"  accept="image/png, image/gif, image/jpeg, image/jpg"  onchange="validateSize(this)">
                                                          
                                                        
                                                    </div>
                                                      <div class="form-group">
                                                      <button  type="submit" class="btn btn-primary" name="update"><span class="glyphicon glyphicon-edit" ></span> Update</button>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div style="clear:both;"></div>
                                                <div class="modal-footer">
                                                  <button class="btn btn-danger" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
                                                  
                                                </div>
                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                     
                                    
                                    
                                    
                                </tbody>
                                <div class="modal fade" id="view_modal<?php echo $row['id']?>" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <form method="POST" action="update_query.php">
                                        <div class="modal-header">
                                          <h3 class="modal-title">View Product Details</h3>
                                        </div>
                                        <div class="modal-body">
                                          <div class="col-md-2"></div>
                                          <div class="col-md-8">
                                            <div class="form-group">
                                              <label>Product Name :</label>
                                              <input type="hidden" name="user_id" value="<?php echo $row['id']?>"/>
                                              <input type="text" name="firstname" value="<?php echo $row['product_name']?>" class="form-control" readonly/>
                                            </div>
                                            <div class="form-group">
                                              <label>Product Price :</label>
                                              <input type="text" name="lastname" value="<?php echo $row['product_price']?>" class="form-control" readonly />
                                            </div>
                                            <div class="form-group">
                                              <label>Product Quantity :</label>
                                              <input type="text" name="lastname" value="<?php echo $row['product_quantity']?>" class="form-control" readonly />
                                            </div>
                                            <div class="form-group">
                                              <label>Description :</label>
                                              <input type="text" name="lastname" value="<?php echo $row['description']?>" class="form-control" readonly />
                                            </div>
                                            <div class="form-group">
                                              <label>Product Image :</label><br>
                                              <img src="product_details/<?= $row['product_image'] ?>"style="width:200px;
                                        height:200px; border-radius:5%;">
                                            </div>
                                          </div>
                                        </div>
                                        <div style="clear:both;"></div>
                                        <div class="modal-footer">
                                          
                                          <button class="btn btn-danger" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
                                        </div>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
<?php } ?>
                            </table>
                            
                        </div>
                     
            </div>
        </div>
    </div>

<?php include('footer.php'); ?>