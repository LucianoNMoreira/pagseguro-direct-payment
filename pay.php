<?php
date_default_timezone_set('America/Sao_Paulo');
require_once "lib/vendor/autoload.php";

$creditCardToken = htmlspecialchars($_POST["token"]);
$senderHash = htmlspecialchars($_POST["senderHash"]);

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

\PagSeguro\Configuration\Configure::getAccountCredentials()

?>

<html>
<head>
    <meta charset="UTF-8">
</head>

<?php
$creditCard = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();
$creditCard->setReceiverEmail('vendedor@mail.com');
$creditCard->setCurrency("BRL");

$creditCard->setReference("LIBPHP000001");
$creditCard->addItems()->withParameters('0001', 'Notebook prata', 2, 10.00);
$creditCard->addItems()->withParameters('0002', 'Notebook preto', 2, 5.00);

$creditCard->setSender()->setName('João Comprador');
$creditCard->setSender()->setEmail('c54939881101756041280@sandbox.pagseguro.com.br');
$creditCard->setSender()->setPhone()->withParameters(11, 56273440);
$creditCard->setSender()->setDocument()->withParameters('CPF', '05747108417');
$creditCard->setSender()->setHash($senderHash);
// $creditCard->setSender()->setIp('127.0.0.0');

$creditCard->setShipping()->setAddress()->withParameters(
    'Av. Brig. Faria Lima',
    '1384',
    'Jardim Paulistano',
    '01452002',
    'São Paulo',
    'SP',
    'BRA',
    'apto. 114'
);

$creditCard->setBilling()->setAddress()->withParameters(
    'Av. Brig. Faria Lima',
    '1384',
    'Jardim Paulistano',
    '01452002',
    'São Paulo',
    'SP',
    'BRA',
    'apto. 114'
);

$creditCard->setToken($creditCardToken);
$creditCard->setInstallment()->withParameters(1, '30.00');
$creditCard->setHolder()->setBirthdate('01/10/1979');
$creditCard->setHolder()->setName('João Comprador'); // Equals in Credit Card
$creditCard->setHolder()->setPhone()->withParameters(11, 56273440);
$creditCard->setHolder()->setDocument()->withParameters('CPF', '05747108417');
$creditCard->setMode('DEFAULT');

try {
    $result = $creditCard->register(
        \PagSeguro\Configuration\Configure::getAccountCredentials()
    );
    echo "<pre>";
    print_r($result);
} catch (Exception $e) {
    echo "</br> <strong>";
    die($e->getMessage());
}

?>

<body>
    <h1>Pagseguro Test</h1>
    
</body>
</html>