<?php
/*
Title: BotPoison
Description: Automatically trap, block and poison bots that don't obey robots.txt rules
Project URL: https://github.com/mithereal/botpoison
Author: Jason Clark (mithereal@gmail.com)
Release: 10.13.2016
Version: 2.0
*/
namespace Mithereal\Botpoison;

interface Jail_Interface

{
    public function admit($ip);
    public function discharge($ip);
    public function lookup($ip);
    public function validate($ip);
    public function clear();

}