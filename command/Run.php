#!/usr/bin/env php
<?php

namespace Core;

require_once __DIR__ . '/src/autoload.php';

$data = new ConsoleRead($argv);

$executor = new Initializer();
$executor->exec($data);
