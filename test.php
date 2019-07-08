<?php 


$colums = 'name';

foreach ($colums as &$column) {

    $column = $column . "=?";
}

var_dump ($column);

