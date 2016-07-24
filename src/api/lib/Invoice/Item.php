<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Lib\Invoice;

use MyPOS;

/**
 * Description of Item
 *
 * @author Thomas
 */
class Item
{
    private $str_name;
    private $i_amount;
    private $i_price;
    private $b_euroSign;

    public function __construct($str_name = '', $i_amount = 0, $i_price = 0, $b_euroSign = false)
    {
        $this->i_amount = $i_amount;
        $this->str_name = $str_name;
        $this->i_price = $i_price;
        $this->b_euroSign = $b_euroSign;
    }

    public function GetTotalPrice()
    {
        return $this->i_price * $this->i_amount;
    }

    public function __toString()
    {
        $i_rightCols = 10;
        $i_leftCols = 38;
        if ($this->b_euroSign)  {
            $i_leftCols = $i_leftCols / 2 - $i_rightCols / 2;
        }
        $str_name = $this->str_name;
        if ($this->i_amount)  {
            $str_name = $this->i_amount . " $str_name";
        }
        $str_left = str_pad($str_name, $i_leftCols);

        $str_sign = ($this -> b_euroSign ? (MyPOS\PRINTER_CARACTER_EURO) . ' ' : '');
        $str_right = str_pad($str_sign . $this -> i_price, $i_rightCols, ' ', STR_PAD_LEFT);
        return "$str_left$str_right\n";
    }
}