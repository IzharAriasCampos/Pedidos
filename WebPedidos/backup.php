<?php
   include('conexion.php');
   session_start();
   $products=getProducts($db);
	if (isset($_POST)||!empty($_POST)){
		if (isset($_POST['add']) && !empty($_POST['add'])) {
		   if (!isset($_SESSION['shop'])) {
				$_SESSION['shop']=array(array($_POST['Productos'],$_POST['lot'],$_POST['check'],$_POST['comment']));
		   }else{
				array_push($_SESSION['shop'],array($_POST['Productos'],$_POST['lot'],$_POST['check'],$_POST['comment']));
		   }
		   var_dump($_SESSION['shop']);
		}
		if (isset($_POST['clear']) && !empty($_POST['clear'])) {
			$_SESSION['shop']=null;
		}
		If (isset($_POST['buy']) && !empty($_POST['buy'])) {
			header('location: pe_altaped.php');
		}
	}
   
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
			<label for="cantidad1">Cantidad:</label>
			<input type="number" name="lot" style="width:50px"><br>
			Introduce tu numero de pago: <input type="text" name="check" style="width:80px"><br>
			Comentario:<input type="text" placeholder="Introduce un comentario" name="comment"></textarea>
			<input type="submit" value="buy" name="buy">
			<input type="submit" value="add" name="add">
			<input type="submit" value="clear" name="clear">
		</div>
		</form>
	</body>
<html>
<?php
function getProducts($db){
	$products=array();
	$result= mysqli_query($db,"SELECT * FROM products WHERE quantityinstock>0");
		while($row = mysqli_fetch_assoc($result)){
			$products[]=$row;
		}
	return $products;
}
?>