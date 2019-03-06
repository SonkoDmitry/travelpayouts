<?php

namespace SonkoDmitry\travelpayouts\widgets;

use yii\base\Widget;

/**
 * Виджет календаря цен на авиабилеты
 *
 * @link https://www.travelpayouts.com/tools/widgets/calendar
 * @link https://support.travelpayouts.com/hc/ru/articles/203912008
 *
 * @package SonkoDmitry\travelpayouts\widgets
 */
class CalendarWidget extends Widget
{
    /**
     * @var int Ширина виджета, null для резиновой
     */
    public $width = 800;
    /**
     * @var string Язык виджета
     */
    public $locale = 'ru';
    /**
     * @var string Валюта
     */
    public $currency = 'rub';
    /**
     * @var bool Маршрут в одну  сторону или нет
     */
    public $one_way = false;
    /**
     * @var bool Только прямые рейсы или нет
     */
    public $only_direct = false;
    /**
     * @var string Период за который показываться календарь (year - год, current_month - текущий месяц, YYYY-MM-01 - формат за конкретный месяц)
     */
    public $period = 'year';
    /**
     * @var string Период поездки, дни
     */
    public $range = '7,14';
    /**
     * @var string Партнерский маркер
     */
    public $marker;
    /**
     * @var string Дополнительный маркер
     */
    public $additionalMarker;
    /**
     * @var string Хост
     */
    public $host = 'hydra.aviasales.ru';
    /**
     * @var string IATA код пункта отправления, обязательный параметр
     */
    public $originIata;
    /**
     * @var string IATA код пункта назначения, обязательный параметр
     */
    public $destinationIata;
    /**
     * @var bool Добавить реферальную ссылку
     */
    public $powered_by = true;
    /**
     * @var string Имя файла отображения, можно изменить на свой разместив в нужном месте проекта и указан его при вызове
     */
    public $view = 'calendarWidget';

    public function run()
    {
        $params = [
            'marker' => $this->marker . '.' . $this->additionalMarker,
            'origin' => strtoupper($this->originIata),
            'destination' => strtoupper($this->destinationIata),
            'currency' => $this->currency,
            'searchUrl' => $this->host,
            'one_way' => var_export(boolval($this->powered_by), true),
            'only_direct' => var_export(boolval($this->powered_by), true),
            'locale' => $this->locale,
            'period' => $this->period,
            'range' => $this->range,
            'powered_by' => var_export(boolval($this->powered_by), true),
        ];

        return $this->render($this->view, ['query' => http_build_query($params)]);
    }
}