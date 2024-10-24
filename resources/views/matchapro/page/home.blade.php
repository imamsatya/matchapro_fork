@extends('matchapro/layouts/contentLayoutMaster')

@section('title', 'Home')

@section('page-style')
    <style>
        .h2-responsive {
            font-size: 48px;
        }

        @media (max-width: 576px) {
            .h2-responsive {
                font-size: 28px;
            }
        }

        .h4-responsive {
            font-size: 28px;
        }

        @media (max-width: 576px) {
            .h4-responsive {
                font-size: 16px;
            }
        }
    </style>
@endsection

@section('content')
    {{-- Welcome --}}
    <section class="welcome-section py-5 text-center" style="background: #78b34d; color: #fff;">
        <div class=" animate__animated animate__fadeInUp">
            <div class="card-body text-center">
                <!-- Avatar Section -->
                <!-- Uncomment this section if you'd like to add an icon back -->
                <!-- <div class="avatar avatar-xl shadow mb-4 d-none d-md-inline-flex" style="background:rgba(72, 139, 24, 0.7);">
                                                                                                                        <div class="avatar-content">
                                                                                                                            <i data-feather="award" class="font-large-1"></i>
                                                                                                                        </div>
                                                                                                                    </div> -->

                <!-- Heading and Text -->
                <div class="text-center">
                    <h1 class="mb-2 text-white" style="font-size: 2.5rem;">
                        Selamat Datang di MatchaPro
                    </h1>
                    <p class="card-text mx-auto" style="max-width: 90%; font-size: 1rem;">
                        Everything is so Matcha better with you
                    </p>
                </div>
            </div>
        </div>
    </section>

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" style="margin-top: -12px;">
        <path fill="#78b34d" fill-opacity="1"
            d="M0,160L26.7,138.7C53.3,117,107,75,160,90.7C213.3,107,267,181,320,197.3C373.3,213,427,171,480,154.7C533.3,139,587,149,640,133.3C693.3,117,747,75,800,80C853.3,85,907,139,960,170.7C1013.3,203,1067,213,1120,208C1173.3,203,1227,181,1280,176C1333.3,171,1387,181,1413,186.7L1440,192L1440,0L1413.3,0C1386.7,0,1333,0,1280,0C1226.7,0,1173,0,1120,0C1066.7,0,1013,0,960,0C906.7,0,853,0,800,0C746.7,0,693,0,640,0C586.7,0,533,0,480,0C426.7,0,373,0,320,0C266.7,0,213,0,160,0C106.7,0,53,0,27,0L0,0Z">
        </path>
    </svg>



    <!-- About Section -->
    <section class="about-section py-5">
        <div class="">
            <div class="row align-items-center animate__animated animate__fadeInLeft">
                <div class="col-md-6 d-flex justify-content-center">
                    <img style="transform: scaleX(-1);" src="{{ asset('images/illustration/pricing-illustration.svg') }}"
                        alt="About MatchaPro" class="img-fluid">
                </div>
                <div class="col-md-6 mt-2">
                    <h2 class="mb-3 h2-responsive" style=" color: #78b34d;"><i class="fas fa-info-circle"></i> Tentang
                        MatchaPro</h2>

                    <div class="card-text" style="font-size: 18px;">
                        <p>MatchaPro, atau Matching Assessment Profiling, adalah aplikasi yang dirancang untuk melakukan
                            profiling data SBR.
                            Kegiatan profiling dilakukan sebagai tahapan untuk mendapatkan gambaran secara utuh unit
                            statistik dan jaringan/struktur hubungan antar unit dalam group di Indonesia agar sejalan dengan
                            konsep System of National Account 2008 (SNA 2008).
                        </p>
                        {{-- <p>MatchaPro adalah alat yang dirancang untuk melakukan <strong>profiling data SBR</strong>, yang
                            mencakup:</p>
                        <ul>
                            <li><i class="fas fa-database text-success"></i> Memetakan unit statistik dalam jaringan grup
                            </li> <br>
                            <li><i class="fas fa-sitemap text-success"></i> Menyesuaikan dengan konsep <strong>SNA
                                    2008</strong>
                                dalam konteks Indonesia</li>
                        </ul> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Goals Section -->
    <section class="goals-section text-center py-5">
        <div class=" animate__animated animate__fadeInUp">
            <h2 class="h2-responsive" style="color: #78b34d;"><i class="fas fa-bullseye"></i> Tujuan
                MatchaPro</h2>
            <p class="mb-4" style="font-size: 18px;">Membantu SBR dalam memperbaharui dan melengkapi data profil
                usaha/perusahaan.</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4" style="border-left: 4px solid #78b34d;">
                        <div class="card-body">
                            <h4 class="card-title h4-responsive " style="color: #78b34d;"><i class="fas fa-chart-bar"></i>
                                Profil Perusahaan</h4>
                            <br>
                            <img style="transform: scaleX(-1);" src="{{ asset('images/pages/forgot-password.png') }}"
                                alt="About MatchaPro" class="img-fluid">
                            <br>

                        </div>
                        <div class="card-body">
                            <p class="card-text" style="font-size: 18px;">Memperoleh profil perusahaan secara
                                komprehensif
                                untuk analisis yang lebih
                                baik.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4" style="border-left: 4px solid #78b34d;">
                        <div class="card-body">
                            <h4 class="card-title h4-responsive" style="color: #78b34d;"><i class="fas fa-building"></i>
                                Direktori Perusahaan</h4> <br>
                            <img style="transform: scaleX(-1);height: 253px;"
                                src="{{ asset('images/pages/coming-soon.svg') }}" alt="About MatchaPro"
                                class="img-fluid"><br>


                        </div>
                        <div class="card-body">
                            <p class="card-text" style="font-size: 18px;">Mengumpulkan informasi terkini suatu
                                usaha/perusahaan sesuai dengan
                                kondisi terbaru perusahaan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" style="margin-bottom: -12px;">
        <path fill="#78b34d" fill-opacity="1"
            d="M0,160L26.7,138.7C53.3,117,107,75,160,90.7C213.3,107,267,181,320,197.3C373.3,213,427,171,480,154.7C533.3,139,587,149,640,133.3C693.3,117,747,75,800,80C853.3,85,907,139,960,170.7C1013.3,203,1067,213,1120,208C1173.3,203,1227,181,1280,176C1333.3,171,1387,181,1413,186.7L1440,192L1440,320L1413.3,320C1386.7,320,1333,320,1280,320C1226.7,320,1173,320,1120,320C1066.7,320,1013,320,960,320C906.7,320,853,320,800,320C746.7,320,693,320,640,320C586.7,320,533,320,480,320C426.7,320,373,320,320,320C266.7,320,213,320,160,320C106.7,320,53,320,27,320L0,320Z">
        </path>
    </svg>
    <!-- Get Started Section -->
    <section class="get-started-section py-5 text-center" style="background: #78b34d; color: #fff;">
        <div class=" animate__animated animate__fadeInUp">
            <h2 class="mb-1 text-white h2-responsive">Siap Memulai?</h2>
            <p class="lead">Langkah mudah untuk memulai profiling data SBR dengan MatchaPro.</p>
            <br>
            <a href="{{ route('profiling.index') }}">
                <button class="btn btn-relief-primary ">
                    <i data-feather="arrow-right" class="align-middle me-sm-25 me-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Mulai Sekarang</span>
                </button>
            </a>
        </div>
        <br><br>
    </section>

    <!-- External Resources -->
    @once
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    @endonce
@endsection
