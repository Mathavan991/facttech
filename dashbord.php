<?php include('header.php'); ?>

 <div class="main_content_iner ">
        <div class="container-fluid plr_30 body_white_bg pt_30">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="single_element">
                        <div class="quick_activity">
                            <div class="row">
                                <div class="col-12">
                                    <div class="quick_activity_wrap">
                                        <div class="single_quick_activity">
                                            <h4>Total Product</h4>
                                            <?php
										include('db_config.php');
										 //$conn = mysqli_connect("localhost", "root", "", "version");
                                          $sql = "SELECT * FROM product_details";
                                          $result = mysqli_query($conn,$sql);
					                       $row = mysqli_num_rows($result);
										 ?>
                                      <h3> <span class="counter"><?php echo $row; ?></span> </h3>
                                            
                                        </div>
                                        <div class="single_quick_activity">
                                            <?php
                                            foreach($conn->query('SELECT SUM(order_total_after_tax) FROM inv_order') as $row) {
								            ?>
                                           <h4>Total Cost</h4>
                                            <h3>$ <span class="counter"><?php echo $row['SUM(order_total_after_tax)'];}?></span> </h3>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="card-body">
                         <div class="container">		
	  <h2 class="title mt-5"> Last Billing</h2>
	 		  
      <table id="data-table" class="table table-borderless">
        <thead>
          <tr>
            <th>Invoice No.</th>
            <th>Customer Name</th>
            <th>Create Date</th>
            <th>Total</th>
            
            
            
          </tr>
        </thead>
        <?php  
              $sql =  "SELECT  * FROM inv_order ORDER BY order_id DESC LIMIT 3;";
                      $result = mysqli_query($conn,$sql);
						$i=0;
				      while($fetch = mysqli_fetch_array($result)){
		            ?>
					<tbody class="">
                        <tr>
						     <td><?php echo $fetch['order_id']; ?></td>
						     <td><?php echo $fetch['order_receiver_name']; ?></td>
							<td><?php echo  $fetch['order_date']; ?></td>
                            <td><?php echo  "₹" .$fetch['order_total_after_tax'] . "<br>";?></td>
							
                        <?php
			                 $i++;
	                        }
	                         ?>
                            
							</tr>
					     </tbody>
                         </table>	
                      </div>	
                    </div>
                
            </div>
        </div>
    </div>
<?php include('footer.php'); ?>

