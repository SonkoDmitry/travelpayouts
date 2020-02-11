<?php

namespace SonkoDmitry\travelpayouts\widgets;

use yii\base\Widget;

/**
 * Виджет спецпредложений
 *
 * @link https://www.travelpayouts.com/tools/widgets/ducklett
 * @link https://support.travelpayouts.com/hc/ru/articles/216486858
 * @package SonkoDmitry\travelpayouts\widgets
 */
class DucklettWidget extends Widget
{
    /**
     * @var int Ширина виджета, null для резиновой
     */
    public $width = 800;
    /**
     * @var string Вид виджета (brickwork - плитка, slider - слайдер)
     */
    public $widget_type = 'brickwork';
    /**
     * @var string Язык виджета
     */
    public $locale = 'ru';
    /**
     * @var string Валюта
     */
    public $currency = 'rub';
    /**
     * @var string Домен куда будет идти трафик
     */
    public $host = 'hydra.aviasales.ru';
    /**
     * @var string Партнерский маркер
     */
    public $marker;
    /**
     * @var string Дополнительный маркер
     */
    public $additional_marker;
    /**
     * @var int Лимит на спецпредложения
     */
    public $limit = 9;
    /**
     * @var bool Добавить реферальную ссылку
     */
    public $powered_by = true;
    /**
     * @var string|array AIATA код авиакомпаний (если строка, разделитель используется запятая, если массив один элмент - одна авиакомпания)
     */
    public $airline_iatas;
    /**
     * @var string Город вылета
     */
    public $origin_iatas;
    /**
     * @var string Город прилета
     */
    public $destination_iatas;
    /**
     * @var string Имя файла отображения, можно изменить на свой разместив в нужном месте проекта и указан его при вызове
     */
    public $view = 'ducklettWidget';

    public function run()
    {
        $params = [
            'widget_type' => $this->widget_type,
            'currency' => $this->currency,
            'locale' => $this->locale,
            'width' => $this->width,
            'host' => $this->host,
            'marker' => $this->marker . '.' . $this->additional_marker,
            'additional_marker' => $this->additional_marker,
            'limit' => $this->limit,
            'powered_by' => var_export(boolval($this->powered_by), true),
        ];

        if (!empty($this->origin_iatas) || !empty($this->destination_iatas)) {
            if (!empty($this->origin_iatas)) {
                $params['origin_iatas'] = strtoupper($this->origin_iatas);
            }
            if (!empty($this->destination_iatas)) {
                $params['destination_iatas'] = strtoupper($this->destination_iatas);
            }
        } elseif (!empty($this->airline_iatas)) {
            if (is_string($this->airline_iatas)) {
                $params['airline_iatas'] = strtoupper($this->airline_iatas);
            } elseif (is_array($this->airline_iatas)) {
                $params['airline_iatas'] = strtoupper(implode(',', $this->airline_iatas));
            }
        }

        return $this->render($this->view, ['query' => http_build_query($params), 'locale' => $this->locale]);
    }
}