<?php

use MySQL\DB;
use MySQL\AR;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require_once "/var/www/html/PDO/src/MySQL/AR.php";
require_once dirname(__DIR__) . '/vendor/autoload.php';
$logsPath = "/var/www/html/PDO/log/messages.log";
$loader = new FilesystemLoader(dirname(__DIR__) . "/twigTemplates/");
$log = new Logger('log');
$loggerHandler = new StreamHandler($logsPath, Logger::INFO);
$log->pushHandler($loggerHandler);
$twig = new Environment($loader);

echo $twig->render("main.html.twig");

$tableRecord = new AR();
if (isset($_GET['ARGetAll']))
{
    $result = $tableRecord->ARGetAll();
    echo "<p>------------------------------</p>";
    foreach ($result as $record){
        $id = $record["id"];
        $date = $record["date"];
        $user = $record["user"];
        $message = $record["msg"];

        echo "<p>id=$id | дата=$date| пользователь=$user| сообщение=$message</p>";

    }
    echo "<p>------------------------------</p>";
}
if (isset($_GET['ARGetByID']) && isset($_GET['ID']) && (string)$_GET['ID'] !== '')
{
    $id = $_GET['ID'];
    $result = $tableRecord->ARGetByID($id);
    echo "<p>------------------------------</p>";
    if(is_null($result))
    {
        echo "Записи с таким id нет";
    }
    else
    {
        $date = $result->getDate();
        $user = $result->getUser();
        $message = $result->getMsg();

        echo "<p>id=$id | дата=$date| пользователь=$user| сообщение=$message</p>";
    }
    echo "<p>------------------------------</p>";
}

if (isset($_GET['ARGetByName']) && isset($_GET['Newuser']))
{
    $name = $_GET['Newuser'];
    $result = $tableRecord->ARGetByName($name);
    echo "<p>------------------------------</p>";
    foreach ($result as $record){
        $id = $record["id"];
        $date = $record["date"];
        $user = $record["user"];
        $message = $record["msg"];

        echo "<p>id=$id | дата=$date| пользователь=$user| сообщение=$message</p>";

    }
    echo "<p>------------------------------</p>";
}

if (isset($_GET['ARAdd']) && isset($_GET['Newdate'])&& isset($_GET['Newuser'])&& isset($_GET['Newmessage']))
{
    $date = $_GET['Newdate'];
    $name = $_GET['Newuser'];
    $message = $_GET['Newmessage'];
    $addRecord = new AR();
    $addRecord->setDate($date);
    $addRecord->setUser($name);
    $addRecord->setMsg($message);
    $addRecord->ARAdd();

}

if (isset($_GET['ARChange']) && isset($_GET['Newmessage'])&& isset($_GET['ID']) && (string)$_GET['ID'] !== '')
{
    $message = $_GET['Newmessage'];
    $id = $_GET['ID'];
    $result = $tableRecord->ARGetByID($id);
    $result->setMsg($message);
    $result->ARChange();

}

if (isset($_GET['ARDelete']) && isset($_GET['ID']) && (string)$_GET['ID'] !== '')
{
    $id = $_GET['ID'];
    $result = $tableRecord->ARGetByID($id);
    $result->ARDelete();
}
print_messages();
?>