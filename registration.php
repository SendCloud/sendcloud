<?php

use Magento\Framework\Component\ComponentRegistrar;

// SETUP DEVELOPMENT ENVIRONMENT

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'SendCloud_SendCloud',
    __DIR__
);
