<?php
namespace Lib;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\PrintConnector;
use Invoice\Item;

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

    private $i_invoiceNr;

    private $str_tableNr;

    private $str_header;

    public function __construct(PrintConnector $o_connector)
    {
        $this->o_printer = new Printer($o_connector);
    }

    public function Add($str_name, $i_amount, $i_price, $i_tax)
    {
        if(!isset($this->a_entries[$i_tax]))
            $this->a_entries[$i_tax] = array();

        $o_entrie = new Item( $str_name, $i_amount, $i_price, TRUE);
        $this->a_entries[$i_tax][] = $o_entrie;
    }

    public function SetDate($d_date)
    {
        $this->d_date = $d_date;
    }

    public function SetInvoiceNr($i_invoiceNr)
    {
        $this->i_invoiceNr = $i_invoiceNr;
    }

    public function SetTableNr($str_tableNr)
    {
        $this->str_tableNr = $str_tableNr;
    }

    public function SetHeader($str_text)
    {
        $this->str_header = $str_text;
    }

    public function PrintInvoice()
    {

        /* Print top logo */
        $this->o_printer -> setJustification(Printer::JUSTIFY_CENTER);
        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->o_printer -> text($this->str_header);
        $this->o_printer -> selectPrintMode();
        $this->o_printer -> feed();

        /* Title of receipt */
        $this->o_printer -> setEmphasis(true);
        $this->o_printer -> text("RECHNUNG: " . $this->i_invoiceNr  . "\n");
        $this->o_printer -> text("TISCH NUMMER: " . $this->str_tableNr  . "\n");
        $this->o_printer -> setEmphasis(false);

        /* Items */
        $this->o_printer -> setJustification(Printer::JUSTIFY_LEFT);
        $this->o_printer -> setEmphasis(true);
        $this->o_printer -> text(new item('Produkt', 'Anzahl', 'Preis'));
        $this->o_printer -> setEmphasis(false);

        $i_total = 0;
        $a_taxes = array();

        foreach ($this->a_entries as $i_tax_percent => $a_entries)
        {
            if(!isset($a_taxes[$i_tax_percent]))
                $a_taxes[$i_tax_percent] = 0;

            foreach($a_entries as $o_item)
            {
                $this->o_printer -> text($o_item);
                $i_price = $o_item->GetTotalPrice();
                $i_total += $i_price;
                $a_taxes[$i_tax_percent] += $i_price * ($i_tax_percent / 100);
            }
        }

        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->o_printer -> setEmphasis(true);
        $this->o_printer -> text(new item('Endsumme', '', $i_total, true));
        $this->o_printer -> setEmphasis(false);
        $this->o_printer -> selectPrintMode();
        $this->o_printer -> feed();

        /* Tax and total */
        $this->o_printer -> text(new Item('Die Endsumme enthält an MwSt', ''));

        foreach($a_taxes as $i_tax => $i_price)
        {
            $this->o_printer -> text(new Item($i_tax . '% MwSt', '', $i_price));
        }

        /* Footer */
        $this->o_printer -> feed(2);
        $this->o_printer -> setJustification(Printer::JUSTIFY_CENTER);
        $this->o_printer -> text("Danke für Ihren Besuch!\n");
        $this->o_printer -> feed(2);
        $this->o_printer -> text(($this->d_date) ? $this->d_date : date("d-m-Y H:i:s") . "\n");

        /* Cut the receipt and open the cash drawer */
        $this->o_printer -> cut();
        $this->o_printer -> pulse();

        $this->o_printer -> close();

    }
}
