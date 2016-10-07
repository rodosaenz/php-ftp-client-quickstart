<?php

	/* For references see
		http://www.asim.pk/2010/05/27/installing-phps-ssh2-extension-on-ubuntu/
		http://php.net/manual/en/book.ssh2.php 
		http://php.net/manual/en/wrappers.ssh2.php
	*/

	// Set up ssh connection. This use port 22 by default
    $connection = ssh2_connect("ftp.example.com");

    if(!$connection){
        exit;
    }

    // login with username and password
    // returns a boolean result
    $login = ssh2_auth_password($connection, "username", "password");

    if(!$login){
        ssh2_exec($connection, 'exit');
        exit;
    }

    // Initialize SFTP subsystem
    $sftp = ssh2_sftp($connection);

    if(!$sftp){
        ssh2_exec($connection, 'exit');
        exit;
    }

    // use of wrappers functions to get remote file
	$stream = fopen( "ssh2.sftp://$sftp/remote.txt", 'r');

	if(!$stream){
		ssh2_exec($connection, 'exit');
		exit;
	}

	// save the stream to local file
	// returns a boolean result
	$put = file_put_contents( "local.txt", $stream );

	if(!$put){
		fclose($stream);
		ssh2_exec($connection, 'exit');
		exit;
	}

	fclose($stream);
    ssh2_exec($connection, 'exit');

?>