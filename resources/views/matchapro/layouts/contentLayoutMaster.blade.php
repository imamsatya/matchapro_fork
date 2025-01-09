@isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
@php
    $configData = Helper::applClasses();
@endphp

<html class="loading {{ $configData['theme'] === 'light' ? '' : $configData['layoutTheme'] }}"
    lang="@if (session()->has('locale')) {{ session()->get('locale') }}@else{{ $configData['defaultLanguage'] }} @endif"
    data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}"
    @if ($configData['theme'] === 'dark') data-layout="dark-layout" @endif>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Profiling SBR">
    <meta name="keywords" content="SBR PROFILING">
    <meta name="author" content="SBR">
    <title>@yield('title') - MATCHAPRO</title>
    <link rel="apple-touch-icon" href="{{ asset('images/logo/logoFRS.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/logoFRS.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    {{-- Include core + vendor Styles --}}
    @include('panels/styles')

    <style>
        .matcha-pro {
            margin-top: 0.3rem;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            font-size: 1.2rem;
            letter-spacing: 1px;
            background-color: #29c770;
            color: white;
            padding: 0px 0px 0px 5px;
            display: inline-block;
            border: 3px solid #29c770;
        }

        .matcha-pro span {
            background-color: white;
            color: #29c770;
            padding: 0px 3px;
            display: inline-block;
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
@isset($configData['mainLayoutType'])
    @extends($configData['mainLayoutType'] === 'horizontal' ? 'matchapro.layouts.horizontalLayoutMaster' : 'matchapro.layouts.verticalLayoutMaster')
@endisset
