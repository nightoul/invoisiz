<?php declare(strict_types=1);

namespace App\Helper;

use Exception;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;

class PdfGenerator
{
    /**
     * @throws MpdfException
     * @throws Exception
     */
    public static function createInvoicePdf($fileName, $contractor, $client, $invoice): string
	{
        $mpdf = new Mpdf(['margin_left' => 20, 'margin_right' => 15, 'margin_top' => 48, 'margin_bottom' => 25, 'margin_header' => 10, 'margin_footer' => 10]);

        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Acme Trading Co. - Invoice");
        $mpdf->SetAuthor("Acme Trading Co.");
        $mpdf->SetDisplayMode('fullpage');

        $mpdf->SetHTMLHeader('
            <hr>
            <div style="text-align: right; font-size: 2em; font-weight: bold;">'.$invoice->title.'</div>');

        $html = '
            <html>
                <head>
                    <style>'.self::getStyles().'</style>
                </head>
                <body>
                    <div style="text-align: right">Datum vystavení: '.DateTime::from($invoice["issue_date"])->format("d. m. Y").'</div>
                    <div style="text-align: right">Datum splatnosti: '.DateTime::from($invoice["due_date"])->format("d. m. Y").'</div>
                    <br />
                    
                    <table width="100%" style="font-family: serif;" cellpadding="10">
                        <tr>
                            <td width="45%" style="border: 0.1mm solid #888888; ">
                                <span style="font-size: 7pt; color: #555555; font-family: sans;">DODAVATEL:</span><br /><br />
                                <strong>'.$contractor["first_name"].' '.$contractor["last_name"].'</strong><br />'
                                .$contractor["street"].'<br />'
                                .$contractor["zip_code"].' '.$contractor["town"].'<br />
                                IČO: '.$contractor["ico"].'<br />
                                Bankovní účet: '.$contractor["bank_account"].'<br />
                                Variabilní symbol: '.$invoice["var_symbol"].'<br />
                            </td>
                            <td width="10%">&nbsp;</td>
                            <td width="45%" style="border: 0.1mm solid #888888; ">
                                <span style="font-size: 7pt; color: #555555; font-family: sans;">ODBĚRATEL:</span><br /><br />
                                <strong>'.$client["name"].'</strong><br />'
                                .$client["street"].'<br />'
                                .$client["zip_code"].' '.$client["town"].'<br />
                                IČO: '.$client["ico"].'<br />
                            </td>
                        </tr>
                    </table>
                    
                    <br /><br />
                    
                    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
                        <thead>
                            <tr>
                                <td width="10%">Počet</td>
                                <td width="60%">Popis</td>
                                <td width="15%">Cena za hod</td>
                                <td width="15%">Celkem</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center">'.$invoice["hours_sum"].' hod</td>
                                <td>'.$invoice["description"].'</td>
                                <td class="cost">'.$invoice["charge_per_hour"].' KČ</td>
                                <td class="cost">'.$invoice["hours_sum"]*$invoice["charge_per_hour"].' KČ</td>
                            </tr>
                        </tbody>
                    </table>
                </body>
            </html>';

        $mpdf->SetHTMLFooter('
            <div>
                <span>Fyzická osoba zapsaná v živnostenském rejstříku.</span>
            </div>');

        $mpdf->WriteHTML($html);
        $mpdf->Output(Strings::webalize($fileName) . '.pdf', Destination::DOWNLOAD);

		return $fileName;
	}


    private static function getStyles(): string
    {
        return '
            body {font-family: sans-serif;
                font-size: 10pt;
            }
            p {	margin: 0pt; }
            table.items {
                border: 0.1mm solid #000000;
            }
            td { vertical-align: top; }
            .items td {
                border-left: 0.1mm solid #000000;
                border-right: 0.1mm solid #000000;
            }
            table thead td { background-color: #EEEEEE;
                text-align: center;
                border: 0.1mm solid #000000;
                font-variant: small-caps;
            }
            .items td.blanktotal {
                background-color: #EEEEEE;
                border: 0.1mm solid #000000;
                background-color: #FFFFFF;
                border: 0mm none #000000;
                border-top: 0.1mm solid #000000;
                border-right: 0.1mm solid #000000;
            }
            .items td.totals {
                text-align: right;
                border: 0.1mm solid #000000;
            }
            .items td.cost {
                text-align: "." center;
            }
        ';
    }
}
