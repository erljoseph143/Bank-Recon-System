<?php

namespace App\Http\Traits;

use App\BankAccount;
use App\BankNo;
use App\BankStatement;
use App\DxTransaction;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait FormatExcel {

    public function sheetHead($sheet, $date, $bscolumns, $dxcolumns, $title, $sheettitle) {

        $sheet->setTitle($sheettitle);
        $sheet->fromArray($bscolumns, NULL, 'A1')
            ->fromArray($dxcolumns, NULL, 'F1');
        $sheet->freezePane('A2');
        $sheet->setCellValue('A2', $date);
        $sheet->mergeCells('A4:J4')->getStyle('A4:J4')->applyFromArray([
            'alignment' => [
                'horizontal'    => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            ]
        ]);
        $sheet->setCellValue('A4', $title);

    }

    public function getTotal($sheet, $bsdata, $dxdata) {

        $bscount = count($bsdata)+7;
        $bstotal = $bscount-1;
        $dxcount = count($dxdata)+7;
        $dxtotal = $dxcount-1;

        if ($bscount >= $dxcount) {
            $count = $bscount;
        } else {
            $count = $dxcount;
        }

        $sheet->setCellValue("I{$count}", "TOTAL");
        $sheet->getStyle("J5:J{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->setCellValue("J{$count}", "=SUM(J5:J{$dxtotal})");

        $sheet->setCellValue("C{$count}", "TOTAL");
        $sheet->getStyle("D5:D{$count}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->setCellValue("D{$count}", "=SUM(D5:D{$bstotal})");

        $dates = $count-3;

//        $sheet->setColumnFormat(array(
//            'C' => 'mm/dd/yyyy',
//            'H' => 'mm/dd/yyyy',
//            'I' => 'mm/dd/yyyy'
//        ));

        $sheet->getStyle("C5:C{$dates}")
            ->getNumberFormat()
            ->setFormatCode('m/d/yyyy');
        $sheet->getStyle("H5:H{$dates}")
            ->getNumberFormat()
            ->setFormatCode('m/d/yyyy');
        $sheet->getStyle("I5:I{$dates}")
            ->getNumberFormat()
            ->setFormatCode('m/d/yyyy');

//        $sheet->getStyle("C5:C{$dates}")
//            ->getNumberFormat()
//            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
    }

    public function setWidth($sheet) {
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(8);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(15);
    }

    public function createFile($request, $dateT) {
        $bu = preg_replace('/[^a-z0-9]/i', '', $request->user()->businessunit->bname);
        $company = preg_replace('/[^a-z0-9]/i', '', $request->user()->businessunit->company->company);

        $code = BankNo::select('id')
            ->where('bankno', $request->code)
            ->first();

        if ($code == '') {
            return false;
        }

        $bank = BankAccount::select('id', 'bank', 'accountno', 'accountname')
            ->where('bankno', $code->id)
            ->where('buid', $request->user()->bunitid)
            ->first();

        $banko = preg_replace('/[^a-z0-9]/i', '', $bank->bank);
        $accountno = preg_replace('/[^a-z0-9]/i', '', $bank->accountno);
        $accountname = preg_replace('/[^a-z0-9]/i', '', $bank->accountname);

        $path = "{$company}/{$bu}/{$banko}-{$accountno}-{$accountname}";

        Storage::makeDirectory("public/designex/reports/".$path);

        $url = asset("storage/designex/reports/{$path}/DISBURSEMENT-{$banko}-{$accountno}-{$accountname}-{$dateT[0]}-{$dateT[1]}.xlsx");

        return [
            'url' => $url,
            'path' => $path,
            'banko' => $banko,
            'accountno' => $accountno,
            'accountname' => $accountname
        ];
    }

    public function sheet1($spreadsheet, $request, $bscolumns, $dxcolumns, $bsdata, $dxdata, $dateT) {
        $sheet1 = $spreadsheet->getActiveSheet();

        $this->sheetHead($sheet1, $request->doc_date, $bscolumns, $dxcolumns, 'DISBURSEMENT WITH MATCH CHECK NO AND AMOUNT', 'Match Check Num. and Amount');

        $this->setWidth($sheet1);

        $bsmatches = collect([]);
//
//        foreach ($dxdata as $key => $dx) {
////
//            $bsmatch = BankStatement::where('label_match', 'match check')
////                ->whereMonth('doc_date', $dateT[0])
////                ->whereYear('doc_date', $dateT[1])
//                ->where('bank_check_no', $dx['check_no'])
//                ->orderBy('bank_check_no')
//                ->get();
////
//            if ($bsmatch->count() == 1) {
//                $bsmatches->push([
//                    $bsmatch[0]->description,
//                    $bsmatch[0]->bank_check_no,
//                    $bsmatch[0]->bank_date,
//                    $bsmatch[0]->bank_amount,
//                ]);
//            }
////
//        }

//        $sheet1->fromArray($bsmatches->toArray(), NULL, 'A5')
        $sheet1->fromArray($bsdata, NULL, 'A5')
            ->fromArray($dxdata, NULL, 'F5');

        $this->getTotal($sheet1, $dxdata, $bsdata);
    }
}