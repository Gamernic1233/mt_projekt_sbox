<?php
namespace Backend\Exceptions;

class NotFoundException extends \Exception {
    public function __construct($message = "Resource not found") {
        parent::__construct($message);
    }
}