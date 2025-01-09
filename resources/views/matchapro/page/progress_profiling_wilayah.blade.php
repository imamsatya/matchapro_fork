@extends('matchapro/layouts/contentLayoutMaster')

@section('title', 'Progress Profiling - Periodik')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')
    @if (!$wilayahAkses && $levelRole != 'PUSAT')
        <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
            <h4 class="alert-heading">Informasi</h4>
            <div class="alert-body">
                User belum memiliki akses terhadap wilayah manapun!
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row match-height">
        <div class="col-lg-8 col-md-8 col-12">
            <div class="card card-statistics">
                <div class="card-header">
                    <h4 class="card-title">Statistics Profiling</h4>
                    <div class="d-flex align-items-center">
                        <!-- <p class="card-text font-small-2 me-25 mb-0">Updated 1 month ago</p> -->
                    </div>
                </div>
                <div class="card-body statistics-body">
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-primary me-2">
                                    <div class="avatar-content">
                                        <i data-feather="trending-up" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0 open-count">{{ $status_usaha['OPEN'] }}</h4>
                                    <p class="card-text font-small-3 mb-0">Open</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-warning me-2">
                                    <div class="avatar-content">
                                        <i data-feather="edit-2" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0 draft-count">{{ $status_usaha['DRAFT'] }}</h4>
                                    <p class="card-text font-small-3 mb-0">Draft</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-info me-2">
                                    <div class="avatar-content">
                                        <i data-feather="send" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0 submitted-count">{{ $status_usaha['SUBMITTED'] }}</h4>
                                    <p class="card-text font-small-3 mb-0">Submitted</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-success me-2">
                                    <div class="avatar-content">
                                        <i data-feather="check-circle" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0 approved-count">{{ $status_usaha['APPROVED'] }}</h4>
                                    <p class="card-text font-small-3 mb-0">Approved</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card mb-6">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        FILTER DATA
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label class="form-label" for="provinsi">Provinsi</label>
                        <select id="provinsi" class="select2 form-select">
                            <option value="">-- All Provinsi --</option>
                            @foreach ($masterProvinsi as $option)
                                <option value="{{ $option->id }}">[{{ $option->kode }}] {{ $option->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6" id="container-kabupaten-kota">
                        <label class="form-label" for="kabupaten_kota">Kabupaten/Kota</label>
                        <select id="kabupaten_kota" class="select2 form-select">
                            <option value="">-- All Kabupaten/Kota --</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="tahun_referensi">Tahun Profiling <span
                                class="text-danger">*</span></label>
                        <select id="tahun_referensi" class="select2 form-select">
                            <option value="">-- Pilih Tahun --</option>
                            <option value="2003">2003</option>
                            @foreach ($tahun as $option)
                                <option value="{{ $option }}" {{ $option == date('Y') ? 'selected' : '' }}>
                                    {{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="tahun_referensi">Periode Profiling</label>
                        <select id="periode_opsi" class="select2 form-select">
                            <option value="">-- Pilih Periode --</option>
                            <option value="2003">2003</option>
                            @foreach ($tahun as $option)
                                <option value="{{ $option }}" {{ $option == date('Y') ? 'selected' : '' }}>
                                    {{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-1 d-grid">
                        <button class="btn btn-relief-primary" id="filter-progres">
                            <i data-feather="filter" class=""></i>
                            Filter</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row match-height">
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Profiling Overview</h4>
                    <i data-feather="help-circle" class="font-medium-3 text-muted cursor-pointer"></i>
                </div>
                <div class="card-body p-0">
                    <div id="goal-overview-radial-bar-chart" class="my-2"></div>
                    <div class="row border-top text-center mx-0">
                        <div class="col-6 border-end py-1">
                            <p class="card-text text-muted mb-0">Completed</p>
                            <h3 class="fw-bolder mb-0 completed-count">{{ $total_approved }}</h3>
                        </div>
                        <div class="col-6 py-1">
                            <p class="card-text text-muted mb-0">In Progress</p>
                            <h3 class="fw-bolder mb-0 inprogress-count">{{ $total_inprogress }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title wilayah-title">
                        {{ $levelRole == 'PUSAT' ? 'Profiling Progress Provinsi' : 'Profiling Progress Kab/Kota' }}</h4>
                    <i data-feather="help-circle" class="font-medium-3 text-muted cursor-pointer"></i>
                </div>
                <div class="card-body p-0">
                    <div id="progress-profiling-chart" class="my-2"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Profiling Progress User </h4>
                    <i data-feather="help-circle" class="font-medium-3 text-muted cursor-pointer"></i>
                </div>
                <div class="card-body p-0">
                    <div id="progress-profiling-chart-user" class="my-2"></div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Profiling Progress User</h4>
                    <i data-feather="help-circle" class="font-medium-3 text-muted cursor-pointer"></i>
                </div>
                <div class="card-body p-0">
                    <div id="progress-profiling-chart-user" class="my-2"></div>
                </div>
            </div>
        </div>
    </div> --}}

@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script>
        $(document).ready(function() {
            let statusStatisticsRoute = "{{ route('progress_wilayah.status_statistics') }}";

            $(".select2").select2();

            var $goalStrokeColor2 = '#51e5a8';
            var $strokeColor = '#ebe9f1';
            var $textHeadingColor = '#5e5873';
            var $goalOverviewChart = document.querySelector('#goal-overview-radial-bar-chart');
            var goalOverviewChartOptions = {
                chart: {
                    height: 245,
                    type: 'radialBar',
                    sparkline: {
                        enabled: true
                    },
                    dropShadow: {
                        enabled: true,
                        blur: 3,
                        left: 1,
                        top: 1,
                        opacity: 0.1
                    }
                },
                colors: [$goalStrokeColor2],
                plotOptions: {
                    radialBar: {
                        offsetY: -10,
                        startAngle: -150,
                        endAngle: 150,
                        hollow: {
                            size: '77%'
                        },
                        track: {
                            background: $strokeColor,
                            strokeWidth: '50%'
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                color: $textHeadingColor,
                                fontSize: '2.86rem',
                                fontWeight: '600'
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        shadeIntensity: 0.5,
                        gradientToColors: [window.colors.solid.success],
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100]
                    }
                },
                series: [{{ $total_target == 0 ? 0 : ($total_approved / $total_target) * 100 }}],
                stroke: {
                    lineCap: 'round'
                },
                grid: {
                    padding: {
                        bottom: 30
                    }
                }
            };
            var goalOverviewChart = new ApexCharts($goalOverviewChart, goalOverviewChartOptions);
            goalOverviewChart.render();


            // Create Stacked Bar Chart for Progress Profiling
            var progressProfilingChartOptions = {
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                    },
                },
                series: @json($kabkot_data),
                xaxis: {
                    categories: @json($kabkot_axis),
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                },
                fill: {
                    opacity: 1
                },
                colors: [window.colors.solid.primary, window.colors.solid.info, window.colors.solid.warning,
                    window.colors.solid.success
                ]
            };

            var progressProfilingChart = new ApexCharts(document.querySelector("#progress-profiling-chart"),
                progressProfilingChartOptions);
            progressProfilingChart.render();


            // Create Stacked Bar Chart for Progress Profiling
            var progresOptionsUser = {
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                    },
                },
                series: @json($user_data),
                xaxis: {
                    categories: @json($user_axis),
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                },
                fill: {
                    opacity: 1
                },
                colors: [window.colors.solid.primary, window.colors.solid.info, window.colors.solid.warning,
                    window.colors.solid.success
                ]
            };

            var progressProfilingChartUser = new ApexCharts(document.querySelector(
                    "#progress-profiling-chart-user"),
                progresOptionsUser);
            progressProfilingChartUser.render();


            $("#filter-progres").on('click', function() {
                let provinsi = $("#provinsi").val();
                let kabupaten = $("#kabupaten_kota").val();
                let tahun_profiling = $("#tahun_referensi").val();

                if (!tahun_profiling) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Tahun profilng harus terpilih!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                    return;
                }

                blockProgress('body');
                $.ajax({
                    url: '{{ route('progres_wilayah_data') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'provinsi': provinsi,
                        'kabupaten': kabupaten,
                        'tahun_profiling': tahun_profiling,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        //update title
                        if (kabupaten) {
                            $(".wilayah-title").text('Profiling Progress Kab/Kota');
                        } else {
                            $(".wilayah-title").text('Profiling Progress Provinsi');
                        }
                        // update status info
                        $('.open-count').html(response.status_usaha['OPEN']);
                        $('.draft-count').html(response.status_usaha['DRAFT']);
                        $('.submitted-count').html(response.status_usaha['SUBMITTED']);
                        $('.approved-count').html(response.status_usaha['APPROVED']);


                        // update chart lingkaran
                        $(".completed-count").html(response.total_approved);
                        $(".inprogress-count").html(response.total_inprogress);
                        goalOverviewChartOptions.series = response.total_target == 0 ? [0] : [((
                                response.total_approved / response.total_target) * 100)
                            .toFixed(2)
                        ];
                        goalOverviewChart = new ApexCharts($goalOverviewChart,
                            goalOverviewChartOptions);
                        goalOverviewChart.render();

                        // update bar chart                            
                        if (progressProfilingChart) {
                            progressProfilingChart.destroy();
                        }
                        progressProfilingChartOptions.xaxis.categories = response.kabkot_axis;
                        progressProfilingChartOptions.series = response.kabkot_data;
                        progressProfilingChart = new ApexCharts(document.querySelector(
                            "#progress-profiling-chart"), progressProfilingChartOptions);
                        progressProfilingChart.render();


                        // update bar chart user                        
                        if (progressProfilingChartUser) {
                            progressProfilingChartUser.destroy();
                        }
                        progresOptionsUser.xaxis.categories = response.user_axis;
                        progresOptionsUser.series = response.user_data;
                        progressProfilingChartUser = new ApexCharts(document.querySelector(
                            "#progress-profiling-chart-user"), progresOptionsUser);
                        progressProfilingChartUser.render();


                        unblockProgress('body');
                    },
                    error: function(er) {
                        unblockProgress('body');
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong!',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    }
                })
            })

            let kabupatenCache = [];
            $("#provinsi").on('change', function() {
                let provinsi = $(this).val();
                if (!provinsi) {
                    setOption($('#kabupaten_kota'), [{
                        id: '',
                        text: '-- All Kabupaten/Kota --'
                    }]);
                    return;
                }

                let findCache = kabupatenCache.find(kc => kc.provinsi == provinsi);
                if (findCache) {
                    setOption($('#kabupaten_kota'), findCache.kabupaten_kota);
                    return;
                }

                blockProgress($("#container-kabupaten-kota"));
                $.ajax({
                    url: "{{ route('wil-kabupaten-kota-user') }}",
                    type: 'POST',
                    data: {
                        provinsi: provinsi,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        let wilKab = [{
                            id: '',
                            text: '-- All Kabupaten/Kota --'
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
                        unblockProgress($("#container-kabupaten-kota"));
                    },
                    error: function(xhr) {
                        unblockProgress($("#container-kabupaten-kota"));
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong. Try again later!',
                            icon: 'error',
                        })
                    }
                })
            })

            function setOption(area, data) {
                // Clear existing options
                area.empty();
                // Append new options dynamically
                let temp = []
                $.each(data, function(index, option) {
                    var newOption = new Option(option.text, option.id, false, false);
                    temp.push(newOption);
                });
                area.append(temp).trigger('change');
            }

            function blockProgress(area) {
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
