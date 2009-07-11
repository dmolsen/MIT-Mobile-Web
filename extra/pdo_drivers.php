<?

# display which PDO drivers are installed
# run at command line by typing 'php pdo_drivers.php'
foreach (PDO::getAvailableDrivers() as $driver) {
    echo($driver."\r\n");
}

?>