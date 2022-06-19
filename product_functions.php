<?php 

class User
{
   public $conn = null;
    public function __construct()
    {
    	include 'db_config.php';
    	 $this->conn = $conn; 
		//$this->conn = mysqli_connect('localhost', 'root', '', 'factura'); 
    } 
    public function execute_query($query)
    {
    	return $this->conn->query($query);
    }
    public function get_all_users()
    {
    	return $this->conn->query("Select * from product_details ORDER BY id DESC");
    }
    public function get_user_by_id($id)
    {
    	return $this->conn->query("Select * from product_details where id='$id'");
    }
    function upload_file($file) {  
	   if(isset($file))  
	   {  
	        $extension = explode('.', $file["name"]);  
	        $new_name = time() . '.' . $extension[0];  
	        $destination = './product_details/' . $new_name;  
	        move_uploaded_file($file['tmp_name'], $destination);  
	        return $new_name;  
	   }  
	}  
    
} 
$usr = new User();

if (isset($_POST['create'])) {
	$product_name = $_POST["product_name"];
	$product_price = $_POST["product_price"];
    $product_quantity = $_POST["product_quantity"];
	$description = $_POST["description"];
	$image = $usr->upload_file($_FILES["product_image"]); 
    
    $select = mysqli_query($conn, "SELECT `product_name` FROM `product_details` WHERE `product_name` = '".$_POST['product_name']."'") or exit(mysqli_error($conn));
      if(mysqli_num_rows($select)) {
		 echo "<script>alert('This  Already Being Using in Product Name'); window.open('product_list.php','_self')</script>";
    //exit('This Size is already being used');
     }
    
    else{
	$query ="INSERT INTO product_details (product_name,product_price,product_quantity,description, product_image) VALUES ('$product_name','$product_price','$product_quantity','$description','$image')";  
    }
        
	 if($usr->execute_query($query)){
	//echo "<script>alert('Service Inserted'); window.open('add_service.php','_self')</script>";
		 ?>
		 <script>
		swal({
          title: 'New Product Uploaded Successfully',
          type: 'success'
        }).then(function() {
            window.location.href = "product_list.php";
        });
      </script>
     <?php
	}else{
	echo "<script>alert('Unable to insert'); window.open('product_list.php','_self')</script>";
	}
}
if (isset($_POST['update'])) {
	$product_name = $_POST["product_name"];
	$product_price = $_POST["product_price"];
    $product_quantity = $_POST["product_quantity"];
	$description = $_POST["description"];
	$id = $_POST["id"];
    //$image= $_POST["product_image"];
    $image = $usr->upload_file($_FILES["product_image"]);
       // echo "<script>alert('$image'); window.open('product_list.php','_self')</script>";
    
    if (empty($_FILES['product_image']['name'])) {
		$query ="UPDATE product_details set product_name='$product_name',product_price='$product_price',product_quantity='$product_quantity',description='$description'  where id='$id'";  
	}
    
    else{
        
		
		$query ="UPDATE product_details set product_name='$product_name',product_price='$product_price',product_quantity='$product_quantity',description='$description', product_image='$image'  where id='$id'"; 
	}
    
	
	
  if($usr->execute_query($query)){
	//echo "<script>alert('Service Updated'); window.open('add_service.php','_self')</script>";
		?>
		 <script>
		swal({
          title: 'Product updated Successfully',
          type: 'success'
        }).then(function() {
            window.location.href = "product_list.php";
        });
      </script>
     <?php
	}else{
	echo "<script>alert('Unable to update'); window.open('product_list.php','_self')</script>";
	}
	 
}

if (isset($_GET['delete'])) {
	  $id = (is_numeric($_GET['delete'])) ? $_GET['delete'] : 0;
	$query ="DELETE from product_details where id='$id'"; 
	if($usr->execute_query($query)){
	//echo "<script>alert('Data deleted'); window.open('add_service.php','_self')</script>";
		?>
		 <script>
		swal({
          title: 'Product Deleted Successfully',
          type: 'success'
        }).then(function() {
            window.location.href = "product_list.php";
        });
      </script>
     <?php
	}else{
	echo "<script>alert('Unable to delete'); window.open('product_list.php','_self')</script>";
	}
}

?>   