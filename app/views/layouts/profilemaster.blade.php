<?php header("Access-Control-Allow-Origin: *");?>
<!DOCTYPE html>

<html lang="en">
  <head>
    @include('includes.head')     
    <link rel="stylesheet" href="/css/datepicker.css">
  </head>

  <body>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default" role="navigation">
        @include('includes.header')
    </div>

	<div id="content" class="content-profile">
    <div class="content-full">
        @if(Session::has('message'))
            <div class="alerts" style="display:none;">
                <div class="alert-message warning">{{ Session::get('message') }}</div>
            </div>
        @endif
        
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
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/megamenu.js"></script>
    <script src="/js/input.js"></script>
    <script src="/js/bootstrap-modal.js"></script>
    <script src="{{ url('/js/jquery.rateit.js') }}" ></script>
    <script>
		$('input[type=file]').bootstrapFileInput();
		$('.file-inputs').bootstrapFileInput();
</script>
<script>
		    $(".buy-overlay").hide(); 
        $(".toggle").click(function(){    
        if($(this).attr("class") == "toggle"){
            $(this).removeClass("toggle");
            $(this).addClass("add_active");
        }else{
            $(this).removeClass("add_active");
            $(this).addClass("toggle");
        }
        $(".buy-overlay").slideToggle("fast");
        return false;
    });
</script>

<script>
		    $(".buy-overlay2").hide(); 
        $(".toggle2").click(function(){    
        if($(this).attr("class") == "toggle2"){
            $(this).removeClass("toggle2");
            $(this).addClass("add_active");
        }else{
            $(this).removeClass("add_active");
            $(this).addClass("toggle2");
        }
        $(".buy-overlay2").slideToggle("fast");
        return false;
    });
</script>
    
  </body>
</html>
