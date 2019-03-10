<?php

namespace SonkoDmitry\travelpayouts;

use \SonkoDmitry\travelpayouts\services\DataService;
use Yii;
use Exception;
use yii\base\Component;
use yii\base\Configurable;

/**
 * Class Travelpayouts
 * @package \SonkoDmitry\travelpayouts
 *
 * @property \SonkoDmitry\travelpayouts\services\DataService $data
 */
class Travelpayouts extends Component implements Configurable
{
    /**
     * @var string партнерский токен
     * @link https://support.travelpayouts.com/hc/ru/articles/203956083
     */
    public $token;
    /**
     * @var string Язык для получения и отображения данных. По умолчанию - ru
     */
    public $locale = 'ru';
    /**
     * @var bool Использование локальных данных или удаленных. Используется при получении статической информации из апи данных абиабилетов
     */
    public $useLocalData = true;
    protected $services = [];

    public function __construct($config = [])
    {
        if (!empty($config)) {
            Yii::configure($this, $config);
        }
        if (empty($this->token)) {
            throw new Exception('Partner token cannot be empty');
        }
        parent::__construct($config);
    }

    /**
     * @return \SonkoDmitry\travelpayouts\services\DataService
     */
    public function getData()
    {
        if (empty($this->services['flightsData'])) {
            $this->services['flightsData'] = new DataService([
                'locale' => $this->locale,
                'useLocalData' => $this->useLocalData,
            ]);
        }

        return $this->services['flightsData'];
    }
}