
<?php
try{
    include('db_config.php');
  $statement = $conn1->prepare("SELECT * FROM inv_order ORDER BY order_id DESC");

  $statement->execute();

  $all_result = $statement->fetchAll();

  $total_rows = $statement->rowCount();

  if(isset($_POST["create_invoice"]))
  { 
      
    $order_total_before_tax = 0;
    $order_total_tax1 = 0;
    $order_total_tax2 = 0;
    $order_total_tax3 = 0;
    $order_total_tax = 0;
    $order_total_after_tax = 0;
      $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $statement = $conn1->prepare("INSERT INTO inv_order (order_no, order_date, order_receiver_name, order_receiver_address, order_total_before_tax, order_total_tax1,  order_total_tax, order_total_after_tax, order_datetime) VALUES (:order_no, :order_date, :order_receiver_name, :order_receiver_address, :order_total_before_tax, :order_total_tax1,  :order_total_tax, :order_total_after_tax, :order_datetime)");
      
    $statement->execute(
      array(
          ':order_no'               =>  trim($_POST["order_no"]),
          ':order_date'             =>  trim($_POST["order_date"]),
          ':order_receiver_name'          =>  trim($_POST["order_receiver_name"]),
          ':order_receiver_address'       =>  trim($_POST["order_receiver_address"]),
          ':order_total_before_tax'       =>  $order_total_before_tax,
          ':order_total_tax1'           =>  $order_total_tax1,
//          ':order_total_tax2'           =>  $order_total_tax2,
//          ':order_total_tax3'           =>  $order_total_tax3,
          ':order_total_tax'            =>  $order_total_tax,
          ':order_total_after_tax'        =>  $order_total_after_tax,
          ':order_datetime'           =>  time()
      )
    );
      
      $statement = $conn1->query("SELECT LAST_INSERT_ID()");
      $order_id = $statement->fetchColumn();

      for($count=0; $count<$_POST["total_item"]; $count++)
      {
        $order_total_before_tax = $order_total_before_tax + floatval(trim($_POST["order_item_actual_amount"][$count]));

        $order_total_tax1 = $order_total_tax1 + floatval(trim($_POST["order_item_tax1_amount"][$count]));

//        $order_total_tax2 = $order_total_tax2 + floatval(trim($_POST["order_item_tax2_amount"][$count]));
//
//        $order_total_tax3 = $order_total_tax3 + floatval(trim($_POST["order_item_tax3_amount"][$count]));

        $order_total_after_tax = $order_total_after_tax + floatval(trim($_POST["order_item_final_amount"][$count]));

        $statement = $conn1->prepare("
          INSERT INTO inv_order_item 
          (order_id, item_name, order_item_quantity, order_item_price, order_item_actual_amount, order_item_tax1_rate, order_item_tax1_amount,  order_item_final_amount)
          VALUES (:order_id, :item_name, :order_item_quantity, :order_item_price, :order_item_actual_amount, :order_item_tax1_rate, :order_item_tax1_amount,  :order_item_final_amount)
        ");

        $statement->execute(
          array(
            ':order_id'               =>  $order_id,
            ':item_name'              =>  trim($_POST["item_name"][$count]),
            ':order_item_quantity'          =>  trim($_POST["order_item_quantity"][$count]),
            ':order_item_price'           =>  trim($_POST["order_item_price"][$count]),
            ':order_item_actual_amount'       =>  trim($_POST["order_item_actual_amount"][$count]),
            ':order_item_tax1_rate'         =>  trim($_POST["order_item_tax1_rate"][$count]),
            ':order_item_tax1_amount'       =>  trim($_POST["order_item_tax1_amount"][$count]),
//            ':order_item_tax2_rate'         =>  trim($_POST["order_item_tax2_rate"][$count]),
//            ':order_item_tax2_amount'       =>  trim($_POST["order_item_tax2_amount"][$count]),
//            ':order_item_tax3_rate'         =>  trim($_POST["order_item_tax3_rate"][$count]),
//            ':order_item_tax3_amount'       =>  trim($_POST["order_item_tax3_amount"][$count]),
            ':order_item_final_amount'        =>  trim($_POST["order_item_final_amount"][$count])
          )
        );
      }
      $order_total_tax = $order_total_tax1 + $order_total_tax2 + $order_total_tax3;

      $statement = $conn1->prepare("
        UPDATE inv_order 
        SET order_total_before_tax = :order_total_before_tax, 
        order_total_tax1 = :order_total_tax1, 

        order_total_tax = :order_total_tax, 
        order_total_after_tax = :order_total_after_tax 
        WHERE order_id = :order_id 
      ");
      $statement->execute(
        array(
          ':order_total_before_tax'     =>  $order_total_before_tax,
          ':order_total_tax1'         =>  $order_total_tax1,
//          ':order_total_tax2'         =>  $order_total_tax2,
//          ':order_total_tax3'         =>  $order_total_tax3,
          ':order_total_tax'          =>  $order_total_tax,
          ':order_total_after_tax'      =>  $order_total_after_tax,
          ':order_id'             =>  $order_id
        )
      );
      ?>
    <script>
		swal({
          title: ' Billing successfully',
          
          type: 'success'
        }).then(function() {
            window.location.href = "add_invoice.php";
        })
</script><?php
  }
}catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
  if(isset($_POST["update_invoice"]))
  {
    $order_total_before_tax = 0;
      $order_total_tax1 = 0;
      $order_total_tax2 = 0;
      $order_total_tax3 = 0;
      $order_total_tax = 0;
      $order_total_after_tax = 0;
      
      $order_id = $_POST["order_id"];
      
      
      
      $statement = $conn1->prepare("
                DELETE FROM inv_order_item WHERE order_id = :order_id
            ");
            $statement->execute(
                array(
                    ':order_id'       =>      $order_id
                )
            );
      
      for($count=0; $count<$_POST["total_item"]; $count++)
      {
        $order_total_before_tax = $order_total_before_tax + floatval(trim($_POST["order_item_actual_amount"][$count]));
        $order_total_tax1 = $order_total_tax1 + floatval(trim($_POST["order_item_tax1_amount"][$count]));
        $order_total_tax2 = $order_total_tax2 + floatval(trim($_POST["order_item_tax2_amount"][$count]));
        $order_total_tax3 = $order_total_tax3 + floatval(trim($_POST["order_item_tax3_amount"][$count]));
        $order_total_after_tax = $order_total_after_tax + floatval(trim($_POST["order_item_final_amount"][$count]));
        $statement = $conn1->prepare("
          INSERT INTO inv_order_item 
          (order_id, item_name, order_item_quantity, order_item_price, order_item_actual_amount, order_item_tax1_rate, order_item_tax1_amount, order_item_tax2_rate, order_item_tax2_amount, order_item_tax3_rate, order_item_tax3_amount, order_item_final_amount) 
          VALUES (:order_id, :item_name, :order_item_quantity, :order_item_price, :order_item_actual_amount, :order_item_tax1_rate, :order_item_tax1_amount, :order_item_tax2_rate, :order_item_tax2_amount, :order_item_tax3_rate, :order_item_tax3_amount, :order_item_final_amount)
        ");
        $statement->execute(
          array(
            ':order_id'                 =>  $order_id,
            ':item_name'                =>  trim($_POST["item_name"][$count]),
            ':order_item_quantity'          =>  trim($_POST["order_item_quantity"][$count]),
            ':order_item_price'            =>  trim($_POST["order_item_price"][$count]),
            ':order_item_actual_amount'     =>  trim($_POST["order_item_actual_amount"][$count]),
            ':order_item_tax1_rate'         =>  trim($_POST["order_item_tax1_rate"][$count]),
            ':order_item_tax1_amount'       =>  trim($_POST["order_item_tax1_amount"][$count]),
            ':order_item_tax2_rate'         =>  trim($_POST["order_item_tax2_rate"][$count]),
            ':order_item_tax2_amount'       =>  trim($_POST["order_item_tax2_amount"][$count]),
            ':order_item_tax3_rate'         =>  trim($_POST["order_item_tax3_rate"][$count]),
            ':order_item_tax3_amount'       =>  trim($_POST["order_item_tax3_amount"][$count]),
            ':order_item_final_amount'      =>  trim($_POST["order_item_final_amount"][$count])
          )
        );
        $result = $statement->fetchAll();
      }
      $order_total_tax = $order_total_tax1 + $order_total_tax2 + $order_total_tax3;
      
      $statement = $conn1->prepare("
        UPDATE inv_order 
        SET order_no = :order_no, 
        order_date = :order_date, 
        order_receiver_name = :order_receiver_name, 
        order_receiver_address = :order_receiver_address, 
        order_total_before_tax = :order_total_before_tax, 
        order_total_tax1 = :order_total_tax1, 
        order_total_tax2 = :order_total_tax2, 
        order_total_tax3 = :order_total_tax3, 
        order_total_tax = :order_total_tax, 
        order_total_after_tax = :order_total_after_tax 
        WHERE order_id = :order_id 
      ");
      
      $statement->execute(
        array(
          ':order_no'               =>  trim($_POST["order_no"]),
          ':order_date'             =>  trim($_POST["order_date"]),
          ':order_receiver_name'        =>  trim($_POST["order_receiver_name"]),
          ':order_receiver_address'     =>  trim($_POST["order_receiver_address"]),
          ':order_total_before_tax'     =>  $order_total_before_tax,
          ':order_total_tax1'          =>  $order_total_tax1,
          ':order_total_tax2'          =>  $order_total_tax2,
          ':order_total_tax3'          =>  $order_total_tax3,
          ':order_total_tax'           =>  $order_total_tax,
          ':order_total_after_tax'      =>  $order_total_after_tax,
          ':order_id'               =>  $order_id
        )
      );
      
      $result = $statement->fetchAll();
            
      header("location:add_invoice.php");
  }

  if(isset($_GET["delete"]) && isset($_GET["id"]))
  {
    $statement = $conn1->prepare("DELETE FROM inv_order WHERE order_id = :id");
    $statement->execute(
      array(
        ':id'       =>      $_GET["id"]
      )
    );
    $statement = $conn1->prepare(
      "DELETE FROM inv_order_item WHERE order_id = :id");
    $statement->execute(
      array(
        ':id'       =>      $_GET["id"]
      )
    );?>
    <script>
		swal({
          title: 'Product Delete successfully',
          
          type: 'success'
        }).then(function() {
            window.location.href = "add_invoice.php";
        })
</script><?php
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title></title>
      <link rel="icon" href="img/favicon.ico" type="image/png">
    
   <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/js/jquery.min.js"></script>
    <script src="js/js/bootstrap.min.js"></script>
    <script src="js/js/jquery.dataTables.min.js"></script>
    <script src="js/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/css/invoice.css">
    <script src="js/js/bootstrap-datepicker.js"></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
 
  <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- themefy CSS -->
    <link rel="stylesheet" href="vendors/themefy_icon/themify-icons.css" />
    <!-- swiper slider CSS -->
    <link rel="stylesheet" href="vendors/swiper_slider/css/swiper.min.css" />
    <!-- select2 CSS -->
    <link rel="stylesheet" href="vendors/select2/css/select2.min.css" />
    <!-- select2 CSS -->
    <link rel="stylesheet" href="vendors/niceselect/css/nice-select.css" />
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="vendors/owl_carousel/css/owl.carousel.css" />
    <!-- gijgo css -->
    <link rel="stylesheet" href="vendors/gijgo/gijgo.min.css" />
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="vendors/font_awesome/css/all.min.css" />
    <link rel="stylesheet" href="vendors/tagsinput/tagsinput.css" />
    <!-- datatable CSS -->
    <link rel="stylesheet" href="vendors/datatable/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="vendors/datatable/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="vendors/datatable/css/buttons.dataTables.min.css" />
    <!-- text editor css -->
    <link rel="stylesheet" href="vendors/text_editor/summernote-bs4.css" />
    <!-- morris css -->
    <link rel="stylesheet" href="vendors/morris/morris.css">
    <!-- metarial icon css -->
    <link rel="stylesheet" href="vendors/material_icon/material-icons.css" />

    <!-- menu css  -->
    <link rel="stylesheet" href="css/metisMenu.css">
    <!-- style CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/colors/default.css" id="colorSkinCSS">
      
        
  </head>
    
    
  <body class="crm_body_bg">
      
    
    <div class="container-fluid">
        <br>
      <?php
      if(isset($_GET["add"]))
      {
      ?>
        
      <form method="post" id="invoice_form">
<!--          <?php var_dump($_REQUEST); ?>-->
        <div class="table-responsive"><br>
            <nav class="navbar navbar-default card">
          <div class="container">
            <div class="navbar-header" >
              <a class=" navbar-brand" href="#" >From:<br>Your Company Name<br>
             10,your Address<br>+91 9876543210<br>info@sample.com<br>
                   </a>
				 <!-- <a href="#" class="header-logo">
                  <img src="img/logo_1.png" class="img-fluid rounded-normal light-logo" alt="logo" style="position: relative;left: 250px;height:120px;">
              </a> -->
            </div>
          </div>
        </nav>
          <table class="table table-bordered card">
            
            <tr>
                <td colspan="2">
                  <div class="row">
                    <div class="col-md-8">
                     
                        <b>TO:</b><br />
                        <div class="form-group">
                        <input type="text" name="order_receiver_name" id="order_receiver_name" class="form-control input-sm" placeholder="Enter Receiver Name" />
                        </div>
                        <div class="form-group">
                        <textarea name="order_receiver_address" id="order_receiver_address" class="form-control" placeholder="Enter Billing Address"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                      <b>INVOICE DETAILS</b><br />
						<?php
		  					include('db_config.php');					                                        
						$query2 = "select * from inv_order order by order_no DESC limit 1";
						$result2 = mysqli_query($conn,$query2);
						$row = mysqli_fetch_array($result2);
						$last_id = $row['order_no'];
						if ($last_id == "")
						{
							$customer_ID = "INV0001";
						}
						else
						{
							$customer_ID = substr($last_id, 3);
							$customer_ID = intval($customer_ID);
							$customer_ID = "INV000" . ($customer_ID + 1);
						}
					?>
						
                      <div class="form-group">
                      <input type="text" name="order_no" id="order_no" class="form-control input-sm number_only" maxlength="6" placeholder="Enter Invoice No." readonly value="<?php echo $customer_ID;?>" />
                      </div>
                      
                      <div class="form-group">
                      <input type="date" name="order_date" id="order_date" class="form-control input-sm"  placeholder="Select Invoice Date" />
                      </div>
                    </div>
                  </div>
                  <br />
                  <table id="invoice-item-table" class="table table-bordered table-hover table-striped">
                    <tr>
                      <th width="5%">S/N.</th>
                      <th width="20%">Product Name</th>
                      <th width="10%">Product Quantity</th>
                      <th width="10%"> ProductPrice(₹)</th>
                      <th width="10%">Actual Amount</th>
                      <th width="12.5%" colspan="2">Gst/Tax(%)</th>
<!--
                      <th width="12.5%" colspan="2">Tax2 (%)</th>
                      <th width="12.5%" colspan="2">Tax3 (%)</th>
-->
                      <th width="12.5%" rowspan="">Total Amount</th>
                      <th width="3%" rowspan="2"></th>
                    </tr>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>Tax(5%)</th>
                      <th>Amount</th>
<!--
                      <th>Rate</th>
                      <th>Amt.</th>
                      <th>Rate</th>
                      <th>Amt.</th>
-->
                    </tr>
                    <tr>
                      <td><span id="sr_no">1</span></td>
                         
                      <td>
                          <input type="text" name="item_name[]" id="item_name1" class="form-control input-sm" />
                          

						</td>
                      <td><input type="text" name="order_item_quantity[]" id="order_item_quantity1" data-srno="1" class="form-control input-sm order_item_quantity" /></td>
                      <td><input type="text" name="order_item_price[]" id="order_item_price1" data-srno="1" class="form-control input-sm number_only order_item_price" /></td>
                      <td><input type="text" name="order_item_actual_amount[]" id="order_item_actual_amount1" data-srno="1" class="form-control input-sm order_item_actual_amount" readonly /></td>
                      <td><input type="text" name="order_item_tax1_rate[]" id="order_item_tax1_rate1" data-srno="1" class="form-control input-sm number_only order_item_tax1_rate"  placeholder="5%" value="5" readonly/></td>
                      <td><input type="text" name="order_item_tax1_amount[]" id="order_item_tax1_amount1" data-srno="1" readonly class="form-control input-sm order_item_tax1_amount" /></td>
<!--
                      <td><input type="text" name="order_item_tax2_rate[]" id="order_item_tax2_rate1" data-srno="1" class="form-control input-sm number_only order_item_tax2_rate" /></td>
                      <td><input type="text" name="order_item_tax2_amount[]" id="order_item_tax2_amount1" data-srno="1" readonly class="form-control input-sm order_item_tax2_amount" /></td>
                      <td><input type="text" name="order_item_tax3_rate[]" id="order_item_tax3_rate1" data-srno="1" class="form-control input-sm number_only order_item_tax3_rate" /></td>
                      <td><input type="text" name="order_item_tax3_amount[]" id="order_item_tax3_amount1" data-srno="1" readonly class="form-control input-sm order_item_tax3_amount" /></td>
-->
                      <td><input type="text" name="order_item_final_amount[]" id="order_item_final_amount1" data-srno="1" readonly class="form-control input-sm order_item_final_amount" /></td>
                      <td></td>
                    </tr>
                  </table>
                  <div align="right">
                    <button type="button" name="add_row" id="add_row" class="btn btn-success">ADD</button>
                  </div>
                </td>
              </tr>
              <tr>
                <td align="right"><b>Total</b></td>
                <td align="right"><b><span id="final_total_amt"></span></b></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input type="hidden" name="total_item" id="total_item" value="1" />
                  <input type="submit" name="create_invoice" id="create_invoice" class="btn btn-success" value="Create" />
                </td>
              </tr>
          </table>
        </div>
      </form>
		
      <script>
		
		  
      $(document).ready(function(){
        var final_total_amt = $('#final_total_amt').text();
        var count = 1;
        
        $(document).on('click', '#add_row', function(){
          count++;
          $('#total_item').val(count);
          var html_code = '';
          html_code += '<tr id="row_id_'+count+'">';
          html_code += '<td><span id="sr_no">'+count+'</span></td>';
          
          html_code += '<td><input type="text" name="item_name[]" id="item_name'+count+'" class="form-control input-sm" /></td>';
          
          html_code += '<td><input type="text" name="order_item_quantity[]" id="order_item_quantity'+count+'" data-srno="'+count+'" class="form-control input-sm number_only order_item_quantity" /></td>';
          html_code += '<td><input type="text" name="order_item_price[]" id="order_item_price'+count+'" data-srno="'+count+'" class="form-control input-sm number_only order_item_price" /></td>';
          html_code += '<td><input type="text" name="order_item_actual_amount[]" id="order_item_actual_amount'+count+'" data-srno="'+count+'" class="form-control input-sm order_item_actual_amount" readonly /></td>';
          
          html_code += '<td><input type="text" name="order_item_tax1_rate[]" id="order_item_tax1_rate'+count+'" data-srno="'+count+'" class="form-control input-sm number_only order_item_tax1_rate" value="5" placeholder="5%"/></td>';
          html_code += '<td><input type="text" name="order_item_tax1_amount[]" id="order_item_tax1_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm order_item_tax1_amount" /></td>';
//          html_code += '<td><input type="text" name="order_item_tax2_rate[]" id="order_item_tax2_rate'+count+'" data-srno="'+count+'" class="form-control input-sm number_only order_item_tax2_rate" /></td>';
//          html_code += '<td><input type="text" name="order_item_tax2_amount[]" id="order_item_tax2_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm order_item_tax2_amount" /></td>';
//          html_code += '<td><input type="text" name="order_item_tax3_rate[]" id="order_item_tax3_rate'+count+'" data-srno="'+count+'" class="form-control input-sm number_only order_item_tax3_rate" /></td>';
//          html_code += '<td><input type="text" name="order_item_tax3_amount[]" id="order_item_tax3_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm order_item_tax3_amount" /></td>';
          html_code += '<td><input type="text" name="order_item_final_amount[]" id="order_item_final_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm order_item_final_amount" /></td>';
          html_code += '<td><button type="button" name="remove_row" id="'+count+'" class="btn btn-danger btn-xs remove_row">Delete</button></td>';
          html_code += '</tr>';
          $('#invoice-item-table').append(html_code);
        });
		   
        
        $(document).on('click', '.remove_row', function(){
          var row_id = $(this).attr("id");
          var total_item_amount = $('#order_item_final_amount'+row_id).val();
          var final_amount = $('#final_total_amt').text();
          var result_amount = parseFloat(final_amount) - parseFloat(total_item_amount);
          $('#final_total_amt').text(result_amount);
          $('#row_id_'+row_id).remove();
          count--;
          $('#total_item').val(count);
        });

        function cal_final_total(count)
        {
          var final_item_total = 0;
          for(j=1; j<=count; j++)
          {
            var quantity = 0;
            var price = 0;
            var actual_amount = 0;
            var tax1_rate = 0;
            var tax1_amount = 0;
            var tax2_rate = 0;
            var tax2_amount = 0;
            var tax3_rate = 0;
            var tax3_amount = 0;
            var item_total = 0;
            quantity = $('#order_item_quantity'+j).val();
            if(quantity > 0)
            {
              price = $('#order_item_price'+j).val();
              if(price > 0)
              {
                actual_amount = parseFloat(quantity) * parseFloat(price);
                $('#order_item_actual_amount'+j).val(actual_amount);
                tax1_rate = $('#order_item_tax1_rate'+j).val();
                if(tax1_rate > 0)
                {
                  tax1_amount = parseFloat(actual_amount)*parseFloat(tax1_rate)/100;
                  $('#order_item_tax1_amount'+j).val(tax1_amount);
                }
                tax2_rate = $('#order_item_tax2_rate'+j).val();
                if(tax2_rate > 0)
                {
                  tax2_amount = parseFloat(actual_amount)*parseFloat(tax2_rate)/100;
                  $('#order_item_tax2_amount'+j).val(tax2_amount);
                }
                tax3_rate = $('#order_item_tax3_rate'+j).val();
                if(tax3_rate > 0)
                {
                  tax3_amount = parseFloat(actual_amount)*parseFloat(tax3_rate)/100;
                  $('#order_item_tax3_amount'+j).val(tax3_amount);
                }
                item_total = parseFloat(actual_amount) + parseFloat(tax1_amount) + parseFloat(tax2_amount) + parseFloat(tax3_amount);
                final_item_total = parseFloat(final_item_total) + parseFloat(item_total);
                $('#order_item_final_amount'+j).val(item_total);
              }
            }
          }
          $('#final_total_amt').text(final_item_total);
        }

        $(document).on('blur', '.order_item_price', function(){
          cal_final_total(count);
        });

        $(document).on('blur', '.order_item_tax1_rate', function(){
          cal_final_total(count);
        });

        $(document).on('blur', '.order_item_tax2_rate', function(){
          cal_final_total(count);
        });

        $(document).on('blur', '.order_item_tax3_rate', function(){
          cal_final_total(count);
        });

        $('#create_invoice').click(function(){
          if($.trim($('#order_receiver_name').val()).length == 0)
          {
            alert("Please Enter Reciever Name");
            return false;
          }

          if($.trim($('#order_no').val()).length == 0)
          {
            alert("Please Enter Invoice Number");
            return false;
          }

          if($.trim($('#order_date').val()).length == 0)
          {
            alert("Please Select Invoice Date");
            return false;
          }

          for(var no=1; no<=count; no++)
          {
            if($.trim($('#item_name'+no).val()).length == 0)
            {
              alert("Please Enter Item Name");
              $('#item_name'+no).focus();
              return false;
            }

            if($.trim($('#order_item_quantity'+no).val()).length == 0)
            {
              alert("Please Enter Quantity");
              $('#order_item_quantity'+no).focus();
              return false;
            }

            if($.trim($('#order_item_price'+no).val()).length == 0)
            {
              alert("Please Enter Price");
              $('#order_item_price'+no).focus();
              return false;
            }

          }

          $('#invoice_form').submit();

        });

      });
	
    $(function() {
    $("#item_name1").autocomplete({
        source: "search.php",
    });
});
      </script>
      <?php
      }
      elseif(isset($_GET["update"]) && isset($_GET["id"]))
      {
        $statement = $conn1->prepare("
          SELECT * FROM inv_order 
            WHERE order_id = :order_id
            LIMIT 1
        ");
        $statement->execute(
          array(
            ':order_id'       =>  $_GET["id"]
            )
          );
        $result = $statement->fetchAll();
        foreach($result as $row)
        {
        ?>
        <script>
        $(document).ready(function(){
          $('#order_no').val("<?php echo $row["order_no"]; ?>");
          $('#order_date').val("<?php echo $row["order_date"]; ?>");
          $('#order_receiver_name').val("<?php echo $row["order_receiver_name"]; ?>");
          $('#order_receiver_address').val("<?php echo $row["order_receiver_address"]; ?>");
        });
        </script>
		
        <form method="post" id="invoice_form">
             <nav class="navbar navbar-default card">
          <div class="container" >
            <div class="navbar-header" >
              <a class=" navbar-brand" href="" style="position: relative;right: 30px;height: 141px;">From:<br>Shwasthik Technologies<br>
                 9, Main DHALAVAI STREET,<br>Madurai <br>Tamil Nadu
                   +91 90033 60242<br>
                     info@shwasthik.com<br></a>
				 <a href="" class="header-logo">
                  <img src="img/logo_1.png" class="img-fluid rounded-normal light-logo" alt="logo" style="position: relative;left:450px;height: 141px;" >
              </a>
				
            </div>
          </div>
        </nav>
        <div class="table-responsive card">
          <table class="table table-bordered table-hover table-striped">
            <tr>
                <td colspan="2">
                  <div class="row">
                    <div class="col-md-8">
                    
                        <b>TO:</b><br />
                        <div class="form-control">
                        <input type="text" name="order_receiver_name" id="order_receiver_name" class="form-control input-sm" placeholder="Enter Receiver Name" />
                        </div>
                        <div class="form-control">
                        <textarea name="order_receiver_address" id="order_receiver_address" class="form-control" placeholder="Enter Billing Address"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                      INVOICE DETAILS<br />
                      <div class="form-control">
                      <input type="text" name="order_no" id="order_no" class="form-control input-sm" placeholder="Enter Invoice No." readonly/>
                      </div>
                      <div class="form-control">
                      <input type="date" name="order_date" id="order_date" class="form-control input-sm"  placeholder="Select Invoice Date" readonly />
                      </div>
                    </div>
                  </div>
                  <br />
                  <table id="invoice-item-table" class="table table-bordered table-hover table-striped">
                    <tr>
                      <th width="5%">S/N</th>
                      <th width="20%">Product Name</th>
                      <th width="10%">Product Quantity</th>
                      <th width="10%">Product Price(₹)</th>
                      <th width="10%">Actual Amount</th>
                      <th width="12.5%" colspan="2">Gst/Tax(%)</th>
<!--
                      <th width="12.5%" colspan="2">Tax2 (%)</th>
                      <th width="12.5%" colspan="2">Tax3 (%)</th>
-->
                      <th width="12.5%" rowspan="2">Total</th>
                      <th width="3%" rowspan="2"></th>
                    </tr>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>Tax(5%)</th>
                      <th>Amount</th>
<!--
                      <th>Rate</th>
                      <th>Amt.</th>
                      <th>Rate</th>
                      <th>Amt.</th>
-->
                    </tr>
                    <?php
                    $statement = $conn1->prepare("
                      SELECT * FROM inv_order_item 
                      WHERE order_id = :order_id
                    ");
                    $statement->execute(
                      array(
                        ':order_id'       =>  $_GET["id"]
                      )
                    );
                    $item_result = $statement->fetchAll();
                    $m = 0;
                    foreach($item_result as $sub_row)
                    {
                      $m = $m + 1;
                    ?>
                    <tr>
                      <td><span id="sr_no"><?php echo $m; ?></span></td>
                      <td><input type="text" name="item_name[]" id="item_name<?php echo $m; ?>" class="form-control input-sm" value="<?php echo $sub_row["item_name"]; ?>" /></td>
                      <td><input type="text" name="order_item_quantity[]" id="order_item_quantity<?php echo $m; ?>" data-srno="<?php echo $m; ?>" class="form-control input-sm order_item_quantity" value = "<?php echo $sub_row["order_item_quantity"]; ?>"/></td>
                      <td><input type="text" name="order_item_price[]" id="order_item_price<?php echo $m; ?>" data-srno="<?php echo $m; ?>" class="form-control input-sm number_only order_item_price" value="<?php echo $sub_row["order_item_price"]; ?>" /></td>
                      <td><input type="text" name="order_item_actual_amount[]" id="order_item_actual_amount<?php echo $m; ?>" data-srno="<?php echo $m; ?>" class="form-control input-sm order_item_actual_amount" value="<?php echo $sub_row["order_item_actual_amount"];?>" readonly /></td>
                      <td><input type="text" name="order_item_tax1_rate[]" id="order_item_tax1_rate<?php echo $m; ?>" data-srno="<?php echo $m; ?>" class="form-control input-sm number_only order_item_tax1_rate" value="<?php echo $sub_row["order_item_tax1_rate"]; ?>" /></td>
                      <td><input type="text" name="order_item_tax1_amount[]" id="order_item_tax1_amount<?php echo $m; ?>" data-srno="<?php echo $m; ?>" readonly class="form-control input-sm order_item_tax1_amount" value="<?php echo $sub_row["order_item_tax1_amount"];?>" /></td>
<!--
                      <td><input type="text" name="order_item_tax2_rate[]" id="order_item_tax2_rate<?php echo $m; ?>" data-srno="<?php //echo $m; ?>" class="form-control input-sm number_only order_item_tax2_rate" value="<?php// echo $sub_row["order_item_tax2_rate"];?>" /></td>
                      <td><input type="text" name="order_item_tax2_amount[]" id="order_item_tax2_amount<?php //echo $m; ?>" data-srno="<?php //echo $m; ?>" readonly class="form-control input-sm order_item_tax2_amount" value="<?php //echo $sub_row["order_item_tax2_amount"]; ?>" /></td>
                      <td><input type="text" name="order_item_tax3_rate[]" id="order_item_tax3_rate<?php //echo $m; ?>" data-srno="<?php //echo $m; ?>" class="form-control input-sm number_only order_item_tax3_rate" value="<?php //echo $sub_row["order_item_tax3_rate"]; ?>" /></td>
                      <td><input type="text" name="order_item_tax3_amount[]" id="order_item_tax3_amount<?php //echo $m; ?>" data-srno="<?php //echo $m; ?>" readonly class="form-control input-sm order_item_tax3_amount" value="<?php// echo $sub_row["order_item_tax3_amount"]; ?>" /></td>
-->
                      <td><input type="text" name="order_item_final_amount[]" id="order_item_final_amount<?php echo $m; ?>" data-srno="<?php echo $m; ?>" readonly class="form-control input-sm order_item_final_amount" value="<?php echo $sub_row["order_item_final_amount"]; ?>" /></td>
                      <td></td>
                    </tr>
                    <?php
                    }
                    ?>
                  </table>
                </td>
              </tr>
              <tr>
                  <td align="right"><b>Total</b></td>
                <td align="right"><b><span id="final_total_amt"><?php echo '$'.$row["order_total_after_tax"]; ?></span></b></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input type="hidden" name="total_item" id="total_item" value="<?php echo $m; ?>" />
                  <input type="hidden" name="order_id" id="order_id" value="<?php echo $row["order_id"]; ?>" />
                  <input type="submit" name="update_invoice" id="create_invoice" class="btn btn-primary" value="Edit" />
					<a href="add_invoice.php" class="header-logo"><button type="button" class="btn btn-info">
					Back
					</button>
                 </a>
                </td>
              </tr>
          </table>
        </div>
      </form>
      <script>
      $(document).ready(function(){
        var final_total_amt = $('#final_total_amt').text();
        var count = "<?php echo $m; ?>";
        
        $(document).on('click', '#add_row', function(){
          count++;
          $('#total_item').val(count);
          var html_code = '';
          html_code += '<tr id="row_id_'+count+'">';
          html_code += '<td><span id="sr_no">'+count+'</span></td>';
          
          html_code += '<td><input type="text" name="item_name[]" id="item_name'+count+'" class="form-control input-sm" /></td>';
          
          html_code += '<td><input type="text" name="order_item_quantity[]" id="order_item_quantity'+count+'" data-srno="'+count+'" class="form-control input-sm number_only order_item_quantity" /></td>';
          html_code += '<td><input type="text" name="order_item_price[]" id="order_item_price'+count+'" data-srno="'+count+'" class="form-control input-sm number_only order_item_price" /></td>';
          html_code += '<td><input type="text" name="order_item_actual_amount[]" id="order_item_actual_amount'+count+'" data-srno="'+count+'" class="form-control input-sm order_item_actual_amount" readonly /></td>';
          
          html_code += '<td><input type="text" name="order_item_tax1_rate[]" id="order_item_tax1_rate'+count+'" data-srno="'+count+'" class="form-control input-sm number_only order_item_tax1_rate" /></td>';
          html_code += '<td><input type="text" name="order_item_tax1_amount[]" id="order_item_tax1_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm order_item_tax1_amount" /></td>';
          html_code += '<td><input type="text" name="order_item_tax2_rate[]" id="order_item_tax2_rate'+count+'" data-srno="'+count+'" class="form-control input-sm number_only order_item_tax2_rate" /></td>';
          html_code += '<td><input type="text" name="order_item_tax2_amount[]" id="order_item_tax2_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm order_item_tax2_amount" /></td>';
          html_code += '<td><input type="text" name="order_item_tax3_rate[]" id="order_item_tax3_rate'+count+'" data-srno="'+count+'" class="form-control input-sm number_only order_item_tax3_rate" /></td>';
          html_code += '<td><input type="text" name="order_item_tax3_amount[]" id="order_item_tax3_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm order_item_tax3_amount" /></td>';
          html_code += '<td><input type="text" name="order_item_final_amount[]" id="order_item_final_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm order_item_final_amount" /></td>';
          html_code += '<td><button type="button" name="remove_row" id="'+count+'" class="btn btn-danger btn-xs remove_row">Delete</button></td>';
          html_code += '</tr>';
          $('#invoice-item-table').append(html_code);
        });
        
        $(document).on('click', '.remove_row', function(){
          var row_id = $(this).attr("id");
          var total_item_amount = $('#order_item_final_amount'+row_id).val();
          var final_amount = $('#final_total_amt').text();
          var result_amount = parseFloat(final_amount) - parseFloat(total_item_amount);
          $('#final_total_amt').text(result_amount);
          $('#row_id_'+row_id).remove();
          count--;
          $('#total_item').val(count);
        });

        function cal_final_total(count)
        {
          var final_item_total = 0;
          for(j=1; j<=count; j++)
          {
            var quantity = 0;
            var price = 0;
            var actual_amount = 0;
            var tax1_rate = 0;
            var tax1_amount = 0;
            var tax2_rate = 0;
            var tax2_amount = 0;
            var tax3_rate = 0;
            var tax3_amount = 0;
            var item_total = 0;
            quantity = $('#order_item_quantity'+j).val();
            if(quantity > 0)
            {
              price = $('#order_item_price'+j).val();
              if(price > 0)
              {
                actual_amount = parseFloat(quantity) * parseFloat(price);
                $('#order_item_actual_amount'+j).val(actual_amount);
                tax1_rate = $('#order_item_tax1_rate'+j).val();
                if(tax1_rate > 0)
                {
                  tax1_amount = parseFloat(actual_amount)*parseFloat(tax1_rate)/100;
                  $('#order_item_tax1_amount'+j).val(tax1_amount);
                }
                tax2_rate = $('#order_item_tax2_rate'+j).val();
                if(tax2_rate > 0)
                {
                  tax2_amount = parseFloat(actual_amount)*parseFloat(tax2_rate)/100;
                  $('#order_item_tax2_amount'+j).val(tax2_amount);
                }
                tax3_rate = $('#order_item_tax3_rate'+j).val();
                if(tax3_rate > 0)
                {
                  tax3_amount = parseFloat(actual_amount)*parseFloat(tax3_rate)/100;
                  $('#order_item_tax3_amount'+j).val(tax3_amount);
                }
                item_total = parseFloat(actual_amount) + parseFloat(tax1_amount) + parseFloat(tax2_amount) + parseFloat(tax3_amount);
                final_item_total = parseFloat(final_item_total) + parseFloat(item_total);
                $('#order_item_final_amount'+j).val(item_total);
              }
            }
          }
          $('#final_total_amt').text(final_item_total);
        }

        $(document).on('blur', '.order_item_price', function(){
          cal_final_total(count);
        });

        $(document).on('blur', '.order_item_tax1_rate', function(){
          cal_final_total(count);
        });

        $(document).on('blur', '.order_item_tax2_rate', function(){
          cal_final_total(count);
        });

        $(document).on('blur', '.order_item_tax3_rate', function(){
          cal_final_total(count);
        });

        $('#create_invoice').click(function(){
          if($.trim($('#order_receiver_name').val()).length == 0)
          {
            alert("Please Enter Reciever Name");
            return false;
          }

          if($.trim($('#order_no').val()).length == 0)
          {
            alert("Please Enter Invoice Number");
            return false;
          }

          if($.trim($('#order_date').val()).length == 0)
          {
            alert("Please Select Invoice Date");
            return false;
          }

          for(var no=1; no<=count; no++)
          {
            if($.trim($('#item_name'+no).val()).length == 0)
            {
              alert("Please Enter Item Name");
              $('#item_name'+no).focus();
              return false;
            }

            if($.trim($('#order_item_quantity'+no).val()).length == 0)
            {
              alert("Please Enter Quantity");
              $('#order_item_quantity'+no).focus();
              return false;
            }

            if($.trim($('#order_item_price'+no).val()).length == 0)
            {
              alert("Please Enter Price");
              $('#order_item_price'+no).focus();
              return false;
            }

          }

          $('#invoice_form').submit();

        });

      });
      </script>
        <?php 
        }
      }
      else
      {
      ?>
        
    <br />
      <br />
     <h2>Invoice Summary</h2>
      
      <table id="data-table"  >
        <thead class="table table-bordered table-sm">
          <tr style="font-style: italic; color: #cc3300;">
            <th>Invoice No.</th>
            <th>Invoice Date</th>
            <th>Receiver Name</th>
            <th>Invoice Total</th>
            <th>PDF</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <?php
        if($total_rows > 0)
        {
          foreach($all_result as $row)
          {
            echo '
              <tr >
                <td>'.$row["order_no"].'</td>
                <td>'.$row["order_date"].'</td>
                <td>'.$row["order_receiver_name"].'</td>
                <td>₹'.$row["order_total_after_tax"].'</td>
                <td><a href="printInvoice.php?pdf=1&id='.$row["order_id"].'"><button type="button" class="btn btn-primary">PDF</button></a></td>
                <td><a href="invoice.php?update=1&id='.$row["order_id"].'"><button type="button" class="btn btn-info">Edit</button></a></td>
                <td><a href="#" id="'.$row["order_id"].'" class="delete text-danger"><button type="button" class="btn btn-danger">Delete</button></a></td>
              </tr>
            ';
          }
        }
        ?>
      </table>
      <?php
      }
      ?>
    </div>
    <br>
    
  </body>
</html>

<?php   
  // else  
  // {  
  //      //header("location:index.php");  
  // } ?>
<script type="text/javascript">
  $(document).ready(function(){
    var table = $('#data-table').DataTable({
          "order":[],
          "columnDefs":[
          {
            "targets":[4, 5, 6],
            "orderable":false,
          },
        ],
        "pageLength": 5
        });
    $(document).on('click', '.delete', function(){
      var id = $(this).attr("id");
      if(confirm("Are you sure you want to remove this?"))
      {
        window.location.href="add_invoice.php?delete=1&id="+id;
      }
      else
      {
        return false;
      }
    });
  });

</script>

<script>
$(document).ready(function(){
$('.number_only').keypress(function(e){
return isNumbers(e, this);      
});
function isNumbers(evt, element) 
{
var charCode = (evt.which) ? evt.which : event.keyCode;
if (
(charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
(charCode < 48 || charCode > 57))
return false;
return true;
}
});
</script>
<script>
      $(document).ready(function(){
        $('#order_date').datepicker({
          format: "yyyy-mm-dd",
          autoclose: true
        });
      });
</script>
<?php //include 'assets/include/footer.php' ?>
