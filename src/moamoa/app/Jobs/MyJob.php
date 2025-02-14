<?php

namespace App\Jobs;

abstract class MyJob {
    public function __construct() {
        $this->process();
    }

    abstract public function process();
}