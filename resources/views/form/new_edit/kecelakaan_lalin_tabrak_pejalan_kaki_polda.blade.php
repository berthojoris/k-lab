<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div id="toggleAccordion">
            <div class="card">
                <div class="card-header" id="heading04">
                    <section class="mb-0 mt-0">
                        <div role="menu" class="" data-toggle="collapse" data-target="#defaultAccordion04" aria-expanded="false" aria-controls="defaultAccordion04">
                        <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" heighviewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-widstroke-linecap="round" stroke-linejoin="round" class="ffeather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-xl-1 col-md-12 col-sm-12 col-12">
                                24
                                </div>
                                <div class="col-xl-5 col-md-12 col-sm-12 col-12">
                                TABRAK PEJALAN KAKI
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12 text-center">
                                    {{ yearMinus() }}
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12 text-center">
                                    {{ year() }}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div id="defaultAccordion04" class="collapse show" aria-labelledby="heading04" data-parent="#toggleAccordion">
                    <div class="card-body">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 line-onsite">
                            <div class="row">
                                <div class="col-xl-1 col-md-12 col-sm-12 col-12">
                                </div>
                                <div class="col-xl-5 col-md-12 col-sm-12 col-12">
                                A. JUMLAH KEJADIAN
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian_p') is-invalid @enderror" name="kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian_p" autocomplete="off" value="{{ $data->dailyInputPrev->kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian_p }}">
                                    @error('kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian_p')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian') is-invalid @enderror" name="kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian" autocomplete="off" value="{{ $data->dailyInput->kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian }}">
                                    @error('kecelakaan_lalin_tabrak_pejalan_kaki_jumlah_kejadian')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 line-onsite">
                            <div class="row">
                                <div class="col-xl-1 col-md-12 col-sm-12 col-12">
                                </div>
                                <div class="col-xl-5 col-md-12 col-sm-12 col-12">
                                B. KORBAN MENINGGAL DUNIA
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal_p') is-invalid @enderror" name="kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal_p" autocomplete="off" value="{{ $data->dailyInputPrev->kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal_p }}">
                                    @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal_p')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal') is-invalid @enderror" name="kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal" autocomplete="off" value="{{ $data->dailyInput->kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal }}">
                                    @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_meninggal')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 line-onsite">
                            <div class="row">
                                <div class="col-xl-1 col-md-12 col-sm-12 col-12">
                                </div>
                                <div class="col-xl-5 col-md-12 col-sm-12 col-12">
                                C. KORBAN LUKA BERAT
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat_p') is-invalid @enderror" name="kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat_p" autocomplete="off" value="{{ $data->dailyInputPrev->kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat_p }}">
                                    @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat_p')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat') is-invalid @enderror" name="kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat" autocomplete="off" value="{{ $data->dailyInput->kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat }}">
                                    @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_berat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 line-onsite">
                            <div class="row">
                                <div class="col-xl-1 col-md-12 col-sm-12 col-12">
                                </div>
                                <div class="col-xl-5 col-md-12 col-sm-12 col-12">
                                D. KORBAN LUKA RINGAN
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan_p') is-invalid @enderror" name="kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan_p" autocomplete="off" value="{{ $data->dailyInputPrev->kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan_p }}">
                                    @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan_p')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan') is-invalid @enderror" name="kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan" autocomplete="off" value="{{ $data->dailyInput->kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan }}">
                                    @error('kecelakaan_lalin_tabrak_pejalan_kaki_korban_luka_ringan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 line-onsite">
                            <div class="row">
                                <div class="col-xl-1 col-md-12 col-sm-12 col-12">
                                </div>
                                <div class="col-xl-5 col-md-12 col-sm-12 col-12">
                                E. MATERIIL
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_tabrak_pejalan_kaki_materiil_p') is-invalid @enderror" name="kecelakaan_lalin_tabrak_pejalan_kaki_materiil_p" autocomplete="off" value="{{ $data->dailyInputPrev->kecelakaan_lalin_tabrak_pejalan_kaki_materiil_p }}">
                                    @error('kecelakaan_lalin_tabrak_pejalan_kaki_materiil_p')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_tabrak_pejalan_kaki_materiil') is-invalid @enderror" name="kecelakaan_lalin_tabrak_pejalan_kaki_materiil" autocomplete="off" value="{{ $data->dailyInput->kecelakaan_lalin_tabrak_pejalan_kaki_materiil }}">
                                    @error('kecelakaan_lalin_tabrak_pejalan_kaki_materiil')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>