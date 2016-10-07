<?php

	/* For references see http://php.net/manual/en/book.ftp.php */

	// Set up basic connection. This use port 21 by default
    $connection = ftp_connect("ftp.example.com");

    if(!$connection){
        exit;
    }

    // login with username and password
    // returns a boolean result
    $login = ftp_login($connection, "username", "password");

    if(!$login){
        ftp_close($connection);
        exit;
    }

	// turn passive mode on
	ftp_pasv($connection, true);

	// downloads a file from the FTP server
	// returns a boolean result
	$get = ftp_get($connection, "local.txt", "remote.txt", FTP_BINARY);

	if($get){
		echo "Successfully written to local file\n";
	}

	// uploads a file to the FTP server
	// returns a boolean result
	$put = ftp_put($connection, "remote_new.txt" , "local.txt" , FTP_BINARY);

	if($put){
		echo "Successfully written to remote file\n";
	}

    ftp_close($connection);

?>