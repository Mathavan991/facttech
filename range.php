<?php
	require 'db_config.php';
	if(ISSET($_POST['search'])){
		$date1 = date("Y-m-d", strtotime($_POST['date1']));
		$date2 = date("Y-m-d", strtotime($_POST['date2']));
		$query=mysqli_query($conn, "SELECT * FROM `inv_order` WHERE date(`order_date`) BETWEEN '$date1' AND '$date2'") or die(mysqli_error());
        //echo "<script>alert('$date2'); </script>";
		$row=mysqli_num_rows($query);
		if($row>0){
			while($fetch=mysqli_fetch_array($query)){
?>
	<tr>
		                     <td><?php echo $fetch['order_no']; ?></td>
						     <td><?php echo $fetch['order_receiver_name']; ?></td>
							<td><?php echo $fetch['order_receiver_address']; ?></td>
							<td><?php echo  $fetch['order_date']; ?></td>
                            <td><?php echo "₹" .$fetch['order_total_after_tax']. "<br>"; ?></td>
	</tr>
<?php
			}
		}else{
			echo'
			<tr>
				<td colspan = "4"><center>Record Not Found</center></td>
			</tr>';
		}
	}else{
		$query=mysqli_query($conn, "SELECT * FROM `inv_order`") or die(mysqli_error());
		while($fetch=mysqli_fetch_array($query)){
?>
	<tr>
		                     <td><?php echo $fetch['order_no']; ?></td>
						     <td><?php echo $fetch['order_receiver_name']; ?></td>
							<td><?php echo $fetch['order_receiver_address']; ?></td>
							<td><?php echo  $fetch['order_date']; ?></td>
                            <td><?php echo "₹" .$fetch['order_total_after_tax']. "<br>"; ?></td>
	</tr>
<?php
		}
	}
?>
