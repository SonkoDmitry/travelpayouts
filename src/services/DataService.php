<?php

namespace SonkoDmitry\travelpayouts\services;

use Yii;
use yii\base\Component;
use yii\base\Configurable;
use Exception;
use yii\httpclient\Client;

/**
 * Компонет для работы со статическими данными
 *
 * @package \SonkoDmitry\travelpayouts\services
 *
 * @property array $airlines Данные об авиакомпаниях
 * @property array $airlines_alliances Данные об авиакомпаниях входящих в альянсы
 * @property array $airports Данные об аэропортах
 * @property array $alliances Данные об альянсах
 * @property array $cities Данные о городах
 * @property array $countries Данные о странах
 * @property array $planes Данные о самолетах
 * @property array $routes Данные о маршрутах
 */
class DataService extends Component implements Configurable
{
    /**
     * @var boolean Использовать локальные json данные для формирования ответа или данные с сервера
     */
    protected $_useLocalData;
    /**
     * @var string Язык для получения и отображения данных. По умолчанию - ru
     */
    protected $_locale;
    /**
     * @var boolean Использовать только локализованные или не локализованные данные
     */
    protected $_localized = false;
    /**
     * @var string Адрес по которому размещаются данные на сервере
     */
    protected $_host = 'http://api.travelpayouts.com/data';
    /**
     * @var \yii\httpclient\Client
     */
    protected $_client;
    /**
     * Возможно доступные типы данных
     * @var array
     */
    protected $_available_datas = [
        'airlines',
        'airlines_alliances',
        'airports',
        'alliances',
        'cities',
        'countries',
        'planes',
        'routes',
    ];

    public function __construct($config = [])
    {
        if (!empty($config)) {
            Yii::configure($this, $config);
        }

        parent::__construct($config);
    }

    public function __get($name)
    {
        if (!in_array($name, $this->_available_datas)) {
            throw new Exception('"' . $name . '" data not available');
        }

        if ($this->_localized && $this->_useLocalData && !file_exists(Yii::getAlias('@SonkoDmitry/travelpayouts/data/' . $this->_locale . '/' . $name . '.json'))) {
            throw new Exception('"' . $name . '" local data not available in "' . $this->_locale . '" locale');
        }

        $path = ($this->_localized ? $this->_locale . '/' : '') . $name . '.json';
        if ($this->_useLocalData) {
            $path = '@SonkoDmitry/travelpayouts/data/' . $path;

            return json_decode(file_get_contents(Yii::getAlias($path)), true);
        } else {
            if (empty($this->_client)) {
                $this->_client = new Client([
                    'baseUrl' => $this->_host,
                ]);
            }

            $return = [];
            if (($response = $this->_client->get($path)->send()) && $response->isOk) {
                $return = $response->data;
            } elseif (!empty($response) && $response->statusCode == '404') {
                throw new Exception('"' . $name . '" remote data not available in "' . $this->_locale . '" locale');
            }

            return $return;
        }
    }

    /**
     * Изменить использование локального кеша данных на запрос данных с сервера
     *
     * @param boolean $value
     *
     * @return $this
     */
    public function setUseLocalData($value)
    {
        $this->_useLocalData = $value;

        return $this;
    }

    public function setLocale($value)
    {
        $this->_locale = $value;

        return $this;
    }

    /**
     * Получить локализованные данные с сервера или локальные. Для локальных в кеше доступна только ru локаль, остальные выбрасывают исключения
     *
     * @param boolean $value
     *
     * @return $this
     */
    public function localized($value)
    {
        $this->_localized = $value;

        return $this;
    }
}