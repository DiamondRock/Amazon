<?php
	$barrelsNum = $_POST['barrelsNum'];
	$commissionType = $_POST['commissionType'];
	$customersUserName = $_POST['customersUserName'];
	require "connectDB.php";
	if(isset($dbConnError))
	{
		$buyError = "There was an error connecting to our database. Please try again.";
	}
	else
	{	
		$stmt = $conn->prepare("select CLevel from Customers where UUserName = :username");
		$stmt->bindParam(':username', $customersUserName);
		$result = $stmt->execute();
		if(!$result)
		{
			//throw new Exception();
		}
		$result = $stmt;
		//$command = "select CLevel from Customers where UUserName = '". $customersUserName. "'";
		//$result = $conn->query($command);
		if($result->rowCount() == 1)
		{
			$customerStatus = $result->fetch(PDO::FETCH_BOTH)[0];
		}
		else
		{
			$buyError = "There was a problem placing your order.";
			goto errorLine;
		}
		$todayDate = date("Y-m-d");
		$command = "select PrBarrelPrice, silverCommissionRate, goldCommissionRate from PRICE where PrDate = '". $todayDate. "'";
		$result = $conn->query($command);
		if($result->rowCount() == 1)
		{
			$result = $result->fetch(PDO::FETCH_BOTH);
			$oilPrice = $result[0];
			$silverCommissionRate = $result[1];
			$goldCommissionRate = $result[2];
		}
		else
		{
			$buyError = "There was a problem placing the order.";
		}
		// calculating commission. It is in calculated in terms of barrels
		//echo "silver commision rate is ".$silverCommissionRate."<br>";
		if($customerStatus == 'G')
		{
			$commission = $barrelsNum * $goldCommissionRate;
		}
		else
		{
			$commission = $barrelsNum * $silverCommissionRate;
		}
		//echo $commission."<br>";
		///start the transaction
		try
		{
			$command = "SET autocommit=0;";
			$conn->exec($command);
			$command = "START TRANSACTION";
			$conn->exec($command);
			$stmt = $conn->prepare("select Cid from CUSTOMERS where UUserName = :username");
			$stmt->bindParam(':username', $customersUserName);
			$result = $stmt->execute();
			if(!$result)
			{
				//throw new Exception();
			}
			$result = $stmt;
			//$command = "select Cid from CUSTOMERS where UUserName = '".$customersUserName."'";
			//$result = $conn->query($command);
			if($result->rowCount() == 1)
			{
				$customersId = $result->fetch(PDO::FETCH_BOTH)[0];
			}
			else
			{
				$buyError = "There was a problem placing your order.";
				throw Exception();
			}
			$XValue = $oilPrice * $barrelsNum;
			$command = "insert into transactions(XBuyFlag, XqtBarrels, XCommissionValue, XCommissionPaymentType, Xvalue, Xdate, Tid, Cid) Values("
			."'B'"
			.", '".$barrelsNum."'"
			.", '".$commission * $oilPrice."'"
			.", '".$commissionType."'"
			.", '".$XValue."'"
			.", '".$todayDate."'"
			.", '".$_SESSION['id']."'"
			.", '".$customersId."'"
			.")";
			//echo $command;
			$conn->exec($command);
			$stmt = $conn->prepare("select CCredit, CQBarrels from CUSTOMERS where UUserName = :username");
			$stmt->bindParam(':username', $customersUserName);
			$result = $stmt->execute();
			if(!$result)
			{
				//throw new Exception();
			}
			$result = $stmt;
			//$command = "select CCredit, CQBarrels from CUSTOMERS where UUserName='".$customersUserName."'";
			//$result = $conn->query($command);
			$result = $result->fetch(PDO::FETCH_BOTH);
			$credit = $result[0];
			$qtbarrels = $result[1];
			if($commissionType == "C")
			{
				$credit = $credit - $XValue - $commission * $oilPrice;
				$qtbarrels = $qtbarrels + $barrelsNum;
			
			}
			else
			{
				$credit = $credit - $XValue;
				$qtbarrels = $qtbarrels + $barrelsNum - $commission;
			}
			$stmt = $conn->prepare("update CUSTOMERS set CCredit='".$credit."', CQBarrels='".$qtbarrels."' where UUserName= :username");
			$stmt->bindParam(':username', $customersUserName);
			$result = $stmt->execute();
			if(!$result)
			{
				//throw new Exception();
			}
			//$command = "update CUSTOMERS set CCredit='".$credit."', CQBarrels='".$qtbarrels."' where UUserName='".$customersUserName."'";
			//echo "<br>".$command;
			//$conn->exec($command);
			$command = "COMMIT";
			$conn->exec($command);
		}
		catch (Exception $e)
		{
			$command = "ROLLBACK";
			$conn->exec($command);
			$buyError = "There was a problem placing the order.";
			//echo $e->getMessage("There was something wrong placing your order");
		}
		errorLine:
	}
?>