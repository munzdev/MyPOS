<?php
namespace API\Lib;

use API\Models\Event\EventPrinter;
use API\Models\Invoice\Customer;
use API\Models\Payment\Coupon;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\PrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use stdClass;
use const API\DATE_PHP_DATEFORMAT;
use const API\DATE_PHP_TIMEFORMAT;
use const API\PAYMENT_TYPE_BANK_TRANSFER;
use const API\PAYMENT_TYPE_CASH;
use const API\PRINTER_CHARACTER_EURO;
use const API\PRINTER_LOGO_BIT_IMAGE;
use const API\PRINTER_LOGO_BIT_IMAGE_COLUMN;
use const API\PRINTER_LOGO_DEFAULT;
use const API\PRINTER_TYPE_CUPS;
use const API\PRINTER_TYPE_DUMMY;
use const API\PRINTER_TYPE_FILE;
use const API\PRINTER_TYPE_NETWORK;
use const API\PRINTER_TYPE_WINDOWS;
use function mb_str_pad;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReciepPrint
{
    private $o_i18n;

    private $a_entries = array();

    private $o_printer;

    private $d_date;

    private $d_date_footer;

    private $i_nr;

    private $str_tableNr;

    private $str_header;

    private $o_customer;

    private $a_coupons = array();

    private $i_paper_row_length;

    private $i_payment_type;

    private $str_bank_name;

    private $str_iban;

    private $str_bic;

    private $str_name;

    private $d_maturity_date;

    private $b_connector_open = false;

    private $str_logo;

    private $i_logo_type;

    const RIGHT_COLS = 8;

    const LEFT_PADDING = 4;

    public function __construct(PrintConnector $o_connector, $i_paper_row_length, stdClass $o_i18n) {
        $this->o_printer = new Printer($o_connector);
        $this->b_connector_open = true;
        $this->i_paper_row_length = $i_paper_row_length;
        $this->o_i18n = $o_i18n;
    }

    public function __destruct() {
        $this->Close();
    }

    public static function GetConnector(EventPrinter $o_printer) : PrintConnector {
        switch($o_printer->getType()) {
            case PRINTER_TYPE_NETWORK:
                return new NetworkPrintConnector($o_printer->getAttr1(), $o_printer->getAttr2());

            case PRINTER_TYPE_FILE:
                return new FilePrintConnector($o_printer->getAttr1());

            case PRINTER_TYPE_WINDOWS:
                return new WindowsPrintConnector($o_printer->getAttr1());

            case PRINTER_TYPE_CUPS:
                return new CupsPrintConnector($o_printer->getAttr1());

            case PRINTER_TYPE_DUMMY:
                return new DummyPrintConnector();
        }
    }

    public function Add($str_name, $i_amount, $i_price = null, $i_tax = null) {
        if(!isset($this->a_entries[$i_tax]))
            $this->a_entries[$i_tax] = array();

        $a_entrie = array('name' => $str_name,
                          'amount' => $i_amount,
                          'price' => $i_price);

        $this->a_entries[$i_tax][] = $a_entrie;
    }

    public function SetDate($d_date) {
        $this->d_date = $d_date;
    }

    public function SetDateFooter($d_date) {
        $this->d_date_footer = $d_date;
    }

    public function SetNr($i_nr) {
        $this->i_nr = $i_nr;
    }

    public function SetTableNr($str_tableNr) {
        $this->str_tableNr = $str_tableNr;
    }

    public function SetHeader($str_text) {
        $this->str_header = $str_text;
    }

    public function SetCustomer(Customer $o_customer)  {
        $this->o_customer = $o_customer;
    }

    public function AddCoupon(Coupon $o_coupon) {
        $this->a_coupons[] = $o_coupon;
    }

    public function SetPaymentType(int $i_payment_type) {
        $this->i_payment_type = $i_payment_type;
    }

    public function SetBankName($str_bank_name) {
        $this->str_bank_name = $str_bank_name;
    }

    public function SetIBAN($str_iban) {
        $this->str_iban = $str_iban;
    }

    public function SetBIC($str_bic) {
        $this->str_bic = $str_bic;
    }

    public function SetMaturityDate($d_maturity_date) {
        $this->d_maturity_date = $d_maturity_date;
    }

    public function SetName($str_name) {
        $this->str_name = $str_name;
    }

    public function SetLogo($str_file, $i_type) {
        $this->str_logo = $str_file;
        $this->i_logo_type = $i_type;
    }


    public function Close() {
        if($this->b_connector_open) {
            $this->o_printer->close();
            $this->b_connector_open = false;
        }
    }

    public function PrintOrder() {
        /* Print top logo and header */

        if($this->str_logo) {
            $o_logo = EscposImage::load($this->str_logo);

            if($this->i_logo_type == PRINTER_LOGO_DEFAULT)
                $this->o_printer->graphics($o_logo);
            elseif($this->i_logo_type == PRINTER_LOGO_BIT_IMAGE)
                $this->o_printer->bitImage ($o_logo);
            elseif($this->i_logo_type == PRINTER_LOGO_BIT_IMAGE_COLUMN)
                $this->o_printer->bitImageColumnFormat($o_logo);
        }

        /* Title of receipt */
        $this->o_printer -> setEmphasis(true);
        $this->o_printer -> text($this->o_i18n->orderNr . ": " . $this->i_nr  . "\n");
        $this->o_printer -> text($this->o_i18n->tableNr . ": ");
        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);
        $this->o_printer -> text($this->str_tableNr  . "\n");
        $this->o_printer -> selectPrintMode();
        $this->o_printer -> text($this->o_i18n->recordedBy . ": " . $this->str_name  . "\n");
        $this->o_printer -> text($this->o_i18n->recordedAt . ": " . date_format($this->d_date, DATE_PHP_TIMEFORMAT) . "\n");
        $this->o_printer -> setEmphasis(false);

        /* Items */
        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->o_printer -> setEmphasis(true);
        $this->o_printer -> text($this->o_i18n->title . "\n");
        $this->o_printer -> selectPrintMode();
        $this->o_printer -> setEmphasis(false);

        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

        $i_leftCols = $this->i_paper_row_length;

        $i_leftCols = $i_leftCols / 2;

        foreach ($this->a_entries as $i_tax_percent => $a_entries) {
            foreach($a_entries as $a_entrie) {
                $str_text = $a_entrie['amount'] . $this->o_i18n->multiplier . " " . $a_entrie['name'];

                $a_left_elements = explode(' ', $str_text);
                $a_final = array('');
                $str_row = "";
                $i_row_counter = 0;

                for($i = 0; $i < count($a_left_elements); $i++) {
                    $str_word = $a_left_elements[$i];

                    if($i == 0)
                        $i_left_padding = strlen($str_word) + 1;

                    if($str_row == '')
                        $str_tmp = $str_word;
                    else
                        $str_tmp = $str_row . ' ' . $str_word;

                    if($i > 1 && strlen($str_tmp) > $i_leftCols) {
                        $a_final[$i_row_counter] .= mb_str_pad($str_row, $i_leftCols);
                        $i_row_counter++;
                        $a_final[$i_row_counter] = '';

                        $str_row = mb_str_pad(' ', $i_left_padding) . $str_word;
                    } else {
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
        $this->o_printer -> text($this->o_i18n->distributionTime . ": " . $this->d_date_footer);
        $this->o_printer -> feed(2);

        /* Cut the receipt and open the cash drawer */
        $this->o_printer -> cut();
        $this->o_printer -> pulse();

        $this->Close();
    }

    public function PrintInvoice() {
        /* Print top logo and header */
        $this->o_printer -> setJustification(Printer::JUSTIFY_CENTER);

        if($this->str_logo) {
            $o_logo = EscposImage::load($this->str_logo);

            if($this->i_logo_type == PRINTER_LOGO_DEFAULT)
                $this->o_printer->graphics($o_logo);
            elseif($this->i_logo_type == PRINTER_LOGO_BIT_IMAGE)
                $this->o_printer->bitImage ($o_logo);
            elseif($this->i_logo_type == PRINTER_LOGO_BIT_IMAGE_COLUMN)
                $this->o_printer->bitImageColumnFormat($o_logo);
        }

        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->o_printer -> text($this->str_header);
        $this->o_printer -> selectPrintMode();
        $this->o_printer -> feed();
        $this->o_printer -> setJustification(Printer::JUSTIFY_LEFT);

        // Add customer data if set
        if($this->o_customer) {
            $this->o_printer -> feed();
            $this->o_printer -> setEmphasis(true);
            $this->o_printer -> text($this->o_i18n->customerData . ":\n");
            $this->o_printer -> setEmphasis(false);
            $this->o_printer -> text($this->o_i18n->customerid . ": " . $this->o_customer->getCustomerid() . "\n");
            $this->o_printer -> text($this->o_customer->getTitle() . "\n");
            $this->o_printer -> text($this->o_customer->getName() . "\n");

            if($this->o_customer->getContactPerson())
                $this->o_printer -> text($this->o_customer->getContactPerson() . "\n");

            $this->o_printer -> text($this->o_customer->getAddress() . "\n");

            if($this->o_customer->getAddress2())
                $this->o_printer -> text($this->o_customer->getAddress2() . "\n");

            $this->o_printer -> text($this->o_customer->getZip() . ' ' . $this->o_customer->getCity() . "\n");

            if($this->o_customer->getTaxIdentificationNr())
                $this->o_printer -> text($this->o_i18n->tax . ": " . $this->o_customer->getTaxIdentificationNr() . "\n");

            if($this->o_customer->getTelephon())
                $this->o_printer -> text($this->o_i18n->tel . ": " . $this->o_customer->getTelephon(). "\n");

            if($this->o_customer->getFax())
                $this->o_printer -> text($this->o_i18n->fax . ": " . $this->o_customer->getFax(). "\n");

            if($this->o_customer->getEmail())
                $this->o_printer -> text($this->o_i18n->email . ": " . $this->o_customer->getEmail(). "\n");

            $this->o_printer -> feed();
        }

        /* Title of receipt */
        $this->o_printer -> setEmphasis(true);
        $this->o_printer -> text($this->o_i18n->invoiceNr . ": " . $this->i_nr  . "\n");
        $this->o_printer -> text($this->o_i18n->tableNr . ": " . $this->str_tableNr  . "\n");
        $this->o_printer -> text($this->o_i18n->cashier . ": " . $this->str_name  . "\n");
        $this->o_printer -> feed();
        $this->o_printer -> setEmphasis(false);

        /* Items */
        $this->o_printer -> setEmphasis(true);
        $this->PrintItem($this->o_i18n->amountAndTitle, '', $this->o_i18n->price);
        $this->o_printer -> setEmphasis(false);

        $i_total = 0;
        $a_taxes = array();

        foreach ($this->a_entries as $i_tax_percent => $a_entries) {
            if(!isset($a_taxes[$i_tax_percent]))
                $a_taxes[$i_tax_percent] = 0;

            foreach($a_entries as $a_entrie) {
                $this->PrintItem($a_entrie['name'], $a_entrie['amount'], $a_entrie['price'], true);
                $i_price = $a_entrie['amount'] * $a_entrie['price'];
                $i_total += $i_price;

                $a_taxes[$i_tax_percent] += $i_price;
            }
        }

        $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->o_printer -> setEmphasis(true);

        if(count($this->a_coupons) == 0)
            $str_totalText = $this->o_i18n->totalSum;
        else
            $str_totalText = $this->o_i18n->subTotal;

        $this->PrintItem($str_totalText, '', sprintf('%0.2f', $i_total), true, true);
        $this->o_printer -> setEmphasis(false);
        $this->o_printer -> selectPrintMode();
        $this->o_printer -> feed();

        // Add Coupons if used
        if(count($this->a_coupons) > 0) {
            $this->o_printer->text($this->o_i18n->usedCoupons . "\n");

            foreach($this->a_coupons as $o_coupon) {
                $i_total -= $o_coupon->getValue();
                $this->PrintItem($this->o_i18n->couponCode . ": " . $o_coupon->getCode(), '', sprintf('%0.2f', $o_coupon->getValue()), true);
            }

            $this->o_printer -> feed();
            $this->o_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $this->o_printer -> setEmphasis(true);
            $this->PrintItem($this->o_i18n->totalSum, '', sprintf('%0.2f', $i_total), true, true);
            $this->o_printer -> setEmphasis(false);
            $this->o_printer -> selectPrintMode();
            $this->o_printer -> feed();
        }

        /* Tax and total */
        $this->o_printer->text($this->o_i18n->totalSumContainsTax . "\n");

        foreach($a_taxes as $i_tax => $i_price) {
            $this->PrintItem($i_tax . $this->o_i18n->percentTaxOfCurrency . sprintf('%0.2f', $i_price), '', sprintf('%0.2f', $i_price * ($i_tax / 100)), true);
        }

        // add payment type and bank information if given
        if($this->i_payment_type) {
            $this->o_printer -> feed();
            $this->o_printer -> text($this->o_i18n->paymentType . ": ");

            if ($this->i_payment_type == PAYMENT_TYPE_CASH) {
                $this->o_printer -> text($this->o_i18n->paymentTypeCash . "\n");
            } elseif ($this->i_payment_type == PAYMENT_TYPE_BANK_TRANSFER) {
                $this->o_printer -> text($this->o_i18n->paymentTypeBankTransfer . "\n");
                $this->o_printer -> feed();

                $this->o_printer -> text($this->o_i18n->bankInformation . ":\n");
                $this->o_printer -> text($this->o_i18n->bankName . ": " . $this->str_bank_name . "\n");
                $this->o_printer -> text($this->o_i18n->iban . ": " . $this->str_iban . "\n");
                $this->o_printer -> text($this->o_i18n->bic . ": " . $this->str_bic . "\n");
            }
        }

        // add a maturity date if given
        if($this->d_maturity_date) {
            $this->o_printer -> feed();
            $this->o_printer -> text($this->o_i18n->maturityDate . ": " . date_format($this->d_maturity_date, DATE_PHP_DATEFORMAT));
        }

        /* Footer */
        $this->o_printer -> feed(2);
        $this->o_printer -> setJustification(Printer::JUSTIFY_CENTER);
        $this->o_printer -> text($this->o_i18n->thanks . "!\n");
        $this->o_printer -> text(($this->d_date) ? date_format($this->d_date, DATE_PHP_TIMEFORMAT) : date(DATE_PHP_TIMEFORMAT) . "\n");
        $this->o_printer -> feed(2);

        /* Cut the receipt and open the cash drawer */
        $this->o_printer -> cut();
        $this->o_printer -> pulse();

        $this->Close();
    }

    private function PrintItem($str_name, $i_amount = 0, $i_price = 0, $b_currencySign = false, $b_bold = false) {
        $i_rightCols = self::RIGHT_COLS;
        $i_left_padding = self::LEFT_PADDING;
        $i_leftCols = $this->i_paper_row_length - $i_rightCols;

        if ($b_bold) {
            $i_leftCols = $i_leftCols / 2 - $i_rightCols / 2;
        }

        $a_left_elements = explode(' ', $str_name);
        $a_final = array('');
        $str_row = "";
        $i_row_counter = 0;

        for($i = 0; $i < count($a_left_elements); $i++) {
            $str_word = $a_left_elements[$i];
            $str_tmp = $str_row . ' ' . $str_word;

            if(strlen($str_tmp) > $i_leftCols) {
                $a_final[$i_row_counter] .= mb_str_pad($str_row, $i_leftCols);
                $i_row_counter++;
                $a_final[$i_row_counter] = '';

                $str_row = mb_str_pad(' ', $i_left_padding) . $str_word;
            } else {
                $str_row = $str_tmp;
            }
        }

        $a_final[$i_row_counter] .= mb_str_pad($str_row, $i_leftCols);

        $str_sign = ($b_currencySign ? $this->o_i18n->currency . ' ' : '');

        if($i_amount)
            $str_right = mb_str_pad($str_sign . sprintf('%0.2f', $i_price * $i_amount), $i_rightCols, ' ', STR_PAD_LEFT);
        else
            $str_right = mb_str_pad($str_sign . $i_price, $i_rightCols, ' ', STR_PAD_LEFT);

        $a_final[0] .= $str_right;
        $str_final = join("\n", $a_final);

        if($i_amount)
            $str_final .= "\n" . mb_str_pad(' ', $i_left_padding) . $i_amount . ' ' . $this->o_i18n->multiplier . ' ' . $this->o_i18n->currency . ' ' . sprintf('%0.2f', $i_price);

        $str_final .= "\n";

        //-- special EURO sign handling needed as this sign is in ESC/POS standard in an special caracter table
        $a_final_parts = explode('€', $str_final);

        for($i = 0; $i < count($a_final_parts); $i++) {
            if($i > 0)
                $this->o_printer->getPrintConnector()->write(PRINTER_CHARACTER_EURO);

            $this->o_printer->text($a_final_parts[$i]);
        }
    }
}
