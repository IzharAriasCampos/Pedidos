<?php
include('conexion.php');
   session_start();
   if (!isset($_POST)||empty($_POST)){
?>
<html>
	<body>
		<form name="form" action="" method="post">
		<div>
			<input type="date" name="start">
			<input type="date" name="finish">
			<input type="submit" value="check" name="check">
		</div>
		</form>
</body>
<html>
<?php
	}else{
		$start=$_POST['start'];
		$finish=$_POST['finish'];
		$amount=totalProducts($db,$start,$finish);
		var_dump($amount);
		//Cambiarlo para hacer multitabla
	}
	//Functions
	function totalProducts($db,$start,$finish){
		$orderNum=[];
		$amount=[];
		$result=mysqli_query($db,"SELECT orderNumber FROM orders WHERE orderDate>='$start' AND orderDate<='$finish'");
		if($result){
			while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
				$orderNum[]=$row;
			}
			foreach($orderNum as $l => $d){
				$n=$d['orderNumber'];
				$on = intval($n,10);
				$res=mysqli_query($db,"SELECT productCode,quantityOrdered FROM orderdetails WHERE orderNumber='$on'");
				if ($res){
					while($row2 = mysqli_fetch_assoc($res)){
						$amount[]=$row2;
					}
				}
			}
		}
		return $amount;
		
	}
?>