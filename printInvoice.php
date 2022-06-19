
<?php
//print_invoice.php

if(isset($_GET["pdf"]) && isset($_GET["id"]))
{
 require_once 'pdf.php';
  //include('pdf.php');  
	$conn1 = new PDO('mysql:host=localhost;dbname=factura','root', '');
 //include('db_config.php');
 $output = '';
 $statement = $conn1->prepare("SELECT * FROM inv_order WHERE order_id = :order_id LIMIT 1");
 $statement->execute(
  array(
   ':order_id'       =>  $_GET["id"]
  )
 );
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output .= '
   <table width="100%" border="1" cellpadding="5" cellspacing="0">
    <tr style= color: #fff">
     <td colspan="2" align="center" style="font-size:18px"><b>Invoice</b></td>
	 </tr>
	<tr>
     <td colspan="2">
      <table width="100%" cellpadding="5">
       <tr>
        <td width="65%"  color: #fff">
        <b>From:<br>Shwasthik Technologies<br>
         9, Main DHALAVAI STREET,<br>Madurai <br>Tamil Nadu<br>
         +91 90033 60242<br>
          info@shwasthik.com<br>
        </td>
        <td width="35%" >
        <img src="assets/images/logo.png"  style="position: relative;left: 250px;height:125px;">
        </td>
       </tr>
    <tr>
     <td colspan="2">
      <table width="100%" cellpadding="5">
       <tr>
        <td width="65%"  color: #fff">
         To,<br />
         <b>RECEIVER (BILL TO)</b><br />
         Name : '.$row["order_receiver_name"].'<br /> 
         Billing Address : '.$row["order_receiver_address"].'<br />
        </td>
        <td width="35%" >
         Invoice Details<br />
         Invoice No. : '.$row["order_no"].'<br />
         Invoice Date : '.$row["order_date"].'<br />
        </td>
       </tr>
      </table>
      <br />
      <table width="90%" border="1" cellpadding="5" cellspacing="0">
       <tr>
        <th>S/N</th>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Actual Amount</th>
        <th colspan="6">Tax5(%)</th>
        
        <th rowspan="2">Total</th>
       </tr>
       <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th colspan="3">Rate</th>
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
   $output .= '
   <tr>
    <td>'.$count.'</td>
    <td>'.$sub_row["item_name"].'</td>
    <td>'.$sub_row["order_item_quantity"].'</td>
    <td>'.$sub_row["order_item_price"].'</td>
    <td>'.$sub_row["order_item_actual_amount"].'</td>
    <td colspan="3">'.$sub_row["order_item_tax1_rate"].'</td>
    <td colspan="3">'.$sub_row["order_item_tax1_amount"].'</td>
    
    
    <td>'.$sub_row["order_item_final_amount"].'</td>
   </tr>
   ';
  }
  $output .= '
  <tr>
   <td align="right" colspan="11"><b>Total</b></td>
   <td align="right"><b>'.$row["order_total_after_tax"].'</b></td>
  </tr>
  <tr>
   <td colspan="11"><b>Total Amount Before Tax :</b></td>
   <td align="right">$'.$row["order_total_before_tax"].'</td>
  </tr>
  <tr>
   <td colspan="11">Add : Tax1 :</td>
   <td align="right">'.$row["order_total_tax1"].'</td>
  </tr>
  
  <tr>
   <td colspan="11"><b>Total Tax Amount  :</b></td>
   <td align="right">$'.$row["order_total_tax"].'</td>
  </tr>
  <tr style=" color: #fff">
   <td colspan="11"><b>Total Amount After Tax :</b></td>
   <td align="right">$'.$row["order_total_after_tax"].'</td>
  </tr>
  
  ';
  $output .= '
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
 $pdf->stream($file_name, array("Attachment" => false));
}
?>

