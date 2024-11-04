@extends('matchapro/layouts/contentLayoutMaster')

@section('title', 'Form Update Usaha')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/maps/leaflet.min.css')) }}">
@endsection

@section('page-style')
<style>
    .row-gap-4 {
        row-gap: 1rem !important;
    }

    .mb-6 {
        margin-bottom: 1.5rem !important;
    }

    .btn-label-primary {
        color: #7367f0 !important;
        border-color: transparent !important;
        background: #e9e7fd !important;
    }

    .btn-label-secondary {
        color: #808390 !important;
        border-color: transparent !important;
        background: #ebebed !important;
    }

    .gap-4 {
        gap: 1rem !important;
    }

    .uppercase-text {
        text-transform: uppercase !important;
    }

</style>
<link rel="stylesheet" href="{{asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
@endsection

@section('content')
<div
    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
    <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-0">Form Update Usaha/Perusahaan</h4>
        <p class="mb-0 text-muted"><small>Update informasi terkait usaha/perusahaan</small></p>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
        @if(auth()->user()->getPermissionsViaRoles()->contains('name','update-usaha-profiling-user'))
        <button style="display: {{ in_array($status_form, ['OPEN', 'DRAFT', 'REJECTED']) ? '' : 'none' }}"
            class="btn btn-label-primary waves-effect" id="save-draft"><i data-feather="save" class="me-25"></i>
            <span>Save draft</span></button>
        <button style="display: {{ in_array($status_form, ['OPEN', 'DRAFT', 'REJECTED']) ? '' : 'none' }}"
            class="btn btn-primary waves-effect waves-light" id="submit-final"><i data-feather="send" class="me-25"></i>
            <span>Submit Final</span></button>
        <button style="display: {{ in_array($status_form, ['SUBMITTED']) ? '' : 'none' }}"
            class="btn btn-danger waves-effect waves-light" id="cancel-submit-final"><i data-feather="x"
                class="me-25"></i> <span>Cancel Submit</span></button>
        @endif
        @if(auth()->user()->getPermissionsViaRoles()->contains('name','approval-usaha-profiling-user') &&
        in_array($status_form, ['SUBMITTED']))
        <button class="btn btn-success waves-effect waves-light" id="approve-update"><i data-feather="check"
                class="me-25"></i> <span>Approve</span></button>
        <button class="btn btn-danger waves-effect waves-light" id="reject-update"><i data-feather="x"
                class="me-25"></i> <span>Reject</span></button>
        @endif

    </div>
</div>
<div class="row match-height">
    <div class="col-12 col-lg-7">
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    IDENTITAS USAHA/PERUSAHAAN
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-6">
                    <label class="form-label" for="nama_usaha">Nama Usaha <span class="text-danger">*</span></label>
                    <input {{$action_type == 'CREATE' ? 'readonly' : ''}} value="{{ $usaha->nama_usaha }}" type="text"
                        class="form-control uppercase-text" id="nama_usaha" placeholder="Nama Usaha/Perusahaan"
                        name="namaUsaha" aria-label="Nama Usaha/Perusahaan">
                    <div class="invalid-feedback"><span id="nama_usaha_error"></span></div>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="nama-komersial">Nama Komersial</label>
                    <input value="{{ $usaha->nama_komersial }}" type="text" class="form-control uppercase-text"
                        id="nama-komersial" placeholder="Nama Komersial Usaha/Perusahaan" name="namaKomersial"
                        aria-label="Nama Komersial Usaha/Perusahaan">
                </div>
                <div class="mb-6">
                    <label class="form-label" for="alamat_usaha">Alamat <span class="text-danger">*</span></label>
                    <input {{$action_type == 'CREATE' ? 'readonly' : ''}} value="{{ $usaha->alamat }}" type="text"
                        class="form-control uppercase-text" id="alamat_usaha" placeholder="Alamat Usaha/Perusahaan"
                        name="alamat" aria-label="Alamat Usaha/Perusahaan">
                    <div class="invalid-feedback"><span id="alamat_usaha_error"></span></div>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="sls">Nama/Kode SLS (Dusun/RT/RW/dll)/Non SLS</label>
                    <input value="{{ $usaha->sls_deskripsi }}" type="text" class="form-control uppercase-text" id="sls"
                        placeholder="Nama/Kode SLS/Non SLS" name="sls" aria-label="Nama SLS/Non SLS">
                </div>
                <div class="mb-6">
                    <label class="form-label" for="kodepos">Kode Pos</label>
                    <input value="{{ $usaha->kodepos }}" type="text" maxlength="5" class="form-control" id="kodepos"
                        placeholder="Kode Pos" name="kodepos" aria-label="Kode Pos">
                </div>
                <div class="mb-6">
                    <label class="form-label" for="telepon">Nomor Telepon</label>
                    <input value="{{ $usaha->telp }}" type="number" class="form-control" id="telepon"
                        placeholder="Nomor Telepon" name="telepon" aria-label="Nomor Telepon">
                </div>
                <div class="mb-6">
                    <label class="form-label" for="whatsapp">Nomor Whatsapp</label>
                    <input type="text" class="form-control" id="whatsapp" placeholder="Nomor Whatsapp" name="whatsapp"
                        aria-label="Nomor Whatsapp">
                </div>
                <div class="mb-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <small class="text-muted"><i> info: un-centang jika email tidak ditemukan</i></small>
                    <div class="input-group">
                        <div class="input-group-text">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" checked id="check-email" />
                            </div>
                        </div>
                        <input value="{{ $usaha->email }}" type="text" id="email" class="form-control"
                            placeholder="Email" />
                        <div class="invalid-feedback"><span id="email_error"></span></div>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="website">Website</label>
                    <input value="{{ $usaha->website }}" type="text" class="form-control" id="website"
                        placeholder="Website" name="website" aria-label="Website">
                    <div class="invalid-feedback"><span id="website_error"></span></div>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="latitude">Latitude</label>
                    <small class="text-muted">eg: <i>-6.2315085326216</i></small>
                    <input value="{{ $usaha->latitude }}" type="text" class="form-control" id="latitude"
                        placeholder="Latitude" name="latitude" aria-label="Latitude">
                    <div class="invalid-feedback"><span id="latitude_error"></span></div>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="longitude">Longitude</label>
                    <small class="text-muted">eg: <i>106.63301713765</i></small>
                    <input value="{{ $usaha->longitude }}" type="text" class="form-control" id="longitude"
                        placeholder="Longitude" name="longitude" aria-label="Longitude">
                    <div class="invalid-feedback"><span id="longitude_error"></span></div>
                </div>
                <div class="mb-6">
                    <button type="button" class="btn btn-outline-dark" id="cek-peta">Cek Peta</button>
                </div>
                <div class="mb-6 container-map">
                    <div id="map" style="height: 200px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="row">
            <div class="col-12">
                <div class="card mb-6">
                    <div class="card-header">
                        <h4 class="card-title">Keberadaan Usaha/Perusahaan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-6">
                                    <label class="form-label">Kondisi Usaha/Perusahaan <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control hidden-input-only" style="display: none">
                                    <div class="invalid-feedback">Salah satu opsi harus terpilih</div>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-check mt-1">
                                                <input {{ $usaha->status_perusahaan_id == '1' ? 'checked' : '' }}
                                                    name="kondisi_usaha" class="form-check-input" type="radio" value="1"
                                                    id="kondisi_aktif">
                                                <label class="form-check-label" for="kondisi_aktif">Aktif</label>
                                            </div>
                                            @if($action_type != 'CREATE')
                                            <div class="form-check mt-1">
                                                <input {{ $usaha->status_perusahaan_id == '2' ? 'checked' : '' }}
                                                    name="kondisi_usaha" class="form-check-input" type="radio" value="2"
                                                    id="kondisi_tutup_sementara">
                                                <label class="form-check-label" for="kondisi_tutup_sementara">Tutup
                                                    Sementara</label>
                                            </div>
                                            <div class="form-check mt-1">
                                                <input {{ $usaha->status_perusahaan_id == '3' ? 'checked' : '' }}
                                                    name="kondisi_usaha" class="form-check-input" type="radio" value="3"
                                                    id="kondisi_belum_operasi">
                                                <label class="form-check-label" for="kondisi_belum_operasi">Belum
                                                    Beroperasi/Berproduksi</label>
                                            </div>
                                            <div class="form-check mt-1">
                                                <input {{ $usaha->status_perusahaan_id == '4' ? 'checked' : '' }}
                                                    name="kondisi_usaha" class="form-check-input" type="radio" value="4"
                                                    id="kondisi_tutup">
                                                <label class="form-check-label" for="kondisi_tutup">Tutup</label>
                                            </div>
                                            <div class="form-check mt-1">
                                                <input {{ $usaha->status_perusahaan_id == '5' ? 'checked' : '' }}
                                                    name="kondisi_usaha" class="form-check-input" type="radio" value="5"
                                                    id="kondisi_alih_usaha">
                                                <label class="form-check-label" for="kondisi_alih_usaha">Alih
                                                    Usaha</label>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            @if($action_type != 'CREATE')
                                            <div class="form-check mt-1">
                                                <input {{ $usaha->status_perusahaan_id == '6' ? 'checked' : '' }}
                                                    name="kondisi_usaha" class="form-check-input" type="radio" value="6"
                                                    id="kondisi_tidak_ditemukan">
                                                <label class="form-check-label" for="kondisi_tidak_ditemukan">Tidak
                                                    Ditemukan</label>
                                            </div>
                                            <div class="form-check mt-1">
                                                <input {{ $usaha->status_perusahaan_id == '7' ? 'checked' : '' }}
                                                    name="kondisi_usaha" class="form-check-input" type="radio" value="7"
                                                    id="kondisi_aktif_pindah">
                                                <label class="form-check-label" for="kondisi_aktif_pindah">Aktif
                                                    Pindah</label>
                                            </div>
                                            <div class="form-check mt-1">
                                                <input {{ $usaha->status_perusahaan_id == '8' ? 'checked' : '' }}
                                                    name="kondisi_usaha" class="form-check-input" type="radio" value="8"
                                                    id="kondisi_aktif_nonrespon">
                                                <label class="form-check-label" for="kondisi_aktif_nonrespon">Aktif
                                                    Nonrespon</label>
                                            </div>
                                            <div class="form-check mt-1">
                                                <input {{ $usaha->status_perusahaan_id == '9' ? 'checked' : '' }}
                                                    name="kondisi_usaha" class="form-check-input" type="radio" value="9"
                                                    id="kondisi_duplikat">
                                                <label class="form-check-label" for="kondisi_duplikat">Duplikat</label>
                                            </div>
                                            <div class="form-check mt-1">
                                                <input {{ $usaha->status_perusahaan_id == '11' ? 'checked' : '' }}
                                                    name="kondisi_usaha" class="form-check-input" type="radio"
                                                    value="11" id="kondisi_salah_kode_wilayah">
                                                <label class="form-check-label" for="kondisi_salah_kode_wilayah">Salah
                                                    Kode Wilayah</label>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-6" id="container-idsbr-duplikat"
                                    style="{{ $usaha->status_perusahaan_id != '9' ? 'display: none' : '' }}">
                                    <label class="form-label" for="idsbr_master">Masukan IDSBR Master <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input value="{{ $usaha->idsbr_master }}" type="number" class="form-control"
                                            placeholder="IDSBR Master" aria-label="IDSBR Master"
                                            aria-describedby="button-check-idsbr" id="idsbr_master">
                                        <button class="btn btn-outline-primary waves-effect" type="button"
                                            id="button-check-idsbr">Check</button>
                                    </div>
                                    <input type="text" class="form-control hidden-input-only-duplikat"
                                        style="display: none">
                                    <div class="invalid-feedback">Belum ada idsbr master yang terkonfimasi!</div>
                                    <div class="alert alert-primary mt-1 alert-validation-msg" role="alert">
                                        <div class="alert-body d-flex align-items-center">
                                            <i data-feather="info" class="me-50"></i>
                                            <span>Klik <strong>CHECK</strong> untuk mengkonfirmasi!</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="card shadow-none bg-transparent border-primary">
                                            <div class="card-body">
                                                <h6 class="card-title"><span
                                                        class="badge rounded-pill badge-light-primary">Konfirmasi</span>
                                                </h6>
                                                <p class="card-text">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <span class="fw-bolder me-25">IDSBR:</span> <span
                                                                id="idsbr-confirm">{{ $idsbrMaster->kode ?? '-'}}</span>
                                                        </li>
                                                        <li><span class="fw-bolder me-25">Nama:</span> <span
                                                                id="nama-confirm">{{ $idsbrMaster->nama ?? '-' }}</span>
                                                        </li>
                                                        <li><span class="fw-bolder me-25">Alamat:</span> <span
                                                                id="alamat-confirm">{{ $idsbrMaster->alamat ?? '-' }}</span>
                                                        </li>
                                                    </ul>
                                                </p>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    id="cancel-accept-idsbr">
                                                    <i data-feather="x" class="me-25"></i>
                                                    <span>Cancel</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-6" id="container-aktif-pindah"
                                    style="{{ $usaha->status_perusahaan_id != '7' ? 'display: none' : '' }}">
                                    <label class="form-label">Pilih Lokasi Pindah <span
                                            class="text-danger">*</span></label>
                                    <small class="text-muted"><i>Pindah Ke Kabupaten/Kota Yang Berbeda</i></small>
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-sm-6">
                                            <select id="provinsi_pindah" class="select2 form-select">
                                                <option value="">-- Pilih Provinsi --</option>
                                                @foreach($masterProvinsiAll as $option)
                                                <option value="{{ $option->id }}"
                                                    {{ $usaha->provinsi_pindah == $option->id ? 'selected' : '' }}>
                                                    {{ $option->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 col-sm-6" id="container-pindah-kabupaten">
                                            <select id="kabupaten_kota_pindah" class="select2 form-select">
                                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                                @foreach($masterKabupatenAll as $option)
                                                <option value="{{ $option->id }}"
                                                    {{ $usaha->kabupaten_kota_pindah == $option->id ? 'selected' : '' }}>
                                                    {{ $option->nama }}</option>
                                                @endforeach
                                            </select>
                                            <div>
                                                <input type="text" class="form-control hidden-input-only-pindah-kab"
                                                    style="display: none">
                                                <div class="invalid-feedback">Kab/Kota terpilih tidak boleh sama dengan
                                                    Kab/Kota
                                                    asal.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            WILAYAH USAHA/PERUSAHAAN
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-6">
                            <label class="form-label" for="provinsi">Provinsi <span class="text-danger">*</span></label>
                            <select {{$action_type == 'CREATE' ? 'disabled' : ''}} id="provinsi"
                                class="select2 form-select">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($masterProvinsi as $option)
                                <option value="{{ $option->id }}"
                                    {{ $option->id == $usaha->provinsi_id ? 'selected' : '' }}>[{{ $option->kode }}]
                                    {{ $option->nama }}
                                </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"><span id="provinsi_error"></span></div>
                        </div>
                        <div class="mb-6" id="container-kabupaten-kota">
                            <label class="form-label" for="kabupaten_kota">Kabupaten/Kota <span
                                    class="text-danger">*</span></label>
                            <select {{$action_type == 'CREATE' ? 'disabled' : ''}} id="kabupaten_kota"
                                class="select2 form-select">
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                @foreach($masterKabKot as $option)
                                <option value="{{ $option->id }}"
                                    {{ $option->id == $usaha->kabupaten_kota_id ? 'selected' : '' }}>
                                    [{{ $option->kode }}] {{ $option->nama }}
                                </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"><span id="kabupaten_kota_error"></span></div>
                        </div>
                        <div class="mb-6" id="container-kecamatan">
                            <label class="form-label" for="kecamatan">Kecamatan</label>
                            <select id="kecamatan" class="select2 form-select">
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach($masterKecamatan as $option)
                                <option value="{{ $option->id }}"
                                    {{ $option->id == $usaha->kecamatan_id ? 'selected' : '' }}>[{{ $option->kode }}]
                                    {{ $option->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-6" id="container-kelurahan-desa">
                            <label class="form-label" for="kelurahan_desa">Kelurahan/Desa</label>
                            <select id="kelurahan_desa" class="select2 form-select">
                                <option value="">-- Pilih Kelurahan/Desa --</option>
                                @foreach($masterDesa as $option)
                                <option value="{{ $option->id }}"
                                    {{ $option->id == $usaha->kelurahan_desa_id ? 'selected' : '' }}>
                                    [{{ $option->kode }}] {{ $option->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            KARAKTERISTIK USAHA/PERUSAHAAN
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-6">
                            <label class="form-label" for="jenis_kepemilikan_usaha">Jenis Kepemilikan Usaha</label>
                            <select id="jenis_kepemilikan_usaha" class="select2 form-select">
                                <option value="">-- Pilih Jenis Kepemilikan Usaha --</option>
                                <option value="BUMN" {{ $usaha->jenis_kepemilikan_usaha == 'BUMN' ? 'selected' : '' }}>
                                    1. BUMN</option>
                                <option value="Non BUMN"
                                    {{ $usaha->jenis_kepemilikan_usaha == 'Non BUMN' ? 'selected' : '' }}>2. Non BUMN
                                </option>
                                <option value="BUMD" {{ $usaha->jenis_kepemilikan_usaha == 'BUMD' ? 'selected' : '' }}>
                                    3. BUMD</option>
                                <option value="BUMDes"
                                    {{ $usaha->jenis_kepemilikan_usaha == 'BUMDes' ? 'selected' : '' }}>4. BUMDes
                                </option>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="form-label" for="badan_usaha">Bentuk Badan Hukum/Usaha</label>
                            <select id="badan_usaha" class="select2 form-select">
                                <option value="">-- Pilih Badan Hukum/Usaha --</option>
                                <option value="1" {{ $usaha->bentuk_badan_usaha_id == '1' ? 'selected' : '' }}>1.
                                    Perseroan (PT/NV, PT Persero, PT Tbk, PT Persero Tbk, Perseroan Daerah, Perseroan
                                    Perseorangan)</option>
                                <option value="2" {{ $usaha->bentuk_badan_usaha_id == '2' ? 'selected' : '' }}>2.
                                    Yayasan</option>
                                <option value="3" {{ $usaha->bentuk_badan_usaha_id == '3' ? 'selected' : '' }}>3.
                                    Koperasi</option>
                                <option value="4" {{ $usaha->bentuk_badan_usaha_id == '4' ? 'selected' : '' }}>4. Dana
                                    Pensiun</option>
                                <option value="5" {{ $usaha->bentuk_badan_usaha_id == '5' ? 'selected' : '' }}>5.
                                    Perum/Perumda</option>
                                <option value="6" {{ $usaha->bentuk_badan_usaha_id == '6' ? 'selected' : '' }}>6. BUM
                                    Desa</option>
                                <option value="7" {{ $usaha->bentuk_badan_usaha_id == '7' ? 'selected' : '' }}>7.
                                    Persekutuan Komanditer (CV)</option>
                                <option value="8" {{ $usaha->bentuk_badan_usaha_id == '8' ? 'selected' : '' }}>8.
                                    Persekutuan Firma</option>
                                <option value="9" {{ $usaha->bentuk_badan_usaha_id == '9' ? 'selected' : '' }}>9.
                                    Persekutuan Perdata (Maatschap)</option>
                                <option value="10" {{ $usaha->bentuk_badan_usaha_id == '10' ? 'selected' : '' }}>10.
                                    Kantor Perwakilan Luar Negeri</option>
                                <option value="11" {{ $usaha->bentuk_badan_usaha_id == '11' ? 'selected' : '' }}>11.
                                    Badan Usaha Luar Negeri</option>
                                <option value="12" {{ $usaha->bentuk_badan_usaha_id == '12' ? 'selected' : '' }}>12.
                                    Usaha Orang Perseorangan</option>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="form-label" for="tahun_berdiri">Tahun Berdiri</label>
                            <input value="{{ $usaha->tahun_berdiri }}" type="text" class="form-control"
                                id="tahun_berdiri" placeholder="Tahun Berdiri Usaha/Perusahaan" name="tahun_berdiri"
                                aria-label="Tahun Berdiri Usaha/Perusahaan">
                            <div class="invalid-feedback"><span id="tahun_berdiri_error"></span></div>
                        </div>
                        <div class="mb-6">
                            <label class="form-label">Jaringan Usaha</label>
                            <div class="row">
                                <div class="col-12 col-md-3 col-sm-4">
                                    <div class="form-check mt-1">
                                        <input {{ $usaha->jaringan_usaha_id == '1' ? 'checked' : '' }}
                                            name="jaringan_usaha" class="form-check-input" type="radio" value="1"
                                            id="jaringan_tunggal">
                                        <label class="form-check-label" for="jaringan_tunggal">Tunggal</label>
                                    </div>
                                    <div class="form-check mt-1">
                                        <input {{ $usaha->jaringan_usaha_id == '2' ? 'checked' : '' }}
                                            name="jaringan_usaha" class="form-check-input" type="radio" value="2"
                                            id="jaringan_kantor_pusat">
                                        <label class="form-check-label" for="jaringan_kantor_pusat">Kantor Pusat</label>
                                    </div>
                                    <div class="form-check mt-1">
                                        <input {{ $usaha->jaringan_usaha_id == '3' ? 'checked' : '' }}
                                            name="jaringan_usaha" class="form-check-input" type="radio" value="3"
                                            id="jaringan_kantor_cabang">
                                        <label class="form-check-label" for="jaringan_kantor_cabang">Kantor
                                            Cabang</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 col-sm-4">
                                    <div class="form-check mt-1">
                                        <input {{ $usaha->jaringan_usaha_id == '4' ? 'checked' : '' }}
                                            name="jaringan_usaha" class="form-check-input" type="radio" value="4"
                                            id="jaringan_perwakilan">
                                        <label class="form-check-label" for="jaringan_perwakilan">Perwakilan</label>
                                    </div>
                                    <div class="form-check mt-1">
                                        <input {{ $usaha->jaringan_usaha_id == '5' ? 'checked' : '' }}
                                            name="jaringan_usaha" class="form-check-input" type="radio" value="5"
                                            id="jaringan_pabrik">
                                        <label class="form-check-label" for="jaringan_pabrik">Pabrik/Unit
                                            Kegiatan</label>
                                    </div>
                                    <div class="form-check mt-1">
                                        <input {{ $usaha->jaringan_usaha_id == '6' ? 'checked' : '' }}
                                            name="jaringan_usaha" class="form-check-input" type="radio" value="6"
                                            id="jaringan_unit_pembantu">
                                        <label class="form-check-label" for="jaringan_unit_pembantu">Unit
                                            Pembantu/Penunjang</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="card">
    <div class="card-header">

        <h4 class="card-title">Kegiatan Usaha</h4>

    </div>
    <div class="card-body">
        <div class="row">
            <div class="mb-6 col-12">
                <input type="text" class="form-control hidden-kegiatan-usaha" style="display: none">
                <div class="invalid-feedback">Minimal 1 kegiatan usaha dan kategori harus terisi</div>
                <form action="#" class="invoice-repeater">
                    <div data-repeater-list="k_usaha">
                        @if(count($kegiatan_usaha) > 0)
                            @foreach($kegiatan_usaha as $kegiatan)
                            <div data-repeater-item>
                                <div class="row d-flex align-items-end">
                                    <div class="col-md-4 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">Kegiatan Usaha</label>
                                            <input name="l_kegiatan_usaha" type="text" class="form-control uppercase-text l_kegiatan_usaha" placeholder="Kegiatan Usaha"
                                            aria-label="Kegiatan Usaha/Perusahaan" value="{{ $kegiatan->kegiatan_usaha ?? '' }}" />
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                            <select name="l_kategori_usaha" class="select2 form-select l_kategori_usaha">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach($masterKategori as $kategori)
                                                <option value="{{ $kategori->Kode }}"
                                                    {{ $kategori->Kode == ($kegiatan->kategori ?? '') ? 'selected' : '' }}>{{ $kategori->Kode }} -
                                                    {{ $kategori->Judul }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="mb-1 container-lkbli">
                                            <label class="form-label">KBLI</label>
                                            <select name="l_kbli" class="select2 form-select l_kbli">
                                                <option value="">-- Pilih KBLI --</option>                                                
                                                @foreach($kegiatan->m_kbli as $kbli)
                                                <option value="{{ $kbli->Kode }}" {{ $kbli->Kode == ($kegiatan->kbli ?? '') ? 'selected' : '' }}>
                                                    {{ $kbli->Kode }} - {{ $kbli->Judul }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" >Produk Utama</label>
                                            <input type="text"
                                                class="form-control uppercase-text l_produk_utama"
                                                placeholder="Produk Usaha/Perusahaan" name="l_produk_utama"
                                                aria-label="Produk Usaha/Perusahaan" value="{{ $kegiatan->produk_usaha ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12 mb-50">
                                        <div class="mb-1">
                                            <button class="btn btn-outline-danger text-nowrap px-1" data-repeater-delete type="button">
                                                <i data-feather="x" class="me-25"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                        <div data-repeater-item>
                            <div class="row d-flex align-items-end">
                                <div class="col-md-4 col-12">
                                    <div class="mb-1">
                                        <label class="form-label">Kegiatan Usaha</label>
                                        <input name="l_kegiatan_usaha" type="text" class="form-control uppercase-text l_kegiatan_usaha" placeholder="Kegiatan Usaha"
                                        aria-label="Kegiatan Usaha/Perusahaan" />
                                    </div>
                                </div>

                                <div class="col-md-2 col-12">
                                    <div class="mb-1">
                                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select name="l_kategori_usaha" class="select2 form-select l_kategori_usaha">
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($masterKategori as $kategori)
                                            <option value="{{ $kategori->Kode }}">{{ $kategori->Kode }} -
                                                {{ $kategori->Judul }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 col-12">
                                    <div class="mb-1 container-lkbli">
                                        <label class="form-label">KBLI</label>
                                        <select name="l_kbli" class="select2 form-select l_kbli">
                                            <option value="">-- Pilih KBLI --</option>                                            
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" >Produk</label>
                                        <input type="text"
                                            class="form-control uppercase-text l_produk_utama"
                                            placeholder="Produk Usaha/Perusahaan" name="l_produk_utama"
                                            aria-label="Produk Usaha/Perusahaan">
                                    </div>
                                </div>

                                <div class="col-md-2 col-12 mb-50">
                                    <div class="mb-1">
                                        <button class="btn btn-outline-danger text-nowrap px-1" data-repeater-delete
                                            type="button">
                                            <i data-feather="x" class="me-25"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr />
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                <i data-feather="plus" class="me-25"></i>
                                <span>Add New</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Lain-Lain</h4>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="mb-6 col-12">
                <label class="form-label" for="sumber_profiling">Sumber Profiling <span
                        class="text-danger">*</span></label>
                <input value="{{ $usaha->sumber_profiling }}" type="text" class="form-control" id="sumber_profiling"
                    placeholder="Sumber Profiling" name="sumber_profiling" aria-label="Sumber Profiling">
                <div class="invalid-feedback"><span id="sumber_profiling_error"></span></div>
            </div>
            <div class="col-12">
                <div class="mb-1">
                    <label class="form-label" for="catatan_profiling">Catatan Profiling <span
                            class="text-danger">*</span></label>
                    <textarea class="form-control" id="catatan_profiling" rows="3"
                        placeholder="Catatan">{{ $usaha->keterangan_submitted }}</textarea>
                    <div class="invalid-feedback"><span id="catatan_profiling_error"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade text-start" id="backdrop" tabindex="-1" aria-labelledby="myModalLabel4" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4"></h4>
                <button type="button" class="btn-close" id="close-idsbr" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h1 class="text-center">KONFIRMASI</h1>
                <p class="text-center">Apakah sudah sesuai?</p>
                <div id="container-check-idsbr-modal"></div>
                <div class="alert alert-danger mt-1 alert-validation-msg" role="alert">
                    <div class="alert-body d-flex align-items-center">
                        <i data-feather="info" class="me-50"></i>
                        <span>Klik <strong>Accept</strong> untuk mengkonfirmasi!</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="cancel-idsbr"
                    data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="accept-idsbr" data-bs-dismiss="modal">Accept</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-start" id="consistency-check-modal" tabindex="-1" aria-labelledby="modal consistency check"
    data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        </div>
    </div>
</div>
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js'))}}"></script>
<script src="{{ asset(mix('vendors/js/maps/leaflet.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/repeater/jquery.repeater.min.js')) }}"></script>
<script>
    $(document).ready(function () {
        $(".select2").select2();

        let pid = '{{ $initPerusahaanId }}';
        let aid = '{{ $initAlokasiId }}';


        let desaCache = [
            // {kecamatan: 100,desa: [{id: 1,text: 'Desa 1'},{id: 2,text: 'Desa 2'}]}            
        ]

        let kecamatanCache = [
            // {kabupaten_kota: 100,kecamatan: [{id: 1,text: 'Desa 1'},{id: 2,text: 'Desa 2'}]}
        ]

        let kabupatenCache = [
            // {provinsi: 100,kabupaten_kota: [{id: 1,text: 'Desa 1'},{id: 2,text: 'Desa 2'}]}
        ]

        let kbliCache = []
        // {kategori: 'A',kbli: [{id:'01111',text: '01111 - Some Description'}]}


        let idsbrAccept = {
            'idsbr': "{{ $idsbrMaster->kode ?? '' }}",
            'nama': "{{ $idsbrMaster->nama ?? '' }}",
            'alamat': "{{ $idsbrMaster->alamat ?? '' }}"
        }
        let idsbrTemporary = {
            'idsbr': '-',
            'nama': '-',
            'alamat': '-'
        }
        let closeTrigger = '';

        let kabupatenCacheAll = [];

        let outputForm = {};
        let listError = {};
        let listConsistencyCheck = {};

        $("#provinsi").on('change', function () {
            blockProgress($("#container-kabupaten-kota"));
            blockProgress($("#container-kecamatan"));
            blockProgress($("#container-kelurahan-desa"));
            let provinsi = $(this).val();

            if (!provinsi) {
                setOption($('#kabupaten_kota'), [{
                    id: '',
                    text: '-- Pilih Kabupaten/Kota --'
                }]);
                setOption($('#kecamatan'), [{
                    id: '',
                    text: '-- Pilih Kecamatan --'
                }]);
                setOption($("#kelurahan_desa"), [{
                    id: '',
                    text: '-- Pilih Kelurahan/Desa --'
                }]);
                unblockProgress($("#container-kabupaten-kota"));
                unblockProgress($("#container-kecamatan"));
                unblockProgress($("#container-kelurahan-desa"));
                return;
            }

            let findCache = kabupatenCache.find(kc => kc.provinsi == provinsi);
            if (findCache) {
                setOption($('#kabupaten_kota'), findCache.kabupaten_kota);
                setOption($('#kecamatan'), [{
                    id: '',
                    text: '-- Pilih Kecamatan --'
                }]);
                setOption($("#kelurahan_desa"), [{
                    id: '',
                    text: '-- Pilih Kelurahan/Desa --'
                }]);
                unblockProgress($("#container-kabupaten-kota"));
                unblockProgress($("#container-kecamatan"));
                unblockProgress($("#container-kelurahan-desa"));
                return;
            }
            $.ajax({
                url: '{{ route("wil-kabupaten-kota") }}',
                type: 'POST',
                data: {
                    'provinsi': provinsi,
                    '_token': '{{ csrf_token() }}',
                    'level': 'user'
                },
                success: function (response) {
                    let wilKab = [{
                        id: '',
                        text: '-- Pilih Kabupaten/Kota --'
                    }]
                    for (let i = 0; i < response.length; i++) {
                        wilKab.push({
                            id: response[i].id,
                            text: '[' + response[i].kode + ']' + ' ' + response[i]
                                .nama
                        })
                    }

                    kabupatenCache.push({
                        provinsi: provinsi,
                        kabupaten_kota: wilKab
                    })

                    setOption($('#kabupaten_kota'), wilKab);
                    setOption($('#kecamatan'), [{
                        id: '',
                        text: '-- Pilih Kecamatan --'
                    }]);
                    setOption($("#kelurahan_desa"), [{
                        id: '',
                        text: '-- Pilih Kelurahan/Desa --'
                    }]);
                    unblockProgress($("#container-kabupaten-kota"));
                    unblockProgress($("#container-kecamatan"));
                    unblockProgress($("#container-kelurahan-desa"));
                }
            })
        });

        $("#kabupaten_kota").on('change', function () {
            blockProgress($("#container-kecamatan"));
            blockProgress($("#container-kelurahan-desa"));
            let kabupaten_kota = $(this).val();

            if (!kabupaten_kota) {
                setOption($('#kecamatan'), [{
                    id: '',
                    text: '-- Pilih Kecamatan --'
                }]);
                setOption($("#kelurahan_desa"), [{
                    id: '',
                    text: '-- Pilih Kelurahan/Desa --'
                }]);
                unblockProgress($("#container-kecamatan"));
                unblockProgress($("#container-kelurahan-desa"));
                return;
            }

            let findCache = kecamatanCache.find(kc => kc.kabupaten_kota == kabupaten_kota);
            if (findCache) {
                setOption($('#kecamatan'), findCache.kecamatan);
                setOption($("#kelurahan_desa"), [{
                    id: '',
                    text: '-- Pilih Kelurahan/Desa --'
                }]);
                unblockProgress($("#container-kecamatan"));
                unblockProgress($("#container-kelurahan-desa"));
                return;
            }
            $.ajax({
                url: '{{ route("wil-kecamatan") }}',
                type: 'POST',
                data: {
                    'kabupaten_kota': kabupaten_kota,
                    '_token': '{{ csrf_token() }}'
                },
                success: function (response) {
                    let wilKecamatan = [{
                        id: '',
                        text: '-- Pilih Kecamatan --'
                    }]
                    for (let i = 0; i < response.length; i++) {
                        wilKecamatan.push({
                            id: response[i].id,
                            text: '[' + response[i].kode + ']' + ' ' + response[i]
                                .nama
                        })
                    }

                    kecamatanCache.push({
                        kabupaten_kota: kabupaten_kota,
                        kecamatan: wilKecamatan
                    });

                    setOption($("#kecamatan"), wilKecamatan);
                    setOption($("#kelurahan_desa"), [{
                        id: '',
                        text: '-- Pilih Kelurahan/Desa --'
                    }]);
                    unblockProgress($("#container-kecamatan"));
                    unblockProgress($("#container-kelurahan-desa"));
                }
            })
        });



        $("#kecamatan").on('change', function () {
            blockProgress($("#container-kelurahan-desa"));
            let kecamatan = $(this).val();

            if (!kecamatan) {
                setOption($("#kelurahan_desa"), [{
                    id: '',
                    text: '-- Pilih Kelurahan/Desa --'
                }]);
                unblockProgress($("#container-kelurahan-desa"));
                return;
            }

            let findCache = desaCache.find(dc => dc.kecamatan == kecamatan)
            if (findCache) {
                setOption($("#kelurahan_desa"), findCache.desa);
                unblockProgress($("#container-kelurahan-desa"));
                return;
            }
            $.ajax({
                url: '{{ route("wil-desa") }}',
                type: 'POST',
                data: {
                    'kecamatan': kecamatan,
                    '_token': '{{ csrf_token() }}'
                },
                success: function (response) {
                    let wilDesa = [{
                        id: '',
                        text: '-- Pilih Kelurahan/Desa --'
                    }]
                    for (let i = 0; i < response.length; i++) {
                        wilDesa.push({
                            id: response[i].id,
                            text: '[' + response[i].kode + ']' + ' ' + response[i]
                                .nama
                        })
                    }
                    // insert into cache 
                    desaCache.push({
                        kecamatan: kecamatan,
                        desa: wilDesa
                    });
                    // set option
                    setOption($("#kelurahan_desa"), wilDesa);
                    unblockProgress($("#container-kelurahan-desa"));
                }
            })
        });

        $(document).on('change', '.l_kategori_usaha', function() {
            updateKBLIOptions(this);
        });

        function updateKBLIOptions(select) {            
            let kategori = $(select).val();            
            let $kbliSelect = $(select).closest('.row').find('.l_kbli');            
            let $containerKbli = $(select).closest('.row').find('.container-lkbli');
            if(kategori == '') {
                setOption($kbliSelect, [{
                    id: '',
                    text: '-- Pilih KBLI --'
                }]);
                return;
            }

            let findCache = kbliCache.find(dc => dc.kategori == kategori)
            if (findCache) {
                setOption($kbliSelect, findCache.kbli);
                return;
            }
            
            blockProgress($containerKbli);
            $.ajax({
                url: '{{ route("master-kbli") }}',
                type: 'POST',
                data: {
                    'kategori': kategori,
                    '_token': '{{ csrf_token() }}'
                },
                success: function (response) {
                    let kbli = [{
                        id: '',
                        text: '-- Pilih KBLI --'
                    }]
                    for (let i = 0; i < response.length; i++) {
                        kbli.push({
                            id: response[i].Kode,
                            text: '[' + response[i].Kode + ']' + ' ' + response[i].Judul
                        })
                    }
                    kbliCache.push({
                        kategori: kategori,
                        kbli: kbli
                    });
                    setOption($kbliSelect, kbli);
                    unblockProgress($containerKbli);
                },
                error: function() {
                    Swal.fire({
                        title: 'Pemberitahuan',
                        text: 'Gagal mengambil data KBLI!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                    unblockProgress($containerKbli);
                }
            });
            
        }

        $("#kategori").on('change', function () {
            blockProgress($("#container-kbli"));
            let kategori = $(this).val();

            if (kategori == '') {
                setOption($("#kbli"), [{
                    id: '',
                    text: '-- Pilih KBLI --'
                }]);
                unblockProgress($("#container-kbli"));
                return;
            }

            let findCache = kbliCache.find(dc => dc.kategori == kategori)
            if (findCache) {
                setOption($("#kbli"), findCache.kbli);
                unblockProgress($("#container-kbli"));
                return;
            }

            $.ajax({
                url: '{{ route("master-kbli") }}',
                type: 'POST',
                data: {
                    'kategori': kategori,
                    '_token': '{{ csrf_token() }}'
                },
                success: function (response) {
                    let kbli = [{
                        id: '',
                        text: '-- Pilih KBLI --'
                    }]
                    for (let i = 0; i < response.length; i++) {
                        kbli.push({
                            id: response[i].Kode,
                            text: '[' + response[i].Kode + ']' + ' ' + response[i]
                                .Judul
                        })
                    }
                    // insert into cache 
                    kbliCache.push({
                        kategori: kategori,
                        kbli: kbli
                    });
                    // set option
                    setOption($("#kbli"), kbli);
                    unblockProgress($("#container-kbli"));
                }
            })
        })

        $("input[name='kondisi_usaha']").on('change', function () {
            let kondisi = $(this).val();
            if (kondisi == '7' || kondisi == '11') {
                // kalau aktif pindah: tampilkan form pilih kabkot terbaru
                $("#container-aktif-pindah").show();
                $("#container-idsbr-duplikat").hide();
                return;
            }

            if (kondisi == '9') {
                // kalau duplikat: tampilkan input untuk memasukan idsbr masternya
                $("#container-idsbr-duplikat").show();
                $("#container-aktif-pindah").hide();
                return;
            }

            $("#container-idsbr-duplikat").hide();
            $("#container-aktif-pindah").hide();

        })

        $("#button-check-idsbr").on('click', function () {
            let idsbr_master = $("#idsbr_master").val().trim();
            if (idsbr_master == '') {
                Swal.fire({
                    title: 'Pemberitahuan',
                    text: 'IDSBR Tidak Boleh Kosong!',
                    icon: 'warning',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
                return;
            }

            if (!isNumeric(idsbr_master)) {
                Swal.fire({
                    title: 'Pemberitahuan',
                    text: 'IDSBR Tidak Valid!',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
                return;
            }

            // check idsbr
            blockProgress('body');
            $.ajax({
                url: '{{ route("check-idsbr") }}',
                type: 'POST',
                data: {
                    'idsbr': idsbr_master,
                    '_token': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (!response) {
                        Swal.fire({
                            title: 'Pemberitahuan',
                            text: 'IDSBR Tidak Ditemukan!',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        unblockProgress('body');
                        return;
                    }
                    // kalau ditemukan , tampilkan:    
                    idsbrTemporary = {
                        idsbr: response.kode,
                        nama: response.nama,
                        alamat: response.alamat
                    };

                    $("#container-check-idsbr-modal").html(`                        
                        <p>
                            <strong>IDSBR</strong>: ${idsbrTemporary['idsbr']} <br/>
                            <strong>NAMA</strong>: ${idsbrTemporary['nama']} <br/>
                            <strong>ALAMAT</strong>: ${idsbrTemporary['alamat']}
                        </p>
                    `)
                    $("#backdrop").modal('show');
                    unblockProgress('body');
                }
            })
        })

        $("#accept-idsbr").on('click', function () {
            closeTrigger = 'accept';
            idsbrAccept = {
                ...idsbrTemporary
            };
            idsbrTemporary = {};
        });

        $("#cancel-idsbr, #close-idsbr").on('click', function () {
            closeTrigger = 'cancel';
            idsbrTemporary = {};
        });

        $("#backdrop").on('hidden.bs.modal', function () {
            if (closeTrigger == 'accept') {
                $("#idsbr-confirm").html(idsbrAccept.idsbr);
                $("#nama-confirm").html(idsbrAccept.nama);
                $("#alamat-confirm").html(idsbrAccept.alamat)
            }
        });

        $("#cancel-accept-idsbr").on('click', function () {
            idsbrAccept = {};
            idsbrTemporary = {};
            $("#idsbr-confirm").html('-');
            $("#nama-confirm").html('-');
            $("#alamat-confirm").html('-');
        });

        $("#provinsi_pindah").on('change', function () {
            blockProgress($("#container-pindah-kabupaten"));
            let provinsi = $(this).val();

            if (!provinsi) {
                setOption($('#kabupaten_kota_pindah'), [{
                    id: '',
                    text: '-- Pilih Kabupaten/Kota --'
                }]);
                unblockProgress($("#container-pindah-kabupaten"));
                return;
            }

            let findCache = kabupatenCacheAll.find(kc => kc.provinsi == provinsi);
            if (findCache) {
                setOption($('#kabupaten_kota_pindah'), findCache.kabupaten_kota);
                unblockProgress($("#container-pindah-kabupaten"));
                return;
            }
            $.ajax({
                url: '{{ route("wil-kabupaten-kota") }}',
                type: 'POST',
                data: {
                    'provinsi': provinsi,
                    '_token': '{{ csrf_token() }}',
                    'level': 'all'
                },
                success: function (response) {
                    let wilKab = [{
                        id: '',
                        text: '-- Pilih Kabupaten/Kota --'
                    }]
                    for (let i = 0; i < response.length; i++) {
                        wilKab.push({
                            id: response[i].id,
                            text: '[' + response[i].kode + ']' + ' ' + response[i]
                                .nama
                        })
                    }

                    kabupatenCacheAll.push({
                        provinsi: provinsi,
                        kabupaten_kota: wilKab
                    })

                    setOption($('#kabupaten_kota_pindah'), wilKab);
                    unblockProgress($("#container-pindah-kabupaten"));
                }
            })
        });

        var cleaveWA = new Cleave("#whatsapp", {
            prefix: '+62',
            delimiter: '-',
            blocks: [3, 3, 4, 4],
            uppercase: true,
            numericOnly: true,
        });
        cleaveWA.setRawValue("{{ $usaha->no_wa ?? '' }}");


        new Cleave($("#tahun_berdiri"), {
            blocks: [4],
            numericOnly: true
        });

        new Cleave($("#kodepos"), {
            blocks: [5],
            numericOnly: true
        });

        $("#cek-peta").on('click', function () {
            let latitude = $("#latitude").val();
            let longitude = $("#longitude").val();

            if (!isNaN(latitude) && !isNaN(longitude) && latitude && longitude) {
                blockProgress($(".container-map"));
                showMap(latitude, longitude);
                return;
            }

            Swal.fire({
                title: 'Pemberitahuan',
                text: 'Latitude dan Longitude harus terisi dengan format yang benar untuk menampilkan Map',
                icon: 'warning',
                customClass: {
                    confirmButton: 'btn btn-outline-warning'
                },
                buttonsStyling: false
            });

        });

        $("#check-email").on('change', function () {
            let isChecked = $(this).is(':checked');
            if (!isChecked) {
                $("#email").attr('disabled', true);
                return;
            }
            $("#email").attr('disabled', false);
            $("#email").focus()
        })

        $("#save-draft").on('click', function () {
            initializeFormOutput();            
            validateForm();
            showErrorMessages();                        

            // save draft
            sendData('DRAFT');
        });

        function showErrorMessages() {
            let keyErrors = Object.keys(listError);
            if (keyErrors.length) {
                for (let key in listError) {
                    $("#" + key + "_error").html(listError[key]);
                }
            }
        }

        $("#submit-final").on('click', function () {
            initializeFormOutput();
            validateForm();
            showErrorMessages();
            let numError = calculateError();            
            if (numError) return;

            let numConsistency = consistencyCheck();
            if (numConsistency) {
                showConsistencyCheck();
                return;
            }

            confirmSubmitData();
        });

        // form repeater jquery
        $('.invoice-repeater, .repeater-default').repeater({            
            show: function () {                
                // Reinitialize Select2 for all select2 elements inside the repeater
                $('.select2-container').remove();
                $('.select2').select2();
                $(this).slideDown();

                $(this).find('select[name="l_kategori_usaha"]').on('change', function() {
                        updateKBLIOptions(this);
                    });
                // Feather Icons
                if (feather) {
                    feather.replace({ width: 14, height: 14 });
                }
            },
            hide: function (deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            }
        });

        function confirmSubmitData() {
            Swal.fire({
                title: 'Konfirmasi!',
                text: 'Setelah data disubmit, anda tidak dapat mengedit data ini!',
                icon: 'info',
                customClass: {
                    cancelButton: 'btn btn-outline-secondary',
                    confirmButton: 'btn btn-primary me-1',
                },
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Ya, Submit!',
                cancelButtonText: 'Cancel'
            }).then(function (result) {
                if (result.value) {
                    sendData('SUBMITTED');
                }
            })
        }

        function showConsistencyCheck() {
            let keyConsistencyCheck = Object.keys(listConsistencyCheck);
            if (keyConsistencyCheck.length) {
                // show modal with listConsistencyCheck
                // Create modal content
                let modalContent = '<div class="modal-header">' +
                    '<h5 class="modal-title">Cek Konsistensi</h5>' +
                    '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                    '</div>' +
                    '<div class="modal-body">' +
                    '<ul class="list-group list-group-numbered">';

                // Add each consistency check item to the modal
                for (let key in listConsistencyCheck) {
                    modalContent += '<li class="list-group-item">' + listConsistencyCheck[key] + '</li>';
                }

                modalContent += '</ul></div>' +
                    '<div class="modal-footer">' +
                    '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
                    '<button type="button" class="btn btn-primary" id="confirm-consistency">Ignore</button>' +
                    '</div>';

                // Set the modal content
                $('#consistency-check-modal .modal-content').html(modalContent);

                // Add event listener for the confirm button
                $('#confirm-consistency').on('click', function () {
                    $('#consistency-check-modal').modal('hide');
                    confirmSubmitData();
                });
                $("#consistency-check-modal").modal('show');
            }
        }

        function calculateError() {
            let keyErrors = Object.keys(listError);
            if (keyErrors.length) {
                Swal.fire({
                    title: 'Pemberitahuan',
                    text: 'Masih terdapat isian yang harus diperbaiki!',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then(function () {
                    setTimeout(function () {
                        $(`#${keyErrors[0]}`).focus();
                    }, 500);
                })
            }
            return keyErrors.length;
        }

        function sendData(status) {
            blockProgress('body');
            outputForm['_token'] = '{{ csrf_token() }}';
            outputForm['status_form'] = status;
            $.ajax({
                url: '{{ route("form_update_usaha.save", ["perusahaan_id" => ":perusahaan_id", "alokasi_id" => ":alokasi_id"]) }}'
                    .replace(':perusahaan_id', pid).replace(':alokasi_id', aid),
                type: 'POST',
                dataType: 'json',
                data: outputForm,
                success: function (response) {
                    unblockProgress('body');
                    // success message for user
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        },
                        buttonsStyling: false
                    });

                    if (['OPEN', 'DRAFT', 'CANCELED'].includes(response.status_form)) {
                        // show success message and allow user to continue edit data  
                        $("#save-draft").show();
                        $("#submit-final").show();
                        $("#approve-update").hide();
                        $("#reject-update").hide();
                        $("#cancel-submit-final").hide();
                        return;
                    }

                    if (response.status_form == 'SUBMITTED') {
                        // show success message but user cannot continue to edit data
                        // instead show cancel submit button
                        $("#save-draft").hide();
                        $("#submit-final").hide();
                        $("#approve-update").hide();
                        $("#reject-update").hide();
                        $("#cancel-submit-final").show();
                        return;
                    }

                    if (response.status_form == 'APPROVED') {
                        // show success message and user cannot do anything
                        // or redirect to somewhere else        
                        $("#save-draft").hide();
                        $("#submit-final").hide();
                        $("#cancel-submit-final").hide();
                        $("#approve-update").hide();
                        $("#reject-update").hide();
                        return;
                    }

                    if (response.status_form == 'REJECTED') {
                        // show success message and user cannot do anything
                        // or redirect to somewhere else
                        $("#approve-update").hide();
                        $("#reject-update").hide();
                        $("#cancel-submit-final").hide();
                        $("#save-draft").hide();
                        $("#submit-final").hide();
                        if (response.allowed_edit) {
                            $("#save-draft").show();
                            $("#submit-final").show();
                        }
                        return;

                        // reject <perspective from admin>:                    
                        // 1. jika dia adalah hanya admin -> hide all button
                        // 2. jika dia adalah admin dan profiler juga:
                        //     - jika usaha yang direject adalah usaha yang memang hak dia -> tampilkan tombol save dan submit
                        //     - jika usaha yang direject bukan usaha hak dia -> hide all button 
                        // // reject <perspertive from profiler only>:
                        // di halaman list profiling usaha :
                        // 1. status usaha tertulis rejected + reject message
                        // 2. button edit usaha enabled
                        // 3. when showing form edit usaha -> show button save and submit  + show message rejected
                    }

                },
                error: function (err) {
                    unblockProgress('body');
                    let errorMessage = 'An unexpected error occurred';
                    if (err.responseJSON && err.responseJSON.message) {
                        errorMessage = err.responseJSON.message;
                    }
                    Swal.fire({
                        title: 'Error',
                        text: errorMessage,
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                }
            })
        }

        function consistencyCheck() {
            // check consistency between data
            // 1. check if badan usaha = PT -> nama perusahaan harus mengandung PT            
            if (outputForm.badan_usaha == '1') {
                if (!outputForm.nama.toLowerCase().includes('pt')) {
                    listConsistencyCheck['nama_usaha'] =
                        'Badan Hukum = PT Tetapi nama perusahaan tidak mengandung PT';
                } else {
                    delete listConsistencyCheck.nama_usaha;
                }
            } else {
                delete listConsistencyCheck.nama_usaha;
            }

            // 2. check if nama contain 'PT' but badan usaha != PT            
            if (outputForm.nama.toLowerCase().includes('pt') && outputForm.badan_usaha != '1') {
                listConsistencyCheck['badan_usaha'] =
                    'Badan Hukum tidak PT Tetapi nama perusahaan mengandung PT';
            } else {
                delete listConsistencyCheck.badan_usaha;
            }

            // 3. check if kbli kantor pusat (70100) tetapi jaringan usaha <> 2 (kantor pusat)            
            if (outputForm.kbli == '70100' && outputForm.jaringan_usaha != '2') {
                listConsistencyCheck['kbli'] =
                    'KBLI kantor pusat (70100) tetapi jaringan usaha tidak kantor pusat';
            } else {
                delete listConsistencyCheck.kbli;
            }

            let consistencyCheckKeys = Object.keys(listConsistencyCheck);
            return consistencyCheckKeys.length;

        }

        function validateForm() {
            // nama perusahaan
            if (!outputForm.nama) {
                $("#nama_usaha").addClass('is-invalid');
                listError['nama_usaha'] = 'Nama usaha harus terisi';
            } else {
                $("#nama_usaha").removeClass('is-invalid');
                delete listError.nama_usaha;
            }

            // alamat perusahaan
            if (!outputForm.alamat) {
                $("#alamat_usaha").addClass('is-invalid');
                listError['alamat_usaha'] = 'Alamat usaha harus terisi';
            } else if (/^\d+$/.test(outputForm.alamat)) {
                $("#alamat_usaha").addClass('is-invalid');
                listError['alamat_usaha'] = 'Alamat tidak valid';
            } else if (outputForm.alamat.length < 5) {
                $("#alamat_usaha").addClass('is-invalid');
                listError['alamat_usaha'] = 'Alamat terlalu pendek, minimal 5 karakter';
            } else {
                $("#alamat_usaha").removeClass('is-invalid');
                delete listError.alamat_usaha;
            }

            // if email is not checked it will not be counted as required field.
            let isEmailCheck = $("#check-email").is(':checked');
            if (isEmailCheck) {
                if (!outputForm.email) {
                    $("#email").addClass('is-invalid');
                    listError['email'] = 'Email harus terisi';
                } else {
                    if (!validateEmail(outputForm.email)) {
                        // if email is not valid
                        $("#email").addClass('is-invalid');
                        listError['email'] = 'Email harus valid';
                    } else {
                        $("#email").removeClass('is-invalid');
                        delete listError.email;
                    }
                }
            } else {
                $("#email").removeClass('is-invalid');
                delete listError.email;
            }

            // provinsi
            if (!outputForm.provinsi) {
                $("#provinsi").next('.select2-container').addClass('form-control is-invalid');
                listError['provinsi'] = 'Provinsi harus terisi';
            } else {
                $("#provinsi").next('.select2-container').removeClass('form-control is-invalid');
                delete listError.provinsi;
            }

            // kabupaten_kota
            if (!outputForm.kabupaten_kota) {
                $("#kabupaten_kota").next('.select2-container').addClass('form-control is-invalid');
                listError['kabupaten_kota'] = 'Kabupaten/Kota harus terisi';
            } else {
                $("#kabupaten_kota").next('.select2-container').removeClass('form-control is-invalid');
                delete listError.kabupaten_kota;
            }

            if(!outputForm.kegiatan_usaha.length) {                
                $(".hidden-kegiatan-usaha").addClass('is-invalid');
                listError['kegiatan_usaha'] = 'Kegiatan Usaha tidak boleh kosong';
            } else {
                let t_err = false;
                for(let i = 0; i < outputForm.kegiatan_usaha.length; i++) {
                    if(!outputForm.kegiatan_usaha[i].kategori) {                        
                        $(".hidden-kegiatan-usaha").addClass('is-invalid');
                        listError['kegiatan_usaha'] = 'Kategori tidak boleh kosong';
                        t_err = true;
                        break;
                    }
                }

                if(!t_err) {
                    $(".hidden-kegiatan-usaha").removeClass('is-invalid');
                    delete listError.kegiatan_usaha;
                }
            }

            // kegiatan usaha
            // if (!outputForm.kegiatan_utama) {
            //     $("#kegiatan_utama").addClass('is-invalid');
            //     listError['kegiatan_utama'] = 'Kegiatan usaha harus terisi';
            // } else {
            //     $("#kegiatan_utama").removeClass('is-invalid');
            //     delete listError.kegiatan_utama;
            // }

            // kategori usaha
            // if (!outputForm.kategori) {
            //     $("#kategori").next('.select2-container').addClass('form-control is-invalid');
            //     listError['kategori'] = 'Kategori harus terisi';
            // } else {
            //     $("#kategori").next('.select2-container').removeClass('form-control is-invalid');
            //     delete listError.kategori;
            // }

            // kbli usaha
            // if (!outputForm.kbli) {
            //     $("#kbli").next('.select2-container').addClass('form-control is-invalid');
            //     listError['kbli'] = 'KBLI harus terisi';
            // } else {
            //     $("#kbli").next('.select2-container').removeClass('form-control is-invalid');
            //     delete listError.kbli;
            // }

            // sumber profiling
            if (!outputForm.sumber_profiling) {
                $("#sumber_profiling").addClass('is-invalid');
                listError['sumber_profiling'] = 'Sumber profiling harus terisi.';
            } else {
                $("#sumber_profiling").removeClass('is-invalid');
                delete listError.sumber_profiling;
            }

            // catatan profiling
            if (!outputForm.catatan_profiling) {
                $("#catatan_profiling").addClass('is-invalid');
                listError['catatan_profiling'] = 'Catatan profiling harus terisi.'
            } else {
                $("#catatan_profiling").removeClass('is-invalid');
                delete listError.catatan_profiling;
            }

            // kondisi usaha/perusahaan
            if (!outputForm.status_perusahaan) {
                $(".hidden-input-only").addClass('is-invalid');
                listError['status_perusahaan'] = 'Kondisi usaha/perusahaan harus terisi';
            } else {

                // if aktif pindah terpilih atau salah kode wilayah terpilih
                if (outputForm.status_perusahaan == '7' || outputForm.status_perusahaan == '11') {
                    if (!outputForm.provinsi_pindah) {
                        $("#provinsi_pindah").next('.select2-container').addClass('form-control is-invalid');
                        listError['provinsi_pindah'] =
                            'Harus terisi jika aktif pindah atau salah kode wilayah terpilih';
                    } else {
                        $("#provinsi_pindah").next('.select2-container').removeClass('form-control is-invalid');
                        delete listError.provinsi_pindah;
                    }

                    if (!outputForm.kabupaten_kota_pindah) {
                        $("#kabupaten_kota_pindah").next('.select2-container').addClass(
                            'form-control is-invalid');
                        listError['kabupaten_kota_pindah'] =
                            'Harus terisi jika aktif pindah atau salah kode wilayah terpilih';
                    } else {

                        // jika kabupaten kota usaha awal  = kabupaten kota pindah -> tidak boleh
                        if (outputForm.kabupaten_kota_pindah == outputForm.kabupaten_kota) {
                            $("#kabupaten_kota_pindah").next('.select2-container').addClass(
                                'form-control is-invalid');
                            listError['kabupaten_kota_pindah'] =
                                'Kabupaten pindah tidak boleh sama dengan kabupaten asal usaha/perusahaan';

                            $(".hidden-input-only-pindah-kab").addClass('is-invalid');
                        } else {
                            $("#kabupaten_kota_pindah").next('.select2-container').removeClass(
                                'form-control is-invalid');
                            delete listError.kabupaten_kota_pindah;

                            $(".hidden-input-only-pindah-kab").removeClass('is-invalid');
                        }
                    }
                    return;
                }

                // if duplicate terpilih
                if (outputForm.status_perusahaan == '9') {
                    // if belum ada idsbr master yang dikonfimasi                    
                    if (!outputForm.idsbr_master) {
                        $(".hidden-input-only-duplikat").addClass('is-invalid');
                        listError['idsbr_master'] = 'idsbr master belum terkonfirmasi';
                    } else {
                        $(".hidden-input-only-duplikat").removeClass('is-invalid');
                        delete listError.idsbr_master;
                    }
                    return;
                }

                delete listError.idsbr_master;
                delete listError.kabupaten_kota_pindah;
                delete listError.provinsi_pindah;

                $(".hidden-input-only").removeClass('is-invalid');
                delete listError.status_perusahaan;
            }

            // website validation
            if (outputForm.website) {
                // Regular expression for validating a website URL
                const websiteRegex = /^(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+([\/\w \.-]*)*\/?$/;

                if (!websiteRegex.test(outputForm.website)) {
                    $("#website").addClass('is-invalid');
                    listError['website'] = 'Website harus memiliki format yang valid';
                } else {
                    $("#website").removeClass('is-invalid');
                    delete listError.website;
                }
            } else {
                $("#website").removeClass('is-invalid');
                delete listError.website;
            }

            // latitude validation
            if (outputForm.latitude) {
                // Regular expression for validating latitude
                const latitudeRegex = /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,15}$/;

                if (!latitudeRegex.test(outputForm.latitude)) {
                    $("#latitude").addClass('is-invalid');
                    listError['latitude'] =
                        'Latitude harus memiliki format yang valid (contoh: -6.2315085326216). Max 15 digit dibelakang koma';
                } else {
                    $("#latitude").removeClass('is-invalid');
                    delete listError.latitude;
                }
            } else {
                $("#latitude").removeClass('is-invalid');
                delete listError.latitude;
            }

            // longitude validation
            if (outputForm.longitude) {
                // Regular expression for validating longitude
                const longitudeRegex = /^-?((1[0-7]|[1-9]?)[0-9]|180)\.{1}\d{1,15}$/;

                if (!longitudeRegex.test(outputForm.longitude)) {
                    $("#longitude").addClass('is-invalid');
                    listError['longitude'] =
                        'Longitude harus memiliki format yang valid (contoh: 106.63301713765). Max 15 digit dibelakang koma';
                } else {
                    $("#longitude").removeClass('is-invalid');
                    delete listError.longitude;
                }
            } else {
                $("#longitude").removeClass('is-invalid');
                delete listError.longitude;
            }

            // if latitude is not empty but longitude is empty
            if (outputForm.latitude && !outputForm.longitude) {
                $("#longitude").addClass('is-invalid');
                listError['longitude'] = 'Jika latitude terisi, Longitude harus terisi';
            }

            // if longitude is not empty but latitude is empty
            if (!outputForm.latitude && outputForm.longitude) {
                $("#latitude").addClass('is-invalid');
                listError['latitude'] = 'Latitude harus terisi';
            }

            // tahun_berdiri validation
            if (outputForm.tahun_berdiri) {
                const currentYear = new Date().getFullYear();
                const tahunBerdiri = parseInt(outputForm.tahun_berdiri);

                if (isNaN(tahunBerdiri) || tahunBerdiri > currentYear) {
                    $("#tahun_berdiri").addClass('is-invalid');
                    listError['tahun_berdiri'] = 'Tahun berdiri tidak boleh lebih dari tahun saat ini';
                } else if (tahunBerdiri.toString().length != 4) {
                    $("#tahun_berdiri").addClass('is-invalid');
                    listError['tahun_berdiri'] = 'Tahun berdiri harus 4 digit';
                } else {
                    $("#tahun_berdiri").removeClass('is-invalid');
                    delete listError.tahun_berdiri;
                }
            } else {
                $("#tahun_berdiri").removeClass('is-invalid');
                delete listError.tahun_berdiri;
            }


        }

        $("#cancel-submit-final").on('click', function () {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah anda yakin ingin membatalkan submit?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545', // Bootstrap 'danger' red color
                cancelButtonColor: '#d3d3d3',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    sendData('CANCELED');
                }
            });
        });

        function validateEmail(email) {
            // Regular expression for validating an email address
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        function initializeFormOutput() {
            outputForm = {
                "nama": $("#nama_usaha").val().trim(),
                "nama_komersial": $("#nama-komersial").val().trim(),
                "alamat": $("#alamat_usaha").val().trim(),
                "sls": $("#sls").val().trim(),
                "kodepos": $("#kodepos").val(),
                "telepon": $("#telepon").val(),
                "whatsapp": $("#whatsapp").val(),
                "email": $("#email").val(),
                "isEmailCheck": $("#check-email").is(':checked'),
                "website": $("#website").val().trim(),
                "latitude": $("#latitude").val().trim(),
                "longitude": $("#longitude").val().trim(),
                "provinsi": $("#provinsi").val(),
                "kabupaten_kota": $("#kabupaten_kota").val(),
                "kecamatan": $("#kecamatan").val(),
                "kelurahan_desa": $("#kelurahan_desa").val(),
                // "kegiatan_utama": $("#kegiatan_utama").val().trim(),
                // "kategori": $("#kategori").val(),
                // "kbli": $("#kbli").val(),
                // "produk_utama": $("#produk_utama").val().trim(),
                "jenis_kepemilikan_usaha": $("#jenis_kepemilikan_usaha").val(),
                "badan_usaha": $("#badan_usaha").val(),
                "tahun_berdiri": $("#tahun_berdiri").val().trim(),
                "jaringan_usaha": $("input[name='jaringan_usaha']:checked").val(),
                "status_perusahaan": $("input[name='kondisi_usaha']:checked").val(),
                "idsbr_master": !idsbrAccept.hasOwnProperty('idsbr') ? '' : idsbrAccept.idsbr,
                "provinsi_pindah": $("#provinsi_pindah").val(),
                "kabupaten_kota_pindah": $("#kabupaten_kota_pindah").val(),
                "sumber_profiling": $("#sumber_profiling").val().trim(),
                "catatan_profiling": $("#catatan_profiling").val()
            }

            let repeaterData = [];
            $('.invoice-repeater, .repeater-default').each(function() {
                $(this).find('[data-repeater-item]').each(function() {
                    let item = {};
                    $(this).find('input, select').each(function() {                        
                        let name = $(this).attr('name').split('[').pop().split(']')[0];
                        if(name == 'l_kegiatan_usaha') {
                            name = 'kegiatan_usaha';    
                        }
                        if(name == 'l_produk_utama') {
                            name = 'produk_usaha';
                        }
                        if(name == 'l_kategori_usaha') {
                            name = 'kategori';
                        }
                        if(name == 'l_kbli') {
                            name = 'kbli';
                        }
                        let value = $(this).val();
                        item[name] = value;
                    });
                    repeaterData.push(item);
                });
            });

            // Add repeater data to form output
            outputForm.kegiatan_usaha = repeaterData;
        }

        // data: [{id:1, text: 'desa'}, {id:2, text: 'desa'}]
        function setOption(area, data) {
            // Clear existing options
            area.empty();
            // Append new options dynamically
            let temp = []
            $.each(data, function (index, option) {
                var newOption = new Option(option.text, option.id, false, false);
                temp.push(newOption);
            });
            area.append(temp).trigger('change');
        }

        function blockProgress(area) {
            // kalo mau ngeblok satu halaman
            if (area == 'body') {
                $.blockUI({
                    message: '<div class="spinner-border text-primary" role="status"></div>',
                    css: {
                        backgroundColor: 'transparent',
                        border: '0'
                    },
                    overlayCSS: {
                        backgroundColor: '#fff',
                        opacity: 0.8
                    }
                });
                return;
            }

            area.block({
                message: '<div class="spinner-border text-primary" role="status"></div>',
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.8
                }
            });
        }

        function unblockProgress(area) {
            if (area == 'body') {
                $.unblockUI();
                return;
            }
            area.unblock();
        }

        function isNumeric(value) {
            return /^[0-9]+$/.test(value);
        }

        var map;

        function showMap(lat, lng) {

            if (map !== undefined) {
                map.remove();
            }
            // Initialize map and set view to the given latitude and longitude
            map = L.map('map').setView([lat, lng], 13); // 13 is the zoom level

            // Add the OpenStreetMap layer (you can customize with other map providers)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).on('load', function () {
                // do something when the map loaded
                unblockProgress($(".container-map"));
            }).addTo(map);

            // Add a marker at the given latitude and longitude
            var marker = L.marker([lat, lng]).addTo(map);

            // Add a popup to the marker (optional)
            marker.bindPopup("Latitude: " + lat + "<br>Longitude: " + lng).openPopup();
        }

        // Example latitude and longitude values
        var lat = "{{ $usaha->latitude }}";
        var lng = "{{ $usaha->longitude }}";

        // Call the function to show the map
        showMap(lat, lng);
    });

</script>
@endsection
