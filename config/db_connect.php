<?php //connessione al database

$host= "localhost";
$port= "5432"; //porta di default di postgre
$dbname= "---";
$user= "---";
$pass= "---";

try{
    $conn= new PDO("pgsql:host= $host;port=$port;dbname= $dbname",$user,$pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //per far restituire un errore piu preciso e non semplicemente false
} catch(PDOException $error){
    echo "Impossibile connettersi al database";
    exit();
}
?>