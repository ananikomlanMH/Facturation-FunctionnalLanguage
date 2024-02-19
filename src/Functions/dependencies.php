<?php

function _init_app(): void
{
    session_start();
    require dirname(__DIR__, 2) . '/vendor/autoload.php';
    require dirname(__DIR__, 2) . '/src/Functions/layout.php';
    require dirname(__DIR__, 2) . '/src/Functions/views.php';
}