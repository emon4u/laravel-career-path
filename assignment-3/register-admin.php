<?php

use BankingCLI\RegisterAdmin;

require_once './vendor/autoload.php';

$cliApp = new RegisterAdmin;
$cliApp->run();
