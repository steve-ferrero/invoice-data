<?php

namespace WebAtrio\InvoiceData;

class InoiceData {

    /**
     * Path of the file to read.
     *
     * @var string
     */
    private $file;

    /**
     * The TesseractPHP object.
     *
     * @var WebAtrio\TesseractPHP\TesseractPHP
     */
    private $tesseract;

    public function __construct($file, $tesseract) {
        $this->file = $file;
        $this->tesseract = $tesseract;
    }

    /**
     * Retrieve the invoice information.
     *
     * @return \stdClass
     */
    public function getData() {
        $output = $tesseract->run();

        $date = null;
        $number = null;
        $ttc = null;
        $ht = null;
        $tva = null;

        foreach ($output as $line) {
            if ($number === null && mb_strstr(mb_strtolower($line), "n°")) {
                $pos = mb_strpos($line, "N°") + 2;
                $number = explode(" ", trim(mb_substr($line, $pos)))[0];
            } else {
                if ($date === null && preg_match('/([0-9]?[0-9])[\.\-\/ ]+([0-1]?[0-9])[\.\-\/ ]+([0-9]{2,4})/', $line, $matches)) {
                    $date = date($matches[0]);
                } else if ($ttc === null && strstr(mb_strtolower($line), "ttc")) {
                    if (preg_match("#([0-9\.]+)#", str_replace(",", ".", $line), $matches)) {
                        $ttc = floatval($matches[0]);
                    }
                } else if ($ttc === null && strstr(mb_strtolower($line), "ht")) {
                    if (preg_match("#([0-9\.]+)#", str_replace(",", ".", $line), $matches)) {
                        $ht = floatval($matches[0]);
                    }
                } else if ($tva === null && strstr(mb_strtolower($line), "tva")) {
                    if (preg_match("#([0-9\.]+)#", str_replace(",", ".", $line), $matches)) {
                        $tva = floatval($matches[0]);
                    }
                }
            }
        }

        if ($ht === null && $tva !== null && $ttc !== null) {
            $ht = $ttc - $tva;
        }

        $invoice = new \stdClass();
        $invoice->date = $date;
        $invoice->number = $number;
        $invoice->ttc = $ttc;
        $invoice->ht = $ht;
        $invoice->tva = $tva;

        return $invoice;
    }

}
