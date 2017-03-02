<?php

namespace API\Lib\Printer\PrintingType;

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use const API\DATE_PHP_TIMEFORMAT;
use const API\PRINTER_LOGO_BIT_IMAGE;
use const API\PRINTER_LOGO_BIT_IMAGE_COLUMN;
use const API\PRINTER_LOGO_DEFAULT;
use function mb_str_pad;

class Order extends AbstractPrintingType
{
    public function printType()
    {
        /* Print top logo and header */

        if ($this->logo) {
            $logo = EscposImage::load($this->logo);

            if ($this->logoType == PRINTER_LOGO_DEFAULT) {
                $this->printer->graphics($logo);
            } elseif ($this->logoType == PRINTER_LOGO_BIT_IMAGE) {
                $this->printer->bitImage($logo);
            } elseif ($this->logoType == PRINTER_LOGO_BIT_IMAGE_COLUMN) {
                $this->printer->bitImageColumnFormat($logo);
            }
        }

        /* Title of receipt */
        $this->printer -> setEmphasis(true);
        $this->printer -> text($this->i18n->orderNr . ": " . $this->orderNr  . "\n");
        $this->printer -> text($this->i18n->tableNr . ": ");
        $this->printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);
        $this->printer -> text($this->tableNr  . "\n");
        $this->printer -> selectPrintMode();
        $this->printer -> text($this->i18n->recordedBy . ": " . $this->name  . "\n");
        $this->printer -> text($this->i18n->recordedAt . ": " . date_format($this->date, DATE_PHP_TIMEFORMAT) . "\n");
        $this->printer -> setEmphasis(false);

        /* Items */
        $this->printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->printer -> setEmphasis(true);
        $this->printer -> text($this->i18n->title . "\n");
        $this->printer -> selectPrintMode();
        $this->printer -> setEmphasis(false);

        $this->printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

        $leftCols = $this->paperRowLength;

        $leftCols = $leftCols / 2;

        foreach ($this->entries as $entries) {
            foreach ($entries as $entrie) {
                $text = $entrie['amount'] . $this->i18n->multiplier . " " . $entrie['name'];

                $leftElements = explode(' ', $text);
                $final = array('');
                $row = "";
                $rowCounter = 0;

                for ($i = 0; $i < count($leftElements); $i++) {
                    $word = $leftElements[$i];

                    if ($i == 0) {
                        $leftPadding = strlen($word) + 1;
                    }

                    if ($row == '') {
                        $tmp = $word;
                    } else {
                        $tmp = $row . ' ' . $word;
                    }

                    if ($i > 1 && strlen($tmp) > $leftCols) {
                        $final[$rowCounter] .= mb_str_pad($row, $leftCols);
                        $rowCounter++;
                        $final[$rowCounter] = '';

                        $row = mb_str_pad(' ', $leftPadding) . $word;
                    } else {
                        $row = $tmp;
                    }
                }

                $final[$rowCounter] .= mb_str_pad($row, $leftCols);

                $this->printer -> text(join("\n", $final) . "\n");
            }
        }

        $this->printer -> setEmphasis(false);
        $this->printer -> selectPrintMode();

        /* Footer */
        $this->printer -> feed(2);
        $this->printer -> text($this->i18n->distributionTime . ": " . date_format($this->dateFooter, DATE_PHP_TIMEFORMAT));
        $this->printer -> feed(2);

        /* Cut the receipt and open the cash drawer */
        $this->printer -> cut();
        $this->printer -> pulse();

        $this->close();
    }

}