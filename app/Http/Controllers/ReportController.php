<?php

namespace App\Http\Controllers;

use App\Models\Polda;
use App\Models\DailyInput;
use Illuminate\Http\Request;
use App\Models\PoldaSubmited;
use App\Models\DailyInputPrev;
use App\Models\KorlantasRekap;
use App\Models\RencanaOperasi;
use Illuminate\Support\Carbon;
use App\Exports\PoldaAllExport;
use App\Exports\DailyInputExport;
use App\Exports\FilterComparison;
use App\Exports\PoldaByUuidExport;
use Illuminate\Support\Facades\DB;
use App\Exports\NewComparisonExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyInputPrevExport;
use App\Exports\PoldaDailyComparison;
use App\Http\Requests\ComparisonExcelRequest;

class ReportController extends Controller
{

    public function __construct()
    {
        // $this->middleware('can-create-plan')->only('dailyAllPolda', 'byId', 'comparison', 'dailyProcess', 'comparisonProcess', 'downloadExcel', 'comparisonGetData');
    }

    public function byId($uuid)
    {
        $poldaSubmited = PoldaSubmited::whereUuid($uuid)->first();

        if(empty($poldaSubmited)) {
            flash('Inputan polda tidak ditemukan. Silakan refresh halaman dan coba lagi')->error();
            return redirect()->back();
        }

        $polda_submited_id = $poldaSubmited->id;
        $rencana_operasi_id = $poldaSubmited->rencana_operasi_id;
        $submited_date = $poldaSubmited->submited_date;
        $now = now()->format("Y-m-d");

        $filename = 'polda-self-report-'.poldaShortName().'-'.$now.'.xlsx';

        return Excel::download(new PoldaDailyComparison(
            $rencana_operasi_id,
            yearMinusOneOnly($submited_date),
            yearOnly($submited_date),
            $submited_date,
            $submited_date,
            poldaName(),
            $poldaSubmited->polda_id,
        ), $filename);
    }

    public function dailyAllPolda()
    {
        $listPolda = Polda::orderBy('id', 'asc')->pluck("name", "id");

        $rencanaOperasi = RencanaOperasi::orderBy('id', 'desc')->pluck("name", "id");

        $prexYear = DailyInputPrev::select('year')->get();
        $currentYear = DailyInput::select('year')->get();

        $yearCollection = [];

        foreach($prexYear as $key => $val) {
            array_push($yearCollection, $val->year);
        }

        foreach($currentYear as $key => $val) {
            array_push($yearCollection, $val->year);
        }

        $cleanYear = array_unique($yearCollection);

        return view('report.daily_rekap', compact('rencanaOperasi', 'listPolda', 'cleanYear'));
    }

    public function dailyProcess()
    {
        $now = now()->format("Y-m-d");
        $filename = 'daily-report-all-polda-'.$now.'.xlsx';

        return Excel::download(new PoldaAllExport(request('tanggal'), request('operation_id')), $filename);
    }

    public function comparison()
    {
        $rencanaOperasi = RencanaOperasi::orderBy('id', 'desc')->pluck("name", "id");

        $currentYear = array_unique(DailyInput::pluck('year')->toArray());
        $prevYear = array_unique(DailyInputPrev::pluck('year')->toArray());

        return view('report.comparison', compact('rencanaOperasi', 'currentYear', 'prevYear'));
    }

    public function comparisonProcess(ComparisonExcelRequest $request)
    {
        $operation_id = $request->operation_id;
        $start_year = $request->tahun_pembanding_pertama;
        $end_year = $request->tahun_pembanding_kedua;
        $date_range = $request->tanggal;

        if (str_contains($date_range, 'to')) {
            $format = explode(" to ", $date_range);

            $start_date = Carbon::parse(rtrim($format[0], " "))->format('Y-m-d');
            $end_date = Carbon::parse(ltrim($format[1], " "))->format('Y-m-d');
        } else {
            $start_date = $date_range;
            $end_date = $date_range;
        }

        $now = now()->format("Y-m-d");
        $filename = 'comparison-report-'.$now.'.xlsx';

        return Excel::download(new NewComparisonExport($operation_id, $start_year, $end_year, $start_date, $end_date), $filename);

        return redirect()->back();
    }

    public function downloadReportToday()
    {
        if(empty(operationPlans())) {
            return "Tidak ada operasi yang sedang berlangsung";
        }

        $fileName = 'report_all_polda_'.indonesianStandart(date('Y-m-d')).'.xlsx';

        return Excel::download(new FilterComparison(
            'polda_all',
            date('Y'),
            operationPlans()->id,
            date('Y-m-d'),
        ), $fileName);
    }

    public function downloadExcel($uuid)
    {
        $korlantasRekap = KorlantasRekap::with(['rencaraOperasi'])->whereUuid($uuid)->first();

        if(empty($korlantasRekap)) {
            flash('Laporan tidak ditemukan. Pastikan filter yang anda atur sudah sesuai!')->warning();
            return redirect()->back();
        }

        $id_rencana_operasi = $korlantasRekap->rencaraOperasi->id;
        $tahun = $korlantasRekap->year;
        $polda = $korlantasRekap->polda;
        $tanggal_operasi = $korlantasRekap->operation_date;

        $now = now()->format("Y-m-d");
        $filename = 'report-'.$now.'.xlsx';

        return Excel::download(new FilterComparison(
            $polda,
            $tahun,
            $id_rencana_operasi,
            $tanggal_operasi,
        ), $filename);
    }

    public function comparisonGetData()
    {
        $operation_id = request('operation_id');
        $start_year = request('start_year');
        $end_year = request('end_year');
        $date_range = request('date_range');

        if(is_null(request('date_range')) || is_null(request('operation_id')) || is_null(request('start_year')) || is_null(request('end_year'))) {
            abort(401);
        }

        if (str_contains($date_range, 'to')) {
            $format = explode(" to ", $date_range);

            $start_date = Carbon::parse(rtrim($format[0], " "))->format('Y-m-d');
            $end_date = Carbon::parse(ltrim($format[1], " "))->format('Y-m-d');

            $prevYear = laporanPrev($operation_id, $start_year, $start_date, $end_date);
            $currentYear = laporanCurrent($operation_id, $end_year, $start_date, $end_date);
        } else {
            $prevYear = laporanPrev($operation_id, $start_year, $date_range, $date_range);
            $currentYear = laporanCurrent($operation_id, $end_year, $date_range, $date_range);
        }

        return [
            'prev' => $prevYear,
            'current' => $currentYear
        ];
    }
}
