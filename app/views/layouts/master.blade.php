<!DOCTYPE html>
<html lang="en">
  <head>
    @include('includes.head')     
  </head>

  <body>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default" role="navigation">
        @include('includes.header')
    </div>

	<div id="content">
    <div class="container">
               
        <!-- Main component for a primary marketing message or call to action -->
        @yield('content')

    </div> <!-- /c
    ontainer -->
    </div>
	<div id="footer">    	
        @include('includes.footer')        
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
   <script src="/js/bootstrap.min.js"></script>
    <script src="/js/megamenu.js"></script>
  </body>
</html>
