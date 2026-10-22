<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/app.php';

function requireLogin()
{
    if (empty($_SESSION['login'])) {
        redirectTo('auth/login.php');
    }
}

function requireAdmin()
{
    if (empty($_SESSION['admin_login'])) {
        redirectTo('admin/login.php');
    }
}
