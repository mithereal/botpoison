<?php

namespace Mithereal\Botpoison;

interface Poison_Interface

{
    public function set($var, $value);
    public function get($var);
    public function activate();
}