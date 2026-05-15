<?php

file_put_contents( __DIR__ . '/log.txt', date(DATE_ATOM)."\n", FILE_APPEND);
file_put_contents(__DIR__ . '/log.txt', print_r($_REQUEST, true), FILE_APPEND );

file_put_contents(__DIR__ . '/log.txt', print_r(file_get_contents('php://input'), true), FILE_APPEND );