<?php
	$transactionId = $_POST['transactionId'];
	require "connectDB.php";
	if(isset($dbConnError))
	{
		$cancelError = "There was an error connecting to our database. Please try again.";
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
			$stmt = $conn->prepare("select * from transactions where Xid = :xid");
			$stmt->bindParam(':xid', $transactionId);
			$result = $stmt->execute();
			if(!$result)
			{
				//throw new Exception();
			}
			$result = $stmt;
			//$command = "select * from transactions where Xid = '".$transactionId."'";
			//echo $command;
			//$result = $conn->query($command);	
			if($result->rowCount() == 1)
			{
				$result = $result->fetch(PDO::FETCH_BOTH);
				$XBuyFlag = $result['XBuyFlag'];
				$XQtbarrels = $result['XQtbarrels'];
				$XCommissionValue = $result['XCommissionValue'];
				$XCommissionPaymentType = $result['XCommissionPaymentType'];
				$XValue = $result['Xvalue'];
				$XDate = $result['XDate'];
				$Tid = $result['Tid'];
				$Cid = $result['Cid'];
			}
			else
			{
				throw new Exception();
			}
			$stmt = $conn->prepare("delete from transactions where Xid = :xid");
			$stmt->bindParam(':xid', $transactionId);
			$result = $stmt->execute();
			if(!$result)
			{
				//throw new Exception();
			}
			$result = $stmt;
			//$command = "delete from transactions where Xid = '".$transactionId."'";
			//echo $command;
			//$conn->exec($command);
			$command = "select PrBarrelPrice from price where PrDate = '".$XDate."'";
			$result = $conn->query($command);
			$PrBarrelPrice = $result->fetch(PDO::FETCH_BOTH)[0];
			$command = "select CCredit, CQBarrels from customers where Cid = '".$Cid."'";
			$result = $conn->query($command);
			$result = $result->fetch(PDO::FETCH_BOTH);
			$credit = $result[0];
			$CQBarrels = $result[1];
			if($XBuyFlag == 'B') // buying
			{
				if($XCommissionPaymentType == 'C')
				{
					$credit = $credit + $XValue + $XCommissionValue;
					$CQBarrels = $CQBarrels - $XQtbarrels;
				}
				else
				{
					$credit = $credit + $XValue;
					$CQBarrels = $CQBarrels - $XQtbarrels + $XCommissionValue/$PrBarrelPrice;
				}
			}
			else // $XBuyFlag= 'S' selling
			{
				if($XCommissionPaymentType == 'C')
				{
					$credit = $credit - $XValue + $XCommissionValue;
					$CQBarrels = $CQBarrels + $XQtbarrels;
				}
				else
				{
					$credit = $credit - $XValue;
					$CQBarrels = $CQBarrels + $XQtbarrels + $XCommissionValue/$PrBarrelPrice;
				}
			}
			$command = "update CUSTOMERS set CCredit=".$credit.", CQBarrels=".$CQBarrels." where Cid = ".$Cid;
			//echo $command;
			$conn->exec($command);
			if($Tid == NULL)
			{
				$Tid = "NULL";
			}
			$command = "insert into auditing(Xid, XBuyFlag, XQTbarrels, XCommissionValue,	XCommissionPmtType, Xvalue,	xDate, Tid,	Cid, CancelDate, TidCancel) Values("
			.$transactionId
			.", '".$XBuyFlag."'"
			.", ".$XQtbarrels
			.", ".$XCommissionValue
			.", '".$XCommissionPaymentType."'"
			.", ".$XValue
			.", '".$XDate."'"
			.", ".$Tid.
			.", ".$Cid
			.", '".$todayDate."'"
			.", ".$_SESSION['id']
			.")";
			//echo $command;
			$conn->exec($command);
			$command = "COMMIT";
			$conn->exec($command);
		}
		catch (Exception $e)
		{
			$command = "ROLLBACK";
			$conn->exec($command);
			$cancelError = "There was a problem canceling the transcation";
			echo $e->getMessage();
		}
		errorLine:
	}
?>