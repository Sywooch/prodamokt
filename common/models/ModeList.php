<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 16.03.17
 * Time: 9:04
 */

namespace common\models;
use Yii;
use yii\helpers\Json;

class ModeList extends \yii\base\Object
{
    private $id;
    private $name;
    private $value;

    public function __construct($id, $name, $value)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
    }

    public function Create()
    {

        $data = Json::decode($this->value);
        if (array_key_exists('prompt', $data)&&array_key_exists('list', $data))
        {
            $input = '<select  class="form-control" name="Board[property]['. $this->id .']">';
            if ($data['prompt'])
            {
                $input = $input. '<option value="">- Выберите: '. $this->name .' -</option>';
            }
            foreach ($data['list'] as $value)
            {
                $input = $input. '<option value = "'. trim($value) .'">'. trim($value) .'</option>';
            }
            return $input. '</select>';
        }
        else
            return null;

        #*/
    }

    /**
     * Формирует JSON строку при создании записи
     */
    public function Record()
    {
        return '{"prompt":false,"list":[]}';
    }

    /**
     * Формирует input при поиске Объявления
     * @return string
     */
    public function Search()
    {
        return '<input id="property-'.$this->id .'" class="form-control" name="Board[property]['.$this->id .']" type="text">';
    }
}