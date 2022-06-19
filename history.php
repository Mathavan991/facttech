<?php include('header.php'); ?>
<?php 
include 'product_functions.php';
?>
 
     <div class="card-body">
         <div class="container">
             
	  <h2 class="title mt-5"> History</h2>
             <hr>
             <form action=""  method="POST">
              <label for="bdaymonth">From:</label>
                <input type="date" class="" placeholder="Start"  name="date1" value="<?php echo isset($_POST['date1']) ? $_POST['date1'] : '' ?>" required>
                 <label for="bdaymonth">To:</label>
                <input type="date" class="" placeholder="End"  name="date2" value="<?php echo isset($_POST['date2']) ? $_POST['date2'] : '' ?>" required>
              <button class="btn btn-primary " name="search"><span class="search_field">Search</span></button> 
                 <a href="history.php" type="button" class="btn btn-success"><span class = "glyphicon glyphicon-refresh"></span>Refresh</a>
            </form>
	     <hr>	
      <table id="data-table" class="table table-borderless">
        <thead>
          <tr>
            <th>Invoice No.</th>
            <th>Customer Name</th>
			 <th>Customer Address</th> 
            <th>Create Date</th>
            <th>Total</th>
            
            
            
          </tr>
        </thead>
       
					<tbody class="">
                        <?php include'range.php'?>
                        
					     </tbody>
                         </table>	
                      </div>	
                    </div>
                              
<?php include('footer.php'); ?>