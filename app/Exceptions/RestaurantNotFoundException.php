<?php

namespace App\Exceptions;

use Exception;

class RestaurantNotFoundException extends Exception
{
    protected $message = "Restaurant not found!";
    protected $code = 404;
}
