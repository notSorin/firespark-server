<?php
	$hash = password_hash("somepassword", PASSWORD_DEFAULT);

	print $hash . "\n";

	if(password_verify("somepassword", $hash))
	{
		print "correct\n";
	}
	else
	{
		print "incorrect\n";
	}
?>