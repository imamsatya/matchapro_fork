@extends('matchapro/layouts/contentLayoutMaster')

@section('title', 'Progress Profiling - Wilayah')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')

    <div class="row match-height">
        <div class="col-lg-8 col-md-8 col-12">
            <div class="card card-statistics">
                <div class="card-header">
                    <h4 class="card-title">Statistics Profiling</h4>
                    <div class="d-flex align-items-center">
                        {{-- <p class="card-text font-small-2 me-25 mb-0">Updated 1 month ago</p> --}}
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
                                    <h4 class="fw-bolder mb-0 open-count"></h4>
                                    <p class="card-text font-small-3 mb-0">Open</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-info me-2">
                                    <div class="avatar-content">
                                        <i data-feather="user" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0 draft-count"></h4>
                                    <p class="card-text font-small-3 mb-0">Draft</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-danger me-2">
                                    <div class="avatar-content">
                                        <i data-feather="box" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0 submitted-count"></h4>
                                    <p class="card-text font-small-3 mb-0">Submitted</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-success me-2">
                                    <div class="avatar-content">
                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0 approved-count"></h4>
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
                        <label class="form-label" for="provinsi">Provinsi <span class="text-danger">*</span></label>
                        <select id="provinsi" class="select2 form-select">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach ($provinsi as $option)
                                <option value="{{ $option->id }}">{{ $option->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6" id="container-kabupaten-kota">
                        <label class="form-label" for="kabupaten_kota">Kabupaten/Kota <span
                                class="text-danger">*</span></label>
                        <select id="kabupaten_kota" class="select2 form-select">
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                            @foreach ($kabupaten as $option)
                                <option value="{{ $option->id }}">{{ $option->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="tahun_referensi">Tahun Profiling <span
                                class="text-danger">*</span></label>
                        <select id="tahun_referensi" class="select2 form-select">
                            <option value="">-- Pilih Tahun --</option>
                            @foreach ($tahun as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
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
                            <h3 class="fw-bolder mb-0 completed-count"></h3>
                        </div>
                        <div class="col-6 py-1">
                            <p class="card-text text-muted mb-0">In Progress</p>
                            <h3 class="fw-bolder mb-0 inprogress-count"></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Profiling Progress User</h4>
                    <i data-feather="help-circle" class="font-medium-3 text-muted cursor-pointer"></i>
                </div>
                <div class="card-body p-0">
                    {{-- <div style="margin: 10px !important;">
                        <ul class="list-unstyled">
                            <li>Kalau User Level Pusat, yang ditampikan barchart provinsi</li>
                            <li>Kalau User Level Provinsi, yang ditampikan barchart kabupaten/kota</li>
                            <li>Kalau User Level Kabupaten/Kota, yang ditampikan barchart per user</li>
                        </ul>
                    </div> --}}
                    <div id="progress-profiling-chart" class="my-2"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
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
                series: [25],
                stroke: {
                    lineCap: 'round'
                },
                grid: {
                    padding: {
                        bottom: 30
                    }
                }
            };


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
                series: [{
                    name: 'OPEN',
                    data: [0, 55, 41, 67, 22, 43]
                }, {
                    name: 'DRAFT',
                    data: [13, 23, 20, 8, 13, 27]
                }, {
                    name: 'SUBMITTED',
                    data: [11, 17, 15, 15, 21, 14]
                }],
                xaxis: {
                    categories: ['Kab 1', 'Kab 2', 'Kab 3', 'Kab 4', 'Kab 5', 'Kab 6'],
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                },
                fill: {
                    opacity: 1
                },
                colors: [window.colors.solid.primary, window.colors.solid.info, window.colors.solid.warning]
            };

            // var progressProfilingChart = new ApexCharts(document.querySelector("#progress-profiling-chart"),
            //     progressProfilingChartOptions);
            // progressProfilingChart.render();

            //Imam
            $.ajax({
                url: statusStatisticsRoute, // Replace with your actual endpoint URL
                type: 'GET', // Use 'POST' if you need to send data to the server
                dataType: 'json', // Expected data type from the server
                success: function(response) {
                    // Handle the response data

                    updateStatusCounts(response.countGroup);
                    updateProfilingChart(response.label, response.series)
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error('Error:', error);
                    // alert('An error occurred while fetching the data.');
                }
            });




            function updateStatusCounts(countGroup) {
                // Reset counts
                var statusCounts = {
                    'OPEN': 0,
                    'DRAFT': 0,
                    'SUBMITTED': 0,
                    'APPROVED': 0,
                    'REJECTED': 0,
                    'CANCELED': 0,
                    'APPROVED': 0
                };

                // Populate counts based on the response data
                countGroup.forEach(function(status) {
                    statusCounts[status.status_form] = status.total;
                });
                let inprogress_count = parseInt(statusCounts['OPEN']) + parseInt(statusCounts['DRAFT'])
                let completed_count = parseInt(statusCounts['APPROVED'])
                let total = parseInt(statusCounts['OPEN']) + parseInt(statusCounts['DRAFT']) + parseInt(
                    statusCounts['SUBMITTED']) + parseInt(
                    statusCounts['REJECTED']) + parseInt(
                    statusCounts['APPROVED'])

                $('.open-count').text(statusCounts['OPEN']);
                $('.draft-count').text(statusCounts['DRAFT']);
                $('.submitted-count').text(statusCounts['SUBMITTED']);
                $('.approved-count').text(statusCounts['APPROVED']);
                $('.inprogress-count').text(parseInt(statusCounts['OPEN']) + parseInt(statusCounts['DRAFT']));
                $('.completed-count').text(statusCounts['APPROVED']);


                goalOverviewChartOptions.series = [((completed_count / total) * 100).toFixed(2)];

                goalOverviewChart = new ApexCharts($goalOverviewChart, goalOverviewChartOptions);
                goalOverviewChart.render();


            }

            function updateProfilingChart(label, series) {
                console.log('chart')
                // newOptions = progressProfilingChartOptions
                progressProfilingChartOptions.xaxis.categories = label
                progressProfilingChartOptions.series = series
                progressProfilingChart = new ApexCharts(document.querySelector(
                        "#progress-profiling-chart"),
                    progressProfilingChartOptions);
                progressProfilingChart.render();
                console.log(progressProfilingChartOptions)

            }


        });
    </script>


@endsection
