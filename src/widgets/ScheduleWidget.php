<?php

namespace SonkoDmitry\travelpayouts\widgets;

use yii\base\Widget;

/**
 * Виджет расписаний
 *
 * @link https://www.travelpayouts.com/tools/widgets/schedule
 * @link https://support.travelpayouts.com/hc/ru/articles/360021814952
 * @package SonkoDmitry\travelpayouts\widgets
 */
class ScheduleWidget extends Widget
{
    /**
     * @var string Язык виджета
     */
    public $locale = 'ru';
    /**
     * @var string Партнерский маркер
     */
    public $marker;
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
     * @var string Код авиакомпании, необязательный параметр
     */
    public $airline;
    /**
     * @var int Радиус скругления, px
     */
    public $border_radius = 0;
    /**
     * @var string Цвет фона, HEX
     */
    public $color_background;
    /**
     * @var string Цвет текста, HEX
     */
    public $color_text;
    /**
     * @var string Цвет бордера, HEX
     */
    public $color_border;
    /**
     * @var int Строк по умолчанию
     */
    public $min_lines;
    /**
     * @var bool Добавить реферальную ссылку
     */
    public $powered_by = true;
    /**
     * @var string Имя файла отображения, можно изменить на свой разместив в нужном месте проекта и указан его при вызове
     */
    public $view = 'scheduleWidget';

    public function run()
    {
        $params = [
            'promo_id' => 2811,
            'shmarker' => $this->marker,
            'campaign_id' => 100,
            'locale' => $this->locale,
            'target_host' => $this->host,
            'origin' => $this->originIata,
            'destination' => $this->destinationIata,
            'airline' => $this->airline,
            'color_background' => $this->color_background,
            'color_text' => $this->color_text,
            'min_lines' => $this->min_lines,
            'color_border' => $this->color_border,
            'min_line' => $this->min_lines,
            'powered_by' => var_export(boolval($this->powered_by), true),
        ];

        return $this->render($this->view, ['query' => http_build_query($params)]);
    }
}