<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';

unset($_SESSION['admin_login'], $_SESSION['admin_username']);

redirectTo('admin/login.php');
