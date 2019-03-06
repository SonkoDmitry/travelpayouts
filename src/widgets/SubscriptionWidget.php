<?php

namespace SonkoDmitry\travelpayouts\widgets;

use yii\base\Widget;

/**
 * Виджет подписки
 *
 * @link https://www.travelpayouts.com/tools/widgets/subscriptions
 * @link https://support.travelpayouts.com/hc/ru/articles/204596297
 * @package SonkoDmitry\travelpayouts\widgets
 */
class SubscriptionWidget extends Widget
{
    /**
     * @var integer Ширина блок подписки. По умолчанию пустое = резиновое
     */
    public $width;
    /**
     * @var string Цветовая схема. transparent - для прозрачного
     */
    public $backgroundColor = '#00b1dd';
    /**
     * @var string Партнерский маркер
     */
    public $marker;
    /**
     * @var string Хост
     */
    public $host = 'hydra.aviasales.ru';
    /**
     * @var string IATA код города вылета
     */
    public $originIata;
    /**
     * @var string Название города вылета
     */
    public $originName;
    /**
     * @var string IATA код города прилета
     */
    public $destinationIata;
    /**
     * @var string Название города прилета
     */
    public $destinationName;
    /**
     * @var bool Добавить реферальную ссылку
     */
    public $powered_by = true;
    /**
     * @var string Имя файла отображения, можно изменить на свой разместив в нужном месте проекта и указан его при вызове
     */
    public $view = 'subscriptionWidget';

    public function run()
    {
        $params = [
            'backgroundColor' => $this->backgroundColor,
            'marker' => $this->marker,
            'host' => $this->host,
            'originIata' => $this->originIata,
            'originName' => $this->originName,
            'destinationIata' => $this->destinationIata,
            'destinationName' => $this->destinationName,
            'powered_by' => var_export(boolval($this->powered_by), true),
        ];
        if ($this->width) {
            $params['width'] = $this->width . 'px';
        }

        return $this->render($this->view, ['query' => http_build_query($params)]);
    }
}