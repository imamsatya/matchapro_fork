@extends('matchapro/layouts/contentLayoutMaster')

@section('title', 'Form Create Usaha')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection

@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">

@endsection

@section('content')
    <!-- Horizontal Wizard -->
    <section class="horizontal-wizard">
        <div class="bs-stepper horizontal-wizard-create">
            <div class="bs-stepper-header" role="tablist">
                <div class="step" data-target="#identitas-usaha" role="tab" id="identitas-usaha-trigger">
                    <button type="button" class="step-trigger">                        
                        <span class="bs-stepper-box">
                            <i data-feather="file-text" class="font-medium-3"></i>
                        </span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Identitas Usaha/Perusahaan</span>
                            <span class="bs-stepper-subtitle">Isi Identitas Usaha/Perusahaan</span>
                        </span>
                    </button>
                </div>
                <div class="line">
                    <i data-feather="chevron-right" class="font-medium-2"></i>
                </div>
                <div class="step" data-target="#cek-usaha" role="tab" id="cek-usaha-trigger">
                    <button type="button" class="step-trigger">                        
                        <span class="bs-stepper-box">
                            <i data-feather="database" class="font-medium-3"></i>
                        </span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title">Cek Duplikasi Usaha/Perusahaan</span>
                            <span class="bs-stepper-subtitle">Periksa Duplikasi Usaha/Perusahaan</span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="bs-stepper-content">
                <div id="identitas-usaha" class="content" role="tabpanel" aria-labelledby="identitas-usaha-trigger">
                    <div class="content-header">
                        <h5 class="mb-0">Identitas Usaha/Perusahaan</h5>
                        <small class="text-muted">Isi Identitas Usaha/Perusahaan</small> <br><br>
                        <button class="btn btn-relief-danger btn-clear-form" id="btnClearForm">
                            <i data-feather="trash-2" class="align-middle me-sm-25 me-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Clear Form</span>
                        </button>
                    </div>
                    <form>
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="mb-2 col-md-12">
                                    <label class="form-label" for="nama_usaha">Nama Usaha <span
                                    class="text-danger">*</span></label>
                                    <input type="text" name="nama_usaha" id="nama_usaha" class="form-control"
                                        placeholder="Eltisweiss, PT" value="{{ old('nama_usaha') }}" />
                                    <div id="nama_usaha-error" class="error-message"></div>

                                </div>
                                <div class="mb-1 col-md-12">
                                    <label class="form-label" for="alamat">Alamat <span
                                    class="text-danger">*</span></label>
                                    <textarea id="alamat" class="form-control" rows="7" placeholder="Alamat"></textarea>
                                    <div id="alamat-error" class="error-message"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="col-md-12 mb-1">
                                    <label class="form-label" for="select2-provinsi">Provinsi <span
                                    class="text-danger">*</span></label>
                                    <select class="select2 form-select" id="select2-provinsi">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($masterProvinsi as $provinsi)
                                        <option value="{{ $provinsi->id }}">[{{ $provinsi->kode }}] {{ $provinsi->nama }}</option>
                                        @endforeach                                        
                                    </select>
                                    <div id="select2-provinsi-error" class="error-message"></div>
                                </div>
                                <div class="col-md-12 mb-1 container-kabupaten">
                                    <label class="form-label" for="select2-kabupaten_kota">Kabupaten/Kota <span
                                    class="text-danger">*</span></label>
                                    <select class="select2 form-select" id="select2-kabupaten_kota">
                                        <option value="">-- Pilih Kabupaten/Kota --</option>
                                    </select>
                                    <div id="select2-kabupaten_kota-error" class="error-message"></div>
                                </div>

                                <div class="col-md-12 mb-1 container-kecamatan">
                                    <label class="form-label" for="select2-kecamatan">Kecamatan</label>
                                    <select class="select2 form-select" id="select2-kecamatan">
                                        <option value="">-- Pilih Kecamatan --</option>                                        
                                    </select>
                                </div>
                                <div class="col-md-12 mb-1 container-desa">
                                    <label class="form-label" for="select2-kelurahan_desa">Kelurahan/Desa</label>
                                    <select class="select2 form-select" id="select2-kelurahan_desa">
                                        <option value="">-- Pilih Kelurahan/Desa --</option>                                        
                                    </select>
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">Status Keberadaan Usaha</label>
                                    <div class="form-check form-check-success">
                                        <input type="radio" id="customColorRadio3" name="customColorRadio3"
                                            class="form-check-input" checked />
                                        <label class="form-check-label" for="customColorRadio3">Aktif</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                    <div class="d-flex flex-row-reverse">
                        <button class="btn btn-relief-primary btn-next btn-step1 mt-4">
                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                        </button>
                    </div>
                </div>

                <div id="cek-usaha" class="content" role="tabpanel" aria-labelledby="cek-usaha-trigger">
                    <div class="content-header">
                        <h5 class="mb-0">Pemeriksaan Usaha/Perusahaan</h5>
                        <small>Periksa Identitas Usaha/Perusahaan</small>
                    </div>
                    <form>
                        <div class="row">
                            <div class="mb-1 col-lg-12 col-md-12">

                                <div class="card-body">
                                    <h4 class="mb-75">Identitas Usaha/Perusahaan yang Ingin Ditambahkan</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mt-2">
                                                <h5 class="mb-75">Nama:</h5>
                                                <p class="card-text nama-step1-cek-usaha"></p>
                                            </div>
                                            <div class="mt-2">
                                                <h5 class="mb-75">Alamat:</h5>
                                                <p class="card-text alamat-step1-cek-usaha"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mt-2">
                                                <h5 class="mb-50">Provinsi:</h5>
                                                <p class="card-text mb-0 provinsi-step1-cek-usaha"></p>
                                            </div>
                                            <div class="mt-2">
                                                <h5 class="mb-50">Kabupaten/Kota:</h5>
                                                <p class="card-text mb-0 kabupaten_kota-step1-cek-usaha"></p>
                                            </div>
                                            <div class="mt-2">
                                                <h5 class="mb-50">Kecamatan:</h5>
                                                <p class="card-text mb-0 kecamatan-step1-cek-usaha"></p>
                                            </div>
                                            <div class="mt-2">
                                                <h5 class="mb-50">Kelurahan/Desa:</h5>
                                                <p class="card-text mb-0 kelurahan_desa-step1-cek-usaha"></p>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="">
                                        <div class="alert alert-warning mt-4">

                                            <h4 class="alert-heading"><i data-feather="alert-triangle"
                                                    class="me-50"></i>Apakah Anda
                                                yakin bahwa usaha yang ingin Anda tambahkan
                                                tidak ada dalam
                                                daftar berikut?</h4>
                                            <div class="alert-body fw-normal">
                                                Pastikan Anda sudah benar-benar yakin sebelum melanjutkan
                                            </div>
                                        </div>

                                        <form id="konfirmasiCheckBoxForm" class="validate-form" onsubmit="return false">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="konfrimasiCheckBox"
                                                    id="konfrimasiCheckBox"
                                                    data-msg="Silakan konfirmasi bahwa usaha yang ingin Anda tambahkan tidak ada dalam daftar berikut" />
                                                <label class="form-check-label font-small-3" for="konfrimasiCheckBox">
                                                    Saya yakin bahwa usaha yang ingin saya tambahkan tidak terdapat di
                                                    daftar
                                                    berikut.
                                                </label>
                                            </div>
                                            <div>

                                            </div>
                                        </form>

                                    </div>
                                </div>
                                <div class="mb-1 col-lg-12 col-md-12">
                                    <div class="card-datatable ">
                                        <table id="cek_data_table"
                                            class="dt-responsive table section-block-cek_data_table" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>Alamat</th>
                                                    <th>Provinsi</th>
                                                    <th>Kabupaten/Kota</th>
                                                    <th>Kecamatan</th>
                                                    <th>Kelurahan/Desa</th>
                                                    <th>Skor</th>
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
                                                    <th>Skor</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                {{-- Load Data Here --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                    </form>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-relief-primary btn-prev">
                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                        </button>

                        <button class="btn btn-relief-success btn-submit" id="step2Button">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Horizontal Wizard -->

@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
    <script>
        const getDataFulltextUrl = "{{ route('getDataFulltext') }}";
        const createPostURL = "{{ route('form_create_usaha.store') }}";
        let buttonState = false
    </script>
    <!-- Page js files -->    
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/matchapro-form-wizard-create.js')) }}"></script>


    {{-- Select Wilayah --}}
    <script>
        $(document).ready(function() {
                    
            $('.select2').select2();            
            

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

            let kabupatenCache = [];
            $("#select2-provinsi").on('change', function() {
                let provinsi = $(this).val();
                if(!provinsi) {                    
                    setOption($('#select2-kabupaten_kota'), [{
                        id: '',
                        text: '-- Pilih Kabupaten/Kota --'
                    }]);
                    return;
                }

                let findCache = kabupatenCache.find(kc => kc.provinsi == provinsi);
                if(findCache) {
                    setOption($('#select2-kabupaten_kota'), findCache.kabupaten_kota);
                    return;
                }

                blockProgress($('.container-kabupaten'));
                $.ajax({
                    url: '{{ route("wil-kabupaten-kota-user") }}',
                    type: 'POST',
                    data: {
                        'provinsi': provinsi,
                        '_token': '{{ csrf_token() }}',                        
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

                        setOption($('#select2-kabupaten_kota'), wilKab);                        
                        unblockProgress($('.container-kabupaten'));
                    }, 
                    error: function(err) {
                        Swal.fire({
                            title: 'Pemberitahuan',
                            text: 'Gagal mengambil data wilayah!',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        unblockProgress($('.container-kabupaten'));
                    }
                })

            })

            //When Kabupaten is selected
            let kecamatanCache = [];
            $('#select2-kabupaten_kota').on('change', function() {

                let kabupaten_kota = $(this).val();
                if(!kabupaten_kota) {
                    setOption($('#select2-kecamatan'), [{
                        id: '',
                        text: '-- Pilih Kecamatan --'
                    }]);
                    return;
                }

                let findCache = kecamatanCache.find(kc => kc.kabupaten_kota == kabupaten_kota);
                if(findCache) {
                    setOption($('#select2-kecamatan'), findCache.kecamatan);
                    return;
                }

                blockProgress($('.container-kecamatan'));
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

                        setOption($("#select2-kecamatan"), wilKecamatan);                        
                        unblockProgress($(".container-kecamatan"));
                    },
                    error: function(err) {
                        Swal.fire({
                            title: 'Pemberitahuan',
                            text: 'Gagal mengambil data wilayah!',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        unblockProgress($('.container-kecamatan'));
                    }
                })                

            })

            // When Kecamatan is selected
            let desaCache = [];
            $('#select2-kecamatan').on('change', function() {
                let kecamatan = $(this).val();

                if(!kecamatan) {
                    setOption($("#select2-kelurahan_desa"), [{
                        id: '',
                        text: '-- Pilih Kelurahan/Desa --'
                    }]);
                    return;
                }

                let findCache = desaCache.find(dc => dc.kecamatan == kecamatan);
                if (findCache) {
                    setOption($("#select2-kelurahan_desa"), findCache.desa);                    
                    return;
                }

                blockProgress($('.container-desa'));
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
                        setOption($("#select2-kelurahan_desa"), wilDesa);
                        unblockProgress($(".container-desa"));
                    }, 
                    error: function(err) {
                        Swal.fire({
                            title: 'Pemberitahuan',
                            text: 'Gagal mengambil data wilayah!',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        unblockProgress($('.container-desa'));
                    }
                })                

            });

            //Step 2
            const checkbox = document.getElementById('konfrimasiCheckBox');
            const button = document.getElementById('step2Button');


            function updateButtonState() {
                // console.log('Checkbox checked:', checkbox.checked); // Log checkbox state
                if (checkbox.checked) {
                    // button.removeAttribute('disabled'); // Enable the button
                    buttonState = true
                } else {
                    // button.setAttribute('disabled', 'disabled'); // Disable the button
                    buttonState = false
                }
            }
            checkbox.addEventListener('change', updateButtonState);

            // Initialize button state on page load
            updateButtonState();
        });
    </script>
    
    <script>        
        //clear Form
        const buttonClearForm = document.getElementById('btnClearForm');
        buttonClearForm.addEventListener('click', clearFormData);
        buttonClearForm.addEventListener('click', clearFormDataPage);

        function clearFormDataPage() {            
            document.getElementById('nama_usaha').value = ''
            document.getElementById('alamat').value = ''

            // Clear Kecamatan dropdown
            const kecamatanSelect = $('#select2-kecamatan');
            kecamatanSelect.val('').trigger('change'); // Reset and update Select2


            // Clear Kelurahan/Desa dropdown
            const kelurahanDesaSelect = $('#select2-kelurahan_desa');
            kelurahanDesaSelect.val('').trigger('change'); // Reset and update Select2

            const kabupatenKotaSelect = $('#select2-kabupaten_kota');
            kabupatenKotaSelect.val('').trigger('change');

            const provinsiSelect = $("#select2-provinsi");
            provinsiSelect.val('').trigger('change');            
        }

        // Function to save form data to localStorage
        function saveFormData() {
            localStorage.setItem('nama_usaha', document.getElementById('nama_usaha').value);
            localStorage.setItem('alamat', document.getElementById('alamat').value);
            // localStorage.setItem('select2-kabupaten_kota', $('#select2-kabupaten_kota').val()); // Use jQuery for Select2
            // localStorage.setItem('select2-kecamatan', $('#select2-kecamatan').val());
            // localStorage.setItem('select2-kelurahan_desa', $('#select2-kelurahan_desa').val());
            // Repeat for other fields as needed
        }

        // Function to load form data from localStorage
        function loadFormData() {
            if (localStorage.getItem('nama_usaha')) {
                document.getElementById('nama_usaha').value = localStorage.getItem('nama_usaha');
            }
            if (localStorage.getItem('alamat')) {
                document.getElementById('alamat').value = localStorage.getItem('alamat');
            }               
            // if (localStorage.getItem('select2-kabupaten_kota') != null && localStorage.getItem('select2-kabupaten_kota') != 'null') {                  
            //     $('#select2-kabupaten_kota').val(localStorage.getItem('select2-kabupaten_kota')).trigger(
            //         'change'); // Use .val() and trigger 'change'
            // }
            // if (localStorage.getItem('select2-kecamatan')) {
            //     $('#select2-kecamatan').val(localStorage.getItem('select2-kecamatan')).trigger(
            //         'change'); // Use .val() and trigger 'change'
            // }
            // if (localStorage.getItem('select2-kelurahan_desa')) {
            //     $('#select2-kelurahan_desa').val(localStorage.getItem('select2-kelurahan_desa')).trigger(
            //         'change'); // Use .val() and trigger 'change'
            // }
        }

        // Clear localStorage on form submission
        function clearFormData() {
            localStorage.removeItem('nama_usaha');
            localStorage.removeItem('alamat');
            // localStorage.removeItem('select2-kabupaten_kota');
            // localStorage.removeItem('select2-kecamatan');
            // localStorage.removeItem('select2-kelurahan_desa');
            // localStorage.removeItem('select2-kelurahan_desa');
            // Repeat for other fields as needed
        }

        // Load data when the page is loaded
        window.onload = loadFormData;

        // Save data when the user types or selects something
        document.getElementById('nama_usaha').addEventListener('input', saveFormData);
        document.getElementById('alamat').addEventListener('input', saveFormData);
        // $('#select2-kabupaten_kota').on('change', saveFormData);
        // $('#select2-kecamatan').on('change', saveFormData);
        // $('#select2-kelurahan_desa').on('change', saveFormData);
        // Repeat for other fields as needed

        // Optionally, clear the data when the form is submitted
        document.querySelector('form').addEventListener('submit', clearFormData);
    </script>
@endsection

<style>
    .blockUI.blockMsg.blockElement {
        width: 100%;
        top: 50%;
    }


    /* .block-message {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        text-align: center;
    } */
</style>
