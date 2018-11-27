<?php
	$zipCode = $_POST['zipCode'];
	$userName = $_POST['userName'];
	require "connectDB.php";
	if(isset($dbConnError))
	{
		$reportError = "There was an error connecting to our database. Please try again.";
	}
	else
	{
		try
		{
			$stmt = $conn->prepare("select * from customers where UUsername = :username or CZipCode = :zipCode");
			$stmt->bindParam(':username', $userName);
			$stmt->bindParam(':zipCode', $zipCode);
			$result = $stmt->execute();
			if(!$result)
			{
				//throw new Exception();
			}
			$result = $stmt;
			//$command = "select * from customers where UUsername = '". $userName. "' or CZipCode ='".$zipCode."'";
			//$result = $conn->query($command);
			$counter = 0;
			if($result->rowCount() > 0)
			{
				$report = "<br/><table border = 1><tr>";
				$report .= "<th>First Name</th>";
				$report .= "<th>Last Name</th>";
				$report .= "<th>Telephone</th>";
				$report .= "<th>Cellphone</th>";
				//$report .= "<th>Email</th>";
				$report .= "<th>Username</th>";
				$report .= "<th>Street</th>";
				$report .= "<th>City</th>";
				$report .= "<th>State</th>";
				$report .= "<th>Zip Code</th>";
				$report .= "<th>Level</th>";
				$report .= "<th>Qt Barrels</th>";
				$report .= "<th>Credit</th>";
				$report .= "</tr>"; 
				foreach($result as $row)
				{
					$selectedCids[$counter] = $row['Cid'];
					$UFname = $row['UFname'];
					$ULname = $row['ULname'];
					$UPhone = $row['UPhone'];
					$UCellPhone = $row['UCellPhone'];
					$Uemail = $row['Uemail'];
					$UUserName = $row['UUserName'];
					$selectedUUserNames[$counter] = $UUserName; $counter ++;
					$CStreet = $row['CStreet'];
					$CCity = $row['CCity'];
					$CState = $row['CState'];
					$CZipCode = $row['CZipCode'];
					$CLevel = $row['CLevel'];
					$CQBarrels = $row['CQBarrels'];
					$CCredit = $row['CCredit'];
					$report .= "<tr>";
					
					$report .= "<td>".$UFname ."</td>";
					$report .= "<td>".$ULname ."</td>";
					$report .= "<td>".$UPhone ."</td>";
					$report .= "<td>".$UCellPhone ."</td>";
					//$report .= "<td>".$Uemail ."</td>";
					$report .= "<td>".$UUserName ."</td>";
					$report .= "<td>".$CStreet ."</td>";
					$report .= "<td>".$CCity ."</td>";
					$report .= "<td>".$CState ."</td>";
					$report .= "<td>".$CZipCode ."</td>";
					$report .= "<td>".$CLevel ."</td>";
					$report .= "<td>".$CQBarrels ."</td>";
					$report .= "<td>".$CCredit ."</td>";
					$report .= "<tr>";
				}	
				$report .= "</table><br/>";
			}
			else
			{
				$report = "There are no customers associated with either the entered zip code or the user name";
			}			
			for($i = 0; $i < $counter; $i++)
			{
				$command = "select * from transactions where Cid = ". $selectedCids[$i];
				//echo $command;
				$result = $conn->query($command);
				if($result->rowCount() > 0)
				{
					$report .= $selectedUUserNames[$i]."<br>";
					$report .= "<table border = 1><tr>";
					$report .= "<th>XID</th>";
					$report .= "<th>Buy or Sell</th>";
					$report .= "<th>QtofBarrels</th>";
					$report .= "<th>Commission Value</th>";
					$report .= "<th>By oil or cash</th>";
					$report .= "<th>Transaction Value</th>";
					$report .= "<th>Date</th>";
					$report .= "<th>TID</th>";
					$report .= "<th>CID</th>";
					$report .= "</tr>"; 
					foreach($result as $row)
					{
						$Xid = $row['Xid'];
						$XBuyFlag = $row['XBuyFlag'];
						$XQtbarrels = $row['XQtbarrels'];
						$XCommissionValue = $row['XCommissionValue'];
						$XCommissionPaymentType = $row['XCommissionPaymentType'];
						$XValue = $row['Xvalue'];
						$XDate = $row['XDate'];
						$Tid = $row['Tid'];
						$Cid = $row['Cid'];
						$report .= "<tr>";
						$report .= "<td>".$Xid."</td>";
						$report .= "<td>".$XBuyFlag."</td>";
						$report .= "<td>".$XQtbarrels."</td>";
						$report .= "<td>".$XCommissionValue."</td>";
						$report .= "<td>".$XCommissionPaymentType."</td>";
						$report .= "<td>".$XValue."</td>";
						$report .= "<td>".$XDate."</td>";
						$report .= "<td>".$Tid."</td>";
						$report .= "<td>".$Cid."</td>";
						$report .= "</tr>";
					}	
					$report .= "</table></br>";
				}
				else
				{
					$report = "No transaction history found for the customer with the id ".$Cid[$i].".";
				}	
			}
		}
		catch (Exception $e)
		{
			$reportError = "There was a problem showing the report.";
			//echo $e->getMessage("There was something wrong placing your order");		
		}
	}
?>