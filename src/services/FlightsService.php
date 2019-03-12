<?php

namespace SonkoDmitry\travelpayouts\services;

use Yii;
use yii\base\Component;
use yii\base\Configurable;
use Exception;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * Компонет для работы с АПИ данных авиабилетов
 *
 * @package SonkoDmitry\travelpayouts\services
 *
 * @property array $cityDirections Популярные направления из города
 */
class FlightsService extends Component implements Configurable
{
    /**
     * @var string партнерский токен
     * @link https://support.travelpayouts.com/hc/ru/articles/203956083
     */
    protected $_token;
    /**
     * @var string Язык для получения и отображения данных. По умолчанию - ru
     */
    public $_locale = 'ru';
    /**
     * @var \yii\httpclient\Client
     */
    protected $_client;
    /**
     * Возможно доступные типы данных
     * @var array
     */
    protected $_available_datas = [
        'cityDirections' => 'v1/city-directions',
    ];
    /**
     * @var string Адрес по которому размещаются данные на сервере
     */
    protected $_host = 'http://api.travelpayouts.com';

    public function __construct($config = [])
    {
        if (!empty($config)) {
            Yii::configure($this, $config);
        }

        parent::__construct($config);
    }

    /*public function __get($name)
    {
        var_dump($params);
        die;
        if (!array_key_exists($name, $this->_available_datas)) {
            throw new Exception('"' . $name . '" data not available');
        }

        if (empty($this->_client)) {
            $this->_client = new Client([
                'baseUrl' => $this->_host,
            ]);
        }


        $path = $this->_available_datas[$name].'?'.http_build_query();

        if (($response = $this->_client->get($path)->send()) && $response->isOk) {
            $return = $response->data;
        } elseif (!empty($response) && $response->statusCode == '404') {
            throw new Exception('"' . $name . '" remote data not available in "' . $this->_locale . '" locale');
        }

        return $return;
    }*/

    /**
     * Популярные направления из города
     *
     * @param string $origin пункт отправления. IATA код города
     * @param string $currency пункт отправления. IATA код города
     *
     * @return mixed
     * @throws \yii\httpclient\Exception
     */
    public function getCityDirections($origin, $currency = 'rub')
    {
        if (empty($this->_client)) {
            $this->_client = new Client([
                'baseUrl' => $this->_host,
            ]);
        }

        $path = '/v1/city-directions?' . http_build_query([
                'origin' => $origin,
                'currency' => $currency,
                'token' => $this->_token,
            ]);

        if (($response = $this->_client->get($path)->send()) && $response->isOk) {
            $return = $response->data;
        } elseif (!empty($response) && $response->statusCode == '404') {
            throw new Exception('remote data not available');
        }

        return $return;
    }

    public function getLatestPrice(
        $origin = null,
        $destination = null,
        $beginning_of_period = null,
        $period_type = null,
        $one_way = null,
        $page = null,
        $limit = null,
        $show_to_affiliates = null,
        $sorting = null,
        $trip_duration = null,
        $currency = null
    ) {
        if (empty($this->_client)) {
            $this->_client = new Client([
                'baseUrl' => $this->_host,
            ]);
        }

        $params = [
            'currency' => $currency,
            'origin' => $origin,
            'destination' => $destination,
            'beginning_of_period' => $beginning_of_period,
            'period_type' => $period_type,
            'one_way' => var_export((boolean) $one_way, true),
            'page' => $page,
            'limit' => $limit,
            'show_to_affiliates' => var_export((boolean) $show_to_affiliates, true),
            'sorting' => $sorting,
            'trip_duration' => $trip_duration,
            'token' => $this->_token,
        ];

        $return = null;
        if (($response = $this->_client->get('/v2/prices/latest?' . http_build_query($params))->send()) && $response->isOk) {
            $return = $response->data;
        } elseif (!empty($response) && $response->statusCode == '404') {
            throw new Exception('remote data not available');
        }

        return $return;
    }

    public function setLocale($value)
    {
        $this->_locale = $value;

        return $this;
    }

    public function setToken($value)
    {
        $this->_token = $value;

        return $this;
    }
}