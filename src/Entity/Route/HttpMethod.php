<?php

namespace App\Entity\Route;

enum HttpMethod : string{
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";
    case DELETE = "DELETE";
}

?>