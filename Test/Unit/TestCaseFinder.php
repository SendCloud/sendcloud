<?php

if (class_exists('PHPUnit_Framework_TestCase')) {
    require 'TestCaseFinder/PHPUnit4.php';
    return;
}
require 'TestCaseFinder/PHPUnit6.php';
