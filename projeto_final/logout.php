<?php
session_start();

// Remove todas as variáveis de sessão
session_unset();

// Destroi a sessão atual
session_destroy();

// Evita cache da página anterior (por segurança)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Redireciona para a página de login
header("Location: login.php");
exit;
?>
