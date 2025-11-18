<?php

$host = $argv[1] ?? 'localhost';
$port = $argv[2] ?? '8000';
$root = 'public';

echo "═══════════════════════════════════════════════════\n";
echo "  🚀 Servidor PHP Iniciado\n";
echo "═══════════════════════════════════════════════════\n\n";
echo "  ➜  Local:   http://{$host}:{$port}\n";
echo "  ➜  Root:    {$root}/\n\n";
echo "═══════════════════════════════════════════════════\n";
echo "\n  Pressione Ctrl+C para parar o servidor\n\n";

// Inicia o servidor
passthru("php -S {$host}:{$port} -t {$root}");