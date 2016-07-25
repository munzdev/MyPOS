<?php
namespace Lib;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\PrintConnector;
use Mike42\Escpos\EscposImage;
use MyPOS;

/**
 * Description of Invoice
 *
 * @author Thomas
 */
class Invoice
{
    //put your code here
    private $a_entries = array();

    private $o_printer;

    private $d_date;

    private $i_nr;

    private $str_tableNr;

    private $str_header;

    private $i_paper_row_length;

    private $str_name;

    private $b_connector_open = false;

    private $str_logo;

    private $i_logo_type;

    const RIGHT_COLS = 8;

    const LEFT_PADDING = 4;

    public function __construct(PrintConnector $o_connector, $i_paper_row_length)
    {
        $this->o_printer = new Printer($o_connector);
        $this->b_connector_open = true;
        $this->i_paper_row_length = $i_paper_row_length;
    }

    public function __destruct()
    {
        $this->Close();
    }

    public function Add($str_name, $i_amount, $i_price = null, $i_tax = null)
    {
        if(!isset($this->a_entries[$i_tax]))
            $this->a_entries[$i_tax] = array();

        $a_entrie = array('name' => $str_name,
                          'amount' => $i_amount,
                          'price' => $i_price);

        $this->a_entries[$i_tax][] = $a_entrie;
    }

    public function SetDate($d_date)
    {
        $this->d_date = $d_date;
    }

    public function SetNr($i_nr)
    {
        $this->i_nr = $i_nr;
    }

    public function SetTableNr($str_tableNr)
    {
        $this->str_tableNr = $str_tableNr;
    }

    public function SetHeader($str_text)
    {
        $this->str_header = $str_text;
    }

    public function SetName($str_name)
    {
        $this->str_name = $str_name;
    }

    public function SetLogo($str_file, $i_type)
    {
        $this->str_logo = $str_file;
        $this->i_logo_type = $i_type;
    }

    public function Close()
    {
        if($this->b_connector_open)
        {
            $this->o_printer->close();
            $this->b_connector_open = false;
        }
    }

    public function PrintOrder()
    {
        /* Print top logo and header */

        if($this->str_logo)
        {
            $o_logo = EscposImage::load($this->str_logo);

            if($this->i_logo_type == MyPOS\PRINTER_LOGO_DEFAULT)
                $this->o_printer->graphics($o_logo);
            elseif($this->i_logo_type == MyPOS\PRINTER_LOGO_BIT_IMAGE)
                $this->o_printer->bitImage ($o_logo);
            elseif($this->i_logo_type == MyPOS\PRINTER_LOGO_BIT_IMAGE_COLUMN)
                $this->o_printer->bitImageColumnFormat($o_logo);
        }

        /* Title of receipt */
        $this->o_printer -> setEmphasis(true);
        $this->o_printer -> text("BESTELLUNG: " . $this->i_nr  . "\n");
        $this->o_printer -> text("TISCH NUMMER: ");
        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);
        $this->o_printer -> text($this->str_tableNr  . "\n");
        $this->o_printer -> selectPrintMode();
        $this->o_printer -> text("AUFGENOMMEN VON: " . $this->str_name  . "\n");
        $this->o_printer -> setEmphasis(false);

        /* Items */
        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->o_printer -> setEmphasis(true);
        $this->o_printer -> text("Bezeichung\n");
        $this->o_printer -> selectPrintMode();
        $this->o_printer -> setEmphasis(false);

        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

        $i_leftCols = $this->i_paper_row_length;
        $i_left_padding = self::LEFT_PADDING;

        $i_leftCols = $i_leftCols / 2;

        foreach ($this->a_entries as $i_tax_percent => $a_entries)
        {
            foreach($a_entries as $a_entrie)
            {
                $str_text = $a_entrie['amount'] . "x " . $a_entrie['name'];

                $a_left_elements = explode(' ', $str_text);
                $a_final = array('');
                $str_row = "";
                $i_row_counter = 0;

                for($i = 0; $i < count($a_left_elements); $i++)
                {
                    $str_word = $a_left_elements[$i];
                    $str_tmp = $str_row . ' ' . $str_word;

                    if(strlen($str_tmp) > $i_leftCols)
                    {
                        $a_final[$i_row_counter] .= mb_str_pad($str_row, $i_leftCols);
                        $i_row_counter++;
                        $a_final[$i_row_counter] = '';

                        $str_row = mb_str_pad(' ', $i_left_padding) . $str_word;
                    }
                    else
                    {
                        $str_row = $str_tmp;
                    }
                }

                $a_final[$i_row_counter] .= mb_str_pad($str_row, $i_leftCols);

                $this->o_printer -> text(join("\n", $a_final) . "\n");
            }
        }

        $this->o_printer -> setEmphasis(false);
        $this->o_printer -> selectPrintMode();

        /* Footer */
        $this->o_printer -> feed(2);
        $this->o_printer -> text("Ausgabe: " . (($this->d_date) ? $this->d_date : date("d.m.Y H:i:s")));
        $this->o_printer -> feed(2);

        /* Cut the receipt and open the cash drawer */
        $this->o_printer -> cut();
        $this->o_printer -> pulse();

        $this->Close();
    }

    public function PrintInvoice()
    {
        /* Print top logo and header */
        $this->o_printer -> setJustification(Printer::JUSTIFY_CENTER);

        if($this->str_logo)
        {
            $o_logo = EscposImage::load($this->str_logo);

            if($this->i_logo_type == MyPOS\PRINTER_LOGO_DEFAULT)
                $this->o_printer->graphics($o_logo);
            elseif($this->i_logo_type == MyPOS\PRINTER_LOGO_BIT_IMAGE)
                $this->o_printer->bitImage ($o_logo);
            elseif($this->i_logo_type == MyPOS\PRINTER_LOGO_BIT_IMAGE_COLUMN)
                $this->o_printer->bitImageColumnFormat($o_logo);
        }

        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->o_printer -> text($this->str_header);
        $this->o_printer -> selectPrintMode();
        $this->o_printer -> feed();

        /* Title of receipt */
        $this->o_printer -> setEmphasis(true);
        $this->o_printer -> text("RECHNUNG: " . $this->i_nr  . "\n");
        $this->o_printer -> text("TISCH NUMMER: " . $this->str_tableNr  . "\n");
        $this->o_printer -> text("KASSIER: " . $this->str_name  . "\n");
        $this->o_printer -> setEmphasis(false);

        /* Items */
        $this->o_printer -> setJustification(Printer::JUSTIFY_LEFT);
        $this->o_printer -> setEmphasis(true);
        $this->PrintItem('Anzahl und Produkt', '', 'Preis');
        $this->o_printer -> setEmphasis(false);

        $i_total = 0;
        $a_taxes = array();

        foreach ($this->a_entries as $i_tax_percent => $a_entries)
        {
            if(!isset($a_taxes[$i_tax_percent]))
                $a_taxes[$i_tax_percent] = 0;

            foreach($a_entries as $a_entrie)
            {
                $this->PrintItem($a_entrie['name'], $a_entrie['amount'], $a_entrie['price'], true);
                $i_price = $a_entrie['amount'] * $a_entrie['price'];
                $i_total += $i_price;

                $a_taxes[$i_tax_percent] += $i_price;
            }
        }

        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->o_printer -> setEmphasis(true);
        $this->PrintItem('Endsumme', '', sprintf('%0.2f', $i_total), true, true);
        $this->o_printer -> setEmphasis(false);
        $this->o_printer -> selectPrintMode();
        $this->o_printer -> feed();

        /* Tax and total */
        $this->o_printer->text("Die Endsumme enthält an MwSt\n");

        foreach($a_taxes as $i_tax => $i_price)
        {
            $this->PrintItem($i_tax . '% MwSt aus € ' . sprintf('%0.2f', $i_price), '', $i_price * ($i_tax / 100), true);
        }

        /* Footer */
        $this->o_printer -> feed(2);
        $this->o_printer -> setJustification(Printer::JUSTIFY_CENTER);
        $this->o_printer -> text("Danke für Ihren Besuch!\n");
        $this->o_printer -> text(($this->d_date) ? $this->d_date : date("d.m.Y H:i:s") . "\n");
        $this->o_printer -> feed(2);

        /* Cut the receipt and open the cash drawer */
        $this->o_printer -> cut();
        $this->o_printer -> pulse();

        $this->Close();
    }

    private function PrintItem($str_name, $i_amount = 0, $i_price = 0, $b_euroSign = false, $b_bold = false)
    {
        $i_rightCols = self::RIGHT_COLS;
        $i_left_padding = self::LEFT_PADDING;
        $i_leftCols = $this->i_paper_row_length - $i_rightCols;

        if ($b_bold)
        {
            $i_leftCols = $i_leftCols / 2 - $i_rightCols / 2;
        }

        $a_left_elements = explode(' ', $str_name);
        $a_final = array('');
        $str_row = "";
        $i_row_counter = 0;

        for($i = 0; $i < count($a_left_elements); $i++)
        {
            $str_word = $a_left_elements[$i];
            $str_tmp = $str_row . ' ' . $str_word;

            if(strlen($str_tmp) > $i_leftCols)
            {
                $a_final[$i_row_counter] .= mb_str_pad($str_row, $i_leftCols);
                $i_row_counter++;
                $a_final[$i_row_counter] = '';

                $str_row = mb_str_pad(' ', $i_left_padding) . $str_word;
            }
            else
            {
                $str_row = $str_tmp;
            }
        }

        $a_final[$i_row_counter] .= mb_str_pad($str_row, $i_leftCols);

        $str_sign = ($b_euroSign ? '€ ' : '');

        if($i_amount)
        {
            $str_right = mb_str_pad($str_sign . sprintf('%0.2f', $i_price * $i_amount), $i_rightCols, ' ', STR_PAD_LEFT);
        }
        else
        {
            $str_right = mb_str_pad($str_sign . $i_price, $i_rightCols, ' ', STR_PAD_LEFT);
        }

        $a_final[0] .= $str_right;
        $str_final = join("\n", $a_final);

        if($i_amount)
        {
            $str_final .= "\n" . mb_str_pad(' ', $i_left_padding) . $i_amount . ' x € ' . sprintf('%0.2f', $i_price);
        }

        $str_final .= "\n";

        //-- special EURO sign handling needed as this sign is in ESC/POS standard in an special caracter table
        $a_final_parts = explode('€', $str_final);

        for($i = 0; $i < count($a_final_parts); $i++)
        {
            if($i > 0)
                $this->o_printer->getPrintConnector()->write(MyPOS\PRINTER_CHARACTER_EURO);

            $this->o_printer->text($a_final_parts[$i]);
        }
    }
}
