<?php

function dump_and_die($value)
{
    echo '<br>';
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
}