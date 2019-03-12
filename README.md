Travelpayouts widgets and API for Yii2
================
[![Packagist](https://img.shields.io/packagist/l/sonko-dmitry/travelpayouts.svg)](https://github.com/SonkoDmitry/travelpayouts/blob/master/LICENSE.md)
[![Packagist](https://img.shields.io/packagist/v/sonko-dmitry/travelpayouts.svg)](https://packagist.org/packages/sonko-dmitry/travelpayouts)
[![Packagist](https://img.shields.io/packagist/dt/sonko-dmitry/travelpayouts.svg)](https://packagist.org/packages/sonko-dmitry/travelpayouts)
[![Yii](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)

This extension is the way to integrate [Travelpayouts.com](http://travelpayouts.com) API and widgets with your Yii2 application.

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/). 

 To install, either run
 ```
 $ php composer.phar require sonko-dmitry/travelpayouts:*
 ```
 or add
 ```
 "sonko-dmitry/travelpayouts": "*"
 ```
 to the `require` section of your `composer.json` file.


Widgets usage
-----
1. Add the component in your view file:
 ```php
 use SonkoDmitry\travelpayouts\widgets\SubscriptionWidget;
 ```

2. Now you can use component
 ```php
 <?= SubscriptionWidget::widget([
     'backgroundColor' => '#00b1dd',
     'originIata' => 'PEE',
     'originName' => 'Пермь',
     'destinationIata' => 'MOW',
     'destinationName' => 'Москва',
     'marker' => '12345',
     'powered_by' => true,
 ]) ?>
 ```
 Where "12345" marker value is your Travelpayouts partner token.
 
 Usage as component
 ------------------
 
0. Add the component configuration in your *global* `main.php` config file:
```php
'travelpayouts' => [
    'class' => 'SonkoDmitry\travelpayouts\Travelpayouts',
    'token' => '12345',
    //'useLocalData' => false, //you can change this property globally for all data components, if you want use only remote data for examle
],
```
Where "12345" marker value is your Travelpayouts partner token.
  
0. Now you can use component (list all contries in data)
```php
$countries = Yii::$app->travelpayouts->data->countries;

``` 