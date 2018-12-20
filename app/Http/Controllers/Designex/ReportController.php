<?php

namespace App\Http\Controllers\Designex;

use App\DxAccount;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index(Request $request) {
        $login_user = Auth::user();

        $title = "BRS - Designex Accounting Dashboard Reports";

        $ptitle = "report";

        $accounts = DxAccount::all()->toArray();

        $colums = Schema::getColumnListing('designex_accounts');

        //get current date and convert to excel
        $dateTimeNow = time();
        $excelDateValue = Date::PHPToExcel( $dateTimeNow );

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        //Set cell A6 with the Excel date/time value
//        $sheet->setCellValue('A6', $excelDateValue);
//
//        $sheet->getStyle('A6')
//            ->getNumberFormat()
//            ->setFormatCode(
//                NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
//            );
//
//        //string value
//        $sheet->setCellValue('A1', 'Hello World!');
//
//        //numeric value
//        $sheet->setCellValue('A2', 12345.6789);
//
//        //boolean value
//        $sheet->setCellValue('A3', TRUE);
//
//        //formula
//        $sheet->setCellValue(
//            'A4',
//            '=IF(A3, CONCATENATE(A1, " ", A2), CONCATENATE(A2, " ", A1))'
//        );
//
//        $sheet->getCell('B8')->setValue('Some Value');

        $sheet->fromArray($colums, NULL, 'A1');
        $sheet->fromArray($accounts, NULL, 'A2');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');

        return view('designex.report', compact('login_user', 'title', 'ptitle'));
    }
}
