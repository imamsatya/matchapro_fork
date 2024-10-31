@extends('layouts/fullLayoutMaster')

@section('title', 'Not Authorized')

@section('page-style')
<link rel="stylesheet" href="{{asset(mix('css/base/pages/page-misc.css'))}}">
@endsection

@section('content')
<!-- Not authorized-->
<div class="misc-wrapper">  
  <div class="misc-inner p-2 p-sm-3">
    <div class="w-100 text-center">
      <h2 class="mb-1">Profiling Periodik Info 🛠</h2>
      @if(session('message'))
      <p class="mb-2">{{ session('message') }}</p>
      @else
      <p class="mb-3">Mohon maaf atas ketidaknyamanannya, Periode Profiling sudah ditutup</p>
      @endif
      <a class="btn btn-primary mb-1 btn-sm-block" href="{{url('home')}}">Back to Home</a>      
      <img class="img-fluid" src="{{asset('images/pages/under-maintenance-dark.svg')}}" alt="Periode Profiling Mandiri Tutup" />
    </div>
  </div>
</div>
<!-- / Not authorized-->
</section>
<!-- maintenance end -->
@endsection