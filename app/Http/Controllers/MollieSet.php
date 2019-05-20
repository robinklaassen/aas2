<?php

# Include the Mollie API and set up the client.

require_once base_path("vendor/mollie/mollie-api-php/src/Mollie/API/Autoloader.php");

$mollie = new \Mollie_API_Client;

$mollie->setApiKey(env("MOLLIE_API_KEY"));

?>