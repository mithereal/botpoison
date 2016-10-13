<?php

namespace Mithereal\Blackhole;

interface Blackhole_Interface

{
    public function addIp($ip);
    public function removeIp($ip);
    public function isFlagged($ip);

    public function set($var, $value);
    public function get($var);
}