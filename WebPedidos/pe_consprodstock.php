<?php
include('conexion.php');
   session_start();
   if (!isset($_POST)||empty($_POST)){
   $products=getProducts($db);
?>
<html>
	<body>
		<form name="form" action="" method="post">
		<div>
			<select name="Productos">
				<?php foreach ($products as $product) : ?>
					<?php echo '<option>'. $product['productName'] . '</option>'; ?>
				<?php endforEach; ?>
			</select>
			<input type="submit" value="stock" name="stock">
		</div>
		</form>
</body>
<html>
<?php
   }else{
   $p=$_POST['Productos'];
   $stock=actualStock($db,$p);
   echo "El stock de este producto es de ".$stock;
   }
   //Functions
  function getProducts($db){
	$products=array();
	$result= mysqli_query($db,"SELECT * FROM products");
		while($row = mysqli_fetch_assoc($result)){
			$products[]=$row;
		}
	return $products;
	}
	function actualStock($db,$p){
	$sql="SELECT quantityinstock FROM products WHERE productName='$p'";
	$result=mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($result);
	return $row['quantityinstock'];
	}
?>