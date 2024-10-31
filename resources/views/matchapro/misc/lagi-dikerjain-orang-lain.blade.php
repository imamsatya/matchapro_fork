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
      <h2 class="mb-1">Profiling Info 🛠</h2>
      @if(session('message'))
      <p class="mb-2">{{ session('message') }}</p>
      @else
      <p class="mb-3">Mohon maaf anda tidak bisa melakukan edit pada usaha/perusahaan ini, karena saat ini usaha/perusahaan tersebut sedang diedit oleh user lain</p>      
      @endif
      <a class="btn btn-primary mb-1 btn-sm-block" href="{{url('home')}}">Back to Home</a>      
      <img class="img-fluid" src="{{asset('images/pages/not-authorized-dark.svg')}}" alt="Usaha/Perusahaan Lagi Dikerjain Orang Lain" />
    </div>
  </div>
</div>
<!-- / Not authorized-->
</section>
<!-- maintenance end -->
@endsection