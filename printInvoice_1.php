
<?php
//print_invoice.php
?>

<!--  <script type="text/javascript">
 window.print();
 window.onafterprint = function(e){
    closePrintView();
};

function myFunction(){
    window.print();
}

function closePrintView() {
    window.location.href = 'add_invoice.php';   
}
 </script> -->
<?php
if( isset($_GET["id"]))
{
 //require_once 'pdf.php';
  include('pdf.php');  
  //$conn1 = new PDO('mysql:host=localhost;dbname=factura','root', '');
 include('db_config.php');
 //$output = '';
 //echo "<script>alert('password Inserted');</script>";
 $statement = $conn1->prepare("SELECT * FROM inv_order WHERE order_id = :order_id LIMIT 1");
 $statement->execute(
  array(
   ':order_id'       =>  $_GET["id"]
  )
 );
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  echo  '
   <table width="100%"  cellpadding="5" cellspacing="0" height="50%">
    <tr style= color: #fff">
     <td colspan="2" align="center" style="font-size:18px"><b>  Invoice</b></td>
   </tr>
  <tr>
     <td colspan="2"><hr>
      <table width="100%" cellpadding="5" height="100%">
       <tr>
        <td width="65%"  color: #fff">
        <b>From:</b>
        <br>Shwasthik Technologies,<br>
         9, Main Dhalavai Street,Madurai .<br>
         Tamil Nadu.<br>
         +91 90033 60242<br>
         info@shwasthik.com<br>
        </td>
        <td width="60%" >
        <img src="img/logo_1.png"  style="position: relative;left:-65px;height:105px;">
        </td>
       </tr>
    <tr>
     <td colspan="2">
      <table width="100%" cellpadding="5" height="30%"><br><hr>
       <tr height="40%">
        <td width="65%"  color: #fff" height="40%">
         <b>To:<b><br />
          Name : '.$row["order_receiver_name"].'<br /> 
          Address : '.$row["order_receiver_address"].'<br />
        </td>
        <td width="35%" ><b>
         Invoice Details : </b><br />
         Invoice No. : '.$row["order_no"].'<br />
         Invoice Date : '.$row["order_date"].'<br />
        </td>
       </tr>
      </table>
      <br />
      <table width="100%" border="1" cellpadding="7" cellspacing="0"><hr>
       <tr  height:70%;>
        <th>S/N</th>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Actual Amount</th>
        <th colspan="6" >Gst/Tax 5(%)</th>
        <th rowspan="2">Total Amount</th>
       </tr>
       <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th colspan="3">Tax 5(%)</th>
        <th colspan="3">Amount</th>
        
        
       </tr>';
  $statement = $conn1->prepare("SELECT * FROM inv_order_item WHERE order_id = :order_id");
  $statement->execute(
   array(
    ':order_id'       =>  $_GET["id"]
   )
  );
  $item_result = $statement->fetchAll();
  $count = 0;
  foreach($item_result as $sub_row)
  {
   $count++;
   echo  '
   <tr>
    <td align="center">'.$count.'.</td>
    <td>'.$sub_row["item_name"].'</td>
    <td align="center">'.$sub_row["order_item_quantity"].'</td>
    <td align="center"><b>₹</b>'.$sub_row["order_item_price"].'</td>
    <td align="center"><b>₹</b>'.$sub_row["order_item_actual_amount"].'</td>
    <td colspan="3" align="center">'.$sub_row["order_item_tax1_rate"].'<b>%</b></td>
    <td colspan="3" align="center"><b>₹</b>'.$sub_row["order_item_tax1_amount"].'</td>
    <td align="right"><b>₹</b>'.$sub_row["order_item_final_amount"].'</td>
   </tr>
   ';
  }
  echo  '
  
  <tr>
   <td align="right" colspan="11"><b>Total Amount Before Tax :</b></td>
   <td align="right"><b>₹'.$row["order_total_before_tax"].'</b></td>
  </tr>
  <tr>
   <td align="right" colspan="11"><b>Total Tax(5%) After  Amount  :</b></td>
   <td align="right"><b>₹'.$row["order_total_tax"].'</b></td>
  </tr>
  
  <tr>
   <td align="right" colspan="11" ><b>Total Amount :</b></td>
   <td align="right"><b>₹'.$row["order_total_after_tax"].'</b></td>
  </tr>
  ';
  echo '
      </table>
     </td>
    </tr>
   </table>
  ';
 }
 
 $pdf = new Pdf();
 $file_name = 'Invoice_'.$row["order_receiver_name"].'.pdf';
 $pdf->loadHtml($output);
  
 $pdf->render();
 $pdf->stream($file_name);
}
?>

