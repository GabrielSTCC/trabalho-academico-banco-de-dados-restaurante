<?php

$configFile = __DIR__ . '/config.php';

if (!file_exists($configFile)) {
    die('Arquivo config.php não encontrado. Copie config.example.php para config.php e ajuste as credenciais.');
}

$config = require $configFile;

$conexao = new mysqli(
    $config['host'],
    $config['user'],
    $config['password'],
    $config['database'],
    $config['port']
);

$conexao->set_charset('utf8mb4');

if ($conexao->connect_error) {
    $msg = $conexao->connect_error;
    if (strpos($msg, 'Unknown database') !== false) {
        die('Banco "' . $config['database'] . '" não existe. Execute setup.ps1 ou os scripts em sql/.');
    }
    die('Erro na conexão: ' . $msg . '. Verifique se o MySQL está rodando e se config.php está correto.');
}

?>
