<?php
include('conexion.php');
   session_start();
   echo "Hola ".$_SESSION['user'];
	   $oln=count($_SESSION['shop']);
	   $date=date("Y-m-d");
	   $shop=array();
	   $shop[]=$_SESSION['shop'];
	   var_dump($shop);
	   foreach ($_SESSION['shop'] as $l =>$d){
		$comment=$d[3];
		$p=$d[0];
		$check=$d[2];
		$id=$_SESSION['id'];
		$lot=$d[1];
		$price=price ($db,$p);
		$amount=$lot*$price;
		$number=contOrders($db);
		$code=$number+10100+$l;
		newPayment($db,$check,$id,$date,$amount);
		newOrder($db,$date,$comment,$id,$code);
		newOrderDetails($db,$code,$p,$lot,$oln);
		switchStock($db,$p,$lot);
	   }
		echo "Caballero su pedido ha sido realizado exitosamente";
	   $_SESSION['shop']=null;
		
//funciones del sistema
function newOrderDetails($db,$code,$p,$lot,$oln){
	$result=mysqli_query($db,"SELECT productCode,buyPrice FROM products WHERE productName='$p'");
	if ($result){
		$row = mysqli_fetch_assoc($result);
		$pCode = $row['productCode'];
		$price = $row['buyPrice'];
		$sql="INSERT INTO orderdetails (orderNumber,productCode,quantityOrdered,priceEach,OrderLineNumber)
		VALUES ('$code','$pCode','$lot','$price','$oln')";
		mysqli_query($db,$sql);
	}
}
function contOrders($db){
	$orders=[];
	$result= mysqli_query($db,"SELECT orderNumber FROM orders group by orderNumber");
	while($row = mysqli_fetch_assoc($result)){
			$orders[]=$row['orderNumber'];
		}
	$total=count($orders);
	return $total;
}
function switchStock($db,$p,$lot){
	$sql="SELECT quantityinstock FROM products WHERE productName='$p'";
	$result=mysqli_query($db,$sql);
	if ($result){
		$row = mysqli_fetch_assoc($result);
		$new = $row['quantityinstock']-$lot;
		mysqli_query($db,"UPDATE products SET quantityinstock='$new' WHERE productName='$p'");
	}
}
function price ($db,$p){
	$result=mysqli_query($db,"SELECT buyPrice FROM products WHERE productName='$p'");
	$row = mysqli_fetch_assoc($result);
	return $row['buyPrice'];
}
function newPayment($db,$check,$id,$date,$amount){
	$sql="INSERT INTO payments (customerNumber,checkNumber,paymentDate,amount) VALUES ('$id','$check','$date','$amount')";
	mysqli_query($db,$sql);
}
function newOrder($db,$date,$comment,$id,$code){
	$sql="INSERT INTO orders (orderNumber,orderDate,requiredDate,shippedDate,status,comments,customerNumber)
	VALUES ('$code','$date','$date','null','In Process','$comment','$id')";
	mysqli_query($db,$sql);
}
function getProducts($db){
	$products=array();
	$result= mysqli_query($db,"SELECT * FROM products WHERE quantityinstock>0");
		while($row = mysqli_fetch_assoc($result)){
			$products[]=$row;
		}
	return $products;
}
?>