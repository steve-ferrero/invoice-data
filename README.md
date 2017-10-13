# invoice-data
Extract data from invoice file.

## Requirements
You should have [Tesseract](https://github.com/tesseract-ocr/tesseract/wiki) and [Imagick](http://www.imagemagick.org/script/download.php) and [Ghostscript](http://www.ghostscript.com/) installed.

## Installation

The package can be installed via composer:
``` bash
$ composer require web-atrio/invoice-data
```

## Usage

Read invoice file.

```php
$invoiceData = new InvoiceData("invoice.png");
$invoice = $invoiceData->getData();
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.