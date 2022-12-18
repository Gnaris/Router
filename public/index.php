<?php

require "../vendor/autoload.php";

use App\Entity\Route\HttpMethod;
use App\Config\Route;

Route::create();

Route::register(HttpMethod::GET, "/article/:id/auteur/:name", function(array $args){
    echo json_encode($args);
});
Route::register(HttpMethod::GET, "/article/all", function(){
    echo "Hello";
});

Route::run();
?> 