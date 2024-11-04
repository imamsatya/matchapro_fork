@extends('matchapro/layouts/contentLayoutMaster')

@section('title', 'FAQ')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-faq.css')) }}">
@endsection

@section('content')
<!-- search header -->
<section id="faq-search-filter">
  <div class="card faq-search" style="background-image: url('{{asset('images/banner/banner.png')}}')">
    <div class="card-body text-center">
      <!-- main title -->
      <h2 class="text-primary">Let's answer some questions</h2>

      <!-- subtitle -->
      <p class="card-text mb-2">or choose a category to quickly find the help you need</p>

      <!-- search input -->
      <form class="faq-search-input">
        <div class="input-group input-group-merge">
          <div class="input-group-text">
            <i data-feather="search"></i>
          </div>
          <input type="text" class="form-control" placeholder="Search faq..." />
        </div>
      </form>
    </div>
  </div>
</section>
<!-- /search header -->

<!-- frequently asked questions tabs pills -->
<section id="faq-tabs">
  <!-- vertical tab pill -->
  <div class="row">
    <div class="col-lg-3 col-md-4 col-sm-12">
      <div class="faq-navigation d-flex justify-content-between flex-column mb-2 mb-md-0">
        <!-- pill tabs navigation -->
        <ul class="nav nav-pills nav-left flex-column" role="tablist">
          <!-- payment -->
          <li class="nav-item">
            <a
              class="nav-link active"
              id="payment"
              data-bs-toggle="pill"
              href="#faq-payment"
              aria-expanded="true"
              role="tab"
            >
              <i data-feather="users" class="font-medium-3 me-1"></i>
              <span class="fw-bold">Manage User</span>
            </a>
          </li>

          <!-- delivery -->
          <li class="nav-item">
            <a
              class="nav-link"
              id="delivery"
              data-bs-toggle="pill"
              href="#faq-delivery"
              aria-expanded="false"
              role="tab"
            >
              <i data-feather="file-text" class="font-medium-3 me-1"></i>
              <span class="fw-bold">Profiling</span>
            </a>
          </li>

          <!-- cancellation and return -->
          <li class="nav-item" style="display: none">
            <a
              class="nav-link"
              id="cancellation-return"
              data-bs-toggle="pill"
              href="#faq-cancellation-return"
              aria-expanded="false"
              role="tab"
            >
              <i data-feather="refresh-cw" class="font-medium-3 me-1"></i>
              <span class="fw-bold">Cancellation & Return</span>
            </a>
          </li>

          <!-- my order -->
          <li class="nav-item" style="display: none">
            <a
              class="nav-link"
              id="my-order"
              data-bs-toggle="pill"
              href="#faq-my-order"
              aria-expanded="false"
              role="tab"
            >
              <i data-feather="package" class="font-medium-3 me-1"></i>
              <span class="fw-bold">My Orders</span>
            </a>
          </li>

          <!-- product and services-->
          <li class="nav-item" style="display: none">
            <a
              class="nav-link"
              id="product-services"
              data-bs-toggle="pill"
              href="#faq-product-services"
              aria-expanded="false"
              role="tab"
            >
              <i data-feather="settings" class="font-medium-3 me-1"></i>
              <span class="fw-bold">Product & Services</span>
            </a>
          </li>
        </ul>

        <!-- FAQ image -->
        <img
          src="{{asset('images/illustration/faq-illustrations.svg')}}"
          class="img-fluid d-none d-md-block"
          alt="demand img"
        />
      </div>
    </div>

    <div class="col-lg-9 col-md-8 col-sm-12">
      <!-- pill tabs tab content -->
      <div class="tab-content">
        <!-- payment panel -->
        <div role="tabpanel" class="tab-pane active" id="faq-payment" aria-labelledby="payment" aria-expanded="true">
          <!-- icon and header -->
          <div class="d-flex align-items-center">
            <div class="avatar avatar-tag bg-light-primary me-1">
              <i data-feather="credit-card" class="font-medium-4"></i>
            </div>
            <div>
              <h4 class="mb-0">Manage User</h4>
              <span>Pertanyaan seputar manajemen user</span>
            </div>
          </div>

          <!-- frequent answer and question  collapse  -->
          <div class="accordion accordion-margin mt-2" id="faq-payment-qna">
            <div class="card accordion-item">
              <h2 class="accordion-header" id="paymentOne">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-payment-one"
                  aria-expanded="false"
                  aria-controls="faq-payment-one"
                >
                  Siapa yang bisa menambahkan user baru?
                </button>
              </h2>

              <div
                id="faq-payment-one"
                class="collapse accordion-collapse"
                aria-labelledby="paymentOne"
                data-bs-parent="#faq-payment-qna"
              >
                <div class="accordion-body">
                  User dengan role <strong>VIEWER</strong>
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="paymentTwo">
                <button
                  class="accordion-button"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-payment-two"
                  aria-expanded="true"
                  aria-controls="faq-payment-two"
                >
                  Bagaimana cara menambahkan user baru?
                </button>
              </h2>
              <div
                id="faq-payment-two"
                class="collapse show"
                aria-labelledby="paymentTwo"
                data-bs-parent="#faq-payment-qna"
              >
                <div class="accordion-body">
                  <ol>
                    <li>Akses halaman <strong>Manage User</strong></li>
                    <li>Tekan tombol  <a href="javascript:void(0)">
                            <button class="dt-button btn btn-sm btn-primary waves-effect waves-light"><i data-feather="plus"
                                    class="me-25"></i>
                                <span>USER</span>
                            </button>
                        </a>
                    </li>
                    <li class="mb-1">
                        <p>Isi form tambah user. Pada pilihan Provinsi dan Kabupaten/Kota 
                            isikan sesuai dengan satker community user yang akan ditambahkan.                            
                        </p>
                        <img width="100%" src="{{ asset('images/faq/muser_add_form.png') }}" alt="Form Add User">
                    </li>
                    <li class="mb-1">
                        Jika user baru berhasil ditambahkan akan muncul notifikasi dan user tersebut akan muncul pada baris pertama di tabel user.
                        <img width="100%" src="{{ asset('images/faq/muser_add_after.png') }}" alt="Berhasil tambah user">
                    </li>
                    <li>User yang baru ditambahkan belum memiliki hak akses terhadap usaha di wilayah manapun. 
                        Perhatikan kolom <strong>Hak Akses Wilayah</strong> memiliki informasi <span class="badge bg-light-danger">Belum Di-assign</span>                                                
                    </li>
                    <li>Berikan hak akses wilayah pada user dengan menekan tombol 
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Hak Akses Wilayah" href="javascript:void(0);" class="btn btn-sm btn-flat-primary me-0 edit-user-wilayah">
                            <i data-feather="edit"></i>
                        </a> 
                    </li>
                    <li class="mb-1">
                        Isi form edit hak akses wilayah
                        <img width="100%" src="{{ asset('images/faq/muser_akses.png') }}" alt="Hak Akses Wilayah">
                    </li>
                    <li>Selesai</li>
                  </ol>
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="paymentThree">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-payment-three"
                  aria-expanded="false"
                  aria-controls="faq-payment-three"
                >
                  Bagaimana cara memperbarui hak akses wilayah user?
                </button>
              </h2>
              <div
                id="faq-payment-three"
                class="collapse"
                aria-labelledby="paymentThree"
                data-bs-parent="#faq-payment-qna"
              >
                <div class="accordion-body">
                    Tekan tombol <a data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Hak Akses Wilayah" href="javascript:void(0);" class="btn btn-sm btn-flat-primary me-0 edit-user-wilayah">
                            <i data-feather="edit"></i>
                        </a> 
                    pada row user yang akan diedit.
                    <img width="100%" src="{{ asset('images/faq/muser_add_after.png') }}" alt="Berhasil tambah user">                  
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="paymentFour">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-payment-four"
                  aria-expanded="false"
                  aria-controls="faq-payment-four"
                >
                  Bagaimana cara memperbarui informasi terkait user ?
                </button>
              </h2>
              <div
                id="faq-payment-four"
                class="collapse accordion-collapse"
                aria-labelledby="paymentFour"
                data-bs-parent="#faq-payment-qna"
              >
                <div class="accordion-body">
                Tekan tombol <a data-bs-toggle="tooltip" data-bs-placement="top" title="Edit User Info" href="javascript:void(0);" class="btn btn-sm btn-flat-warning me-0 edit-user" data-user-id="${data.id}">
                    <i data-feather="user"></i>
                </a> 
                    pada row user yang akan diedit.
                    <img width="100%" src="{{ asset('images/faq/muser_add_after.png') }}" alt="Berhasil tambah user">                  
                </div>
              </div>
            </div>
            <div class="card accordion-item" style="display:none">
              <h2 class="accordion-header" id="paymentFive">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-payment-five"
                  aria-expanded="false"
                  aria-controls="faq-payment-five"
                >
                  Which license do I need for an end product that is only accessible to paying users?
                </button>
              </h2>
              <div
                id="faq-payment-five"
                class="collapse accordion-collapse"
                aria-labelledby="paymentFive"
                data-bs-parent="#faq-payment-qna"
              >
                <div class="accordion-body">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                  dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                  aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                  dolore eu fugiat nulla pariatur.
                </div>
              </div>
            </div>
            <div class="card accordion-item" style="display:none">
              <h2 class="accordion-header" id="paymentSix">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-payment-six"
                  aria-expanded="false"
                  aria-controls="faq-payment-six"
                >
                  Which license do I need to use an item in a commercial?
                </button>
              </h2>
              <div
                id="faq-payment-six"
                class="collapse accordion-collapse"
                aria-labelledby="paymentSix"
                data-bs-parent="#faq-payment-qna"
              >
                <div class="accordion-body">
                  At tempor commodo ullamcorper a lacus vestibulum. Ultrices neque ornare aenean euismod. Dui vivamus
                  arcu felis bibendum. Turpis in eu mi bibendum neque egestas congue. Nullam ac tortor vitae purus
                  faucibus ornare suspendisse sed.
                </div>
              </div>
            </div>
            <div class="card accordion-item" style="display:none">
              <h2 class="accordion-header" id="paymentSeven">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-payment-seven"
                  aria-expanded="false"
                  aria-controls="faq-payment-seven"
                >
                  Can I re-distribute an item? What about under an Extended License?
                </button>
              </h2>
              <div
                id="faq-payment-seven"
                class="collapse"
                aria-labelledby="paymentSeven"
                data-bs-parent="#faq-payment-qna"
              >
                <div class="accordion-body">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                  dolore magna aliqua. Euismod lacinia at quis risus sed vulputate odio ut enim. Dictum at tempor
                  commodo ullamcorper a lacus vestibulum.
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- delivery panel -->
        <div class="tab-pane" id="faq-delivery" role="tabpanel" aria-labelledby="delivery" aria-expanded="false">
          <!-- icon and header -->
          <div class="d-flex align-items-center">
            <div class="avatar avatar-tag bg-light-primary me-1">
              <i data-feather="file-text" class="font-medium-4"></i>
            </div>
            <div>
              <h4 class="mb-0">Profiling</h4>
              <span>Pertanyaan seputar profiling</span>
            </div>
          </div>

          <!-- frequent answer and question  collapse  -->
          <div class="accordion accordion-margin mt-2" id="faq-delivery-qna">
            <div class="card accordion-item">
              <h2 class="accordion-header" id="deliveryOne">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-delivery-one"
                  aria-expanded="false"
                  aria-controls="faq-delivery-one"
                >
                  Apa itu profiling mandiri?
                </button>
              </h2>

              <div
                id="faq-delivery-one"
                class="collapse accordion-collapse"
                aria-labelledby="deliveryOne"
                data-bs-parent="#faq-delivery-qna"
              >
                <div class="accordion-body">
                  Profiling mandiri merupakan kegiatan updating data usaha secara mandiri. 
                  User dapat memperbarui seluruh data usaha yang ada di wilayah aksesnya (Tidak ada alokasi sampel usaha dari Tim SBR RI).                  
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="deliveryTwo">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-delivery-two"
                  aria-expanded="false"
                  aria-controls="faq-delivery-two"
                >
                  Apa itu profiling periodik?
                </button>
              </h2>
              <div
                id="faq-delivery-two"
                class="collapse accordion-collapse"
                aria-labelledby="deliveryTwo"
                data-bs-parent="#faq-delivery-qna"
              >
                <div class="accordion-body">
                  Profiling periodik merupakan kegiatan updating data usaha sesuai dengan alokasi yang diberikan oleh Tim SBR RI.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="deliveryThree">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-delivery-three"
                  aria-expanded="false"
                  aria-controls="faq-delivery-three"
                >
                  Siapa yang bisa melakukan edit usaha (profiling) ?
                </button>
              </h2>
              <div
                id="faq-delivery-three"
                class="collapse"
                aria-labelledby="deliveryThree"
                data-bs-parent="#faq-delivery-qna"
              >
                <div class="accordion-body">
                    User dengan role <strong>PROFILER</strong>
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="deliveryFour">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-delivery-four"
                  aria-expanded="false"
                  aria-controls="faq-delivery-four"
                >
                  Siapa yang bisa mengunduh data direktori usaha?
                </button>
              </h2>
              <div
                id="faq-delivery-four"
                class="collapse"
                aria-labelledby="deliveryFour"
                data-bs-parent="#faq-delivery-qna"
              >
                <div class="accordion-body">
                  User dengan role <strong>VIEWER</strong>
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="deliveryFive">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-delivery-five"
                  aria-expanded="false"
                  aria-controls="faq-delivery-five"
                >
                  Bagaimana memulai proses profiling mandiri?
                </button>
              </h2>
              <div
                id="faq-delivery-five"
                class="collapse"
                aria-labelledby="deliveryFive"
                data-bs-parent="#faq-delivery-qna"
              >
                <div class="accordion-body">
                  <ol>
                    <li>Akses menu <strong>Direktori Usaha</strong></li>
                    <li class="mb-1">Pada halaman direktori usaha tekan tombol
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" href="javascript:void(0)" class="btn btn-sm btn-flat-warning me-1">
                        <i data-feather="edit" class="font-medium-4"></i>    
                    </a>  
                    <img width="100%" src="{{ asset('images/faq/dir_usaha.png') }}" alt="Direktori Usaha">                             
                    </li>
                    <li class="mb-1">
                        Akan muncul alert konfirmasi. Tekan tombol <strong>Ya, edit!</strong> untuk melakukan proses edit usaha.
                        <img width="100%" src="{{ asset('images/faq/konfirmasi_edit.png') }}" alt="Konfirmasi Profiling Mandiri">
                    </li>
                    <li>
                        Selanjutnya sistem akan menampilkan form edit usaha. Lengkapi form dan tekan tombol <strong>Save Draft</strong> atau <strong>Submit Final</strong>
                        untuk menyimpan perubahan isian.
                        <img width="100%" src="{{ asset('images/faq/form_edit_usaha.png') }}" alt="Form Edit Usaha">  
                    </li>                    
                  </ol>
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="deliverySix">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-delivery-six"
                  aria-expanded="false"
                  aria-controls="faq-delivery-six"
                >
                  Bagaimana memulai proses profiling periodik?
                </button>
              </h2>
              <div
                id="faq-delivery-six"
                class="collapse"
                aria-labelledby="deliverySix"
                data-bs-parent="#faq-delivery-qna"
              >
                <div class="accordion-body">
                    <ol>
                        <li>Akses menu <strong>Profiling</strong></li>
                        <li class="mb-1">Pada halaman profiling, perhatikan tab <strong>Profiling Periodik</strong>
                            <img width="100%" src="{{ asset('images/faq/hal_profiling.png') }}" alt="Halaman Profiling">                             
                        </li>
                        <li class="mb-1">Seluruh sampel yang teralokasikan kepada user akan muncul pada tabel profiling. Tekan tombol 
                            <button type="button" class="btn btn-icon btn-flat-primary btn-lg" 
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i data-feather="edit" width="40" height="40"></i>
                            </button> untuk memulai proses edit usaha.
                            <img class="mt-1" width="100%" src="{{ asset('images/faq/list_profiling.png') }}" alt="Halaman Profiling - List Profiling">
                        </li>
                        <li>
                            Selanjutnya sistem akan menampilkan form edit usaha. Lengkapi form dan tekan tombol <strong>Save Draft</strong> atau <strong>Submit Final</strong>
                            untuk menyimpan perubahan isian.
                            <img width="100%" src="{{ asset('images/faq/form_edit_usaha.png') }}" alt="Form Edit Usaha">  
                        </li>                
                    </ol>
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="deliverySeven">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-delivery-seven"
                  aria-expanded="false"
                  aria-controls="faq-delivery-seven"
                >
                  Siapa yang melakukan proses Approval data setelah data di-SUBMIT oleh user
                  dan kapan proses Approval dilakukan ?
                </button>
              </h2>
              <div
                id="faq-delivery-seven"
                class="collapse"
                aria-labelledby="deliverySeven"
                data-bs-parent="#faq-delivery-qna"
              >
                <div class="accordion-body">
                    <p>Proses APPROVAL dilakukan oleh <strong>Tim SBR RI</strong></p>
                    <ul>
                        <li>Pada <strong>Profiling Mandiri</strong> proses approval dilakukan di-bulan selanjutnya setelah proses <strong>SUBMITTED</strong>
                        <br>
                            Contoh: user melakukan submit data tanggal 3 Januari, maka proses approval akan dilakukan di-awal bulan Februari.
                        </li>
                        <li>Pada <strong>Profiling Periodik</strong> proses approval dilakukan di-bulan selanjutnya setelah periode profiling habis.
                        <br>
                            Contoh: Periode profiling periodik dibuka dari Oktober - Desember 2024. Maka proses approval akan dilakukan di bulan Januari 2025.
                        </li>
                    </ul>                    
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="deliveryEight">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-delivery-eight"
                  aria-expanded="false"
                  aria-controls="faq-delivery-eight"
                >
                  Bisakah membatalkan proses profiling mandiri dan bagaimana caranya?
                </button>
              </h2>
              <div
                id="faq-delivery-eight"
                class="collapse"
                aria-labelledby="deliveryEight"
                data-bs-parent="#faq-delivery-qna"
              >
                <div class="accordion-body">
                    User bisa membatalkan proses profiling mandiri selama status nya 
                    <strong>OPEN</strong> & <strong>DRAFT</strong> 
                    <ol>
                        <li>Akses menu <strong>Profiling</strong></li>
                        <li class="mb-1">Pada halaman profiling, perhatikan tab <strong>Profiling Mandiri</strong>
                            <img width="100%" src="{{ asset('images/faq/profiling_mandiri.png') }}" alt="Halaman Profiling Mandiri">                             
                        </li>
                        <li class="mb-1">Seluruh sampel yang teralokasikan kepada user akan muncul pada tabel profiling. Tekan tombol 
                            <button type="button" class="btn btn-icon btn-flat-danger btn-lg"                                                     
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="Cancel">
                                <i data-feather="x" width="40" height="40"></i>
                            </button>
                             untuk membatalkan profiling mandiri pada usaha terpilih.
                            <img class="mt-1" width="100%" src="{{ asset('images/faq/cancel_mandiri.png') }}" alt="Cancel Mandiri">
                        </li>
                        <li class="mb-1">
                            Selanjutnya sistem akan menampilkan form konfirmasi untuk membatalkan profiling mandiri. Tekan tombol <strong>Iya, Saya Yakin!</strong> 
                            untuk mengkonfirmasi proses pembatalan profiling mandiri
                            <img width="100%" src="{{ asset('images/faq/konfirmasi_cancel.png') }}" alt="Konfirmasi Cancel Profiling Mandiri">  
                        </li> 
                        <li>
                            Usaha yang berhasil dibatalkan akan memiliki status 
                            <span class="badge rounded-pill badge-light-danger">CANCELED</span>
                            <img width="100%" src="{{ asset('images/faq/list_cancel.png') }}" alt="Usaha Cancel Profiling Mandiri">  
                        </li>
                    </ol>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- cancellation return  -->
        <div
          class="tab-pane"
          id="faq-cancellation-return"
          role="tabpanel"
          aria-labelledby="cancellation-return"
          aria-expanded="false"
        >
          <!-- icon and header -->
          <div class="d-flex align-items-center">
            <div class="avatar avatar-tag bg-light-primary me-1">
              <i data-feather="refresh-cw" class="font-medium-4"></i>
            </div>
            <div>
              <h4 class="mb-0">Cancellation & Return</h4>
              <span>Which license do I need?</span>
            </div>
          </div>

          <!-- frequent answer and question  collapse  -->
          <div class="accordion accordion-margin mt-2" id="faq-cancellation-qna">
            <div class="card accordion-item">
              <h2 class="accordion-header" id="cancellationOne">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-cancellation-one"
                  aria-expanded="false"
                  aria-controls="faq-cancellation-one"
                >
                  Can my security guard or neighbour receive my shipment if I am not available?
                </button>
              </h2>

              <div
                id="faq-cancellation-one"
                class="collapse"
                aria-labelledby="cancellationOne"
                data-bs-parent="#faq-cancellation-qna"
              >
                <div class="accordion-body">
                  Pastry pudding cookie toffee bonbon jujubes jujubes powder topping. Jelly beans gummi bears sweet roll
                  bonbon muffin liquorice. Wafer lollipop sesame snaps. Brownie macaroon cookie muffin cupcake candy
                  caramels tiramisu. Oat cake chocolate cake sweet jelly-o brownie biscuit marzipan. Jujubes donut
                  marzipan chocolate bar. Jujubes sugar plum jelly beans tiramisu icing cheesecake.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="cancellationTwo">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-cancellation-two"
                  aria-expanded="false"
                  aria-controls="faq-cancellation-two"
                >
                  How can I get the contact number of my delivery agent?
                </button>
              </h2>
              <div
                id="faq-cancellation-two"
                class="collapse"
                aria-labelledby="cancellationTwo"
                data-bs-parent="#faq-cancellation-qna"
              >
                <div class="accordion-body">
                  Sweet pie candy jelly. Sesame snaps biscuit sugar plum. Sweet roll topping fruitcake. Caramels
                  liquorice biscuit ice cream fruitcake cotton candy tart. Donut caramels gingerbread jelly-o
                  gingerbread pudding. Gummi bears pastry marshmallow candy canes pie. Pie apple pie carrot cake.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="cancellationThree">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-cancellation-three"
                  aria-expanded="false"
                  aria-controls="faq-cancellation-three"
                >
                  How can I cancel my shipment?
                </button>
              </h2>
              <div
                id="faq-cancellation-three"
                class="collapse"
                aria-labelledby="cancellationThree"
                data-bs-parent="#faq-cancellation-qna"
              >
                <div class="accordion-body">
                  Tart gummies dragée lollipop fruitcake pastry oat cake. Cookie jelly jelly macaroon icing jelly beans
                  soufflé cake sweet. Macaroon sesame snaps cheesecake tart cake sugar plum. Dessert jelly-o sweet
                  muffin chocolate candy pie tootsie roll marzipan. Carrot cake marshmallow pastry. Bonbon biscuit
                  pastry topping toffee dessert gummies. Topping apple pie pie croissant cotton candy dessert tiramisu.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="cancellationFour">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-cancellation-four"
                  aria-expanded="false"
                  aria-controls="faq-cancellation-four"
                >
                  I have received a defective/damaged product. What do I do?
                </button>
              </h2>
              <div
                id="faq-cancellation-four"
                class="collapse"
                aria-labelledby="cancellationFour"
                data-bs-parent="#faq-cancellation-qna"
              >
                <div class="accordion-body">
                  Cheesecake muffin cupcake dragée lemon drops tiramisu cake gummies chocolate cake. Marshmallow tart
                  croissant. Tart dessert tiramisu marzipan lollipop lemon drops. Cake bonbon bonbon gummi bears topping
                  jelly beans brownie jujubes muffin. Donut croissant jelly-o cake marzipan. Liquorice marzipan cookie
                  wafer tootsie roll. Tootsie roll sweet cupcake.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="cancellationFive">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-cancellation-five"
                  aria-expanded="false"
                  aria-controls="faq-cancellation-five"
                >
                  How do I change my delivery address?
                </button>
              </h2>
              <div
                id="faq-cancellation-five"
                class="collapse"
                aria-labelledby="cancellationFive"
                data-bs-parent="#faq-cancellation-qna"
              >
                <div class="accordion-body">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                  dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                  aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                  dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui
                  officia deserunt mollit anim id est laborum.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="cancellationSix">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-cancellation-six"
                  aria-expanded="false"
                  aria-controls="faq-cancellation-six"
                >
                  What documents do I need to carry for self-collection of my shipment?
                </button>
              </h2>
              <div
                id="faq-cancellation-six"
                class="collapse"
                aria-labelledby="cancellationSix"
                data-bs-parent="#faq-cancellation-qna"
              >
                <div class="accordion-body">
                  At tempor commodo ullamcorper a lacus vestibulum. Ultrices neque ornare aenean euismod. Dui vivamus
                  arcu felis bibendum. Turpis in eu mi bibendum neque egestas congue. Nullam ac tortor vitae purus
                  faucibus ornare suspendisse sed. Commodo viverra maecenas accumsan lacus vel facilisis volutpat est
                  velit. Tortor consequat id porta nibh. Id aliquet lectus proin nibh nisl condimentum id venenatis a.
                  Faucibus nisl tincidunt eget nullam non nisi. Enim nunc faucibus a pellentesque. Pellentesque diam
                  volutpat commodo sed egestas egestas fringilla phasellus. Nec nam aliquam sem et tortor consequat id.
                  Fringilla est ullamcorper eget nulla facilisi. Morbi tristique senectus et netus et.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="cancellationSeven">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-cancellation-seven"
                  aria-expanded="false"
                  aria-controls="faq-cancellation-seven"
                >
                  What are the timings for self-collecting shipments from the Delhivery Branch?
                </button>
              </h2>
              <div
                id="faq-cancellation-seven"
                class="collapse"
                aria-labelledby="cancellationSeven"
                data-bs-parent="#faq-cancellation-qna"
              >
                <div class="accordion-body">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                  dolore magna aliqua. Euismod lacinia at quis risus sed vulputate odio ut enim. Dictum at tempor
                  commodo ullamcorper a lacus vestibulum.
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- my order -->
        <div class="tab-pane" id="faq-my-order" role="tabpanel" aria-labelledby="my-order" aria-expanded="false">
          <!-- icon and header -->
          <div class="d-flex align-items-center">
            <div class="avatar avatar-tag bg-light-primary me-1">
              <i data-feather="package" class="font-medium-4"></i>
            </div>
            <div>
              <h4 class="mb-0">My Orders</h4>
              <span>Which license do I need?</span>
            </div>
          </div>

          <!-- frequent answer and question  collapse  -->
          <div class="accordion accordion-margin mt-2" id="faq-my-order-qna">
            <div class="card accordion-item">
              <h2 class="accordion-header" id="myOrderOne">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-my-order-one"
                  aria-expanded="false"
                  aria-controls="faq-my-order-one"
                >
                  Can I avail of an open delivery?
                </button>
              </h2>

              <div
                id="faq-my-order-one"
                class="collapse accordion-collapse"
                aria-labelledby="myOrderOne"
                data-bs-parent="#faq-my-order-qna"
              >
                <div class="accordion-body">
                  Pastry pudding cookie toffee bonbon jujubes jujubes powder topping. Jelly beans gummi bears sweet roll
                  bonbon muffin liquorice. Wafer lollipop sesame snaps. Brownie macaroon cookie muffin cupcake candy
                  caramels tiramisu. Oat cake chocolate cake sweet jelly-o brownie biscuit marzipan. Jujubes donut
                  marzipan chocolate bar. Jujubes sugar plum jelly beans tiramisu icing cheesecake.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="myOrderTwo">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-my-order-two"
                  aria-expanded="false"
                  aria-controls="faq-my-order-two"
                >
                  I haven’t received the refund of my returned shipment. What do I do?
                </button>
              </h2>
              <div
                id="faq-my-order-two"
                class="collapse accordion-collapse"
                aria-labelledby="myOrderTwo"
                data-bs-parent="#faq-my-order-qna"
              >
                <div class="accordion-body">
                  Sweet pie candy jelly. Sesame snaps biscuit sugar plum. Sweet roll topping fruitcake. Caramels
                  liquorice biscuit ice cream fruitcake cotton candy tart. Donut caramels gingerbread jelly-o
                  gingerbread pudding. Gummi bears pastry marshmallow candy canes pie. Pie apple pie carrot cake.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="myOrderThree">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-my-order-three"
                  aria-expanded="false"
                  aria-controls="faq-my-order-three"
                >
                  How can I ship my order to an international location?
                </button>
              </h2>
              <div
                id="faq-my-order-three"
                class="collapse"
                aria-labelledby="myOrderThree"
                data-bs-parent="#faq-my-order-qna"
              >
                <div class="accordion-body">
                  Tart gummies dragée lollipop fruitcake pastry oat cake. Cookie jelly jelly macaroon icing jelly beans
                  soufflé cake sweet. Macaroon sesame snaps cheesecake tart cake sugar plum. Dessert jelly-o sweet
                  muffin chocolate candy pie tootsie roll marzipan. Carrot cake marshmallow pastry. Bonbon biscuit
                  pastry topping toffee dessert gummies. Topping apple pie pie croissant cotton candy dessert tiramisu.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="myOrderFour">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-my-order-four"
                  aria-expanded="false"
                  aria-controls="faq-my-order-four"
                >
                  I missed the delivery of my order today. What should I do?
                </button>
              </h2>
              <div
                id="faq-my-order-four"
                class="collapse"
                aria-labelledby="myOrderFour"
                data-bs-parent="#faq-my-order-qna"
              >
                <div class="accordion-body">
                  Cheesecake muffin cupcake dragée lemon drops tiramisu cake gummies chocolate cake. Marshmallow tart
                  croissant. Tart dessert tiramisu marzipan lollipop lemon drops. Cake bonbon bonbon gummi bears topping
                  jelly beans brownie jujubes muffin. Donut croissant jelly-o cake marzipan. Liquorice marzipan cookie
                  wafer tootsie roll. Tootsie roll sweet cupcake.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="myOrderFive">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-my-order-five"
                  aria-expanded="false"
                  aria-controls="faq-my-order-five"
                >
                  The delivery of my order is delayed. What should I do?
                </button>
              </h2>
              <div
                id="faq-my-order-five"
                class="collapse"
                aria-labelledby="myOrderFive"
                data-bs-parent="#faq-my-order-qna"
              >
                <div class="accordion-body">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                  dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                  aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                  dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui
                  officia deserunt mollit anim id est laborum.
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- product services -->
        <div
          class="tab-pane"
          id="faq-product-services"
          role="tabpanel"
          aria-labelledby="product-services"
          aria-expanded="false"
        >
          <!-- icon and header -->
          <div class="d-flex align-items-center">
            <div class="avatar avatar-tag bg-light-primary me-1">
              <i data-feather="settings" class="font-medium-4"></i>
            </div>
            <div>
              <h4 class="mb-0">Product & Services</h4>
              <span>Which license do I need?</span>
            </div>
          </div>

          <!-- frequent answer and question  collapse  -->
          <div class="accordion accordion-margin mt-2" id="faq-product-qna">
            <div class="card accordion-item">
              <h2 class="accordion-header" id="productOne">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-product-one"
                  aria-expanded="false"
                  aria-controls="faq-product-one"
                >
                  How can I register a complaint against the courier executive who came to deliver my order?
                </button>
              </h2>

              <div
                id="faq-product-one"
                class="collapse accordion-collapse"
                aria-labelledby="productOne"
                data-bs-parent="#faq-product-qna"
              >
                <div class="accordion-body">
                  Pastry pudding cookie toffee bonbon jujubes jujubes powder topping. Jelly beans gummi bears sweet roll
                  bonbon muffin liquorice. Wafer lollipop sesame snaps. Brownie macaroon cookie muffin cupcake candy
                  caramels tiramisu. Oat cake chocolate cake sweet jelly-o brownie biscuit marzipan. Jujubes donut
                  marzipan chocolate bar. Jujubes sugar plum jelly beans tiramisu icing cheesecake.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="productTwo">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-product-two"
                  aria-expanded="false"
                  aria-controls="faq-product-two"
                >
                  The status for my shipment shows as ‘not picked up’. What do I do?
                </button>
              </h2>
              <div
                id="faq-product-two"
                class="collapse accordion-collapse"
                aria-labelledby="productTwo"
                data-bs-parent="#faq-product-qna"
              >
                <div class="accordion-body">
                  Sweet pie candy jelly. Sesame snaps biscuit sugar plum. Sweet roll topping fruitcake. Caramels
                  liquorice biscuit ice cream fruitcake cotton candy tart. Donut caramels gingerbread jelly-o
                  gingerbread pudding. Gummi bears pastry marshmallow candy canes pie. Pie apple pie carrot cake.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="productThree">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-product-three"
                  aria-expanded="false"
                  aria-controls="faq-product-three"
                >
                  How can I get a proof of delivery for my shipment?
                </button>
              </h2>
              <div
                id="faq-product-three"
                class="collapse"
                aria-labelledby="productThree"
                data-bs-parent="#faq-product-qna"
              >
                <div class="accordion-body">
                  Tart gummies dragée lollipop fruitcake pastry oat cake. Cookie jelly jelly macaroon icing jelly beans
                  soufflé cake sweet. Macaroon sesame snaps cheesecake tart cake sugar plum. Dessert jelly-o sweet
                  muffin chocolate candy pie tootsie roll marzipan. Carrot cake marshmallow pastry. Bonbon biscuit
                  pastry topping toffee dessert gummies. Topping apple pie pie croissant cotton candy dessert tiramisu.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="productFour">
                <button
                  class="accordion-button collapsed"
                  data-bs-toggle="collapse"
                  role="button"
                  data-bs-target="#faq-product-four"
                  aria-expanded="false"
                  aria-controls="faq-product-four"
                >
                  How can I avail your services?
                </button>
              </h2>
              <div
                id="faq-product-four"
                class="collapse accordion-collapse"
                aria-labelledby="productFour"
                data-bs-parent="#faq-product-qna"
              >
                <div class="accordion-body">
                  Cheesecake muffin cupcake dragée lemon drops tiramisu cake gummies chocolate cake. Marshmallow tart
                  croissant. Tart dessert tiramisu marzipan lollipop lemon drops. Cake bonbon bonbon gummi bears topping
                  jelly beans brownie jujubes muffin. Donut croissant jelly-o cake marzipan. Liquorice marzipan cookie
                  wafer tootsie roll. Tootsie roll sweet cupcake.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- / frequently asked questions tabs pills -->

<!-- contact us -->
<section class="faq-contact">
  <div class="row mt-5 pt-75">
    <div class="col-12 text-center">
      <h2>You still have a question?</h2>
      <p class="mb-3">
        If you cannot find a question in our FAQ, you can always contact us. We will answer to you shortly!
      </p>
    </div>
    <div class="col-sm-6">
      <div class="card text-center faq-contact-card shadow-none py-1">
        <div class="accordion-body">
          <div class="avatar avatar-tag bg-light-primary mb-2 mx-auto">
            <i data-feather="phone-call" class="font-medium-3"></i>
          </div>
          <h4>+6285791927509</h4>
          <span class="text-body">We are always happy to help!</span>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card text-center faq-contact-card shadow-none py-1">
        <div class="accordion-body">
          <div class="avatar avatar-tag bg-light-primary mb-2 mx-auto">
            <i data-feather="mail" class="font-medium-3"></i>
          </div>
          <h4>sekretariatsbr@bps.go.id</h4>
          <span class="text-body">If you prefer mailing us instead!</span>
        </div>
      </div>
    </div>
  </div>
</section>
<!--/ contact us -->
@endsection
