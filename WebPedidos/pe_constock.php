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
					<?php echo '<option>'. $product['productLine'] . '</option>'; ?>
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
	foreach($stock as $linea => $valor){
		echo $valor['productName']." tiene ".$valor['quantityinstock']."<br>";
	}
   }
   //Functions
  function getProducts($db){
	$products=array();
	$result= mysqli_query($db,"SELECT productLine FROM products GROUP BY productLine");
		while($row = mysqli_fetch_assoc($result)){
			$products[]=$row;
		}
	return $products;
	}
	function actualStock($db,$p){
	$stock=[];
	$sql="SELECT productName,quantityinstock FROM products WHERE productLine='$p' order by quantityinstock DESC";
	$result=mysqli_query($db,$sql);
	while($row = mysqli_fetch_assoc($result)){
			$stock[]=$row;
		}
	return $stock;
	}