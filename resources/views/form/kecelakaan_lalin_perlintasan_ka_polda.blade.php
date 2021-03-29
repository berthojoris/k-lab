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
                                29
                                </div>
                                <div class="col-xl-5 col-md-12 col-sm-12 col-12">
                                KECELAKAAN DI PERLINTASAN KA
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12 text-center">
                                2019
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12 text-center">
                                2020
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
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_jumlah_kejadian_prev') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_jumlah_kejadian_prev" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_jumlah_kejadian_prev') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_jumlah_kejadian_prev')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_jumlah_kejadian') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_jumlah_kejadian" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_jumlah_kejadian') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_jumlah_kejadian')
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
                                B. BERPALANG PINTU
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_berpalang_pintu_prev') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_berpalang_pintu_prev" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_berpalang_pintu_prev') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_berpalang_pintu_prev')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_berpalang_pintu') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_berpalang_pintu" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_berpalang_pintu') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_berpalang_pintu')
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
                                C. TIDAK BERPALANG PINTU
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu_prev') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu_prev" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu_prev') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu_prev')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_tidak_berpalang_pintu')
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
                                D. KORBAN MENINGGAL DUNIA
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_korban_meninggal_prev') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_korban_meninggal_prev" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_korban_meninggal_prev') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_korban_meninggal_prev')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_korban_meninggal') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_korban_meninggal" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_korban_meninggal') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_korban_meninggal')
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
                                E. KORBAN LUKA BERAT
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_korban_luka_berat_prev') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_korban_luka_berat_prev" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_korban_luka_berat_prev') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_korban_luka_berat_prev')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_korban_luka_berat') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_korban_luka_berat" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_korban_luka_berat') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_korban_luka_berat')
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
                                F. KORBAN LUKA RINGAN
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_korban_luka_ringan_prev') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_korban_luka_ringan_prev" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_korban_luka_ringan_prev') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_korban_luka_ringan_prev')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_korban_luka_ringan') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_korban_luka_ringan" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_korban_luka_ringan') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_korban_luka_ringan')
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
                                G. MATERIIL
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_materiil_prev') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_materiil_prev" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_materiil_prev') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_materiil_prev')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('kecelakaan_lalin_perlintasan_ka_materiil') is-invalid @enderror" name="kecelakaan_lalin_perlintasan_ka_materiil" autocomplete="off" value="{{ old('kecelakaan_lalin_perlintasan_ka_materiil') }}">
                                    @error('kecelakaan_lalin_perlintasan_ka_materiil')
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