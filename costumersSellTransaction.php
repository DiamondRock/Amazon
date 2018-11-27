<?php
	$barrelsNum = $_POST['barrelsNum'];
	$commissionType = $_POST['commissionType'];
	require "connectDB.php";
	if(isset($dbConnError))
	{
		
		$sellError = "There was an error connecting to our database. Please try again.";
	}
	else
	{	
		try
		{	
			$command = "select CLevel, CQBarrels from Customers where UUserName = '". $_SESSION['userName']. "'";
			$result = $conn->query($command);
			if($result->rowCount() == 1)
			{
				$result = $result->fetch(PDO::FETCH_BOTH);
				$customerStatus = $result[0];
				$customersCQBarrels = $result[1];
			}
			else
			{
				throw new Excpetion();
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
				$sellError = "There was a problem placing your order.";
			}
			// calculating commission. It is calculated in terms of barrels
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
				$XValue = $oilPrice * $barrelsNum;
				$command = "select CCredit, CQBarrels from CUSTOMERS where UUserName='".$_SESSION['userName']."'";
				$result = $conn->query($command);
				$result = $result->fetch(PDO::FETCH_BOTH);
				$credit = $result[0];
				$qtbarrels = $result[1];
				//echo $barrelsNum."<br>".$customersCQBarrels."<br>".$commission."<br>";
				if($commissionType == "C")
				{
					if($barrelsNum > $customersCQBarrels)
					{
						$sellError = "You do not have enough barrels to sell!";
						throw new Exception();
					}
					$credit = $credit + $XValue - $commission * $oilPrice;
					$qtbarrels = $qtbarrels - $barrelsNum;
				}
				else
				{
					if($commission + $barrelsNum > $customersCQBarrels)
					{
						$sellError = $sellError = "You do not have enough barrels to sell!";
						$sellError = $sellError." The commission for you in terms of barrels is ".$commission;
						throw new Exception();
					}
					$credit = $credit + $XValue;
					$qtbarrels = $qtbarrels - $barrelsNum - $commission;
				}
				$command = "insert into transactions(XBuyFlag, XqtBarrels, XCommissionValue, XCommissionPaymentType, Xvalue, Xdate, Tid, Cid) Values("
				."'S'"
				.", '".$barrelsNum."'"
				.", '".$commission * $oilPrice."'"
				.", '".$commissionType."'"
				.", '".$XValue."'"
				.", '".$todayDate."'"
				.", NULL"
				.", '".$_SESSION['id']."'"
				.")";
				//echo $command;
				$conn->exec($command);
				//$command = "update customer set CCredit=$credit, CQBarrels=$qtbarrels where UUserName='$_SESSION['userName']'";
				$command = "update CUSTOMERS set CCredit='".$credit."', CQBarrels='".$qtbarrels."' where UUserName='".$_SESSION['userName']."'";
				//echo "<br>".$command;
				$conn->exec($command);
				$command = "COMMIT";
				$conn->exec($command);
			}
			catch (Exception $e)
			{
				$command = "ROLLBACK";
				$conn->exec($command);
				$sellError = "There was a problem placing your order. ". $sellError;
				//echo $e->getMessage("There was something wrong placing your order");
			}
		}
		catch(Exception $e)
		{
			$sellError = "There was a problem placing your order. ";
		}
	}
?>