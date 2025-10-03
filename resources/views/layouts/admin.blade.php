@extends('adminlte::page')



@section('footer')
<span style="text-align: right;">Developed by Sandip R.</span>
@endsection


@section('adminlte_js')

   @stack('js')
<script src="/custom.js"></script>
   @yield('js')

@stop
