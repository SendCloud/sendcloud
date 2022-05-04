# SendCloud Magento 2 module

## Requirements
This module requires Magento2 CE version 2.2 or higher.

# Beta checkout installation
To try the beta checkout functionality please follow these instructions:
1. Add the Sendcloud repository to the list of repositories within the `composer.json` file:
```
"repositories": {
    "sendcloud": {
        "type": "vcs",
        "no-api": true,
        "url": "git@github.com:SendCloud/sendcloud.git"
    }
}
```
2. Reference the `dynamic-checkout-beta` feature branch within the list of dependencies in the `composer.json`file:
```
"require": {
    "sendcloud/sendcloud": "dev-dynamic-checkout-beta",
}
```
3. Update that dependency using the following command:
```
composer update sendcloud/sendcloud
```
4. Run the following console commands in the Magento 2 root folder:
```
php bin/magento module:enable SendCloud_SendCloud
php bin/magento setup:upgrade
```
5. This will set up the plugin and after both commands finish their execution, the plugin will be available in the Magento admin, under Sales > Sendcloud > Configuration. 

## Installation
This module can be installed through Composer. 
````
composer require sendcloud/sendcloud
php bin/magento module:enable SendCloud_SendCloud
php bin/magento setup:upgrade
````

## Configuration
A guide on how to configure this module can be found on the [Magento Marketplace](https://marketplace.magento.com/sendcloud-sendcloud.html)

## Support
For support, please contact [SendCloud](https://www.sendcloud.com/contact/)

## License
Apache License 2.0