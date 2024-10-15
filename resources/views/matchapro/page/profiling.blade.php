@extends('matchapro/layouts/contentLayoutMaster')

@section('title', 'Profiling')


@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection

@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">

@endsection



@section('content')
    <!-- Kick start -->
    <div class="card">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Profiling</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="homeIcon-tab" data-bs-toggle="tab" href="#homeIcon"
                                aria-controls="home" role="tab" aria-selected="true"><i data-feather="file-text"></i>
                                Profiling Periodik</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profileIcon-tab" data-bs-toggle="tab" href="#profileIcon"
                                aria-controls="profile" role="tab" aria-selected="false"><i data-feather="file"></i>
                                Profiling Mandiri</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="homeIcon" aria-labelledby="homeIcon-tab" role="tabpanel">
                            <div class="card-body">
                                <div class="card-text">
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label" for="select2-periode">Periode Profiling</label>
                                            <select class="select2 form-select" id="select2-periode">

                                                @foreach ($periode_profiling as $periode)
                                                    <option value="{{ $periode->id }}"
                                                        data-is_active="{{ $periode->is_active }}"
                                                        data-start_date="{{ $periode->start_date }}"
                                                        data-end_date="{{ $periode->end_date }}">
                                                        {{ $periode->nama_kegiatan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label" for="select2-status_form">Status Form</label>
                                            <select class="select2 form-select" id="select2-status_form">
                                                <option value="">--Pilih Status Form--</option>
                                                <option value="OPEN">OPEN</option>
                                                <option value="DRAFT">DRAFT</option>
                                                <option value="SUBMITTED">SUBMITTED</option>
                                                <option value="REJECTED">REJECTED</option>
                                                <option value="APPROVED">APPROVED</option>
                                                <option value="CANCELED">CANCELED</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-warning p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="file" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder open-count">0</h2>
                                                    <p class="card-text">Open</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-warning p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="file-text" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder draft-count">0</h2>
                                                    <p class="card-text">Draft</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-success p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="send" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder submitted-count">0</h2>
                                                    <p class="card-text">Submitted</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-success p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="check" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder approved-count">0</h2>
                                                    <p class="card-text">Approved</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-danger p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="x" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder rejected-count">0</h2>
                                                    <p class="card-text">Rejected</p>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-danger p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="trash-2" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder canceled-count">0</h2>
                                                    <p class="card-text">Canceled</p>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="card">

                                <div id="alert-periode_berakhir"></div>


                                <div class="card-header">
                                    <h4 class="card-title">Data Alokasi Profiling</h4>
                                </div>
                                <div class="card-body">
                                    {{-- Load Data --}}
                                    <div class="card-datatable">
                                        <table id="data_profiling" class="dt-responsive table " style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>Alamat</th>
                                                    <th>Wilayah</th>
                                                    <th>Status</th>
                                                    <th>Updated At</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>Alamat</th>
                                                    {{-- <th>Provinsi</th>
                                                    <th>Kabupaten/Kota</th>
                                                    <th>Kecamatan</th>
                                                    <th>Kelurahan/Desa</th> --}}
                                                    <th>Wilayah</th>
                                                    <th>Status</th>
                                                    <th>Updated At</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                {{-- Load Data Here --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="profileIcon" aria-labelledby="profileIcon-tab" role="tabpanel">
                            <div class="card-body">
                                <div class="card-text">
                                    <div class="row">

                                        <div class="col-md-6 mb-1">
                                            <label class="form-label" for="select2-status_form_mandiri">Status
                                                Form</label>
                                            <select class="select2 form-select" id="select2-status_form_mandiri">
                                                <option value="">--Pilih Status Form--</option>
                                                <option value="OPEN">OPEN</option>
                                                <option value="DRAFT">DRAFT</option>
                                                <option value="SUBMITTED">SUBMITTED</option>
                                                <option value="REJECTED">REJECTED</option>
                                                <option value="APPROVED">APPROVED</option>
                                                <option value="CANCELED">CANCELED</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-warning p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="file" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder open-count-mandiri">0</h2>
                                                    <p class="card-text">Open</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-warning p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="file-text" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder draft-count-mandiri">0</h2>
                                                    <p class="card-text">Draft</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-success p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="send" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder submitted-count-mandiri">0</h2>
                                                    <p class="card-text">Submitted</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-success p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="check" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder approved-count-mandiri">0</h2>
                                                    <p class="card-text">Approved</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-danger p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="x" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder rejected-count-mandiri">0</h2>
                                                    <p class="card-text">Rejected</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="card text-center">
                                                <div class="">
                                                    <div class="avatar bg-light-danger p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="trash-2" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder canceled-count-mandiri">0</h2>
                                                    <p class="card-text">Canceled</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Data Profiling Mandiri</h4>
                                </div>
                                <div class="card-body">
                                    {{-- Load Data --}}
                                    <div class="card-datatable">
                                        <table id="data_profiling_mandiri" class="dt-responsive table "
                                            style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>Alamat</th>
                                                    <th>Wilayah</th>
                                                    <th>Status</th>
                                                    <th>Updated At</th>
                                                    <th>Updated By</th>
                                                    <th>Tipe Update</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>Alamat</th>
                                                    <th>Wilayah</th>
                                                    <th>Status</th>
                                                    <th>Updated At</th>
                                                    <th>Updated By</th>
                                                    <th>Tipe Update</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                {{-- Load Data Here --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--/ Kick start -->

    <!-- Modal View -->
    <div class="modal fade text-start" id="backdrop" tabindex="-1" aria-labelledby="myModalLabel4"
        data-bs-backdrop="true" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel4">History Data</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-1 col-lg-12 col-md-12">
                        <h5>Data yang Ditampilkan</h5>
                        <div class="card-datatable">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="kodeCheckbox" value="checked"
                                    checked />
                                <label class="form-check-label" for="kodeCheckbox">Kode</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="namaCheckbox" value="checked"
                                    checked />
                                <label class="form-check-label" for="namaCheckbox">Nama</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="alamatCheckbox" value="checked"
                                    checked />
                                <label class="form-check-label" for="alamatCheckbox">Alamat</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="provinsiCheckbox" value="checked"
                                    checked />
                                <label class="form-check-label" for="provinsiCheckbox">Provinsi</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="kabupatenKotaCheckbox"
                                    value="checked" checked />
                                <label class="form-check-label" for="kabupatenKotaCheckbox">Kabupaten/Kota</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="kecamatanCheckbox" value="checked"
                                    checked />
                                <label class="form-check-label" for="kecamatanCheckbox">Kecamatan</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="kelurahanDesaCheckbox"
                                    value="checked" checked />
                                <label class="form-check-label" for="kelurahanDesaCheckbox">Kelurahan/Desa</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="statusFormCheckbox" value="checked"
                                    checked />
                                <label class="form-check-label" for="statusFormCheckbox">Status Form</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="statusUsahaCheckbox" value="checked"
                                    checked />
                                <label class="form-check-label" for="statusUsahaCheckbox">Status Usaha</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="updatedAtCheckbox" value="checked"
                                    checked />
                                <label class="form-check-label" for="updatedAtCheckbox">Updated At</label>
                            </div>
                            <div class="form-check form-check-inline d-none">
                                <input class="form-check-input" type="checkbox" id="createdAtCheckbox"
                                    value="checked" />
                                <label class="form-check-label" for="createdAtCheckbox">Created At</label>
                            </div>
                            <br> <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="emailCheckbox" value="checked" />
                                <label class="form-check-label" for="emailCheckbox">Email</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="websiteCheckbox" value="checked" />
                                <label class="form-check-label" for="websiteCheckbox">Website</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="kodePosCheckbox" value="checked" />
                                <label class="form-check-label" for="kodePosCheckbox">Kode Pos</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="latitudeCheckbox" value="checked" />
                                <label class="form-check-label" for="latitudeCheckbox">Latitude</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="longitudeCheckbox"
                                    value="checked" />
                                <label class="form-check-label" for="longitudeCheckbox">Longitude</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="telpCheckbox" value="checked" />
                                <label class="form-check-label" for="telpCheckbox">Telepon</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="noWaCheckbox" value="checked" />
                                <label class="form-check-label" for="noWaCheckbox">No Whatsapp</label>
                            </div>

                            <br><br>

                            {{-- Blok 2 --}}
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="kategoriCheckbox" value="checked" />
                                <label class="form-check-label" for="kategoriCheckbox">Kategori KBLI</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="kbliCheckbox" value="checked" />
                                <label class="form-check-label" for="kbliCheckbox">KBLI</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="kegiatanUtamaCheckbox"
                                    value="checked" />
                                <label class="form-check-label" for="kegiatanUtamaCheckbox">Kegiatan Utama</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="deskripsiProdukUsahaCheckbox"
                                    value="checked" />
                                <label class="form-check-label" for="deskripsiProdukUsahaCheckbox">Deskripsi Produk
                                    Usaha</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="jenisKepemilikanUsahaCheckbox"
                                    value="checked" />
                                <label class="form-check-label" for="jenisKepemilikanUsahaCheckbox">Jenis Kepemilikan
                                    Usaha</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="bentukBadanUsahaCheckbox"
                                    value="checked" />
                                <label class="form-check-label" for="bentukBadanUsahaCheckbox">Bentuk Badan Usaha</label>
                            </div>


                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="tahunBerdiriCheckbox"
                                    value="checked" />
                                <label class="form-check-label" for="tahunBerdiriCheckbox">Tahun Berdiri</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="jaringanUsahaCheckbox"
                                    value="checked" />
                                <label class="form-check-label" for="jaringanUsahaCheckbox">Jaringan Usaha</label>
                            </div>
                        </div>
                        <hr>
                        <div class="card-datatable ">
                            <table id="history_table" class="dt-responsive table section-block-history_periodik"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Provinsi</th>
                                        <th>Kabupaten/Kota</th>
                                        <th>Kecamatan</th>
                                        <th>Kelurahan/Desa</th>
                                        <th>Status Form</th>
                                        <th>Status Usaha</th>
                                        <th>Updated At</th>
                                        <th>Created At</th>
                                        <th>Email</th>
                                        <th>Website</th>
                                        <th>Kode Pos</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Telepon</th>
                                        <th>No Whatsapp</th>
                                        <th>Kategori KBLI</th>
                                        <th>KBLI</th>
                                        <th>Kegiatan Utama</th>
                                        <th>Deskripsi Produk Usaha</th>
                                        <th>Jenis Kepemilikan Usaha</th>
                                        <th>Bentuk Badan Usaha</th>
                                        <th>Tahun Berdiri</th>
                                        <th>Jaringan Usaha</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Provinsi</th>
                                        <th>Kabupaten/Kota</th>
                                        <th>Kecamatan</th>
                                        <th>Kelurahan/Desa</th>
                                        <th>Status Form</th>
                                        <th>Status Usaha</th>
                                        <th>Updated At</th>
                                        <th>Created At</th>
                                        <th>Email</th>
                                        <th>Website</th>
                                        <th>Kode Pos</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Telepon</th>
                                        <th>No Whatsapp</th>
                                        <th>Kategori KBLI</th>
                                        <th>KBLI</th>
                                        <th>Kegiatan Utama</th>
                                        <th>Deskripsi Produk Usaha</th>
                                        <th>Jenis Kepemilikan Usaha</th>
                                        <th>Bentuk Badan Usaha</th>
                                        <th>Tahun Berdiri</th>
                                        <th>Jaringan Usaha</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    {{-- Load Data Here --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button class="btn btn-relief-danger btn-clear-form" data-bs-dismiss="modal" id="btnClearForm">
                        <i data-feather="x" class="align-middle me-sm-25 me-0"></i>
                        <span class="align-middle d-sm-inline-block d-none">Close</span>
                    </button>

                </div>
            </div>
        </div>
    </div>

    <!--/ Modal View -->
@endsection
@section('vendor-script')
    <!-- vendor files -->
    {{-- <script src="{{ asset(mix('js/scripts/components/components-tooltips.js')) }}"></script> --}}
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script>
        $(document).ready(function() {
            // Define the route template for form_update_usaha.index in JavaScript
            let formUpdateUsahaRoute =
                "{{ route('form_update_usaha.index', ['perusahaan_id' => ':perusahaan_id', 'alokasi_id' => ':alokasi_id']) }}";
            let cancelUpdateUsahaRoute =
                "{{ route('form_update_usaha.cancel', ['perusahaan_id' => ':perusahaan_id', 'alokasi_id' => ':alokasi_id']) }}";

            var table = $('#data_profiling').DataTable({
                language: {
                    emptyTable: "Tidak ada Data yang tersedia"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('profiling.getData') }}",
                    data: function(d) {
                        d.periode_id = $('#select2-periode').val();
                        d.status_form = $('#select2-status_form').val();
                    },
                    dataSrc: function(json) {
                        // Update HTML with the count of each status
                        updateStatusCounts(json.countGroup, 'periodik');
                        return json.data;
                    }

                },
                // language: {
                //     processing: '<div class="d-flex justify-content-center align-items-center"><p class="me-50 mb-0">Mohon Menunggu...</p></div> <div class="spinner-border text-primary" role="status">',
                // },
                columns: [{
                        data: 'kode',
                        title: 'Kode',
                        width: '1%'
                    },
                    {
                        data: 'nama',
                        title: 'Nama Usaha'
                    },
                    {
                        data: 'alamat',
                        title: 'Alamat'
                    },
                    {
                        data: null,
                        title: 'Wilayah',
                        render: function(data, type, row) {
                            // Generate buttons with actions
                            // return `${row.provinsi_nama}-${row.kabupaten_kota_nama}-${row.kecamatan_nama}-${row.kelurahan_desa_nama} `;
                            return `${row.provinsi_kode}-${row.kabupaten_kota_kode}${row.kecamatan_kode ? '-'+row.kecamatan_kode : ''}${row.kelurahan_desa_kode ? '-'+row.kelurahan_desa_kode : ''} <br> <hr>
                            ${row.provinsi_nama}-${row.kabupaten_kota_nama}${row.kecamatan_nama ? '-'+row.kecamatan_nama : ''}${row.kelurahan_desa_nama ? '-'+row.kelurahan_desa_nama : ''}
                            `;
                        }
                    },
                    {
                        data: 'status_form',
                        title: 'Status',
                        render: function(data, type, row) {

                            if (row.status_form == 'OPEN') {
                                return `<span class="badge rounded-pill badge-light-warning">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'DRAFT') {
                                return `<span class="badge rounded-pill badge-light-warning">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'SUBMITTED') {
                                return `<span class="badge rounded-pill badge-light-success">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'REJECTED') {
                                return `<span class="badge rounded-pill badge-light-danger">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'APPROVED') {
                                return `<span class="badge rounded-pill badge-light-success">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'CANCELED') {
                                return `<span class="badge rounded-pill badge-light-danger">${row.status_form}</span>`;
                            }
                            // Generate buttons with actions
                            // return `${row.status_form}`;

                        }
                    },
                    {
                        data: 'updated_at',
                        title: 'Updated At',
                        render: function(data, type, row) {
                            // Generate buttons with actions
                            return `${row.updated_at}`;
                        }
                    },
                    {
                        data: null, // This column doesn't correspond to a field in the dataset
                        title: 'Aksi',
                        orderable: false, // Disable ordering on this column
                        searchable: false, // Disable searching on this column
                        render: function(data, type, row) {
                            // Replace placeholders with actual row data
                            let editUrl = formUpdateUsahaRoute
                                .replace(':perusahaan_id', row.perusahaan_id)
                                .replace(':alokasi_id', row.id);

                            // Generate buttons with actions
                            return `
                            <a href="javascript:void(0)" data-bs-toggle="modal" 
                                data-url="{{ route('form_update_usaha.history', ['perusahaan_id' => '__PERUSAHAAN_ID__', 'alokasi_id' => '__ALOKASI_ID__']) }}" 
                                data-perusahaan_id="${row.perusahaan_id}" data-alokasi_id="${row.id}" data-bs-target="#backdrop">
                                <button type="button" class="btn btn-icon btn-flat-primary btn-lg" data-bs-toggle="tooltip" data-bs-placement="top" title="View History">
                                    <i data-feather="eye" width="40" height="40"></i>
                                </button>
                            </a>
                            <a href="${editUrl}" > 
                                <button type="button" class="btn btn-icon btn-flat-primary btn-lg " data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i data-feather="edit" width="40" height="40"></i></button>
                            </a>`;
                        },
                        width: '12%'
                    }
                ],
                drawCallback: function() {
                    // Re-render Feather icons after the table is drawn
                    feather.replace();
                }
            });

            function updateStatusCounts(countGroup, tipeProfiling) {
                // Reset counts
                var statusCounts = {
                    'OPEN': 0,
                    'DRAFT': 0,
                    'SUBMITTED': 0,
                    'APPROVED': 0,
                    'REJECTED': 0,
                    'CANCELED': 0
                };

                // Populate counts based on the response data
                countGroup.forEach(function(status) {
                    statusCounts[status.status_form] = status.total;
                });

                // Update the HTML elements
                if (tipeProfiling == 'periodik') {
                    $('.open-count').text(statusCounts['OPEN']);
                    $('.draft-count').text(statusCounts['DRAFT']);
                    $('.submitted-count').text(statusCounts['SUBMITTED']);
                    $('.approved-count').text(statusCounts['APPROVED']);
                    $('.rejected-count').text(statusCounts['REJECTED']);
                    // $('.canceled-count').text(statusCounts['CANCELED']);
                }

                if (tipeProfiling == 'mandiri') {
                    $('.open-count-mandiri').text(statusCounts['OPEN']);
                    $('.draft-count-mandiri').text(statusCounts['DRAFT']);
                    $('.submitted-count-mandiri').text(statusCounts['SUBMITTED']);
                    $('.approved-count-mandiri').text(statusCounts['APPROVED']);
                    $('.rejected-count-mandiri').text(statusCounts['REJECTED']);
                    $('.canceled-count-mandiri').text(statusCounts['CANCELED']);
                }

            }




            function handlePeriodeChange() {
                let isActive = $('#select2-periode').find(":selected").data("is_active");
                let endDate = $('#select2-periode').find(":selected").data("end_date");

                // Check if the selected period is inactive
                if (isActive === 0) {
                    // Check if the alert already exists, if not, create it
                    if ($('#alert-periode_berakhir .alert-danger').length === 0) {
                        $('#alert-periode_berakhir').html(`
                            <div class="card-body">
                                <div class="alert alert-danger mt-2">
                                    <h4 class="alert-heading">
                                        <i data-feather="alert-triangle" class="me-50"></i>Periode Profiling yang dipilih telah berakhir
                                    </h4>
                                    <div class="alert-body fw-normal"></div>
                                </div>
                            </div>
                        `);
                    }

                    // Update the alert message with the end date
                    $("#alert-periode_berakhir .alert-body").text(`Periode Profiling berakhir pada ${endDate}`);

                    // Show the alert by appending it back if it was removed
                    if (!$('#alert-periode_berakhir').has('.alert-danger').length) {
                        $('#alert-periode_berakhir').append(`
                            <div class="card-body">
                                <div class="alert alert-danger mt-2">
                                    <h4 class="alert-heading">
                                        <i data-feather="alert-triangle" class="me-50"></i>Periode Profiling yang dipilih telah berakhir
                                    </h4>
                                    <div class="alert-body fw-normal">Periode Profiling berakhir pada ${endDate}</div>
                                </div>
                            </div>
                        `);
                    }
                } else {
                    // Remove the alert if the period is active
                    $("#alert-periode_berakhir .alert-danger").parent().remove();
                }

            }

            // Run the initial check without reloading the table
            handlePeriodeChange();
            // Bind the change event to reload the table and run the check
            $('#select2-periode').on('change', function() {
                handlePeriodeChange();
                table.ajax.reload();
            });

            $('#select2-status_form').on('change', function() {
                table.ajax.reload();
            });




            // Trigger change event on page load to check the initial selection
            // $("#select2-periode").change();



            //Profiling Mandiri
            var id_profiling_mandiri = @json($id_profiling_mandiri);


            // console.log('id_mandiri', id_profiling_mandiri)
            var table_mandiri = $('#data_profiling_mandiri').DataTable({
                language: {
                    emptyTable: "Tidak ada Data yang tersedia"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('profiling.getData') }}",
                    data: function(d) {
                        d.periode_id = id_profiling_mandiri;
                        d.status_form = $('#select2-status_form_mandiri').val();
                    },
                    dataSrc: function(json) {
                        // Update HTML with the count of each status
                        updateStatusCounts(json.countGroup, 'mandiri');
                        return json.data;
                    }
                },
                // language: {
                //     processing: '<div class="d-flex justify-content-center align-items-center"><p class="me-50 mb-0">Mohon Menunggu...</p></div> <div class="spinner-border text-primary" role="status">',
                // },
                columns: [{
                        data: 'kode',
                        title: 'Kode',

                    },
                    {
                        data: 'nama',
                        title: 'Nama Usaha'
                    },
                    {
                        data: 'alamat',
                        title: 'Alamat'
                    },
                    {
                        data: null,
                        title: 'Wilayah',
                        render: function(data, type, row) {
                            // Generate buttons with actions
                            // return `${row.provinsi_nama}-${row.kabupaten_kota_nama}-${row.kecamatan_nama}-${row.kelurahan_desa_nama} `;
                            return `${row.provinsi_kode}-${row.kabupaten_kota_kode} ${row.kecamatan_kode ? '-'+row.kecamatan_kode : ''}${row.kelurahan_desa_kode ? '-'+row.kelurahan_desa_kode : ''} <br> <hr>
                            ${row.provinsi_nama}-${row.kabupaten_kota_nama} ${row.kecamatan_nama ? '-'+row.kecamatan_nama : ''} ${row.kelurahan_desa_nama ? '-'+row.kelurahan_desa_nama : ''}
                            `;
                        }
                    },
                    {
                        data: 'status_form',
                        title: 'Status',
                        render: function(data, type, row) {

                            if (row.status_form == 'OPEN') {
                                return `<span class="badge rounded-pill badge-light-warning">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'DRAFT') {
                                return `<span class="badge rounded-pill badge-light-warning">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'SUBMITTED') {
                                return `<span class="badge rounded-pill badge-light-success">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'REJECTED') {
                                return `<span class="badge rounded-pill badge-light-danger">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'APPROVED') {
                                return `<span class="badge rounded-pill badge-light-success">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'CANCELED') {
                                return `<span class="badge rounded-pill badge-light-danger">${row.status_form}</span>`;
                            }
                            // Generate buttons with actions
                            // return `${row.status_form}`;

                        }
                    },
                    {
                        data: 'updated_at',
                        title: 'Updated At',
                        render: function(data, type, row) {
                            // Generate buttons with actions
                            return `${row.updated_at}`;
                        }
                    },
                    {
                        data: 'updated_by_nama',
                        title: 'Updated By',
                        render: function(data, type, row) {
                            // Generate buttons with actions
                            return `${row.updated_by_nama}`;
                        }
                    },
                    {
                        data: 'action_type',
                        title: 'TIPE UPDATE',
                        render: function(data, type, row) {
                            // Generate buttons with actions
                            return `${row.action_type}`;
                        }
                    },
                    {
                        data: null, // This column doesn't correspond to a field in the dataset
                        title: 'Aksi',
                        orderable: false, // Disable ordering on this column
                        searchable: false, // Disable searching on this column
                        render: function(data, type, row) {
                            let cancelUrl = formUpdateUsahaRoute
                                .replace(':perusahaan_id', row.perusahaan_id)
                                .replace(':alokasi_id', row.id);
                            let editUrl = formUpdateUsahaRoute
                                .replace(':perusahaan_id', row.perusahaan_id)
                                .replace(':alokasi_id', row.id);

                            // Generate buttons with actions
                            return `
                                        <a href="javascript:void(0)" data-bs-toggle="modal" 
                                        data-url="{{ route('form_update_usaha.history', ['perusahaan_id' => '__PERUSAHAAN_ID__', 'alokasi_id' => '__ALOKASI_ID__']) }}" 
                                        data-perusahaan_id="${row.perusahaan_id}" data-alokasi_id="${row.id}" data-bs-target="#backdrop">
                                            <button type="button" class="btn btn-icon btn-flat-primary btn-lg" 
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="View History">
                                                <i data-feather="eye" width="40" height="40"></i>
                                            </button>
                                        </a>

                                        <a href="${editUrl}"> 
                                            <button type="button" class="btn btn-icon btn-flat-primary btn-lg" 
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i data-feather="edit" width="40" height="40"></i>
                                            </button>
                                        </a>

                                        ${(row.action_type === 'CREATE' && 
                                        (row.status_form === 'OPEN' || row.status_form === 'DRAFT' || row.status_form === 'REJECTED')) ? `
                                                                                                                                    <button type="button" class="cancel-button btn btn-icon btn-flat-danger btn-lg" 
                                                                                                                                            data-url="{{ route('form_update_usaha.cancel', ['perusahaan_id' => '__PERUSAHAAN_ID__', 'alokasi_id' => '__ALOKASI_ID__']) }}" 
                                                                                                                                            data-perusahaan_id="${row.perusahaan_id}" 
                                                                                                                                            data-alokasi_id="${row.id}" 
                                                                                                                                            data-bs-toggle="tooltip" 
                                                                                                                                            data-bs-placement="top" 
                                                                                                                                            title="Cancel">
                                                                                                                                        <i data-feather="x" width="40" height="40"></i>
                                                                                                                                    </button>
                                                                                                                                ` : ``}
                                    `;
                        },

                        width: '15%'
                    }
                ],
                drawCallback: function() {
                    // Re-render Feather icons after the table is drawn
                    feather.replace();
                }
            });

            $('#select2-status_form_mandiri').on('change', function() {
                table_mandiri.ajax.reload();
            });

            // Cancel Button
            $(document).on('click', '.cancel-button', function(event) {
                event.preventDefault();


                // Get the data attributes
                const perusahaanId = $(this).data('perusahaan_id');
                const alokasiId = $(this).data('alokasi_id');
                const url = $(this).data('url').replace('__PERUSAHAAN_ID__', perusahaanId).replace(
                    '__ALOKASI_ID__',
                    alokasiId);

                // Show confirmation dialog
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Apakah anda yakin akan membatalkan penambahan data?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, Saya yakin!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading animation
                        Swal.fire({
                            title: 'Memprosess...',
                            text: 'Mohon menunggu...',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Perform AJAX request
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}', // Include CSRF token for security
                                perusahaan_id: perusahaanId,
                                alokasi_id: alokasiId
                            },
                            success: function(response) {
                                // Close the loading animation
                                table_mandiri.ajax.reload();
                                Swal.close();

                                // Show success message
                                Swal.fire({
                                    title: 'Cancelled!',
                                    text: 'Penambahan data berhasil dibatalkan',
                                    icon: 'success'
                                });

                                // Optionally, you can close the modal or refresh part of the page here
                                console.log('Request successful:', response);
                            },
                            error: function(xhr) {
                                // Close the loading animation
                                Swal.close();

                                // Show error message
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan. Silakan coba kembali.',
                                    icon: 'error'
                                });

                                console.error('Request failed:', xhr);
                            }
                        });
                    }
                });
            });
        });
    </script>
    {{-- //History --}}
    <script>
        $(document).on('click', '[data-bs-target="#backdrop"]', function() {
            var sectionBlock = $('.section-block-history_periodik')
            // Get perusahaan_id and alokasi_id from the clicked button
            const perusahaanId = $(this).data('perusahaan_id');
            const alokasiId = $(this).data('alokasi_id');
            console.log(`perusahaanId : ${perusahaanId}`)
            console.log(`alokasiId : ${alokasiId}`)
            // Get the URL from the data-url attribute and replace placeholders with actual IDs
            let getDetailUrl = $(this).data('url');
            getDetailUrl = getDetailUrl.replace('__PERUSAHAAN_ID__', perusahaanId);
            getDetailUrl = getDetailUrl.replace('__ALOKASI_ID__', alokasiId);

            // Perform the AJAX request
            $.ajax({
                url: getDetailUrl, // The dynamically built URL using route name
                type: 'GET',
                data: {
                    perusahaan_id: perusahaanId,
                    alokasi_id: alokasiId,
                },
                beforeSend: function() {
                    sectionBlock.block({
                        message: '<div class="d-flex justify-content-center align-items-center"><p class="me-50 mb-0">Mohon Menunggu...</p></div> <div class="spinner-border text-white" role="status"></div>',
                        timeout: 5000,
                        css: {
                            backgroundColor: 'transparent',
                            color: '#fff',
                            border: '0',
                            width: '100%',
                            top: '50%',
                        },
                        overlayCSS: {
                            opacity: 0.5,
                        },
                        centerY: false, // Ensures vertical centering
                    });
                },
                success: function(data) {
                    sectionBlock.unblock();
                    console.log(data);
                    buildDataTableDetail(data);
                },
                error: function(err) {
                    sectionBlock.unblock();
                    alert('Unable to load data.');
                }
            });
        });


        // Function to build the DataTable
        function buildDataTableDetail(data) {
            // Check if the DataTable already exists and destroy it before re-initializing
            if ($.fn.DataTable.isDataTable('#history_table')) {
                $('#history_table').DataTable().clear().destroy();
            }

            // Initialize DataTable
            const tableDetail = $('#history_table').DataTable({
                language: {
                    emptyTable: "Tidak ada Data yang tersedia"
                },
                data: data, // Pass the data from the server
                columns: [{
                        //0
                        title: "Kode",
                        data: "idsbr_master"
                    },
                    {
                        //1
                        title: "Nama",
                        data: "nama_usaha"
                    },
                    {
                        //2
                        title: "Alamat",
                        data: "alamat"
                    },
                    {
                        //3
                        data: null,
                        title: 'Provinsi',
                        render: function(data, type, row) {
                            return `${row.provinsi_kode}-${row.provinsi_nama}`;
                        }
                    },
                    {
                        //4
                        data: null,
                        title: 'Kabupaten/Kota',
                        render: function(data, type, row) {
                            return `${row.kabupaten_kota_kode}-${row.kabupaten_kota_nama}`;
                        }
                    },
                    {
                        //5
                        data: null,
                        title: 'Kecamatan',
                        render: function(data, type, row) {
                            if (row.kecamatan_kode === null || row.kecamatan_nama === null) {
                                return null;
                            }
                            return `${row.kecamatan_kode}-${row.kecamatan_nama}`;
                        }
                    },
                    {
                        //6
                        data: null,
                        title: 'Kelurahan/Desa',
                        render: function(data, type, row) {
                            if (row.kelurahan_desa_kode === null || row.kelurahan_desa_nama === null) {
                                return null;
                            }
                            return `${row.kelurahan_desa_kode}-${row.kelurahan_desa_nama}`;
                        }
                    },
                    {
                        //7
                        data: 'status_form',
                        title: 'Status Form',
                        render: function(data, type, row) {

                            if (row.status_form == 'OPEN') {
                                return `<span class="badge rounded-pill badge-light-warning">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'DRAFT') {
                                return `<span class="badge rounded-pill badge-light-warning">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'SUBMITTED') {
                                return `<span class="badge rounded-pill badge-light-success">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'REJECTED') {
                                return `<span class="badge rounded-pill badge-light-danger">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'APPROVED') {
                                return `<span class="badge rounded-pill badge-light-success">${row.status_form}</span>`;
                            }

                            if (row.status_form == 'CANCELED') {
                                return `<span class="badge rounded-pill badge-light-danger">${row.status_form}</span>`;
                            }
                        }
                    },
                    {
                        //8
                        data: "status_perusahaan_id",
                        title: 'Status Usaha',
                        render: function(data, type, row) {
                            if (row.status_perusahaan_id == 1) {
                                return 'Aktif';
                            }
                            if (row.status_perusahaan_id == 2) {
                                return 'Tutup Sementara';
                            }
                            if (row.status_perusahaan_id == 3) {
                                return 'Belum Berproduksi';
                            }
                            if (row.status_perusahaan_id == 4) {
                                return 'Tutup';
                            }
                            if (row.status_perusahaan_id == 5) {
                                return 'Alih Usaha';
                            }
                            if (row.status_perusahaan_id == 6) {
                                return 'Tidak Ditemukan';
                            }
                            if (row.status_perusahaan_id == 7) {
                                return 'Aktif Pindah';
                            }
                            if (row.status_perusahaan_id == 8) {
                                return 'Aktif Nonrespons';
                            }
                            if (row.status_perusahaan_id == 9) {
                                return 'Dilaporkan Duplikat oleh Sekretatriat IBR';
                            }
                            if (row.status_perusahaan_id == 10) {
                                return 'Hapus';
                            }
                        }
                    },
                    {
                        //9
                        data: "updated_at",
                        title: 'UPDATED AT'
                    },
                    {
                        //10
                        data: "created_at",
                        title: 'CREATED AT'
                    },
                    {
                        //11
                        data: "email",
                        title: 'Email'
                    },
                    {
                        //12
                        data: "website",
                        title: 'Website'
                    },
                    {
                        //13
                        data: "kodepos",
                        title: 'Kode Pos'
                    },
                    {
                        //14
                        data: "latitude",
                        title: 'Latitude'
                    },
                    {
                        //15
                        data: "longitude",
                        title: 'Longitude'
                    },
                    {
                        //16
                        data: "telp",
                        title: 'Telepon'
                    },
                    {
                        //17
                        data: "no_wa",
                        title: 'No Whatsapp'
                    },
                    {
                        //18
                        data: "kategori",
                        title: 'Kategori'
                    },
                    {
                        //19
                        data: "kbli",
                        title: 'KBLI'
                    },
                    {
                        //20
                        data: "kegiatan_utama",
                        title: 'Kegiatan Utama'
                    },
                    {
                        //21
                        data: "deskripsi_produk_usaha",
                        title: 'Kegiatan Utama'
                    },
                    {
                        //22
                        data: "jenis_kepemilikan_usaha",
                        title: 'Jenis Kepemilikan Usaha'
                    },
                    {
                        //23
                        data: "bentuk_badan_usaha_id",
                        title: 'Bentuk Badan Usaha'
                    },
                    {
                        //24
                        data: "tahun_berdiri",
                        title: 'Tahun Berdiri'
                    },
                    {
                        //25
                        data: "jaringan_usaha_id",
                        title: 'Jarinagan Usaha'
                    },

                ],
                processing: true,
                serverSide: false,
                paging: true,
                searching: true,
                ordering: true,
                order: [
                    [9, 'desc']
                ]
            });

            // Loop through checkboxes and set up a single change event listener
            const columnsMap = {
                'kodeCheckbox': 0,
                'namaCheckbox': 1,
                'alamatCheckbox': 2,
                'provinsiCheckbox': 3,
                'kabupatenKotaCheckbox': 4,
                'kecamatanCheckbox': 5,
                'kelurahanDesaCheckbox': 6,
                'statusFormCheckbox': 7,
                'statusUsahaCheckbox': 8,
                'updatedAtCheckbox': 9,
                'createdAtCheckbox': 10,
                'emailCheckbox': 11,
                'websiteCheckbox': 12,
                'kodePosCheckbox': 13,
                'latitudeCheckbox': 14,
                'longitudeCheckbox': 15,
                'telpCheckbox': 16,
                'noWaCheckbox': 17,
                'kategoriCheckbox': 18,
                'kbliCheckbox': 19,
                'kegiatanUtamaCheckbox': 20,
                'deskripsiProdukUsahaCheckbox': 21,
                'jenisKepemilikanUsahaCheckbox': 22,
                'bentukBadanUsahaCheckbox': 23,
                'tahunBerdiriCheckbox': 24,
                'jaringanUsahaCheckbox': 25

            };

            $.each(columnsMap, (checkboxId, columnIdx) => {
                $(`#${checkboxId}`).on('change', function() {
                    tableDetail.column(columnIdx).visible(this.checked);
                }).trigger('change'); // Set initial visibility based on checkbox state
            });
        }
    </script>


@endsection
