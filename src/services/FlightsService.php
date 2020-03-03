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
 * @property array $airlineDirections Популярные направления авиакомпании
 * @property array $latestPrice Цены на авиабилеты за 48 часов
 *
 * @property string $lastUrl Последний запрашиваемый урл АПИ
 * @property string $lastData Параметры последнего запроса данных
 * @property string $lastResponse Последний ответ от АПИ
 * @property string $lastResponseStatus Последний статус ответ от АПИ
 */
class FlightsService extends Component implements Configurable
{
    protected $_lastUrl;
    protected $_lastData;
    protected $_lastResponseStatus;
    protected $_lastResponse;
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
        'airlineDirections' => 'v1/airline-directions',
        'latestPrice' => '/v2/prices/latest',
    ];
    /**
     * @var string Адрес по которому размещаются данные на сервере
     */
    protected $_host = 'http://api.travelpayouts.com';

    public function getLastUrl()
    {
        return $this->_lastUrl;
    }

    public function getLastData()
    {
        return $this->_lastData;
    }

    public function getLastResponseStatus()
    {
        return $this->_lastResponseStatus;
    }

    public function getLastResponse()
    {
        return $this->_lastResponse;
    }

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
        $this->_lastUrl = $this->_host . '/v1/city-directions';
        $requestData = [
            'origin' => strtoupper($origin),
            'currency' => $currency,
            'token' => $this->_token,
        ];

        $path = '/v1/city-directions?' . http_build_query($requestData);
        if (isset($requestData['token'])) {
            unset($requestData['token']);
        }
        $this->_lastData = json_encode($requestData, JSON_UNESCAPED_UNICODE);
        $this->_lastResponse = $this->_lastResponseStatus = null;

        if (($response = $this->_client->get($path)->send()) && $response->isOk) {
            $return = $response->data;
            $this->_lastResponseStatus = $response->statusCode;
            $this->_lastResponse = json_encode($return, JSON_UNESCAPED_UNICODE);
        } else {
            $this->_lastResponseStatus = $response->statusCode;
            if (!empty($response) && $response->statusCode == '404') {
                throw new Exception('remote data not available');
            }
        }

        return $return;
    }

    /**
     * Популярные направления из города
     *
     * @param string $airline_code авиакомпания, IATA код
     * @param string $limit количество отображаемых записей
     *
     * @return mixed
     * @throws \yii\httpclient\Exception
     */
    public function getAirlineDirections($airline_code = 'SU', $limit = 10)
    {
        if (empty($this->_client)) {
            $this->_client = new Client([
                'baseUrl' => $this->_host,
            ]);
        }

        $requestData = [
            'airline_code' => strtoupper($airline_code),
            'limit' => $limit,
            'token' => $this->_token,
        ];
        $path = '/v1/airline-directions?' . http_build_query($requestData);
        if (isset($requestData['token'])) {
            unset($requestData['token']);
        }
        $this->_lastData = json_encode($requestData, JSON_UNESCAPED_UNICODE);
        $this->_lastResponse = $this->_lastResponseStatus = null;

        if (($response = $this->_client->get($path)->send()) && $response->isOk) {
            $return = $response->data;
            $this->_lastResponseStatus = $response->statusCode;
            $this->_lastResponse = json_encode($return, JSON_UNESCAPED_UNICODE);
        } else {
            $this->_lastResponseStatus = $response->statusCode;
            if (!empty($response) && $response->statusCode == '404') {
                throw new Exception('remote data not available');
            }
        }

        return $return;
    }

    public function getLatestPrice($params)
    {
        $paramsDefault = [
            'currency' => 'rub',
            'origin' => null,
            'destination' => null,
            'beginning_of_period' => null,
            'period_type' => null,
            'one_way' => false,
            'page' => 1,
            'limit' => 30,
            'show_to_affiliates' => true,
            'sorting' => 'price',
            'trip_duration' => null,
        ];

        if (empty($this->_client)) {
            $this->_client = new Client([
                'baseUrl' => $this->_host,
            ]);
        }

        $requestParams = [
            'token' => $this->_token,
        ];

        foreach ($params as $index => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            if ($index == 'origin' || $index == 'destination') {
                $requestParams[$index] = strtoupper($value);
            } elseif ($index == 'one_way' || $index == 'show_to_affiliates') {
                $requestParams[$index] = var_export((boolean) $value, true);
            } else {
                $requestParams[$index] = $value;
            }
        }

        $path = '/v2/prices/latest';
        $this->_lastUrl = $this->_host . $path;

        $path = $path . '?' . http_build_query($requestParams);
        if (isset($requestParams['token'])) {
            unset($requestParams['token']);
        }
        $this->_lastData = json_encode($requestParams, JSON_UNESCAPED_UNICODE);
        $this->_lastResponse = $this->_lastResponseStatus = null;

        $return = null;
        if (($response = $this->_client->get($path, null, [
                'Accept-Encoding' => 'gzip, deflate',
            ])->send()) && $response->isOk) {
            $return = $response->data;
            $this->_lastResponseStatus = $response->statusCode;
            $this->_lastResponse = json_encode($return, JSON_UNESCAPED_UNICODE);
        } else {
            $this->_lastResponseStatus = $response->statusCode;
            if (!empty($response) && $response->statusCode == '404') {
                throw new Exception('remote data not available');
            }
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