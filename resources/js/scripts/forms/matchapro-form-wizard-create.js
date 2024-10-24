/*=========================================================================================
    File Name: matchapro-form-wizard-create.js
    Description: wizard steps page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
    'use strict';
  
    var bsStepper = document.querySelectorAll('.bs-stepper'),
      select = $('.select2'),
      horizontalWizard = document.querySelector('.horizontal-wizard-create'),
      verticalWizard = document.querySelector('.vertical-wizard-example'),
      modernWizard = document.querySelector('.modern-wizard-example'),
      modernVerticalWizard = document.querySelector('.modern-vertical-wizard-example');
  
    // Adds crossed class
    if (typeof bsStepper !== undefined && bsStepper !== null) {
      for (var el = 0; el < bsStepper.length; ++el) {
        bsStepper[el].addEventListener('show.bs-stepper', function (event) {
          var index = event.detail.indexStep;
          var numberOfSteps = $(event.target).find('.step').length - 1;
          var line = $(event.target).find('.step');
  
          // The first for loop is for increasing the steps,
          // the second is for turning them off when going back
          // and the third with the if statement because the last line
          // can't seem to turn off when I press the first item. ¯\_(ツ)_/¯
            // console.log('index', index, 'numberofSteps', numberOfSteps, 'line', line)
          for (var i = 0; i < index; i++) {
            line[i].classList.add('crossed');
  
            for (var j = index; j < numberOfSteps; j++) {
              line[j].classList.remove('crossed');
            }
          }
          if (event.detail.to == 0) {
            for (var k = index; k < numberOfSteps; k++) {
              line[k].classList.remove('crossed');
            }
            line[0].classList.remove('crossed');
          }
        });
      }
    }
  
    // select2
    select.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>');
      $this.select2({
        placeholder: 'Select value',
        dropdownParent: $this.parent()
      });
    });
  
    // Horizontal Wizard
    // --------------------------------------------------------------------
    if (typeof horizontalWizard !== undefined && horizontalWizard !== null) {
      var numberedStepper = new Stepper(horizontalWizard),
        $form = $(horizontalWizard).find('form');
        $form.each(function () {
        var $this = $(this);
        
        // $this.validate({
        //   rules: {
        //     'nama_usaha': {
        //         required: true
        //       },
        //       'yy': {
        //         required: true
        //       },
        //       'aa': {
        //         required: true
        //       },
        //       'alamat': {
        //         required: true
        //       },
        //       'select2-provinsi': {
        //         required: true
        //       },
        //       'select2-kabupaten_kota': {
        //         required: true
        //       },
        //       'select2-kecamatan': {
        //         required: true
        //       },
        //       'select2-kelurahan_desa': {
        //         required: true
        //       },
        //     username: {
        //       required: true
        //     },
        //     email: {
        //       required: true
        //     },
        //     password: {
        //       required: true
        //     },
        //     'confirm-password': {
        //       required: true,
        //       equalTo: '#password'
        //     },
        //     'first-name': {
        //       required: true
        //     },
        //     'last-name': {
        //       required: true
        //     },
        //     address: {
        //       required: true
        //     },
        //     landmark: {
        //       required: true
        //     },
        //     country: {
        //       required: true
        //     },
        //     language: {
        //       required: true
        //     },
        //     twitter: {
        //       required: true,
        //       url: true
        //     },
        //     facebook: {
        //       required: true,
        //       url: true
        //     },
        //     google: {
        //       required: true,
        //       url: true
        //     },
        //     linkedin: {
        //       required: true,
        //       url: true
        //     }
        //   }
        // });
        // console.log('$this', $this)
      });
      // Function to display error messages
      function displayErrors(errors) 
      {
        // Clear all error messages and reset input outlines
        document.querySelectorAll('.error-message').forEach(function(el) {
            el.innerHTML = "";
        });
    
        document.querySelectorAll('input, select').forEach(function(input) {
            input.style.outline = ''; // Reset outline
        });
    
        // Display errors and apply red outline to the relevant input fields
        errors.forEach(function(err) {
            var errorContainer = document.getElementById(err.field + '-error');
            var inputField = document.getElementById(err.field);
    
            if (errorContainer) {
                
                errorContainer.innerHTML += `<p style="color: #ea5455;font-size: 0.857rem;">${err.message}</p>`;
            }
    
            if (inputField) {
                inputField.classList.add('error')
            }
        });
    }

    // Function to remove error message when user starts typing and reset outline
function removeErrorOnInput(fieldId, minLength) {
    var inputField = document.getElementById(fieldId);
    var errorContainer = document.getElementById(fieldId + '-error');

    inputField.addEventListener('input', function() {
        

        // If the field has a minimum length requirement, remove error once the condition is met
        if (minLength && inputField.value.length >= minLength) {
            errorContainer.innerHTML = ""; // Clear error related to length

             // Clear error message if it exists
            errorContainer.innerHTML = "";

            // Reset outline
            inputField.classList.remove('error');
        }
    });
}

// Attach event listeners to remove errors when the user starts typing
removeErrorOnInput('nama_usaha', 5);
removeErrorOnInput('alamat', 10);

$('#select2-kabupaten_kota').on('change', function() {
    var selectedKabupatenKotaId = $(this).val()
    if(selectedKabupatenKotaId != "" || selectedKabupatenKotaId != null){
        var inputField = document.getElementById("select2-kabupaten_kota");
        var errorContainer = document.getElementById("select2-kabupaten_kota-error")

        errorContainer.innerHTML = ""; // Clear error related to length
        inputField.classList.remove('error');
    }
})

$('#select2-provinsi').on('change', function() {
  var selectedProvinsi = $(this).val()
  if(selectedProvinsi != "" || selectedProvinsi != null){
      var inputField = document.getElementById("select2-provinsi");
      var errorContainer = document.getElementById("select2-provinsi-error")

      errorContainer.innerHTML = ""; // Clear error related to length
      inputField.classList.remove('error');
  }
})
    

      $(horizontalWizard)
        .find('.btn-next')
        .each(function () {
          $(this).on('click', function (e) {
            e.preventDefault()
            var isValid = $(this).parent().siblings('form').valid();

            // console.log('numberedStepper', numberedStepper._currentIndex)
            var formStep = numberedStepper._currentIndex
            var sectionBlock = $('.section-block-cek_data_table')
            //Step 1
            if(formStep == 0)
            {
                var nama_usaha = document.getElementById('nama_usaha').value
                var alamat = document.getElementById('alamat').value
                var provinsi = document.getElementById('select2-provinsi').value
                var kabkot  = document.getElementById('select2-kabupaten_kota').value
                var kecamatan = document.getElementById('select2-kecamatan').value
                var kelurahan_desa  = document.getElementById('select2-kelurahan_desa').value

                var error = []
                //Rule Nama
                if (nama_usaha == null || nama_usaha.trim() === "") {
                    error.push({
                        field: 'nama_usaha',
                        message: "Nama usaha tidak boleh kosong dan tidak boleh kurang dari 5 karakter"
                    });
                }else if (nama_usaha.length < 5){
                    error.push({
                        field: 'nama_usaha',
                        message: "Nama usaha tidak boleh kurang dari 5 karakter"
                    });
                }

                //Rule Alamat
                if(alamat == null || alamat.trim() == ""){
                    error.push({
                        field: 'alamat',
                        message: "Alamat tidak boleh kosong dan tidak boleh kurang dari 10 karakter"
                    });
                }else if(alamat.length < 10){
                    error.push({
                        field: 'alamat',
                        message: "Alamat tidak boleh kurang dari 10 karakter"
                    });
                }

                //Rule Kabupaten_Kota
                if(kabkot == null || kabkot == ""){
                    error.push({
                        field: 'select2-kabupaten_kota',
                        message: "Kabupaten/Kota tidak boleh kosong"
                    });
                }

                if(provinsi == null || provinsi == '') {
                  error.push({
                    field: 'select2-provinsi',
                    message: "Provinsi tidak boleh kosong"
                  })
                }

                if (error.length > 0) {
                    displayErrors(error);
                    e.preventDefault();
                }
                else{
                    if (isValid) {
                        //Set Data
                        // Get the values from the input fields
                        var nama_usaha = document.getElementById('nama_usaha').value;
                        var alamat = document.getElementById('alamat').value;

                        // Get the selected option text for select2 dropdowns
                        var provinsiText = $('#select2-provinsi').select2('data')[0].text;
                        var kabkotText = $('#select2-kabupaten_kota').select2('data')[0].text;
                        var kecamatanText = $('#select2-kecamatan').select2('data')[0].text;
                        var kelurahanDesaText = $('#select2-kelurahan_desa').select2('data')[0].text;

                        // Set the values into the corresponding elements
                        document.querySelector('.nama-step1-cek-usaha').textContent = nama_usaha;
                        document.querySelector('.alamat-step1-cek-usaha').textContent = alamat;
                        document.querySelector('.provinsi-step1-cek-usaha').textContent = provinsi ? provinsiText : '';
                        document.querySelector('.kabupaten_kota-step1-cek-usaha').textContent = kabkot ? kabkotText : '';
                        document.querySelector('.kecamatan-step1-cek-usaha').textContent = kecamatan ? kecamatanText : '';
                        document.querySelector('.kelurahan_desa-step1-cek-usaha').textContent = kelurahan_desa ? kelurahanDesaText : '';
                        //get Fulltext Data
                        // Make AJAX request to get Kelurahan/Desa based on Kecamatan ID
                        $.ajax({
                          url: getDataFulltextUrl, // Define your route
                          type: 'GET',
                          data: {
                              nama_usaha: nama_usaha,
                              alamat : alamat,
                              provinsi_id : provinsi,
                              kabkot_id : kabkot,
                              kecamatan_id : kecamatan,
                              kelurahan_desa_id : kelurahan_desa
                          },
                          beforeSend: function() {
                            sectionBlock.block({
                                  message: '<div class="d-flex justify-content-center align-items-center"><p class="me-50 mb-0">Mohon Menunggu...</p></div> <div class="spinner-border text-white" role="status">',
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
                                  centerY: false,  // Ensures vertical centering
                                });
                        },
                          success: function(data) {
                             // Unblock the section once data is loaded
                              sectionBlock.unblock();
                              //Build Datatable in id cek_data_table
                              buildDataTable(data);
                             
                          },
                          error: function(err) {
                            
                              alert('Unable to load data.');
                          }
                      });
                        numberedStepper.next();
                      } else {
                        // Unblock the section even if there’s an error
                        sectionBlock.unblock();
                        e.preventDefault();
                      }
                }
            }

            //Step 1
            if (formStep == 1) {
              numberedStepper.next();
            }
            
          });
        });

        // Function to initialize DataTable
function buildDataTable(data) {
  // Check if the DataTable already exists and destroy it before re-initializing
  if ($.fn.DataTable.isDataTable('#cek_data_table')) {
      $('#cek_data_table').DataTable().clear().destroy();
  }

  // Initialize DataTable
  $('#cek_data_table').DataTable({
        language: {
          emptyTable: "Tidak ada Data yang tersedia"
      },
      data: data, // Pass the data from the server
      columns: [
          { title: "Kode", data: "idsbr" },
          { title: "Nama", data: "nama" },
          { title: "Alamat", data: "alamat" },
          {
            data: null,
            title: 'Provinsi',
            render: function(data, type, row) {
                // Generate buttons with actions
                // return `${row.provinsi_nama}-${row.kabupaten_kota_nama}-${row.kecamatan_nama}-${row.kelurahan_desa_nama} `;
                return `${row.provinsi_kode}-${row.provinsi_nama}`;
            }
          },
          {
            data: null,
            title: 'Kabupaten/Kota',
            render: function(data, type, row) {
                // Generate buttons with actions
                // return `${row.provinsi_nama}-${row.kabupaten_kota_nama}-${row.kecamatan_nama}-${row.kelurahan_desa_nama} `;
                return `${row.kabupaten_kode}-${row.kabupaten_nama}`;
            }
          },
          {
              data: null,
              title: 'Kecamatan',
              render: function(data, type, row) {
                  // Return null if either kecamatan_kode or kecamatan_nama is null
                  if (row.kecamatan_kode === null || row.kecamatan_nama === null) {
                      return null;
                  }
                  return `${row.kecamatan_kode}-${row.kecamatan_nama}`;
              }
          },
          {
              data: null,
              title: 'Kelurahan/Desa',
              render: function(data, type, row) {
                  // Return null if either kelurahan_kode or kelurahan_nama is null
                  if (row.kelurahan_kode === null || row.kelurahan_nama === null) {
                      return null;
                  }
                  return `${row.kelurahan_kode}-${row.kelurahan_nama}`;
              }
          },
          {
            data: "skor_kalo",
            title: 'Skor',
          }
          // Define other columns as needed
      ],
      // Additional options for DataTable
      processing: true,
      serverSide: false, // Set to true if you are using server-side processing
      paging: true,
      searching: true,
      ordering: true,
      order: [[7, 'desc']]
  });
}


  
      $(horizontalWizard)
        .find('.btn-prev')
        .on('click', function (event) {
          event.preventDefault()
          numberedStepper.previous();
        });
  
      $(horizontalWizard)
        .find('.btn-submit')
        .on('click', function (event) {
          event.preventDefault()
          
          var nama_usaha = $('#nama_usaha').val();
          var alamat = $('#alamat').val();
          var provinsi = $('#select2-provinsi').val();
          var kabkot = $('#select2-kabupaten_kota').val();
          var kecamatan = $('#select2-kecamatan').val();
          var kelurahan_desa = $('#select2-kelurahan_desa').val();
          console.log('buttonState', buttonState)
          //if buttonState true do something, else show Sweetalert Error

          if (buttonState) {
            // Perform the desired action when buttonState is true
                  $.ajax({
                beforeSend: function() {
                    // Show SweetAlert loading indicator
                    Swal.fire({
                        title: 'Mohon Menunggu...',
                        text: 'Memproses pengiriman Data',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                url: createPostURL,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),  // CSRF token
                    nama_usaha: nama_usaha,
                    alamat: alamat,
                    provinsi: provinsi,
                    kabkot: kabkot,
                    kecamatan: kecamatan,
                    kelurahan_desa: kelurahan_desa
                },
                success: function(response) {
                    // Hide loading and show success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data telah disimpan!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Clear local storage
                        localStorage.removeItem('nama_usaha');
                        localStorage.removeItem('alamat');
                        localStorage.removeItem('select2-kabupaten_kota');
                        localStorage.removeItem('select2-kecamatan');
                        localStorage.removeItem('select2-kelurahan_desa');
                        // Redirect to the response URL
                        window.location = response.redirect_url;
                    });
                },
                error: function(xhr) {
                    // Hide loading and show error notification
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan. Silakan coba lagi.',
                    });
                    console.error('Error:', xhr);
                }
            });
        
            // Add your action code here
        } else {
            // Show SweetAlert error if buttonState is false
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Silakan centang kotak untuk mengonfirmasi bahwa usaha yang ingin Anda tambahkan tidak ada dalam daftar berikut.'
            });
        }

        
        });
    }
  
    // Vertical Wizard
    // --------------------------------------------------------------------
    if (typeof verticalWizard !== undefined && verticalWizard !== null) {
      var verticalStepper = new Stepper(verticalWizard, {
        linear: false
      });
      $(verticalWizard)
        .find('.btn-next')
        .on('click', function () {
          verticalStepper.next();
        });
      $(verticalWizard)
        .find('.btn-prev')
        .on('click', function () {
          verticalStepper.previous();
        });
  
      $(verticalWizard)
        .find('.btn-submit')
        .on('click', function () {
          alert('Submitted..!!');
        });
    }
  
    // Modern Wizard
    // --------------------------------------------------------------------
    if (typeof modernWizard !== undefined && modernWizard !== null) {
      var modernStepper = new Stepper(modernWizard, {
        linear: false
      });
      $(modernWizard)
        .find('.btn-next')
        .on('click', function () {
          console.log('tes1')
          modernStepper.next();
        });
      $(modernWizard)
        .find('.btn-prev')
        .on('click', function () {
          modernStepper.previous();
        });
  
      $(modernWizard)
        .find('.btn-submit')
        .on('click', function () {
          alert('Submitted..!!');
        });
    }
  
    // Modern Vertical Wizard
    // --------------------------------------------------------------------
    if (typeof modernVerticalWizard !== undefined && modernVerticalWizard !== null) {
      var modernVerticalStepper = new Stepper(modernVerticalWizard, {
        linear: false
      });
      $(modernVerticalWizard)
        .find('.btn-next')
        .on('click', function () {
          modernVerticalStepper.next();
        });
      $(modernVerticalWizard)
        .find('.btn-prev')
        .on('click', function () {
          modernVerticalStepper.previous();
        });
  
      $(modernVerticalWizard)
        .find('.btn-submit')
        .on('click', function () {
          alert('Submitted..!!');
        });
    }
  });
  