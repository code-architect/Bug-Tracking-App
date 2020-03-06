<?php

namespace App\Exception;

use App\Helpers\App;

class ExceptionHandler
{
    public function handle(\Throwable $exception): void
    {
        $app = new App;
        if($app->isDebugMode()){
            var_dump($exception);
        }else{
            echo "This should not have happened, pleas try again";
        }
        exit;
    }
}