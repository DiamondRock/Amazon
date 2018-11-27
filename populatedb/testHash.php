<?php
	$handle = fopen("originalPasswords.txt", "r");
	$writeHandle = fopen("test.txt", "w");
	if ($handle) 
	{
		while (($line = fgets($handle)) !== false) 
		{
			echo $line."<br>";
			echo strlen($line)."<br>";
			$password = substr($line,0,strlen($line)-2);
			echo $password."<br>";
			$str = PASSWORD_HASH(substr($line,0,strlen($line)-2), PASSWORD_DEFAULT);
			echo $str."<br>";
			if(password_verify($password,$str))
			{
				echo "right!";
			}
			else
			{
				echo "passwords don't match";
			}
			fwrite($writeHandle, $str."\n");
		}

		fclose($handle);
	} 
	else 
	{
		echo "There was an error reading the file.";
	}
?>