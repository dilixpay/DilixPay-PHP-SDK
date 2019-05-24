<?php

        try{
                    
                    $strConection ='mysql:host=localhost;dbname=nom de la base de données';
                               $db = new \PDO($strConection, 'username', 'mot de passe');
                $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                                    
                                    return $db;
                }
                                 catch(PDOException $e){

                    $msg = 'ERREUR PDO dans '.$e->getFile() .$e->getLine().' : '. $e->getMessage();
                                     die($msg);

                    }

