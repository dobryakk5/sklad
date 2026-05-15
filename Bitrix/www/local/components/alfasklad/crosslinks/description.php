<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    "NAME" => "Перелинковка",
    "DESCRIPTION" => "Компонент для перелинковски страниц",
    "PATH" => array(
        "ID" => "custom_components",
        "CHILD" => array(
            "ID" => "custom_crosslinks",
            "NAME" => "Перелинковка"
        )
    ),
);
