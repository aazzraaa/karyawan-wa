<?php
define('BASE_URL', '/karyawan-wa');

function url($path = '')
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function redirectTo($path)
{
    header('Location: ' . url($path));
    exit;
}
