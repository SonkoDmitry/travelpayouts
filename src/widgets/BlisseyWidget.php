<?php

namespace SonkoDmitry\travelpayouts\widgets;

use yii\base\Widget;

/**
 * Виджет отельных подборок
 *
 * @link https://www.travelpayouts.com/tools/widgets/blissey
 * @link https://support.travelpayouts.com/hc/ru/articles/215942897
 * @package SonkoDmitry\travelpayouts\widgets
 */
class BlisseyWidget extends Widget
{
    /**
     * @var int Ширина виджета, null для резиновой
     */
    public $width = 800;
    /**
     * @var string Вид виджета (full - расширенный, compact - компактный)
     */
    public $type = 'full';
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
    public $host = 'search.hotellook.com';
    /**
     * @var string Партнерский маркер
     */
    public $marker;
    /**
     * @var string Дополнительный маркер
     */
    public $additional_marker;
    /**
     * @var int Отелей в подборке
     */
    public $limit = 9;
    /**
     * @var bool Добавить реферальную ссылку
     */
    public $powered_by = true;
    /**
     * @var string|array Отели. Используются ИД номера отелей из списка
     */
    public $ids;
    /**
     * @var integer ИД города, можно использовать свойство iata - передав туда код города, вместо ИД
     */
    public $id;
    /**
     * @var string ИАТА код города, для которого необходимо показать отельную подборку
     */
    public $iata;
    /**
     * @var string|array Категории подборок. Можно указать до трех категорий в выборке
     * @value 3stars - три звезды
     * @value 4stars - 4 звезды
     * @value center - близко к центру
     * @value distance - близко к центру
     * @value price - дешевые
     * @value smoke - для курящих
     * @value highprice - дорогие
     * @value pets - животные
     * @value popularity - популярные
     * @value rating - рейтинг
     * @value restaurant - ресторан
     * @value pool - с бассейном
     * @value tophotels - топ отелей
     */
    public $categories;
    /**
     * @var string Имя файла отображения, можно изменить на свой разместив в нужном месте проекта и указан его при вызове
     */
    public $view = 'blisseyWidget';

    public function run()
    {
        $params = [
            'type' => $this->type,
            'currency' => $this->currency,
            'width' => $this->width,
            'host' => $this->host,
            'marker' => $this->marker . '.' . $this->additional_marker,
            'additional_marker' => $this->additional_marker,
            'limit' => $this->limit,
            'powered_by' => var_export(boolval($this->powered_by), true),
        ];

        if (!empty($this->iata)) {
            $params['iata'] = strtoupper($this->iata);
        }

        if (!empty($this->ids)) {
            if (is_string($this->ids)) {
                $params['ids'] = $this->ids;
            } elseif (is_array($this->ids)) {
                $params['ids'] = implode(',', $this->ids);
            }
        }

        if (!empty($this->categories)) {
            if (is_string($this->categories)) {
                $params['categories'] = $this->categories;
            } elseif (is_array($this->categories)) {
                $params['categories'] = implode(',', $this->categories);
            }
        }

        return $this->render($this->view, ['query' => http_build_query($params), 'locale' => $this->locale]);
    }
}