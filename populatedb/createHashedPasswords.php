<?php

	die(password_hash("admin", PASSWORD_BCRYPT));
	//echo "x";
	//echo "y";
	$handle = fopen("originalPasswords.txt", "r");
	$writeHandle = fopen("hashedPasswords.txt", "w");
	if ($handle) 
	{
		while (($line = fgets($handle)) !== false) 
		{
			echo $line;
			echo strlen($line)."<br>";
			echo substr($line,0,strlen($line)-2);
			$str = PASSWORD_HASH(substr($line,0,strlen($line)-2), PASSWORD_BCRYPT);
			fwrite($writeHandle, $str."\n");
		}

		fclose($handle);
	} else 
	{
		echo "There was an error reading the file.";
	}
?>