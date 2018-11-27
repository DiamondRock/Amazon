<?php
	$customersPaymentAmount = $_POST['customersPaymentAmount'];
	$customersUserName = $_POST['customersUserName'];
	require "connectDB.php";
	if(isset($dbConnError))
	{
		$paymentError = "There was an error connecting to our database. Please try again.";
	}
	else if($customersPaymentAmount <=0)
	{
		$paymentError = "Payment amount should be greater than zero";
	}
	else
	{	
		$todayDate = date("Y-m-d");
		try
		{
			$command = "SET autocommit=0;";
			$conn->exec($command);
			$command = "START TRANSACTION";
			$conn->exec($command);
			$stmt = $conn->prepare("select Cid, CCredit from Customers where UUserName = :username");
			$stmt->bindParam(':username', $customersUserName);
			$result = $stmt->execute();
			if(!$result)
			{
				//throw new Exception();
			}
			$result = $stmt;
			//$command = "select Cid, CCredit from CUSTOMERS where UUserName = '".$customersUserName."'";
			//echo $command;
			//$result = $conn->query($command);	
			if($result->rowCount() == 1)
			{
				$result = $result->fetch(PDO::FETCH_BOTH);
				$customersId = $result[0];
				$credit = $result[1];
			}
			else
			{
				throw new Exception();
			}
			$command = "insert into payments(Tid, Cid, PAmtpaid, PDate) Values("
			.$_SESSION['id']
			.", ".$customersId
			.", :customersPaymentAmount"
			.", '".$todayDate."'"
			.")";
			$stmt = $conn->prepare($command);
			$stmt->bindParam(':customersPaymentAmount', $customersPaymentAmount);
			$result = $stmt->execute();
			if(!$result)
			{
				//throw new Exception();
			}
			//$command = "insert into payments(Tid, Cid, PAmtpaid, PDate) Values("
			//.$_SESSION['id']
			//.", ".$customersId
			//.", ".$customersPaymentAmount
			//.", '".$todayDate."'"
			//.")";
			//echo $command;
			//$conn->exec($command);
			$credit = $credit + $customersPaymentAmount;
			$stmt = $conn->prepare("update CUSTOMERS set CCredit= ".$credit." where UUserName= :username");
			$stmt->bindParam(':username', $customersUserName);
			$result = $stmt->execute();
			if(!$result)
			{
				//throw new Exception();
			}
			//$command = "update CUSTOMERS set CCredit=".$credit." where UUserName='".$customersUserName."'";
			//$conn->exec($command);
			$command = "COMMIT";
			$conn->exec($command);
		}
		catch (Exception $e)
		{
			$command = "ROLLBACK";
			$conn->exec($command);
			$paymentError = "There was a problem doing the payment";
			//echo $e->getMessage("There was something wrong placing your order");
		}
		errorLine:
	}
?>