<!doctype html>
<html>
<head>
    @include('includes.head')
</head>
<body>
<div class="container-fluid">

    <header class="row">
        @include('includes.header')
    </header>

    <div id="main" class="row">
        @if ( Session::has('flash_message') )

          <div class="alert alert-dismissible {{ Session::get('flash_type') }}">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              {{ Session::get('flash_message') }}
          </div>

        @endif

        @yield('content')

    </div>

    <footer class="row">
        @include('includes.footer')
    </footer>

</div>
    @yield('js_includes')
</body>
</html>
