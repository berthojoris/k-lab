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
use App\Exports\ComparisonExport;
use App\Exports\DailyInputExport;
use App\Exports\PoldaByUuidExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyInputPrevExport;

class ReportController extends Controller
{

    public function __construct()
    {
        // $this->middleware('can-create-plan')->only('dailyAllPolda', 'poldaUuid', 'comparison');
    }

    public function byId($uuid)
    {
        $now = now()->format("Y-m-d");
        $filename = 'daily-report.xlsx';

        $submitedData = PoldaSubmited::with(['rencanaOperasi', 'polda'])->whereUuid($uuid)->first();

        return Excel::download(new PoldaByUuidExport($submitedData), $filename);
    }

    public function dailyAllPolda()
    {
        // $polda = Polda::select("id", "uuid", "name", "short_name", "logo")
        //     ->with(['dailyInput' => function($query) {
        //         $query->where(DB::raw('DATE(created_at)'), date("Y-m-d"));
        //     }])
        //     ->orderBy("name", "asc")
        //     ->get();

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

    public function comparisonProcess()
    {
        $startYear = request('tahun_pembanding_pertama');
        $endYear = request('tahun_pembanding_kedua');
        $operation = request('operation_id');

        $findOperation = RencanaOperasi::find($operation);

        $tahunPertama = DailyInput::selectRaw('
            sum(pelanggaran_lalu_lintas_tilang) as pelanggaran_lalu_lintas_tilang,
            sum(pelanggaran_lalu_lintas_teguran) as pelanggaran_lalu_lintas_teguran,
            sum(pelanggaran_sepeda_motor_gun_helm_sni) as pelanggaran_sepeda_motor_gun_helm_sni,
            sum(pelanggaran_sepeda_motor_melawan_arus) as pelanggaran_sepeda_motor_melawan_arus,
            sum(pelanggaran_sepeda_motor_gun_hp_saat_berkendara) as pelanggaran_sepeda_motor_gun_hp_saat_berkendara,
            sum(pelanggaran_sepeda_motor_berkendara_dibawah_pengaruh_alkohol) as pelanggaran_sepeda_motor_berkendara_dibawah_pengaruh_alkohol,
            sum(pelanggaran_sepeda_motor_melebihi_batas_kecepatan) as pelanggaran_sepeda_motor_melebihi_batas_kecepatan,
            sum(pelanggaran_sepeda_motor_berkendara_dibawah_umur) as pelanggaran_sepeda_motor_berkendara_dibawah_umur,
            sum(pelanggaran_sepeda_motor_lain_lain) as pelanggaran_sepeda_motor_lain_lain,
            sum(pelanggaran_mobil_melawan_arus) as pelanggaran_mobil_melawan_arus,
            sum(pelanggaran_mobil_gun_hp_saat_berkendara) as pelanggaran_mobil_gun_hp_saat_berkendara,
            sum(pelanggaran_mobil_berkendara_dibawah_pengaruh_alkohol) as pelanggaran_mobil_berkendara_dibawah_pengaruh_alkohol,
            sum(pelanggaran_mobil_melebihi_batas_kecepatan) as pelanggaran_mobil_melebihi_batas_kecepatan,
            sum(pelanggaran_mobil_berkendara_dibawah_umur) as pelanggaran_mobil_berkendara_dibawah_umur,
            sum(pelanggaran_mobil_gun_safety_belt) as pelanggaran_mobil_gun_safety_belt,
            sum(pelanggaran_mobil_lain_lain) as pelanggaran_mobil_lain_lain,
            sum(barang_bukti_yg_disita_sim) as barang_bukti_yg_disita_sim,
            sum(barang_bukti_yg_disita_stnk) as barang_bukti_yg_disita_stnk,
            sum(barang_bukti_yg_disita_kendaraan) as barang_bukti_yg_disita_kendaraan,
            sum(kendaraan_yang_terlibat_pelanggaran_sepeda_motor) as kendaraan_yang_terlibat_pelanggaran_sepeda_motor,
            sum(kendaraan_yang_terlibat_pelanggaran_mobil_penumpang) as kendaraan_yang_terlibat_pelanggaran_mobil_penumpang,
            sum(kendaraan_yang_terlibat_pelanggaran_mobil_bus) as kendaraan_yang_terlibat_pelanggaran_mobil_bus,
            sum(kendaraan_yang_terlibat_pelanggaran_mobil_barang) as kendaraan_yang_terlibat_pelanggaran_mobil_barang,
            sum(kendaraan_yang_terlibat_pelanggaran_kendaraan_khusus) as kendaraan_yang_terlibat_pelanggaran_kendaraan_khusus,
            sum(profesi_pelaku_pelanggaran_pns) as profesi_pelaku_pelanggaran_pns,
            sum(profesi_pelaku_pelanggaran_karyawan_swasta) as profesi_pelaku_pelanggaran_karyawan_swasta,
            sum(profesi_pelaku_pelanggaran_pelajar_mahasiswa) as profesi_pelaku_pelanggaran_pelajar_mahasiswa,
            sum(profesi_pelaku_pelanggaran_pengemudi_supir) as profesi_pelaku_pelanggaran_pengemudi_supir,
            sum(profesi_pelaku_pelanggaran_tni) as profesi_pelaku_pelanggaran_tni,
            sum(profesi_pelaku_pelanggaran_polri) as profesi_pelaku_pelanggaran_polri,
            sum(profesi_pelaku_pelanggaran_lain_lain) as profesi_pelaku_pelanggaran_lain_lain,
            sum(usia_pelaku_pelanggaran_kurang_dari_15_tahun) as usia_pelaku_pelanggaran_kurang_dari_15_tahun,
            sum(usia_pelaku_pelanggaran_16_20_tahun) as usia_pelaku_pelanggaran_16_20_tahun,
            sum(usia_pelaku_pelanggaran_21_25_tahun) as usia_pelaku_pelanggaran_21_25_tahun,
            sum(usia_pelaku_pelanggaran_26_30_tahun) as usia_pelaku_pelanggaran_26_30_tahun,
            sum(usia_pelaku_pelanggaran_31_35_tahun) as usia_pelaku_pelanggaran_31_35_tahun,
            sum(usia_pelaku_pelanggaran_36_40_tahun) as usia_pelaku_pelanggaran_36_40_tahun,
            sum(usia_pelaku_pelanggaran_41_45_tahun) as usia_pelaku_pelanggaran_41_45_tahun,
            sum(usia_pelaku_pelanggaran_46_50_tahun) as usia_pelaku_pelanggaran_46_50_tahun,
            sum(usia_pelaku_pelanggaran_51_55_tahun) as usia_pelaku_pelanggaran_51_55_tahun,
            sum(usia_pelaku_pelanggaran_56_60_tahun) as usia_pelaku_pelanggaran_56_60_tahun,
            sum(usia_pelaku_pelanggaran_diatas_60_tahun) as usia_pelaku_pelanggaran_diatas_60_tahun,
            sum(sim_pelaku_pelanggaran_sim_a) as sim_pelaku_pelanggaran_sim_a,
            sum(sim_pelaku_pelanggaran_sim_a_umum) as sim_pelaku_pelanggaran_sim_a_umum,
            sum(sim_pelaku_pelanggaran_sim_b1) as sim_pelaku_pelanggaran_sim_b1,
            sum(sim_pelaku_pelanggaran_sim_b1_umum) as sim_pelaku_pelanggaran_sim_b1_umum,
            sum(sim_pelaku_pelanggaran_sim_b2) as sim_pelaku_pelanggaran_sim_b2,
            sum(sim_pelaku_pelanggaran_sim_b2_umum) as sim_pelaku_pelanggaran_sim_b2_umum,
            sum(sim_pelaku_pelanggaran_sim_c) as sim_pelaku_pelanggaran_sim_c,
            sum(sim_pelaku_pelanggaran_sim_d) as sim_pelaku_pelanggaran_sim_d,
            sum(sim_pelaku_pelanggaran_sim_internasional) as sim_pelaku_pelanggaran_sim_internasional,
            sum(sim_pelaku_pelanggaran_tanpa_sim) as sim_pelaku_pelanggaran_tanpa_sim,
            sum(lokasi_pelanggaran_pemukiman) as lokasi_pelanggaran_pemukiman,
            sum(lokasi_pelanggaran_perbelanjaan) as lokasi_pelanggaran_perbelanjaan,
            sum(lokasi_pelanggaran_perkantoran) as lokasi_pelanggaran_perkantoran,
            sum(lokasi_pelanggaran_wisata) as lokasi_pelanggaran_wisata,
            sum(lokasi_pelanggaran_industri) as lokasi_pelanggaran_industri,
            sum(lokasi_pelanggaran_status_jalan_nasional) as lokasi_pelanggaran_status_jalan_nasional,
            sum(lokasi_pelanggaran_status_jalan_propinsi) as lokasi_pelanggaran_status_jalan_propinsi,
            sum(lokasi_pelanggaran_status_jalan_kab_kota) as lokasi_pelanggaran_status_jalan_kab_kota,
            sum(lokasi_pelanggaran_status_jalan_desa_lingkungan) as lokasi_pelanggaran_status_jalan_desa_lingkungan,
            sum(lokasi_pelanggaran_fungsi_jalan_arteri) as lokasi_pelanggaran_fungsi_jalan_arteri,
            sum(lokasi_pelanggaran_fungsi_jalan_kolektor) as lokasi_pelanggaran_fungsi_jalan_kolektor,
            sum(lokasi_pelanggaran_fungsi_jalan_lokal) as lokasi_pelanggaran_fungsi_jalan_lokal,
            sum(lokasi_pelanggaran_fungsi_jalan_lingkungan) as lokasi_pelanggaran_fungsi_jalan_lingkungan,
            sum(kecelakaan_lalin_jumlah_kejadian) as kecelakaan_lalin_jumlah_kejadian,
            sum(kecelakaan_lalin_jumlah_korban_meninggal) as kecelakaan_lalin_jumlah_korban_meninggal,
            sum(kecelakaan_lalin_jumlah_korban_luka_berat) as kecelakaan_lalin_jumlah_korban_luka_berat,
            sum(kecelakaan_lalin_jumlah_korban_luka_ringan) as kecelakaan_lalin_jumlah_korban_luka_ringan,
            sum(kecelakaan_lalin_jumlah_kerugian_materiil) as kecelakaan_lalin_jumlah_kerugian_materiil,
            sum(kecelakaan_barang_bukti_yg_disita_sim) as kecelakaan_barang_bukti_yg_disita_sim,
            sum(kecelakaan_barang_bukti_yg_disita_stnk) as kecelakaan_barang_bukti_yg_disita_stnk,
            sum(kecelakaan_barang_bukti_yg_disita_kendaraan) as kecelakaan_barang_bukti_yg_disita_kendaraan,
            sum(profesi_korban_kecelakaan_lalin_pns) as profesi_korban_kecelakaan_lalin_pns,
            sum(profesi_korban_kecelakaan_lalin_karwayan_swasta) as profesi_korban_kecelakaan_lalin_karwayan_swasta,
            sum(profesi_korban_kecelakaan_lalin_pelajar_mahasiswa) as profesi_korban_kecelakaan_lalin_pelajar_mahasiswa,
            sum(profesi_korban_kecelakaan_lalin_pengemudi) as profesi_korban_kecelakaan_lalin_pengemudi,
            sum(profesi_korban_kecelakaan_lalin_tni) as profesi_korban_kecelakaan_lalin_tni,
            sum(profesi_korban_kecelakaan_lalin_polri) as profesi_korban_kecelakaan_lalin_polri,
            sum(profesi_korban_kecelakaan_lalin_lain_lain) as profesi_korban_kecelakaan_lalin_lain_lain,
            sum(usia_korban_kecelakaan_kurang_15) as usia_korban_kecelakaan_kurang_15,
            sum(usia_korban_kecelakaan_16_20) as usia_korban_kecelakaan_16_20,
            sum(usia_korban_kecelakaan_21_25) as usia_korban_kecelakaan_21_25,
            sum(usia_korban_kecelakaan_26_30) as usia_korban_kecelakaan_26_30,
            sum(usia_korban_kecelakaan_31_35) as usia_korban_kecelakaan_31_35,
            sum(usia_korban_kecelakaan_36_40) as usia_korban_kecelakaan_36_40,
            sum(usia_korban_kecelakaan_41_45) as usia_korban_kecelakaan_41_45,
            sum(usia_korban_kecelakaan_45_50) as usia_korban_kecelakaan_45_50,
            sum(usia_korban_kecelakaan_51_55) as usia_korban_kecelakaan_51_55,
            sum(usia_korban_kecelakaan_56_60) as usia_korban_kecelakaan_56_60,
            sum(usia_korban_kecelakaan_diatas_60) as usia_korban_kecelakaan_diatas_60,
            sum(sim_korban_kecelakaan_sim_a) as sim_korban_kecelakaan_sim_a,
            sum(sim_korban_kecelakaan_sim_a_umum) as sim_korban_kecelakaan_sim_a_umum,
            sum(sim_korban_kecelakaan_sim_b1) as sim_korban_kecelakaan_sim_b1,
            sum(sim_korban_kecelakaan_sim_b1_umum) as sim_korban_kecelakaan_sim_b1_umum,
            sum(sim_korban_kecelakaan_sim_b2) as sim_korban_kecelakaan_sim_b2,
            sum(sim_korban_kecelakaan_sim_b2_umum) as sim_korban_kecelakaan_sim_b2_umum,
            sum(sim_korban_kecelakaan_sim_c) as sim_korban_kecelakaan_sim_c,
            sum(sim_korban_kecelakaan_sim_d) as sim_korban_kecelakaan_sim_d,
            sum(sim_korban_kecelakaan_sim_internasional) as sim_korban_kecelakaan_sim_internasional,
            sum(sim_korban_kecelakaan_tanpa_sim) as sim_korban_kecelakaan_tanpa_sim,
            sum(kendaraan_yg_terlibat_kecelakaan_sepeda_motor) as kendaraan_yg_terlibat_kecelakaan_sepeda_motor,
            sum(kendaraan_yg_terlibat_kecelakaan_mobil_penumpang) as kendaraan_yg_terlibat_kecelakaan_mobil_penumpang,
            sum(kendaraan_yg_terlibat_kecelakaan_mobil_bus) as kendaraan_yg_terlibat_kecelakaan_mobil_bus,
            sum(kendaraan_yg_terlibat_kecelakaan_mobil_barang) as kendaraan_yg_terlibat_kecelakaan_mobil_barang,
            sum(kendaraan_yg_terlibat_kecelakaan_kendaraan_khusus) as kendaraan_yg_terlibat_kecelakaan_kendaraan_khusus,
            sum(kendaraan_yg_terlibat_kecelakaan_kendaraan_tidak_bermotor) as kendaraan_yg_terlibat_kecelakaan_kendaraan_tidak_bermotor,
            sum(jenis_kecelakaan_tunggal_ooc) as jenis_kecelakaan_tunggal_ooc,
            sum(jenis_kecelakaan_depan_depan) as jenis_kecelakaan_depan_depan,
            sum(jenis_kecelakaan_depan_belakang) as jenis_kecelakaan_depan_belakang,
            sum(jenis_kecelakaan_depan_samping) as jenis_kecelakaan_depan_samping,
            sum(jenis_kecelakaan_beruntun) as jenis_kecelakaan_beruntun,
            sum(jenis_kecelakaan_pejalan_kaki) as jenis_kecelakaan_pejalan_kaki,
            sum(jenis_kecelakaan_tabrak_lari) as jenis_kecelakaan_tabrak_lari,
            sum(jenis_kecelakaan_tabrak_hewan) as jenis_kecelakaan_tabrak_hewan,
            sum(jenis_kecelakaan_samping_samping) as jenis_kecelakaan_samping_samping,
            sum(jenis_kecelakaan_lainnya) as jenis_kecelakaan_lainnya,
            sum(profesi_pelaku_kecelakaan_lalin_pns) as profesi_pelaku_kecelakaan_lalin_pns,
            sum(profesi_pelaku_kecelakaan_lalin_karyawan_swasta) as profesi_pelaku_kecelakaan_lalin_karyawan_swasta,
            sum(profesi_pelaku_kecelakaan_lalin_mahasiswa_pelajar) as profesi_pelaku_kecelakaan_lalin_mahasiswa_pelajar,
            sum(profesi_pelaku_kecelakaan_lalin_pengemudi) as profesi_pelaku_kecelakaan_lalin_pengemudi,
            sum(profesi_pelaku_kecelakaan_lalin_tni) as profesi_pelaku_kecelakaan_lalin_tni,
            sum(profesi_pelaku_kecelakaan_lalin_polri) as profesi_pelaku_kecelakaan_lalin_polri,
            sum(profesi_pelaku_kecelakaan_lalin_lain_lain) as profesi_pelaku_kecelakaan_lalin_lain_lain,
            sum(usia_pelaku_kecelakaan_kurang_dari_15_tahun) as usia_pelaku_kecelakaan_kurang_dari_15_tahun,
            sum(usia_pelaku_kecelakaan_16_20_tahun) as usia_pelaku_kecelakaan_16_20_tahun,
            sum(usia_pelaku_kecelakaan_21_25_tahun) as usia_pelaku_kecelakaan_21_25_tahun,
            sum(usia_pelaku_kecelakaan_26_30_tahun) as usia_pelaku_kecelakaan_26_30_tahun,
            sum(usia_pelaku_kecelakaan_31_35_tahun) as usia_pelaku_kecelakaan_31_35_tahun,
            sum(usia_pelaku_kecelakaan_36_40_tahun) as usia_pelaku_kecelakaan_36_40_tahun,
            sum(usia_pelaku_kecelakaan_41_45_tahun) as usia_pelaku_kecelakaan_41_45_tahun,
            sum(usia_pelaku_kecelakaan_46_50_tahun) as usia_pelaku_kecelakaan_46_50_tahun,
            sum(usia_pelaku_kecelakaan_51_55_tahun) as usia_pelaku_kecelakaan_51_55_tahun,
            sum(usia_pelaku_kecelakaan_56_60_tahun) as usia_pelaku_kecelakaan_56_60_tahun,
            sum(usia_pelaku_kecelakaan_diatas_60_tahun) as usia_pelaku_kecelakaan_diatas_60_tahun,
            sum(sim_pelaku_kecelakaan_sim_a) as sim_pelaku_kecelakaan_sim_a,
            sum(sim_pelaku_kecelakaan_sim_a_umum) as sim_pelaku_kecelakaan_sim_a_umum,
            sum(sim_pelaku_kecelakaan_sim_b1) as sim_pelaku_kecelakaan_sim_b1,
            sum(sim_pelaku_kecelakaan_sim_b1_umum) as sim_pelaku_kecelakaan_sim_b1_umum,
            sum(sim_pelaku_kecelakaan_sim_b2) as sim_pelaku_kecelakaan_sim_b2,
            sum(sim_pelaku_kecelakaan_sim_b2_umum) as sim_pelaku_kecelakaan_sim_b2_umum,
            sum(sim_pelaku_kecelakaan_sim_c) as sim_pelaku_kecelakaan_sim_c,
            sum(sim_pelaku_kecelakaan_sim_d) as sim_pelaku_kecelakaan_sim_d,
            sum(sim_pelaku_kecelakaan_sim_internasional) as sim_pelaku_kecelakaan_sim_internasional,
            sum(sim_pelaku_kecelakaan_tanpa_sim) as sim_pelaku_kecelakaan_tanpa_sim,
            sum(lokasi_kecelakaan_lalin_pemukiman) as lokasi_kecelakaan_lalin_pemukiman,
            sum(lokasi_kecelakaan_lalin_perbelanjaan) as lokasi_kecelakaan_lalin_perbelanjaan,
            sum(lokasi_kecelakaan_lalin_perkantoran) as lokasi_kecelakaan_lalin_perkantoran,
            sum(lokasi_kecelakaan_lalin_wisata) as lokasi_kecelakaan_lalin_wisata,
            sum(lokasi_kecelakaan_lalin_industri) as lokasi_kecelakaan_lalin_industri,
            sum(lokasi_kecelakaan_lalin_lain_lain) as lokasi_kecelakaan_lalin_lain_lain,
            sum(lokasi_kecelakaan_status_jalan_nasional) as lokasi_kecelakaan_status_jalan_nasional,
            sum(lokasi_kecelakaan_status_jalan_propinsi) as lokasi_kecelakaan_status_jalan_propinsi,
            sum(lokasi_kecelakaan_status_jalan_kab_kota) as lokasi_kecelakaan_status_jalan_kab_kota,
            sum(lokasi_kecelakaan_status_jalan_desa_lingkungan) as lokasi_kecelakaan_status_jalan_desa_lingkungan,
            sum(lokasi_kecelakaan_fungsi_jalan_arteri) as lokasi_kecelakaan_fungsi_jalan_arteri,
            sum(lokasi_kecelakaan_fungsi_jalan_kolektor) as lokasi_kecelakaan_fungsi_jalan_kolektor,
            sum(lokasi_kecelakaan_fungsi_jalan_lokal) as lokasi_kecelakaan_fungsi_jalan_lokal,
            sum(lokasi_kecelakaan_fungsi_jalan_lingkungan) as lokasi_kecelakaan_fungsi_jalan_lingkungan,
            sum(faktor_penyebab_kecelakaan_manusia) as faktor_penyebab_kecelakaan_manusia,
            sum(faktor_penyebab_kecelakaan_ngantuk_lelah) as faktor_penyebab_kecelakaan_ngantuk_lelah,
            sum(faktor_penyebab_kecelakaan_mabuk_obat) as faktor_penyebab_kecelakaan_mabuk_obat,
            sum(faktor_penyebab_kecelakaan_sakit) as faktor_penyebab_kecelakaan_sakit,
            sum(faktor_penyebab_kecelakaan_handphone_elektronik) as faktor_penyebab_kecelakaan_handphone_elektronik,
            sum(faktor_penyebab_kecelakaan_menerobos_lampu_merah) as faktor_penyebab_kecelakaan_menerobos_lampu_merah,
            sum(faktor_penyebab_kecelakaan_melanggar_batas_kecepatan) as faktor_penyebab_kecelakaan_melanggar_batas_kecepatan,
            sum(faktor_penyebab_kecelakaan_tidak_menjaga_jarak) as faktor_penyebab_kecelakaan_tidak_menjaga_jarak,
            sum(faktor_penyebab_kecelakaan_mendahului_berbelok_pindah_jalur) as faktor_penyebab_kecelakaan_mendahului_berbelok_pindah_jalur,
            sum(faktor_penyebab_kecelakaan_berpindah_jalur) as faktor_penyebab_kecelakaan_berpindah_jalur,
            sum(faktor_penyebab_kecelakaan_tidak_memberikan_lampu_isyarat) as faktor_penyebab_kecelakaan_tidak_memberikan_lampu_isyarat,
            sum(faktor_penyebab_kecelakaan_tidak_mengutamakan_pejalan_kaki) as faktor_penyebab_kecelakaan_tidak_mengutamakan_pejalan_kaki,
            sum(faktor_penyebab_kecelakaan_lainnya) as faktor_penyebab_kecelakaan_lainnya,
            sum(faktor_penyebab_kecelakaan_alam) as faktor_penyebab_kecelakaan_alam,
            sum(faktor_penyebab_kecelakaan_kelaikan_kendaraan) as faktor_penyebab_kecelakaan_kelaikan_kendaraan,
            sum(faktor_penyebab_kecelakaan_kondisi_jalan) as faktor_penyebab_kecelakaan_kondisi_jalan,
            sum(faktor_penyebab_kecelakaan_prasarana_jalan) as faktor_penyebab_kecelakaan_prasarana_jalan,
            sum(faktor_penyebab_kecelakaan_rambu) as faktor_penyebab_kecelakaan_rambu,
            sum(faktor_penyebab_kecelakaan_marka) as faktor_penyebab_kecelakaan_marka,
            sum(faktor_penyebab_kecelakaan_apil) as faktor_penyebab_kecelakaan_apil,
            sum(faktor_penyebab_kecelakaan_perlintasan_ka_palang_pintu) as faktor_penyebab_kecelakaan_perlintasan_ka_palang_pintu,
            sum(waktu_kejadian_kecelakaan_00_03) as waktu_kejadian_kecelakaan_00_03,
            sum(waktu_kejadian_kecelakaan_03_06) as waktu_kejadian_kecelakaan_03_06,
            sum(waktu_kejadian_kecelakaan_06_09) as waktu_kejadian_kecelakaan_06_09,
            sum(waktu_kejadian_kecelakaan_09_12) as waktu_kejadian_kecelakaan_09_12,
            sum(waktu_kejadian_kecelakaan_12_15) as waktu_kejadian_kecelakaan_12_15,
            sum(waktu_kejadian_kecelakaan_15_18) as waktu_kejadian_kecelakaan_15_18,
            sum(waktu_kejadian_kecelakaan_18_21) as waktu_kejadian_kecelakaan_18_21,
            sum(waktu_kejadian_kecelakaan_21_24) as waktu_kejadian_kecelakaan_21_24,
            sum(kecelakaan_lalin_menonjol_jumlah_kejadian) as kecelakaan_lalin_menonjol_jumlah_kejadian,
            sum(kecelakaan_lalin_menonjol_korban_meninggal) as kecelakaan_lalin_menonjol_korban_meninggal,
            sum(kecelakaan_lalin_menonjol_korban_luka_berat) as kecelakaan_lalin_menonjol_korban_luka_berat,
            sum(kecelakaan_lalin_menonjol_korban_luka_ringan) as kecelakaan_lalin_menonjol_korban_luka_ringan,
            sum(kecelakaan_lalin_menonjol_materiil) as kecelakaan_lalin_menonjol_materiil,
            sum(kecelakaan_lalin_tunggal_jumlah_kejadian) as kecelakaan_lalin_tunggal_jumlah_kejadian,
            sum(kecelakaan_lalin_tunggal_korban_meninggal) as kecelakaan_lalin_tunggal_korban_meninggal,
            sum(kecelakaan_lalin_tunggal_korban_luka_berat) as kecelakaan_lalin_tunggal_korban_luka_berat,
            sum(kecelakaan_lalin_tunggal_korban_luka_ringan) as kecelakaan_lalin_tunggal_korban_luka_ringan,
            sum(kecelakaan_lalin_tunggal_materiil) as kecelakaan_lalin_tunggal_materiil,
            sum(kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian) as kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian,
            sum(kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal) as kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal,
            sum(kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat) as kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat,
            sum(kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan) as kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan,
            sum(kecelakaan_lalin_tabrak_pejalan_kaki_materiil) as kecelakaan_lalin_tabrak_pejalan_kaki_materiil,
            sum(kecelakaan_lalin_tabrak_lari_jumlah_kejadian) as kecelakaan_lalin_tabrak_lari_jumlah_kejadian,
            sum(kecelakaan_lalin_tabrak_lari_korban_meninggal) as kecelakaan_lalin_tabrak_lari_korban_meninggal,
            sum(kecelakaan_lalin_tabrak_lari_korban_luka_berat) as kecelakaan_lalin_tabrak_lari_korban_luka_berat,
            sum(kecelakaan_lalin_tabrak_lari_korban_luka_ringan) as kecelakaan_lalin_tabrak_lari_korban_luka_ringan,
            sum(kecelakaan_lalin_tabrak_lari_materiil) as kecelakaan_lalin_tabrak_lari_materiil,
            sum(kecelakaan_lalin_tabrak_sepeda_motor_jumlah_kejadian) as kecelakaan_lalin_tabrak_sepeda_motor_jumlah_kejadian,
            sum(kecelakaan_lalin_tabrak_sepeda_motor_korban_meninggal) as kecelakaan_lalin_tabrak_sepeda_motor_korban_meninggal,
            sum(kecelakaan_lalin_tabrak_sepeda_motor_korban_luka_berat) as kecelakaan_lalin_tabrak_sepeda_motor_korban_luka_berat,
            sum(kecelakaan_lalin_tabrak_sepeda_motor_korban_luka_ringan) as kecelakaan_lalin_tabrak_sepeda_motor_korban_luka_ringan,
            sum(kecelakaan_lalin_tabrak_sepeda_motor_materiil) as kecelakaan_lalin_tabrak_sepeda_motor_materiil,
            sum(kecelakaan_lalin_tabrak_roda_empat_jumlah_kejadian) as kecelakaan_lalin_tabrak_roda_empat_jumlah_kejadian,
            sum(kecelakaan_lalin_tabrak_roda_empat_korban_meninggal) as kecelakaan_lalin_tabrak_roda_empat_korban_meninggal,
            sum(kecelakaan_lalin_tabrak_roda_empat_korban_luka_berat) as kecelakaan_lalin_tabrak_roda_empat_korban_luka_berat,
            sum(kecelakaan_lalin_tabrak_roda_empat_korban_luka_ringan) as kecelakaan_lalin_tabrak_roda_empat_korban_luka_ringan,
            sum(kecelakaan_lalin_tabrak_roda_empat_materiil) as kecelakaan_lalin_tabrak_roda_empat_materiil,
            sum(kecelakaan_lalin_tabrak_tidak_bermotor_jumlah_kejadian) as kecelakaan_lalin_tabrak_tidak_bermotor_jumlah_kejadian,
            sum(kecelakaan_lalin_tabrak_tidak_bermotor_korban_meninggal) as kecelakaan_lalin_tabrak_tidak_bermotor_korban_meninggal,
            sum(kecelakaan_lalin_tabrak_tidak_bermotor_korban_luka_berat) as kecelakaan_lalin_tabrak_tidak_bermotor_korban_luka_berat,
            sum(kecelakaan_lalin_tabrak_tidak_bermotor_korban_luka_ringan) as kecelakaan_lalin_tabrak_tidak_bermotor_korban_luka_ringan,
            sum(kecelakaan_lalin_tabrak_tidak_bermotor_materiil) as kecelakaan_lalin_tabrak_tidak_bermotor_materiil,
            sum(kecelakaan_lalin_perlintasan_ka_jumlah_kejadian) as kecelakaan_lalin_perlintasan_ka_jumlah_kejadian,
            sum(kecelakaan_lalin_perlintasan_ka_berpalang_pintu) as kecelakaan_lalin_perlintasan_ka_berpalang_pintu,
            sum(kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu) as kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu,
            sum(kecelakaan_lalin_perlintasan_ka_korban_luka_ringan) as kecelakaan_lalin_perlintasan_ka_korban_luka_ringan,
            sum(kecelakaan_lalin_perlintasan_ka_korban_luka_berat) as kecelakaan_lalin_perlintasan_ka_korban_luka_berat,
            sum(kecelakaan_lalin_perlintasan_ka_korban_meninggal) as kecelakaan_lalin_perlintasan_ka_korban_meninggal,
            sum(kecelakaan_lalin_perlintasan_ka_materiil) as kecelakaan_lalin_perlintasan_ka_materiil,
            sum(kecelakaan_transportasi_kereta_api) as kecelakaan_transportasi_kereta_api,
            sum(kecelakaan_transportasi_laut_perairan) as kecelakaan_transportasi_laut_perairan,
            sum(kecelakaan_transportasi_udara) as kecelakaan_transportasi_udara,
            sum(penlu_melalui_media_cetak) as penlu_melalui_media_cetak,
            sum(penlu_melalui_media_elektronik) as penlu_melalui_media_elektronik,
            sum(penlu_melalui_tempat_keramaian) as penlu_melalui_tempat_keramaian,
            sum(penlu_melalui_tempat_istirahat) as penlu_melalui_tempat_istirahat,
            sum(penlu_melalui_daerah_rawan_laka_dan_langgar) as penlu_melalui_daerah_rawan_laka_dan_langgar,
            sum(penyebaran_pemasangan_spanduk) as penyebaran_pemasangan_spanduk,
            sum(penyebaran_pemasangan_leaflet) as penyebaran_pemasangan_leaflet,
            sum(penyebaran_pemasangan_sticker) as penyebaran_pemasangan_sticker,
            sum(penyebaran_pemasangan_bilboard) as penyebaran_pemasangan_bilboard,
            sum(polisi_sahabat_anak) as polisi_sahabat_anak,
            sum(cara_aman_sekolah) as cara_aman_sekolah,
            sum(patroli_keamanan_sekolah) as patroli_keamanan_sekolah,
            sum(pramuka_bhayangkara_krida_lalu_lintas) as pramuka_bhayangkara_krida_lalu_lintas,
            sum(police_goes_to_campus) as police_goes_to_campus,
            sum(safety_riding_driving) as safety_riding_driving,
            sum(forum_lalu_lintas_angkutan_umum) as forum_lalu_lintas_angkutan_umum,
            sum(kampanye_keselamatan) as kampanye_keselamatan,
            sum(sekolah_mengemudi) as sekolah_mengemudi,
            sum(taman_lalu_lintas) as taman_lalu_lintas,
            sum(global_road_safety_partnership_action) as global_road_safety_partnership_action,
            sum(giat_lantas_pengaturan) as giat_lantas_pengaturan,
            sum(giat_lantas_penjagaan) as giat_lantas_penjagaan,
            sum(giat_lantas_pengawalan) as giat_lantas_pengawalan,
            sum(giat_lantas_patroli) as giat_lantas_patroli
        ')
        ->whereRaw('YEAR(created_at) = ? AND rencana_operasi_id = ?', [$startYear, $operation])
        ->first();

        $tahunKedua = DailyInput::selectRaw('
            sum(pelanggaran_lalu_lintas_tilang) as pelanggaran_lalu_lintas_tilang,
            sum(pelanggaran_lalu_lintas_teguran) as pelanggaran_lalu_lintas_teguran,
            sum(pelanggaran_sepeda_motor_gun_helm_sni) as pelanggaran_sepeda_motor_gun_helm_sni,
            sum(pelanggaran_sepeda_motor_melawan_arus) as pelanggaran_sepeda_motor_melawan_arus,
            sum(pelanggaran_sepeda_motor_gun_hp_saat_berkendara) as pelanggaran_sepeda_motor_gun_hp_saat_berkendara,
            sum(pelanggaran_sepeda_motor_berkendara_dibawah_pengaruh_alkohol) as pelanggaran_sepeda_motor_berkendara_dibawah_pengaruh_alkohol,
            sum(pelanggaran_sepeda_motor_melebihi_batas_kecepatan) as pelanggaran_sepeda_motor_melebihi_batas_kecepatan,
            sum(pelanggaran_sepeda_motor_berkendara_dibawah_umur) as pelanggaran_sepeda_motor_berkendara_dibawah_umur,
            sum(pelanggaran_sepeda_motor_lain_lain) as pelanggaran_sepeda_motor_lain_lain,
            sum(pelanggaran_mobil_melawan_arus) as pelanggaran_mobil_melawan_arus,
            sum(pelanggaran_mobil_gun_hp_saat_berkendara) as pelanggaran_mobil_gun_hp_saat_berkendara,
            sum(pelanggaran_mobil_berkendara_dibawah_pengaruh_alkohol) as pelanggaran_mobil_berkendara_dibawah_pengaruh_alkohol,
            sum(pelanggaran_mobil_melebihi_batas_kecepatan) as pelanggaran_mobil_melebihi_batas_kecepatan,
            sum(pelanggaran_mobil_berkendara_dibawah_umur) as pelanggaran_mobil_berkendara_dibawah_umur,
            sum(pelanggaran_mobil_gun_safety_belt) as pelanggaran_mobil_gun_safety_belt,
            sum(pelanggaran_mobil_lain_lain) as pelanggaran_mobil_lain_lain,
            sum(barang_bukti_yg_disita_sim) as barang_bukti_yg_disita_sim,
            sum(barang_bukti_yg_disita_stnk) as barang_bukti_yg_disita_stnk,
            sum(barang_bukti_yg_disita_kendaraan) as barang_bukti_yg_disita_kendaraan,
            sum(kendaraan_yang_terlibat_pelanggaran_sepeda_motor) as kendaraan_yang_terlibat_pelanggaran_sepeda_motor,
            sum(kendaraan_yang_terlibat_pelanggaran_mobil_penumpang) as kendaraan_yang_terlibat_pelanggaran_mobil_penumpang,
            sum(kendaraan_yang_terlibat_pelanggaran_mobil_bus) as kendaraan_yang_terlibat_pelanggaran_mobil_bus,
            sum(kendaraan_yang_terlibat_pelanggaran_mobil_barang) as kendaraan_yang_terlibat_pelanggaran_mobil_barang,
            sum(kendaraan_yang_terlibat_pelanggaran_kendaraan_khusus) as kendaraan_yang_terlibat_pelanggaran_kendaraan_khusus,
            sum(profesi_pelaku_pelanggaran_pns) as profesi_pelaku_pelanggaran_pns,
            sum(profesi_pelaku_pelanggaran_karyawan_swasta) as profesi_pelaku_pelanggaran_karyawan_swasta,
            sum(profesi_pelaku_pelanggaran_pelajar_mahasiswa) as profesi_pelaku_pelanggaran_pelajar_mahasiswa,
            sum(profesi_pelaku_pelanggaran_pengemudi_supir) as profesi_pelaku_pelanggaran_pengemudi_supir,
            sum(profesi_pelaku_pelanggaran_tni) as profesi_pelaku_pelanggaran_tni,
            sum(profesi_pelaku_pelanggaran_polri) as profesi_pelaku_pelanggaran_polri,
            sum(profesi_pelaku_pelanggaran_lain_lain) as profesi_pelaku_pelanggaran_lain_lain,
            sum(usia_pelaku_pelanggaran_kurang_dari_15_tahun) as usia_pelaku_pelanggaran_kurang_dari_15_tahun,
            sum(usia_pelaku_pelanggaran_16_20_tahun) as usia_pelaku_pelanggaran_16_20_tahun,
            sum(usia_pelaku_pelanggaran_21_25_tahun) as usia_pelaku_pelanggaran_21_25_tahun,
            sum(usia_pelaku_pelanggaran_26_30_tahun) as usia_pelaku_pelanggaran_26_30_tahun,
            sum(usia_pelaku_pelanggaran_31_35_tahun) as usia_pelaku_pelanggaran_31_35_tahun,
            sum(usia_pelaku_pelanggaran_36_40_tahun) as usia_pelaku_pelanggaran_36_40_tahun,
            sum(usia_pelaku_pelanggaran_41_45_tahun) as usia_pelaku_pelanggaran_41_45_tahun,
            sum(usia_pelaku_pelanggaran_46_50_tahun) as usia_pelaku_pelanggaran_46_50_tahun,
            sum(usia_pelaku_pelanggaran_51_55_tahun) as usia_pelaku_pelanggaran_51_55_tahun,
            sum(usia_pelaku_pelanggaran_56_60_tahun) as usia_pelaku_pelanggaran_56_60_tahun,
            sum(usia_pelaku_pelanggaran_diatas_60_tahun) as usia_pelaku_pelanggaran_diatas_60_tahun,
            sum(sim_pelaku_pelanggaran_sim_a) as sim_pelaku_pelanggaran_sim_a,
            sum(sim_pelaku_pelanggaran_sim_a_umum) as sim_pelaku_pelanggaran_sim_a_umum,
            sum(sim_pelaku_pelanggaran_sim_b1) as sim_pelaku_pelanggaran_sim_b1,
            sum(sim_pelaku_pelanggaran_sim_b1_umum) as sim_pelaku_pelanggaran_sim_b1_umum,
            sum(sim_pelaku_pelanggaran_sim_b2) as sim_pelaku_pelanggaran_sim_b2,
            sum(sim_pelaku_pelanggaran_sim_b2_umum) as sim_pelaku_pelanggaran_sim_b2_umum,
            sum(sim_pelaku_pelanggaran_sim_c) as sim_pelaku_pelanggaran_sim_c,
            sum(sim_pelaku_pelanggaran_sim_d) as sim_pelaku_pelanggaran_sim_d,
            sum(sim_pelaku_pelanggaran_sim_internasional) as sim_pelaku_pelanggaran_sim_internasional,
            sum(sim_pelaku_pelanggaran_tanpa_sim) as sim_pelaku_pelanggaran_tanpa_sim,
            sum(lokasi_pelanggaran_pemukiman) as lokasi_pelanggaran_pemukiman,
            sum(lokasi_pelanggaran_perbelanjaan) as lokasi_pelanggaran_perbelanjaan,
            sum(lokasi_pelanggaran_perkantoran) as lokasi_pelanggaran_perkantoran,
            sum(lokasi_pelanggaran_wisata) as lokasi_pelanggaran_wisata,
            sum(lokasi_pelanggaran_industri) as lokasi_pelanggaran_industri,
            sum(lokasi_pelanggaran_status_jalan_nasional) as lokasi_pelanggaran_status_jalan_nasional,
            sum(lokasi_pelanggaran_status_jalan_propinsi) as lokasi_pelanggaran_status_jalan_propinsi,
            sum(lokasi_pelanggaran_status_jalan_kab_kota) as lokasi_pelanggaran_status_jalan_kab_kota,
            sum(lokasi_pelanggaran_status_jalan_desa_lingkungan) as lokasi_pelanggaran_status_jalan_desa_lingkungan,
            sum(lokasi_pelanggaran_fungsi_jalan_arteri) as lokasi_pelanggaran_fungsi_jalan_arteri,
            sum(lokasi_pelanggaran_fungsi_jalan_kolektor) as lokasi_pelanggaran_fungsi_jalan_kolektor,
            sum(lokasi_pelanggaran_fungsi_jalan_lokal) as lokasi_pelanggaran_fungsi_jalan_lokal,
            sum(lokasi_pelanggaran_fungsi_jalan_lingkungan) as lokasi_pelanggaran_fungsi_jalan_lingkungan,
            sum(kecelakaan_lalin_jumlah_kejadian) as kecelakaan_lalin_jumlah_kejadian,
            sum(kecelakaan_lalin_jumlah_korban_meninggal) as kecelakaan_lalin_jumlah_korban_meninggal,
            sum(kecelakaan_lalin_jumlah_korban_luka_berat) as kecelakaan_lalin_jumlah_korban_luka_berat,
            sum(kecelakaan_lalin_jumlah_korban_luka_ringan) as kecelakaan_lalin_jumlah_korban_luka_ringan,
            sum(kecelakaan_lalin_jumlah_kerugian_materiil) as kecelakaan_lalin_jumlah_kerugian_materiil,
            sum(kecelakaan_barang_bukti_yg_disita_sim) as kecelakaan_barang_bukti_yg_disita_sim,
            sum(kecelakaan_barang_bukti_yg_disita_stnk) as kecelakaan_barang_bukti_yg_disita_stnk,
            sum(kecelakaan_barang_bukti_yg_disita_kendaraan) as kecelakaan_barang_bukti_yg_disita_kendaraan,
            sum(profesi_korban_kecelakaan_lalin_pns) as profesi_korban_kecelakaan_lalin_pns,
            sum(profesi_korban_kecelakaan_lalin_karwayan_swasta) as profesi_korban_kecelakaan_lalin_karwayan_swasta,
            sum(profesi_korban_kecelakaan_lalin_pelajar_mahasiswa) as profesi_korban_kecelakaan_lalin_pelajar_mahasiswa,
            sum(profesi_korban_kecelakaan_lalin_pengemudi) as profesi_korban_kecelakaan_lalin_pengemudi,
            sum(profesi_korban_kecelakaan_lalin_tni) as profesi_korban_kecelakaan_lalin_tni,
            sum(profesi_korban_kecelakaan_lalin_polri) as profesi_korban_kecelakaan_lalin_polri,
            sum(profesi_korban_kecelakaan_lalin_lain_lain) as profesi_korban_kecelakaan_lalin_lain_lain,
            sum(usia_korban_kecelakaan_kurang_15) as usia_korban_kecelakaan_kurang_15,
            sum(usia_korban_kecelakaan_16_20) as usia_korban_kecelakaan_16_20,
            sum(usia_korban_kecelakaan_21_25) as usia_korban_kecelakaan_21_25,
            sum(usia_korban_kecelakaan_26_30) as usia_korban_kecelakaan_26_30,
            sum(usia_korban_kecelakaan_31_35) as usia_korban_kecelakaan_31_35,
            sum(usia_korban_kecelakaan_36_40) as usia_korban_kecelakaan_36_40,
            sum(usia_korban_kecelakaan_41_45) as usia_korban_kecelakaan_41_45,
            sum(usia_korban_kecelakaan_45_50) as usia_korban_kecelakaan_45_50,
            sum(usia_korban_kecelakaan_51_55) as usia_korban_kecelakaan_51_55,
            sum(usia_korban_kecelakaan_56_60) as usia_korban_kecelakaan_56_60,
            sum(usia_korban_kecelakaan_diatas_60) as usia_korban_kecelakaan_diatas_60,
            sum(sim_korban_kecelakaan_sim_a) as sim_korban_kecelakaan_sim_a,
            sum(sim_korban_kecelakaan_sim_a_umum) as sim_korban_kecelakaan_sim_a_umum,
            sum(sim_korban_kecelakaan_sim_b1) as sim_korban_kecelakaan_sim_b1,
            sum(sim_korban_kecelakaan_sim_b1_umum) as sim_korban_kecelakaan_sim_b1_umum,
            sum(sim_korban_kecelakaan_sim_b2) as sim_korban_kecelakaan_sim_b2,
            sum(sim_korban_kecelakaan_sim_b2_umum) as sim_korban_kecelakaan_sim_b2_umum,
            sum(sim_korban_kecelakaan_sim_c) as sim_korban_kecelakaan_sim_c,
            sum(sim_korban_kecelakaan_sim_d) as sim_korban_kecelakaan_sim_d,
            sum(sim_korban_kecelakaan_sim_internasional) as sim_korban_kecelakaan_sim_internasional,
            sum(sim_korban_kecelakaan_tanpa_sim) as sim_korban_kecelakaan_tanpa_sim,
            sum(kendaraan_yg_terlibat_kecelakaan_sepeda_motor) as kendaraan_yg_terlibat_kecelakaan_sepeda_motor,
            sum(kendaraan_yg_terlibat_kecelakaan_mobil_penumpang) as kendaraan_yg_terlibat_kecelakaan_mobil_penumpang,
            sum(kendaraan_yg_terlibat_kecelakaan_mobil_bus) as kendaraan_yg_terlibat_kecelakaan_mobil_bus,
            sum(kendaraan_yg_terlibat_kecelakaan_mobil_barang) as kendaraan_yg_terlibat_kecelakaan_mobil_barang,
            sum(kendaraan_yg_terlibat_kecelakaan_kendaraan_khusus) as kendaraan_yg_terlibat_kecelakaan_kendaraan_khusus,
            sum(kendaraan_yg_terlibat_kecelakaan_kendaraan_tidak_bermotor) as kendaraan_yg_terlibat_kecelakaan_kendaraan_tidak_bermotor,
            sum(jenis_kecelakaan_tunggal_ooc) as jenis_kecelakaan_tunggal_ooc,
            sum(jenis_kecelakaan_depan_depan) as jenis_kecelakaan_depan_depan,
            sum(jenis_kecelakaan_depan_belakang) as jenis_kecelakaan_depan_belakang,
            sum(jenis_kecelakaan_depan_samping) as jenis_kecelakaan_depan_samping,
            sum(jenis_kecelakaan_beruntun) as jenis_kecelakaan_beruntun,
            sum(jenis_kecelakaan_pejalan_kaki) as jenis_kecelakaan_pejalan_kaki,
            sum(jenis_kecelakaan_tabrak_lari) as jenis_kecelakaan_tabrak_lari,
            sum(jenis_kecelakaan_tabrak_hewan) as jenis_kecelakaan_tabrak_hewan,
            sum(jenis_kecelakaan_samping_samping) as jenis_kecelakaan_samping_samping,
            sum(jenis_kecelakaan_lainnya) as jenis_kecelakaan_lainnya,
            sum(profesi_pelaku_kecelakaan_lalin_pns) as profesi_pelaku_kecelakaan_lalin_pns,
            sum(profesi_pelaku_kecelakaan_lalin_karyawan_swasta) as profesi_pelaku_kecelakaan_lalin_karyawan_swasta,
            sum(profesi_pelaku_kecelakaan_lalin_mahasiswa_pelajar) as profesi_pelaku_kecelakaan_lalin_mahasiswa_pelajar,
            sum(profesi_pelaku_kecelakaan_lalin_pengemudi) as profesi_pelaku_kecelakaan_lalin_pengemudi,
            sum(profesi_pelaku_kecelakaan_lalin_tni) as profesi_pelaku_kecelakaan_lalin_tni,
            sum(profesi_pelaku_kecelakaan_lalin_polri) as profesi_pelaku_kecelakaan_lalin_polri,
            sum(profesi_pelaku_kecelakaan_lalin_lain_lain) as profesi_pelaku_kecelakaan_lalin_lain_lain,
            sum(usia_pelaku_kecelakaan_kurang_dari_15_tahun) as usia_pelaku_kecelakaan_kurang_dari_15_tahun,
            sum(usia_pelaku_kecelakaan_16_20_tahun) as usia_pelaku_kecelakaan_16_20_tahun,
            sum(usia_pelaku_kecelakaan_21_25_tahun) as usia_pelaku_kecelakaan_21_25_tahun,
            sum(usia_pelaku_kecelakaan_26_30_tahun) as usia_pelaku_kecelakaan_26_30_tahun,
            sum(usia_pelaku_kecelakaan_31_35_tahun) as usia_pelaku_kecelakaan_31_35_tahun,
            sum(usia_pelaku_kecelakaan_36_40_tahun) as usia_pelaku_kecelakaan_36_40_tahun,
            sum(usia_pelaku_kecelakaan_41_45_tahun) as usia_pelaku_kecelakaan_41_45_tahun,
            sum(usia_pelaku_kecelakaan_46_50_tahun) as usia_pelaku_kecelakaan_46_50_tahun,
            sum(usia_pelaku_kecelakaan_51_55_tahun) as usia_pelaku_kecelakaan_51_55_tahun,
            sum(usia_pelaku_kecelakaan_56_60_tahun) as usia_pelaku_kecelakaan_56_60_tahun,
            sum(usia_pelaku_kecelakaan_diatas_60_tahun) as usia_pelaku_kecelakaan_diatas_60_tahun,
            sum(sim_pelaku_kecelakaan_sim_a) as sim_pelaku_kecelakaan_sim_a,
            sum(sim_pelaku_kecelakaan_sim_a_umum) as sim_pelaku_kecelakaan_sim_a_umum,
            sum(sim_pelaku_kecelakaan_sim_b1) as sim_pelaku_kecelakaan_sim_b1,
            sum(sim_pelaku_kecelakaan_sim_b1_umum) as sim_pelaku_kecelakaan_sim_b1_umum,
            sum(sim_pelaku_kecelakaan_sim_b2) as sim_pelaku_kecelakaan_sim_b2,
            sum(sim_pelaku_kecelakaan_sim_b2_umum) as sim_pelaku_kecelakaan_sim_b2_umum,
            sum(sim_pelaku_kecelakaan_sim_c) as sim_pelaku_kecelakaan_sim_c,
            sum(sim_pelaku_kecelakaan_sim_d) as sim_pelaku_kecelakaan_sim_d,
            sum(sim_pelaku_kecelakaan_sim_internasional) as sim_pelaku_kecelakaan_sim_internasional,
            sum(sim_pelaku_kecelakaan_tanpa_sim) as sim_pelaku_kecelakaan_tanpa_sim,
            sum(lokasi_kecelakaan_lalin_pemukiman) as lokasi_kecelakaan_lalin_pemukiman,
            sum(lokasi_kecelakaan_lalin_perbelanjaan) as lokasi_kecelakaan_lalin_perbelanjaan,
            sum(lokasi_kecelakaan_lalin_perkantoran) as lokasi_kecelakaan_lalin_perkantoran,
            sum(lokasi_kecelakaan_lalin_wisata) as lokasi_kecelakaan_lalin_wisata,
            sum(lokasi_kecelakaan_lalin_industri) as lokasi_kecelakaan_lalin_industri,
            sum(lokasi_kecelakaan_lalin_lain_lain) as lokasi_kecelakaan_lalin_lain_lain,
            sum(lokasi_kecelakaan_status_jalan_nasional) as lokasi_kecelakaan_status_jalan_nasional,
            sum(lokasi_kecelakaan_status_jalan_propinsi) as lokasi_kecelakaan_status_jalan_propinsi,
            sum(lokasi_kecelakaan_status_jalan_kab_kota) as lokasi_kecelakaan_status_jalan_kab_kota,
            sum(lokasi_kecelakaan_status_jalan_desa_lingkungan) as lokasi_kecelakaan_status_jalan_desa_lingkungan,
            sum(lokasi_kecelakaan_fungsi_jalan_arteri) as lokasi_kecelakaan_fungsi_jalan_arteri,
            sum(lokasi_kecelakaan_fungsi_jalan_kolektor) as lokasi_kecelakaan_fungsi_jalan_kolektor,
            sum(lokasi_kecelakaan_fungsi_jalan_lokal) as lokasi_kecelakaan_fungsi_jalan_lokal,
            sum(lokasi_kecelakaan_fungsi_jalan_lingkungan) as lokasi_kecelakaan_fungsi_jalan_lingkungan,
            sum(faktor_penyebab_kecelakaan_manusia) as faktor_penyebab_kecelakaan_manusia,
            sum(faktor_penyebab_kecelakaan_ngantuk_lelah) as faktor_penyebab_kecelakaan_ngantuk_lelah,
            sum(faktor_penyebab_kecelakaan_mabuk_obat) as faktor_penyebab_kecelakaan_mabuk_obat,
            sum(faktor_penyebab_kecelakaan_sakit) as faktor_penyebab_kecelakaan_sakit,
            sum(faktor_penyebab_kecelakaan_handphone_elektronik) as faktor_penyebab_kecelakaan_handphone_elektronik,
            sum(faktor_penyebab_kecelakaan_menerobos_lampu_merah) as faktor_penyebab_kecelakaan_menerobos_lampu_merah,
            sum(faktor_penyebab_kecelakaan_melanggar_batas_kecepatan) as faktor_penyebab_kecelakaan_melanggar_batas_kecepatan,
            sum(faktor_penyebab_kecelakaan_tidak_menjaga_jarak) as faktor_penyebab_kecelakaan_tidak_menjaga_jarak,
            sum(faktor_penyebab_kecelakaan_mendahului_berbelok_pindah_jalur) as faktor_penyebab_kecelakaan_mendahului_berbelok_pindah_jalur,
            sum(faktor_penyebab_kecelakaan_berpindah_jalur) as faktor_penyebab_kecelakaan_berpindah_jalur,
            sum(faktor_penyebab_kecelakaan_tidak_memberikan_lampu_isyarat) as faktor_penyebab_kecelakaan_tidak_memberikan_lampu_isyarat,
            sum(faktor_penyebab_kecelakaan_tidak_mengutamakan_pejalan_kaki) as faktor_penyebab_kecelakaan_tidak_mengutamakan_pejalan_kaki,
            sum(faktor_penyebab_kecelakaan_lainnya) as faktor_penyebab_kecelakaan_lainnya,
            sum(faktor_penyebab_kecelakaan_alam) as faktor_penyebab_kecelakaan_alam,
            sum(faktor_penyebab_kecelakaan_kelaikan_kendaraan) as faktor_penyebab_kecelakaan_kelaikan_kendaraan,
            sum(faktor_penyebab_kecelakaan_kondisi_jalan) as faktor_penyebab_kecelakaan_kondisi_jalan,
            sum(faktor_penyebab_kecelakaan_prasarana_jalan) as faktor_penyebab_kecelakaan_prasarana_jalan,
            sum(faktor_penyebab_kecelakaan_rambu) as faktor_penyebab_kecelakaan_rambu,
            sum(faktor_penyebab_kecelakaan_marka) as faktor_penyebab_kecelakaan_marka,
            sum(faktor_penyebab_kecelakaan_apil) as faktor_penyebab_kecelakaan_apil,
            sum(faktor_penyebab_kecelakaan_perlintasan_ka_palang_pintu) as faktor_penyebab_kecelakaan_perlintasan_ka_palang_pintu,
            sum(waktu_kejadian_kecelakaan_00_03) as waktu_kejadian_kecelakaan_00_03,
            sum(waktu_kejadian_kecelakaan_03_06) as waktu_kejadian_kecelakaan_03_06,
            sum(waktu_kejadian_kecelakaan_06_09) as waktu_kejadian_kecelakaan_06_09,
            sum(waktu_kejadian_kecelakaan_09_12) as waktu_kejadian_kecelakaan_09_12,
            sum(waktu_kejadian_kecelakaan_12_15) as waktu_kejadian_kecelakaan_12_15,
            sum(waktu_kejadian_kecelakaan_15_18) as waktu_kejadian_kecelakaan_15_18,
            sum(waktu_kejadian_kecelakaan_18_21) as waktu_kejadian_kecelakaan_18_21,
            sum(waktu_kejadian_kecelakaan_21_24) as waktu_kejadian_kecelakaan_21_24,
            sum(kecelakaan_lalin_menonjol_jumlah_kejadian) as kecelakaan_lalin_menonjol_jumlah_kejadian,
            sum(kecelakaan_lalin_menonjol_korban_meninggal) as kecelakaan_lalin_menonjol_korban_meninggal,
            sum(kecelakaan_lalin_menonjol_korban_luka_berat) as kecelakaan_lalin_menonjol_korban_luka_berat,
            sum(kecelakaan_lalin_menonjol_korban_luka_ringan) as kecelakaan_lalin_menonjol_korban_luka_ringan,
            sum(kecelakaan_lalin_menonjol_materiil) as kecelakaan_lalin_menonjol_materiil,
            sum(kecelakaan_lalin_tunggal_jumlah_kejadian) as kecelakaan_lalin_tunggal_jumlah_kejadian,
            sum(kecelakaan_lalin_tunggal_korban_meninggal) as kecelakaan_lalin_tunggal_korban_meninggal,
            sum(kecelakaan_lalin_tunggal_korban_luka_berat) as kecelakaan_lalin_tunggal_korban_luka_berat,
            sum(kecelakaan_lalin_tunggal_korban_luka_ringan) as kecelakaan_lalin_tunggal_korban_luka_ringan,
            sum(kecelakaan_lalin_tunggal_materiil) as kecelakaan_lalin_tunggal_materiil,
            sum(kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian) as kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian,
            sum(kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal) as kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal,
            sum(kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat) as kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat,
            sum(kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan) as kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan,
            sum(kecelakaan_lalin_tabrak_pejalan_kaki_materiil) as kecelakaan_lalin_tabrak_pejalan_kaki_materiil,
            sum(kecelakaan_lalin_tabrak_lari_jumlah_kejadian) as kecelakaan_lalin_tabrak_lari_jumlah_kejadian,
            sum(kecelakaan_lalin_tabrak_lari_korban_meninggal) as kecelakaan_lalin_tabrak_lari_korban_meninggal,
            sum(kecelakaan_lalin_tabrak_lari_korban_luka_berat) as kecelakaan_lalin_tabrak_lari_korban_luka_berat,
            sum(kecelakaan_lalin_tabrak_lari_korban_luka_ringan) as kecelakaan_lalin_tabrak_lari_korban_luka_ringan,
            sum(kecelakaan_lalin_tabrak_lari_materiil) as kecelakaan_lalin_tabrak_lari_materiil,
            sum(kecelakaan_lalin_tabrak_sepeda_motor_jumlah_kejadian) as kecelakaan_lalin_tabrak_sepeda_motor_jumlah_kejadian,
            sum(kecelakaan_lalin_tabrak_sepeda_motor_korban_meninggal) as kecelakaan_lalin_tabrak_sepeda_motor_korban_meninggal,
            sum(kecelakaan_lalin_tabrak_sepeda_motor_korban_luka_berat) as kecelakaan_lalin_tabrak_sepeda_motor_korban_luka_berat,
            sum(kecelakaan_lalin_tabrak_sepeda_motor_korban_luka_ringan) as kecelakaan_lalin_tabrak_sepeda_motor_korban_luka_ringan,
            sum(kecelakaan_lalin_tabrak_sepeda_motor_materiil) as kecelakaan_lalin_tabrak_sepeda_motor_materiil,
            sum(kecelakaan_lalin_tabrak_roda_empat_jumlah_kejadian) as kecelakaan_lalin_tabrak_roda_empat_jumlah_kejadian,
            sum(kecelakaan_lalin_tabrak_roda_empat_korban_meninggal) as kecelakaan_lalin_tabrak_roda_empat_korban_meninggal,
            sum(kecelakaan_lalin_tabrak_roda_empat_korban_luka_berat) as kecelakaan_lalin_tabrak_roda_empat_korban_luka_berat,
            sum(kecelakaan_lalin_tabrak_roda_empat_korban_luka_ringan) as kecelakaan_lalin_tabrak_roda_empat_korban_luka_ringan,
            sum(kecelakaan_lalin_tabrak_roda_empat_materiil) as kecelakaan_lalin_tabrak_roda_empat_materiil,
            sum(kecelakaan_lalin_tabrak_tidak_bermotor_jumlah_kejadian) as kecelakaan_lalin_tabrak_tidak_bermotor_jumlah_kejadian,
            sum(kecelakaan_lalin_tabrak_tidak_bermotor_korban_meninggal) as kecelakaan_lalin_tabrak_tidak_bermotor_korban_meninggal,
            sum(kecelakaan_lalin_tabrak_tidak_bermotor_korban_luka_berat) as kecelakaan_lalin_tabrak_tidak_bermotor_korban_luka_berat,
            sum(kecelakaan_lalin_tabrak_tidak_bermotor_korban_luka_ringan) as kecelakaan_lalin_tabrak_tidak_bermotor_korban_luka_ringan,
            sum(kecelakaan_lalin_tabrak_tidak_bermotor_materiil) as kecelakaan_lalin_tabrak_tidak_bermotor_materiil,
            sum(kecelakaan_lalin_perlintasan_ka_jumlah_kejadian) as kecelakaan_lalin_perlintasan_ka_jumlah_kejadian,
            sum(kecelakaan_lalin_perlintasan_ka_berpalang_pintu) as kecelakaan_lalin_perlintasan_ka_berpalang_pintu,
            sum(kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu) as kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu,
            sum(kecelakaan_lalin_perlintasan_ka_korban_luka_ringan) as kecelakaan_lalin_perlintasan_ka_korban_luka_ringan,
            sum(kecelakaan_lalin_perlintasan_ka_korban_luka_berat) as kecelakaan_lalin_perlintasan_ka_korban_luka_berat,
            sum(kecelakaan_lalin_perlintasan_ka_korban_meninggal) as kecelakaan_lalin_perlintasan_ka_korban_meninggal,
            sum(kecelakaan_lalin_perlintasan_ka_materiil) as kecelakaan_lalin_perlintasan_ka_materiil,
            sum(kecelakaan_transportasi_kereta_api) as kecelakaan_transportasi_kereta_api,
            sum(kecelakaan_transportasi_laut_perairan) as kecelakaan_transportasi_laut_perairan,
            sum(kecelakaan_transportasi_udara) as kecelakaan_transportasi_udara,
            sum(penlu_melalui_media_cetak) as penlu_melalui_media_cetak,
            sum(penlu_melalui_media_elektronik) as penlu_melalui_media_elektronik,
            sum(penlu_melalui_tempat_keramaian) as penlu_melalui_tempat_keramaian,
            sum(penlu_melalui_tempat_istirahat) as penlu_melalui_tempat_istirahat,
            sum(penlu_melalui_daerah_rawan_laka_dan_langgar) as penlu_melalui_daerah_rawan_laka_dan_langgar,
            sum(penyebaran_pemasangan_spanduk) as penyebaran_pemasangan_spanduk,
            sum(penyebaran_pemasangan_leaflet) as penyebaran_pemasangan_leaflet,
            sum(penyebaran_pemasangan_sticker) as penyebaran_pemasangan_sticker,
            sum(penyebaran_pemasangan_bilboard) as penyebaran_pemasangan_bilboard,
            sum(polisi_sahabat_anak) as polisi_sahabat_anak,
            sum(cara_aman_sekolah) as cara_aman_sekolah,
            sum(patroli_keamanan_sekolah) as patroli_keamanan_sekolah,
            sum(pramuka_bhayangkara_krida_lalu_lintas) as pramuka_bhayangkara_krida_lalu_lintas,
            sum(police_goes_to_campus) as police_goes_to_campus,
            sum(safety_riding_driving) as safety_riding_driving,
            sum(forum_lalu_lintas_angkutan_umum) as forum_lalu_lintas_angkutan_umum,
            sum(kampanye_keselamatan) as kampanye_keselamatan,
            sum(sekolah_mengemudi) as sekolah_mengemudi,
            sum(taman_lalu_lintas) as taman_lalu_lintas,
            sum(global_road_safety_partnership_action) as global_road_safety_partnership_action,
            sum(giat_lantas_pengaturan) as giat_lantas_pengaturan,
            sum(giat_lantas_penjagaan) as giat_lantas_penjagaan,
            sum(giat_lantas_pengawalan) as giat_lantas_pengawalan,
            sum(giat_lantas_patroli) as giat_lantas_patroli
        ')
        ->whereRaw('YEAR(created_at) = ? AND rencana_operasi_id = ?', [$endYear, $operation])
        ->first();

        $now = now()->format("Y-m-d");
        $filename = 'comparison-report-'.$startYear.'-'.$endYear.'.xlsx';

        return Excel::download(new ComparisonExport($tahunPertama, $tahunKedua, $findOperation), $filename);

        return redirect()->back();
    }

    public function downloadExcel($uuid)
    {
        $korlantasRekap = KorlantasRekap::with(['rencaraOperasi', 'poldaData'])->whereUuid($uuid)->first();

        if(!empty($korlantasRekap)) {

            $now = now()->format("Y-m-d");
            $rename = (empty($korlantasRekap->report_name)) ? "report" : $korlantasRekap->report_name;
            $filename = slugTitle($rename).'-'.$now.'.xlsx';

            $findOperation = RencanaOperasi::where("id", $korlantasRekap->rencana_operasi_id)->first();

            $prevYear = DailyInputPrev::where('year', $korlantasRekap->year)
                ->where('rencana_operasi_id', $korlantasRekap->rencana_operasi_id)
                ->first();

            if(!empty($prevYear)) {

                return Excel::download(new DailyInputPrevExport(
                    $korlantasRekap->year,
                    $korlantasRekap->rencana_operasi_id,
                    $korlantasRekap->polda,
                    $korlantasRekap->operation_date,
                    $korlantasRekap->rencaraOperasi->name,
                    $korlantasRekap->poldaData,
                    $findOperation
                ), $filename);

            } else {

                $currentYear = DailyInput::where('year', $korlantasRekap->year)
                    ->where('rencana_operasi_id', $korlantasRekap->rencana_operasi_id)
                    ->first();

                if(!empty($currentYear)) {

                    return Excel::download(new DailyInputExport(
                        $korlantasRekap->year,
                        $korlantasRekap->rencana_operasi_id,
                        $korlantasRekap->polda,
                        $korlantasRekap->operation_date,
                        $korlantasRekap->rencaraOperasi->name,
                        $korlantasRekap->poldaData,
                        $findOperation
                    ), $filename);

                } else {
                    flash('Laporan tidak ditemukan. Pastikan data yang diinput sudah benar!')->warning();
                    return redirect()->back();
                }
            }

        } else {
            flash('Data rekap laporan tidak ditemukan!')->warning();
            return redirect()->back();
        }
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

        $format = explode(" to ", $date_range);

        $start_date = Carbon::parse(rtrim($format[0], " "))->format('Y-m-d');
        $end_date = Carbon::parse(ltrim($format[1], " "))->format('Y-m-d');

        $prevYear = laporanPrev($operation_id, $start_year, $start_date, $end_date);
        $currentYear = laporanCurrent($operation_id, $end_year, $start_date, $end_date);

        return [
            'prev' => $prevYear,
            'current' => $currentYear
        ];
    }
}
