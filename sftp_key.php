<?php

	/* For references see
		https://techtuts.info/2014/06/convert-ppk-id_rsa-linux/
    	http://superuser.com/questions/232362/how-to-convert-ppk-key-to-openssh-key-under-linux
    	http://www.asim.pk/2010/05/27/installing-phps-ssh2-extension-on-ubuntu/
		http://php.net/manual/en/book.ssh2.php 
		http://php.net/manual/en/wrappers.ssh2.php
	*/

	// Set up ssh connection. This use port 22 by default
    $connection = ssh2_connect("ftp.example.com");

    if(!$connection){
        exit;
    }

    // login with username and keys
    //
    // To generate public and private key, I use:
    // 		puttygen key.ppk -O public-openssh -o ~/.ssh/id_rsa.pub
    // 		puttygen key.ppk -O private-openssh -o ~/.ssh/id_rsa
    //
    // returns a boolean result
    $login = ssh2_auth_pubkey_file( $connection, "username",
                    '~/.ssh/id_rsa.pub',
                    '~/.ssh/id_rsa'
                );

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