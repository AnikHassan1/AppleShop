@extends('Layout.app')
@section('content')
@include('Component.MenuBar')
@include('Component.HeroSlider')
@include('Component.TopCategories')
@include('Component.ExclusiveProducts')
@include('Component.TopBrands')
@include('Component.Footer')

<script>
  (async()=>{
    await Category()
    $(".preloader").delay(90).fadeOut(100).addClass('loaded');
  })()
</script>
@endsection