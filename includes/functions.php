<?php
function addOrder($pdo){
	
	$stmt_inserCustomer = $pdo->prepare('INSERT INTO pizza_customers (cust_fname, cust_lname, cust_phone, cust_address, cust_zip, cust_city, cust_email) VALUES (:fname, :lname, :phone, :address, :zip, :city, :email)');
	$stmt_inserCustomer->bindParam(':fname', $_POST['fname'], PDO::PARAM_STR);
	$stmt_inserCustomer->bindParam(':lname', $_POST['lname'], PDO::PARAM_STR);
	$stmt_inserCustomer->bindParam(':phone', $_POST['phone'], PDO::PARAM_STR);
	$stmt_inserCustomer->bindParam(':address', $_POST['address'], PDO::PARAM_STR);
	$stmt_inserCustomer->bindParam(':zip', $_POST['zip'], PDO::PARAM_STR);
	$stmt_inserCustomer->bindParam(':city', $_POST['city'], PDO::PARAM_STR);
	$stmt_inserCustomer->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
	$stmt_inserCustomer->execute();
	
	//Hämta up sista Inserts id
	
	$last_id = $pdo->lastInsertId();
	echo $last_id;
	echo "Top1: " . $_SESSION['topping1'] . "<br>";
	echo "Top2: " . $_SESSION['topping2'] . "<br>";
	echo "Top3: " . $_SESSION['topping3'] . "<br>";
	echo "Delivery: " . $_SESSION['delivery'] . "<br>";
	echo "Oregano: " . $_SESSION['oregano'] . "<br>";
	echo "Garlic: " . $_SESSION['Garlic'] . "<br>";
	echo "Size: " . $_SESSION['size'] . "<br>";
	echo "Additional info: " . $_SESSION['additional-info'] . "<br>";
	echo "Allergy: " . $_SESSION['allergy1'] . "<br>";
	
	/*$allergy = intval($_SESSION['allergy1']);
	$garlic = intval($_SESSION['Garlic']);
	$oregano = intval($_SESSION['oregano']);
	$delivery = intval($_SESSION['delivery']); */
	
	//Lägg in beställningen och skapa länkning för foreign keys
	$stmt_inserOrder = $pdo->prepare('INSERT INTO pizza_orders 
	(pizza_topping_1_fk, pizza_topping_2_fk, pizza_topping_3_fk, pizza_delivery, customer_fk, pizza_oregano, pizza_garlic, pizza_size_fk, order_info, pizza_gluten, order_status_fk) 
	VALUES 
	(:pizza_topping_1, :pizza_topping_2, :pizza_topping_3, :pizza_delivery, :customer_fk, :pizza_oregano, :pizza_garlic, :pizza_size_fk, :order_info, :allergy1, 1)');
	$stmt_inserOrder->bindParam(':pizza_topping_1', $_SESSION['topping1'], PDO::PARAM_INT);
	$stmt_inserOrder->bindParam(':pizza_topping_2', $_SESSION['topping2'], PDO::PARAM_INT);
	$stmt_inserOrder->bindParam(':pizza_topping_3', $_SESSION['topping3'], PDO::PARAM_INT);
	$stmt_inserOrder->bindParam(':pizza_delivery', $_SESSION['delivery'], PDO::PARAM_INT);
	$stmt_inserOrder->bindParam(':customer_fk', $last_id, PDO::PARAM_INT);
	$stmt_inserOrder->bindParam(':pizza_oregano', $_SESSION['oregano'], PDO::PARAM_INT);
	$stmt_inserOrder->bindParam(':pizza_garlic', $_SESSION['Garlic'], PDO::PARAM_INT);
	$stmt_inserOrder->bindParam(':pizza_size_fk', $_SESSION['size'], PDO::PARAM_INT);
	$stmt_inserOrder->bindParam(':order_info', $_SESSION['additional-info'], PDO::PARAM_STR);
	$stmt_inserOrder->bindParam(':allergy1', $_SESSION['allergy1'], PDO::PARAM_INT);
	
//execute returnerar true om det lyckas-> vi skickar användarfeedback tillbaka dit funktionen har startat
	if($stmt_inserOrder->execute()){
		return "Order placed successfully";
	}
	else{
		return "Something went wrong, try again later or contact support";
	}
}

function updateOrderStatus($pdo){
	
	$stmt_updateOrderStatus = $pdo->prepare("
	UPDATE pizza_orders
	SET order_status_fk = :newStatus
	WHERE pizza_id = :currentId
	");
	$stmt_updateOrderStatus->bindParam(":newStatus", $_POST['order-status']);
	$stmt_updateOrderStatus->bindParam(":currentId", $_POST['cust-id']);
	
	if($stmt_updateOrderStatus->execute()){
		return "<h2 class='text-success'>Order status uppdated</h2>";
	}
	else{
		return "<h2 class='text-danger'>Something went wrong, try again later or contact support</h2>";
	}
}

function selectNodeInfo($pdo, $currentId){
	
	$stmt_getNodeData = $pdo->prepare('SELECT * FROM pizza_pages WHERE page_id = :id');
	$stmt_getNodeData->bindParam(':id', $currentId, PDO::PARAM_INT);
	$stmt_getNodeData->execute();
	return $stmt_getNodeData->fetch();	
}

function editNode($pdo, $currentId){
	$stmt_editNodeData = $pdo->prepare('
	UPDATE pizza_pages
	SET page_heading = :pheading, page_text = :ptext 
	WHERE page_id = :id');
	$stmt_editNodeData->bindParam(':id', $currentId, PDO::PARAM_INT);
	$stmt_editNodeData->bindParam(':pheading', $_POST['heading'], PDO::PARAM_STR);
	$stmt_editNodeData->bindParam(':ptext', $_POST['page-text'], PDO::PARAM_STR);
	
	if($stmt_editNodeData->execute()){
		return "<h2 class='text-success'>Content uppdated successfully, stand by for reload</h2>";
	}
	else{
		return "<h2 class='text-danger'>Something went wrong, try again later or contact support</h2>";
	}
}


?>