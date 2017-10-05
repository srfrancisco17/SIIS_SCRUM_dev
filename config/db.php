<?php

<<<<<<< HEAD
return [
    'class' => 'yii\db\Connection',
    
    //Configuracion Postgres-CDO
    
        'dsn' => 'pgsql:host=192.168.101.76;port=5434;dbname=desarrollo_siis',
        'username' => 'admin',
        'password' => 'coc70a',
    
    //Configuracion Postgres-Casa
    /*
        'dsn' => 'pgsql:host=localhost;port=5432;dbname=desarrollo',
        'username' => 'postgres',
        'password' => '123456',
    */
    //Configuracion MySQL-CDO
    /*
    'dsn' => 'mysql:host=localhost;dbname=desarrollo',
    'username' => 'root',
    'password' => '',   
    'charset' => 'utf8',
    */
=======
//Configuracion Postgres-CDO
return [
    'class' => 'yii\db\Connection',
	'dsn' => 'pgsql:host=192.168.101.76;port=5434;dbname=desarrollo_siis',
	'username' => 'admin',
	'password' => 'coc70a'
>>>>>>> c0e9523c33d80f33bc96fe1f0dce9d6d8bbde5ef
];
