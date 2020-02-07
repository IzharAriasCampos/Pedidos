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
			<input type="date" name="start">
			<input type="date" name="finish">
			<input type="submit" value="check" name="check">
		</div>
		</form>
</body>
<html>
<?php
	}else{
		$customer=$_POST['Clients'];
		$start=$_POST['start'];
		$finish=$_POST['finish'];
		if($start!="" && $finish!=""){
			$payments=customerPayments($db,$start,$finish,$customer);
			var_dump($payments);
		}else{
			$payments=historical($db,$customer);
			var_dump($payments);
		}
	}
	//Functions
	function customerPayments($db,$start,$finish,$customer){
		$payments=[];
		$result=mysqli_query($db,"SELECT checkNumber,amount,paymentDate FROM payments WHERE paymentDate>='$start' AND paymentDate<='$finish' AND customerNumber='$customer'");
		if($result){
			while($row = mysqli_fetch_assoc($result)){
				$payments[]=$row;
			}
		}else echo "No tienes pagos entre estas 2 fechas";
		return $payments;
	}
	function historical($db,$customer){
		$payments=[];
		$result=mysqli_query($db,"SELECT checkNumber,amount,paymentDate FROM payments WHERE customerNumber='$customer'");
		if($result){
			while($row = mysqli_fetch_assoc($result)){
				$payments[]=$row;
			}
		}else echo "No tienes pagos entre estas 2 fechas";
		return $payments;
	}
	function getClients($db){
		$clients=Array();
		$result=mysqli_query($db,"SELECT customerNumber FROM customers ORDER BY customerNumber");
		while($row = mysqli_fetch_assoc($result)){
			$clients[]=$row;
		}
		return $clients;
	}
?>