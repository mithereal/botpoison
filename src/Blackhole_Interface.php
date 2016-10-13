<?php

namespace Mithereal\Tarpit;

interface Blackhole_Interface

{
    public function addIp($ip);
    public function removeIp($ip);
    public function isFlagged($ip);
}