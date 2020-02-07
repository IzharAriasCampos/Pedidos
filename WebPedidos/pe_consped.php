<?php
include('conexion.php');
   session_start();
   if (!isset($_POST)||empty($_POST)){
   $clients=getClients($db);
?>
<html>
	<body>
		<form name="form" action="" method="post">
		<div>
			<select name="Clients">
				<?php foreach ($clients as $client) : ?>
					<?php echo '<option>'. $client['customerNumber'] . '</option>'; ?>
				<?php endforEach; ?>
			</select>
			<input type="submit" value="stock" name="stock">
		</div>
		</form>
</body>
<html>
<?php
   }else{
	$c=$_POST['Clients'];
	$ofc=ordersFromCustomer($db,$c);
	$odc=ordersDetailsCustomer($db,$ofc);
	echo "Estos son los pedidos que ha realizado ".$c.":<br>";
	var_dump($ofc);
	echo "Y estos son los datos de cada pedido : <br>";
	var_dump($odc);
	//Cambiar modo de visualizacion
   }
   //Functions
  function getClients($db){
		$clients=Array();
		$result=mysqli_query($db,"SELECT * FROM orders GROUP BY customerNumber ORDER BY customerNumber");
		while($row = mysqli_fetch_assoc($result)){
			$clients[]=$row;
		}
		return $clients;
	}
  function ordersDetailsCustomer($db,$ofc){
		$on=array();
		$orders=array();
		foreach($ofc as $l => $d){
			$on[]=$d['orderNumber'];
		}
		foreach($on as $l => $d){
			$result=mysqli_query($db,"SELECT orderNumber,orderLineNumber,productCode,quantityOrdered,priceEach FROM orderdetails WHERE orderNumber='$d'");
			while($row=mysqli_fetch_assoc($result)){
				$orders[]=$row;
			}
		}
		return $orders;
	}
  function ordersFromCustomer($db,$c){
		$orders=array();
		$result=mysqli_query($db,"SELECT orderNumber,orderDate,status FROM orders WHERE customerNumber='$c'");
		while($row=mysqli_fetch_assoc($result)){
			$orders[]=$row;
		}
		return $orders;
	} 