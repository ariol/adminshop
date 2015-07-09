<?php

return array(
	'default' => array(
		'type'	     => 'MySQLi',
		'connection' => array(
			'username'   => 'root',
			'password'   => '',
			'database'   => 'database',
			'hostname'   => 'localhost',
		),
		'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => false,
		'profiling' => false
	)
);
