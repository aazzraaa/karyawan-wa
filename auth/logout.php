<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';

$_SESSION = [];
session_destroy();

redirectTo('auth/login.php');
