<?php

namespace Mithereal\Botpoison;

interface Jail_Interface

{
    public function admit($ip);
    public function discharge($ip);
    public function lookup($ip);
    public function validate($ip);
    public function clear();

}