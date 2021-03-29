<div class="col-lg-12 layout-spacing">

    @if ($errors->any())
        <div class="alert alert-danger custom">
            <ul>
                <li>Inputan anda belum lengkap. Silahkan diperiksa lagi</li>
            </ul>
        </div>
    @endif

    @include('flash::message')

    <blockquote class="blockquote">
        <p class="d-inline">LAPORAN HARIAN OPERASI ZEBRA</p>
        <span>TANGGAL : 10 FEBRUARI S/D 24 FEBRUARI 2021</span>
        <div class="button-onsite">
            <span class="inputan">input data</span>
            <span class="seehow">lihat data</span>
        </div>
    </blockquote>

    <div class="statbox widget box box-shadow">
        <div id="toggleAccordion">
            <div class="card">
                <div class="card-header" id="heading01">
                    <section class="mb-0 mt-0">
                        <div role="menu" class="" data-toggle="collapse" data-target="#defaultAccordion01" aria-expanded="false" aria-controls="defaultAccordion01">
                        <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" heighviewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-widstroke-linecap="round" stroke-linejoin="round" class="ffeather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-xl-2 col-md-12 col-sm-12 col-12">
                                NO.
                                </div>
                                <div class="col-xl-6 col-md-12 col-sm-12 col-12">
                                URAIAN
                                </div>
                                <div class="col-xl-4 col-md-12 col-sm-12 col-12">
                                TAHUN
                                </div>
                            </div>                                            
                        </div>
                    </section>
                </div>
                <div id="defaultAccordion01" class="collapse show" aria-labelledby="heading01" data-parent="#toggleAccordion">
                    <div class="card-body">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                I DATA TERKAIT MASALAH PELANGGARAN LALU LINTAS
                                </div>
                            </div>                                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="heading02">
                    <section class="mb-0 mt-0">
                        <div role="menu" class="" data-toggle="collapse" data-target="#defaultAccordion02" aria-expanded="false" aria-controls="defaultAccordion02">
                        <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" heighviewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-widstroke-linecap="round" stroke-linejoin="round" class="ffeather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-xl-1 col-md-12 col-sm-12 col-12">
                                01
                                </div>
                                <div class="col-xl-5 col-md-12 col-sm-12 col-12">
                                PELANGGARAN LALU LINTAS
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
                <div id="defaultAccordion02" class="collapse show" aria-labelledby="heading02" data-parent="#toggleAccordion">
                    <div class="card-body">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 line-onsite">
                            <div class="row">
                                <div class="col-xl-1 col-md-12 col-sm-12 col-12">
                                </div>
                                <div class="col-xl-5 col-md-12 col-sm-12 col-12">
                                A. TILANG
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('pelanggaran_lalu_lintas_tilang_prev') is-invalid @enderror" name="pelanggaran_lalu_lintas_tilang_prev" autocomplete="off" value="{{ old('pelanggaran_lalu_lintas_tilang_prev') }}">
                                    @error('pelanggaran_lalu_lintas_tilang_prev')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('pelanggaran_lalu_lintas_tilang') is-invalid @enderror" name="pelanggaran_lalu_lintas_tilang" autocomplete="off" value="{{ old('pelanggaran_lalu_lintas_tilang') }}">
                                    @error('pelanggaran_lalu_lintas_tilang')
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
                                B. TEGURAN
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('pelanggaran_lalu_lintas_teguran_prev') is-invalid @enderror" name="pelanggaran_lalu_lintas_teguran_prev" autocomplete="off" value="{{ old('pelanggaran_lalu_lintas_teguran_prev') }}">
                                    @error('pelanggaran_lalu_lintas_teguran_prev')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-md-12 col-sm-12 col-12">
                                    <input type="number" class="form-onsite @error('pelanggaran_lalu_lintas_teguran') is-invalid @enderror" name="pelanggaran_lalu_lintas_teguran" autocomplete="off" value="{{ old('pelanggaran_lalu_lintas_teguran') }}">
                                    @error('pelanggaran_lalu_lintas_teguran')
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