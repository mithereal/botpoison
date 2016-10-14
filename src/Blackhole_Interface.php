<?php

namespace Mithereal\Blackhole;

interface Blackhole_Interface

{
    public function swallow($ip);
    public function spit($ip);
    public function detect($ip);
    public function validate($ip);
    public function clear();

}