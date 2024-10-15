@extends('matchapro/layouts/contentLayoutMaster')

@section('title', 'User List')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">    
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">User List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="users-table">
                        <thead>
                            <tr>                                
                                <th>Nama</th>
                                <th>Satuan Kerja</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-secondary">
                <h5 class="modal-title text-secondary" id="editUserModalLabel">
                    <i class="fas fa-user-edit me-2"></i>Edit User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body container-modal-edit-user">
                <form id="editUserForm" class="row gy-2">
                    <div class="col-md-6">
                        <label for="edit-name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                        <div class="invalid-feedback"><span id="edit-name-error"></span></div>
                    </div>
                    <div class="col-md-6">
                        <label for="edit-username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit-username" name="username" required>
                        <div class="invalid-feedback"><span id="edit-username-error"></span></div>
                    </div>
                    <div class="col-md-6">
                        <label for="edit-provinsi" class="form-label">Provinsi</label>
                        <select class="form-select select2" id="edit-provinsi" name="provinsi_id">
                            <option value="">Select Provinsi</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit-kabupaten" class="form-label">Kabupaten/Kota</label>
                        <select class="form-select select2" id="edit-kabupaten" name="kabupaten_kota_id">
                            <option value="">Select Kabupaten/Kota</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit-role" class="form-label">Role</label>
                        <select class="form-select select2" id="edit-role" name="role_id">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label d-block">Status <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" id="edit-active" value="1">
                            <label class="form-check-label" for="edit-active">
                                <span class="badge bg-light-success text-success">Active</span>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" id="edit-inactive" value="0">
                            <label class="form-check-label" for="edit-inactive">
                                <span class="badge bg-light-danger text-danger">Inactive</span>
                            </label>
                        </div>
                        <div>
                            <input type="text" class="hidden-input-only-pindah-kab" id="edit-status" style="display: none">
                            <div class="invalid-feedback"><span id="edit-status-error"></span></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="whatsapp">Nomor Whatsapp</label>
                        <input type="text" class="form-control" id="edit-whatsapp" placeholder="Nomor Whatsapp" name="whatsapp"
                            aria-label="Nomor Whatsapp">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i data-feather="x" class="me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-relief-primary" id="saveUserChanges">
                    <i data-feather="save" class="me-1"></i>Save changes
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editWilayahModal" tabindex="-1" aria-labelledby="editWilayahModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light-secondary">
                <h5 class="modal-title text-secondary" id="editWilayahModalLabel">
                    <i class="fas fa-user-edit me-2"></i>Edit User - Akses Wilayah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body container-modal-edit-user-wilayah">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-info mb-3 shadow-sm">
                            <div class="card-header bg-info">
                                <h5 class="mb-0 text-white">
                                    <i data-feather="user" class="me-1"></i>User Information
                                </h5>
                            </div>
                            <div class="card-body bg-light">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-1">
                                            <label class="form-label fw-bold text-info">Nama:</label>
                                            <p class="mb-0 border-bottom pb-2" id="info-nama"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-1">
                                            <label class="form-label fw-bold text-info">Satuan Kerja:</label>
                                            <p class="mb-0 border-bottom pb-2" id="info-satker"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-1">
                                            <label class="form-label fw-bold text-info">Role:</label>
                                            <p class="mb-0"><span class="badge bg-info text-white" id="info-role"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gy-2">
                    <div class="col-md-7 col-lg-7 col-sm-12">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="provinsi-wilayah" class="form-label">Provinsi</label>
                                    <select class="form-select select2" id="provinsi-wilayah" name="provinsi-wilayah">
                                        <option value="">-- Pilih Provinsi --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Daftar Kabupaten/Kota</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="wilayah-kabupaten-list" class="p-2">
                                            <!-- Kabupaten list will be populated here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg 5 col-sm-12">                        
                        <div class="card">
                            <div class="card-header bg-primary" id="selectedWilayahHeader">
                                <h5 class="mb-0">
                                    <button class="btn btn-link text-white" type="button" data-bs-toggle="collapse" data-bs-target="#selectedWilayahContent" aria-expanded="true" aria-controls="selectedWilayahContent">
                                        <i class="fas fa-map-marker-alt me-2"></i>Selected Wilayah
                                    </button>
                                </h5>
                            </div>
                            <div id="selectedWilayahContent" class="collapse show" aria-labelledby="selectedWilayahHeader" data-parent="#selectedWilayahAccordion">
                                <div class="card-body">
                                    <div class="accordion accordion-border" id="selectedWilayahAccordion">
                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i data-feather="x" class="me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-relief-primary" id="saveWilayahChanges">
                    <i data-feather="save" class="me-1"></i>Save changes
                </button>
            </div>
        </div>
    </div>
</div>






@endsection

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script>
        $(document).ready(function() {
            let roles = {!! json_encode($roles) !!};        
            let m_provinsi = {!! json_encode($m_provinsi) !!};                

            $('#edit-provinsi, #edit-kabupaten').select2({
                dropdownParent: $('#editUserModal')
            });

            $('#provinsi-wilayah').select2({
                dropdownParent: $('#editWilayahModal')
            });

            var table_users = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.list.data') }}"   ,
                    data: function(d) {
                        d.name = $('input[name="name"]').val();
                        d.satuan_kerja = $('input[name="satuan_kerja"]').val();
                        d.role = $('select[name="role"]').val();
                        d.is_active = $('select[name="is_active"]').val();
                    }                 
                },
                columns: [                    
                    { data: 'name', name: 'name' , 
                        render: function(data, type, full, meta) {
                            return renderUser(full);
                        }
                    },
                    { data: 'satuan_kerja', name: 'satuan_kerja', width: '20%',
                        render: function(data, type, full, meta) {
                            if(data == '0000') {
                                return `PUSAT<br/><span class="text-muted">${data}</span>`;
                            } else if(full.kode_kabupaten == '00') {
                                return `PROVINSI ${full.nama_provinsi}<br/><span class="text-muted">${data}</span>`;
                            } else {                                
                                return `KAB/KOTA ${full.nama_kabupaten}<br/><span class="text-muted">${data}</span>`;
                            }
                        }
                     },
                    { data: 'role', name: 'role', orderable: false, searchable: false, width: '20%' },
                    { data: 'is_active', name: 'is_active', width: '10%', 
                        render: function(data, type, full, meta) {
                            return `<span class="badge ${data == '1' ? 'bg-light-success' : 'bg-light-danger'}">${data == '1' ? 'Active' : 'Inactive'}</span>`;
                        }
                    },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, width: '5%',
                        render: function(data, type, full, meta) {
                            return renderButton(full);
                        }
                     }
                ],
                responsive: true,
                searching: false,
                ordering: false
            });

            $('#users-table thead tr').clone(true).appendTo('#users-table thead');
            $('#users-table thead tr:eq(1) th').each(function(i) {
                if(i < 2) {
                    var title = $(this).text();
                    var name = i == 0 ? 'name' : (i == 1 ? 'satuan_kerja' : '');
                    $(this).html('<input name="' + name + '" type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                } else if(i === 2) {
                    $(this).html(`<select name="role" class="form-control form-control-sm">${getRoleOptions()}</select>`);
                } else if(i == 3) {
                    $(this).html(`<select name="is_active" class="form-control form-control-sm">
                        <option value="-">-- All --</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>`);
                } else if(i ==4) {
                    $(this).html(`
                        <div class="d-flex align-items-center col-actions">
                            <button id="filter-data" type="button" class="btn btn-sm btn-outline-secondary me-1"><i data-feather="filter"></i></button>
                            <button id="clear-filter" type="button" class="btn btn-sm btn-outline-secondary"><i data-feather="x"></i></button>
                        </div>
                        `);
                }
                else {
                    $(this).html('');
                }
            });

            $('#users-table  thead').on('click', '#filter-data', function() {                
                table_users.ajax.reload();
            });

            $("#users-table thead").on('click', '#clear-filter', function() {
                $('select[name="is_active"]').val('-');
                $('select[name="role"]').val('-');
                $('input[name="name"]').val('');
                $('input[name="satuan_kerja"]').val('');                
                table_users.ajax.reload();       
            });

            let userSelected = null;
            $('#users-table tbody').on('click', '.edit-user', function() {
                userSelected = $(this).data('user-id');
                $("#editUserModal").modal('show');                
            });

            let selectedKabupatenAll = [];
            $("#users-table tbody").on('click', '.edit-user-wilayah', function() {
                userSelected = $(this).data('user-id');
                // Get the row data from the DataTable
                let rowData = table_users.row($(this).closest('tr')).data();                
                $("#info-nama").html(`[${rowData.username}] ${rowData.name}`)                
                if(rowData.satuan_kerja == '0000') {
                    $("#info-satker").html(`[${rowData.satuan_kerja}] PUSAT`)
                } else if(rowData.kode_kabupaten == '00') {
                    $("#info-satker").html(`[${rowData.satuan_kerja}] PROVINSI ${rowData.nama_provinsi}`)
                } else {
                    $("#info-satker").html(`[${rowData.satuan_kerja}] KAB/KOTA ${rowData.nama_kabupaten}`)
                }
                $("#info-role").html(rowData.role)
                $("#editWilayahModal").modal('show');                
            });

            var cleaveWA = new Cleave("#edit-whatsapp", {
                prefix: '+62',
                delimiter: '-',
                blocks: [3, 3, 4, 4],
                uppercase: true,
                numericOnly: true,
            });

            $("#editWilayahModal").on('shown.bs.modal', function() {
                blockProgress($('.container-modal-edit-user-wilayah'));
                getWilayahAksesUser(userSelected).then(function(response) { 
                    // here 
                    for(let i = 0; i < response.length; i++) {
                        let provinsi = response[i];                        
                        let provinsiOption = {
                            provinsi: provinsi.provinsi_id,
                            nmprovinsi: `[${provinsi.kdprovinsi}] ${provinsi.nmprovinsi}`,
                            kabupaten: [{
                                nmkab: `[${provinsi.kdkab}] ${provinsi.nmkab}`,
                                value: provinsi.kabupaten_kota_id
                            }]
                        };                        
                        
                        let f_provinsi = selectedKabupatenAll.find(p => p.provinsi == provinsi.provinsi_id);
                        if(!f_provinsi) {
                            selectedKabupatenAll.push(provinsiOption);
                        }  else {
                            f_provinsi.kabupaten.push({
                                nmkab: `[${provinsi.kdkab}] ${provinsi.nmkab}`,
                                value: provinsi.kabupaten_kota_id
                            });
                        }                                               
                    }

                    setOption($("#provinsi-wilayah"), optionProv());
                    updateSelectedWilayahList();
                    unblockProgress($('.container-modal-edit-user-wilayah'));
                }).catch(function(error) {
                    unblockProgress($('.container-modal-edit-user-wilayah'));
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Try again later!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                });
            });

            function getWilayahAksesUser(userSelected) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ route('user.wilayah.akses') }}",
                        type: 'POST',
                        data: { user: userSelected, _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            resolve(response);
                        },
                        error: function(xhr) {
                            reject(xhr.responseJSON);
                        }
                    });
                });
            }

            $("#editWilayahModal").on('hidden.bs.modal', function(){
                userSelected = null;
                selectedKabupatenAll = [];
                updateSelectedWilayahList();
            });

            $("#editUserModal").on('shown.bs.modal', function(){                                
                blockProgress($('.container-modal-edit-user'));
                getDetailUser(userSelected).then(function(response) {
                    fillForm(response);            
                    unblockProgress($('.container-modal-edit-user'));
                }).catch(function(error) {
                    unblockProgress($('.container-modal-edit-user'));
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Try again later!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });                                      
                });
            });

            $("#editUserModal").on('hidden.bs.modal', function(){
                userSelected = null;
                formData = null;
                listFormError = {};
                cleanDataModal();
            });


            function fillForm(data) {
                $("#edit-name").val(data.user.nama);
                $("#edit-username").val(data.user.username);
                $("#edit-active").prop("checked", data.user.is_active == 1);
                $("#edit-inactive").prop("checked", data.user.is_active == 0);
                cleaveWA.setRawValue(data.user.nomor_whatsapp);

                $("#edit-role").val(data.role).trigger('change');

                setOption($("#edit-provinsi"), optionProv());
                $("#edit-provinsi").val(data.user.provinsi_id).trigger('change');   

                setOption($("#edit-kabupaten"), optionKab(data.m_kabupaten));
                $("#edit-kabupaten").val(data.user.kabupaten_kota_id).trigger('change');
            }

            let cacheKab = [];
            $("#provinsi-wilayah").on('change', function() {
                let provId = $(this).val();

                if(!provId) {
                    showKabupatenOption([]);
                    return;
                }
                
                if(cacheKab.length && cacheKab.find(k => k.provinsi_id == provId)) {
                    let m_kab = cacheKab.find(k => k.provinsi_id == provId).kabupaten;
                    showKabupatenOption(m_kab);
                    return;
                }
                
                blockProgress($('.container-modal-edit-user-wilayah'));
                getWilayahKab(provId).then(function(response) {
                    showKabupatenOption(response);
                    if(response.length && !cacheKab.find(k => k.provinsi_id == response[0].provinsi_id)) {
                        cacheKab.push({
                            'provinsi_id': response[0].provinsi_id,
                            'kabupaten': response
                        });
                    }
                    unblockProgress($('.container-modal-edit-user-wilayah'));
                }).catch(function(err) {
                    unblockProgress($('.container-modal-edit-user-wilayah'));
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Try again later!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                })
                
            });


            
            $('#wilayah-kabupaten-list').on('change', 'input[name="select-kabkot-wilayah"]', function() {
                let provinsi_active = $("#provinsi-wilayah").val();
                let nmprovinsi = $("#provinsi-wilayah").find('option:selected').text();
                let tempKab = {
                    provinsi: provinsi_active,
                    nmprovinsi: nmprovinsi,
                    kabupaten: []                    
                };
                let selectedKabupaten = $('input[name="select-kabkot-wilayah"]:checked').map(function() {
                    let nmkab = `[${$(this).data('kd-kab')}] ${$(this).data('nm-kab')}`;                    
                    return { nmkab: nmkab, value: this.value };
                }).get();

                tempKab.kabupaten = selectedKabupaten;

                let provinsi = selectedKabupatenAll.find(p => p.provinsi == tempKab.provinsi);
                if(!provinsi) {
                    selectedKabupatenAll.push(tempKab);
                } else {                    
                    if(tempKab.kabupaten.length) {
                        provinsi.kabupaten = tempKab.kabupaten;
                    } else {
                        // Remove the provinsi object from selectedKabupatenAll
                        selectedKabupatenAll = selectedKabupatenAll.filter(p => p.provinsi != tempKab.provinsi);
                    }
                }

                updateSelectedWilayahList();
            });

            function updateSelectedWilayahList() {               
                let listHtml = '';
                let provEmpty = [];
                selectedKabupatenAll.forEach(provinsi => {   
                    if(provinsi.kabupaten.length) {
                        listHtml += `<div class="accordion-item">
                        <h2 class="accordion-header" id="headingSelectedWilayah${provinsi.provinsi}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSelectedWilayah${provinsi.provinsi}" aria-expanded="true" aria-controls="collapseSelectedWilayah${provinsi.provinsi}">
                                ${provinsi.nmprovinsi}
                            </button>
                        </h2>
                        <div id="collapseSelectedWilayah${provinsi.provinsi}" class="accordion-collapse collapse show" aria-labelledby="headingSelectedWilayah${provinsi.provinsi}" data-bs-parent="#selectedWilayahAccordion">
                            <div class="accordion-body">
                                <div id="selectedWilayahList${provinsi.provinsi}">`;
                        listHtml += '<ul>';
                        provinsi.kabupaten.forEach(kabupaten => {
                            listHtml += `<li>${kabupaten.nmkab}</li>`;
                        });
                        listHtml += '</ul>';
                        listHtml += '</div></div></div></div>';
                    } else {
                        provEmpty.push(provinsi.provinsi);
                    }                    
                });

                // Remove provinces with empty kabupaten lists
                selectedKabupatenAll = selectedKabupatenAll.filter(province => !provEmpty.includes(province.provinsi));

                if(!selectedKabupatenAll.length) {
                    listHtml = '<p>No kabupaten/kota selected.</p>';
                }
                $(`#selectedWilayahAccordion`).html(listHtml);

                
            }

            function showKabupatenOption(m_kab) {                
                let kabupatenList = $('#wilayah-kabupaten-list');
                kabupatenList.empty();

                if (m_kab.length === 0) {
                    kabupatenList.append('<p>No kabupaten/kota available for this provinsi.</p>');
                    return;
                }

                let columns = Math.ceil(m_kab.length / 10); // Create columns if more than 10 items
                let itemsPerColumn = Math.ceil(m_kab.length / columns);
                
                let row = $('<div class="row"></div>');
                
                for (let i = 0; i < columns; i++) {
                    let column = $('<div class="col-md-' + (12 / columns) + '"></div>');
                    let list = $('<ul class="list-unstyled"></ul>');
                    // Add "Select All" checkbox at the beginning of each column
                    let selectAllCheckbox = $(`
                        <li class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input select-all-kabupaten" type="checkbox" id="select-all-${i}">
                                <label class="form-check-label" for="select-all-${i}">
                                    <strong>Select All</strong>
                                </label>
                            </div>
                        </li>
                    `);
                    list.append(selectAllCheckbox);

                    // Add event listener for "Select All" checkbox
                    selectAllCheckbox.find('input').on('change', function() {
                        let isChecked = $(this).prop('checked');
                        $(this).closest('ul').find('input[name="select-kabkot-wilayah"]').prop('checked', isChecked).trigger('change');
                    });
                    
                    for (let j = i * itemsPerColumn; j < (i + 1) * itemsPerColumn && j < m_kab.length; j++) {
                        
                        let kab = m_kab[j];
                        let isChecked = false;
                        if(selectedKabupatenAll.length) {
                            let provinsi = selectedKabupatenAll.find(p => p.provinsi == kab.provinsi_id);
                            if (provinsi) {
                                isChecked = provinsi.kabupaten.some(item => item.value == kab.id);
                            }
                        }
                        let item = $(`
                            <li class="mb-1">
                                <div class="form-check">
                                    <input ${isChecked ? 'checked' : ''} class="form-check-input" type="checkbox" name="select-kabkot-wilayah" data-provinsi="${kab.provinsi_id}" value="${kab.id}" id="kab-${kab.id}" data-nm-kab="${kab.nama}" data-kd-kab="${kab.kode}">
                                    <label class="form-check-label" for="kab-${kab.id}">
                                        [${kab.kode}] ${kab.nama}
                                    </label>
                                </div>
                            </li>
                        `);
                        list.append(item);
                    }
                    
                    column.append(list);
                    row.append(column);
                }
                
                kabupatenList.append(row);
            }
            
            $("#edit-provinsi").on('change', function(){
                let provId = $(this).val();
                if(!provId) {
                    setOption($("#edit-kabupaten"), optionKab([]));                                    
                    return;
                }

                if(cacheKab.length && cacheKab.find(k => k.provinsi_id == provId)) {
                    let m_kab = cacheKab.find(k => k.provinsi_id == provId).kabupaten;
                    setOption($("#edit-kabupaten"), optionKab(m_kab));                    
                    return;
                }

                blockProgress($('.container-modal-edit-user'));

                getWilayahKab(provId).then(function(response) {
                    setOption($("#edit-kabupaten"), optionKab(response));
                    $("#edit-kabupaten").val('').trigger('change');
                    unblockProgress($('.container-modal-edit-user'));
                }).catch(function(err) {
                    unblockProgress($('.container-modal-edit-user'));
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Try again later!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                });
            });

            function getWilayahKab(provId) {
                return $.ajax({
                    url: '{{ route("wil-kabupaten-kota") }}',
                    type: 'POST',
                    data: {
                        provinsi: provId,
                        level: 'all',
                        _token: '{{ csrf_token() }}'
                    },
                });
            }   

            let formData = null;
            let listFormError = {};
            $("#saveUserChanges").on('click', function() {
                initializeFormOutput();
                let err = validateForm();
                if(err) {
                    showErrorMessages();
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Masih terdapat isian yang harus diperbaiki!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                    return;
                }
                
                sendData();
            });

            function sendData(type) {
                blockProgress($('.container-modal-edit-user'));
                $.ajax({
                    url: '{{ route("user.list.update") }}',
                    type: 'POST',
                    data: {
                        user: userSelected,
                        data: formData,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        unblockProgress($('.container-modal-edit-user'));
                        Swal.fire({
                            title: 'Success!',
                            text: 'Data berhasil disimpan!',
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        })
                        $("#editUserModal").modal('hide');
                        table_users.ajax.reload(null, false);
                    },
                    error: function(err) {
                        unblockProgress($('.container-modal-edit-user'));
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong. Try again later!',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        
                    }
                })
            }

            $("#saveWilayahChanges").on('click', function(){                
                Swal.fire({
                    title: 'Warning!',
                    text: 'Simpan perubahan wilayah?',
                    icon: 'warning',
                    customClass: {
                        confirmButton: 'btn btn-primary me-1',
                        cancelButton: 'btn btn-danger'
                    },
                    showCancelButton: true,
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        saveChangesWilayah();
                    }
                });
            });

            function saveChangesWilayah() {                
                blockProgress($('.container-modal-edit-user-wilayah'));
                $.ajax({
                    url: '{{ route("user.list.update.wilayah") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        user: userSelected,
                        data: selectedKabupatenAll,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        unblockProgress($('.container-modal-edit-user-wilayah'));
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },  
                            buttonsStyling: false
                        })
                        $("#editUserWilayahModal").modal('hide');
                    },
                    error: function(err) {
                        unblockProgress($('.container-modal-edit-user-wilayah'));
                        let errorMessage = 'An unexpected error occurred';
                        if (err.responseJSON && err.responseJSON.message) {
                            errorMessage = err.responseJSON.message;
                        }
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                    }
                })
            }

            function showErrorMessages() {
                let keyErrors = Object.keys(listFormError);
                if (keyErrors.length) {
                    for (let key in listFormError) {
                        $("#" + key+"-error").html(listFormError[key]);
                    }
                }
            }

            function initializeFormOutput(){
                formData = {
                    name: $("#edit-name").val().trim(),
                    username: $("#edit-username").val().trim(),
                    provinsi_id: $("#edit-provinsi").val(),
                    kabupaten_kota_id: $("#edit-kabupaten").val(),
                    role_id: $("#edit-role").val(),
                    is_active: $("#edit-active").prop("checked") ? 1 : 0,
                    whatsapp: $("#edit-whatsapp").val()
                } 
            }

            function validateForm() {
                if(!formData.name) {
                    $("#edit-name").addClass('is-invalid');
                    listFormError['edit-name'] = 'Nama harus terisi';
                } else {
                    $("#edit-name").removeClass('is-invalid');
                    delete listFormError['edit-name'];
                }

                if(!formData.username) {
                    $("#edit-username").addClass('is-invalid');
                    listFormError['edit-username'] = 'Username harus terisi';
                } else if(formData.username.includes(' ')) {
                    $("#edit-username").addClass('is-invalid');
                    listFormError['edit-username'] = 'Username tidak boleh mengandung spasi';
                } else {
                    $("#edit-username").removeClass('is-invalid');
                    delete listFormError['edit-username'];
                }           
                
                return Object.keys(listFormError).length;
                
            }

            function optionKab(m_kab) {  
                // check if already exist inside cache
                if(m_kab.length && !cacheKab.find(k => k.provinsi_id == m_kab[0].provinsi_id)) {
                    cacheKab.push({
                        'provinsi_id': m_kab[0].provinsi_id,
                        'kabupaten': m_kab
                    });
                }
                
                let wilKab = [{
                        id: '',
                        text: '-- Pilih Kabupaten/Kota --'
                    }]
                for(let i= 0; i<m_kab.length; i++) {
                    wilKab.push({
                        id: m_kab[i].id,
                        text: '[' + m_kab[i].kode + ']' + ' ' + m_kab[i].nama                        
                    })   
                }
                return wilKab;
            }

            function optionProv() {
                let wilProv = [{
                        id: '',
                        text: '-- Pilih Provinsi --'
                    }]
                for(let i= 0; i<m_provinsi.length; i++) {
                    wilProv.push({
                        id: m_provinsi[i].id,
                        text: '[' + m_provinsi[i].kode + ']' + ' ' + m_provinsi[i].nama                        
                    })   
                }
                return wilProv;
            }

            function cleanDataModal() {                
                cleaveWA.setRawValue("+62-");
                $("#edit-name").val('');
                $("#edit-username").val('');
                $("#edit-provinsi").val('');
                $("#edit-kabupaten").val('');
                $("#edit-role").val('');
                $("#edit-active").prop("checked", false);
                $("#edit-inactive").prop("checked", false);
            }

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

            function getDetailUser(userId) {                
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '{{ route("user.list.detail") }}',
                        type: 'POST',
                        data: {
                            'user': userId,
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            resolve(response);
                        },
                        error: function(err) {                            
                            reject(err);
                        }
                    });
                });
            }

            function renderButton(data) {
                return `
                <div class="d-flex align-items-center col-actions">
                    <a href="javascript:void(0);" class="btn btn-sm btn-flat-warning me-1 edit-user" data-user-id="${data.id}">${feather.icons['user'].toSvg({ class: 'font-small-4' })}</a> 
                    <a href="javascript:void(0);" class="btn btn-sm btn-flat-primary me-1 edit-user-wilayah" data-user-id="${data.id}">${feather.icons['edit'].toSvg({ class: 'font-small-4' })}</a> 
                    <a href="javascript:void(0);" class="btn btn-sm btn-flat-danger me-1 delete-user" data-user-id="${data.id}">${feather.icons['trash'].toSvg({ class: 'font-small-4' })}</a> 

                </div>
                `;
            }

            function renderUser(data) {
                let stateNum = Math.floor(Math.random() * 6) + 1;
                let states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                let initials = data.name.match(/\b\w/g) || [];
                initials = ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
                initials = '<span class="avatar-content">' + initials + '</span>';
                let output = data.photo ? `<img src="${data.photo}" alt="user-avatar" height="32" width="32" />` : initials;
                return `
                    <div class="d-flex justify-content-left align-items-center">
                        <div class="avatar-wrapper">
                            <div class="avatar ${!data.photo ? ' bg-light-'+states[stateNum] : ''} me-1">
                                ${output}
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="javascript:void(0);" class="user_name text-body text-truncate">
                            <span class="fw-bolder">${data.name}</span>
                            </a>
                            <small class="emp_post text-muted">${data.username}</small>
                            <small class="emp_post text-muted">WA: ${data.wa ?? '-'}</small>
                        </div>
                    </div>
                `;
            }

            function getRoleOptions() {
                let options = '<option value="-">-- All --</option>';
                options += '<option value="norole">[NO-ROLE]</option>';
                roles.forEach(role => {
                    options += `<option value="${role.id}">${role.name}</option>`;
                });
                return options;
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
        });
    </script>
@endsection
