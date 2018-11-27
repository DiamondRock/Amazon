<?php
	$startDate = $_POST['startDate'];
	$reportType = $_POST['reportType'];
	require "connectDB.php";
	if(isset($dbConnError))
	{
		$reportError = "There was an error connecting to our database. Please try again.";
	}
	else
	{
		$startDate = date("Y-m-d", strtotime($startDate));	
		$nextDate = $startDate;
		if($reportType == 'w')
		{
			$nextDate = strtotime(date("Y-m-d", strtotime($startDate)) . " +1 week");
			$nextDate = date("Y-m-d", $nextDate);
			//echo $nextDate;
		}
		else if($reportType == 'm')
		{
			$nextDate = strtotime(date("Y-m-d", strtotime($startDate)) . " +1 month");
			$nextDate = date("Y-m-d", $nextDate);
			//echo $nextDate;
		}
		try
		{
			$command = "select * from transactions where XDate >= '". $startDate. "' and XDate <= '".$nextDate."'";
			$result = $conn->query($command);
			if($result->rowCount() > 0)
			{
				$report = "<table border = 1><tr>";
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
				$report .= "</table>";
			}
			else
			{
				$report = "There are no transactions in this period";
			}
		}
		catch (Exception $e)
		{
			$reportError = "There was a problem showing the report.";
			//echo $e->getMessage("There was something wrong placing your order");		
		}
	}
?>