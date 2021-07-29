<?php

class MySQLiConnectionFactory{
	 array(
			'name' => '',
			'host' => '',
			'username' => '',
			'password' => ''
		),
		array(
			'name' => '',
			'host' => '',
			'username' => '',
			'password' => ''
		)
	);

	public static function getCon($serverName,$schema){
		for ($i = 0, $n = count(MySQLiConnectionFactory::$SERVERS); $i < $n; $i ++) {
			$server = MySQLiConnectionFactory::$SERVERS[$i];
			if ($server['name'] == $serverName) {
				$connection = new mysqli($server['host'], $server['username'], $server['password'], $schema, '3306');
				if (mysqli_connect_errno()) {
					throw new Exception('Could not connect to any databases! Please try again later.');
				}
				if (! $connection->set_charset('utf8')) {
					throw new Exception('Error loading character set utf8: ' . $connection->error);
				}
				return $connection;
			}
		}
	}
}
?>