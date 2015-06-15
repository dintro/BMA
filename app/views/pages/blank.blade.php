@extends('layouts.master')
@section('content')
				@if(Session::has('message'))
		            <div class="alerts">
		                <div class="alert-message warning">{{ Session::get('message') }}</div>
		            </div>
		        @endif
				<ul>
			        @foreach($errors->all() as $error)
			            <li>{{ $error }}</li>
			        @endforeach
			    </ul>	
                
                
                <?php 
                                    $encoded1 = hash('md5', 'susu1234');//md5('susu1234');
                                    echo "Encoded 1 : ".$encoded1."<br />";
                                    $license = 'lekrj5n23nsx8an4kl';
                                    $encoded2 = strtolower($encoded1) . strtolower($license);
                                    echo "Encoded 2 : ".$encoded2."<br />";
                                    $digest = hash('md5', $encoded2);
                                    echo "Digest : " .$digest."<br />";
                                    $sharkuser = "sildeyna@gmail.com";
                                ?>

                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $.ajax({
                                            type: "GET",
                                            accepts: "application/json; charset=utf-8",
                                            crossDomain: true,
                                            dataType: 'json',
                                            url: "http://www.sharkscope.com/api/bma/networks/PokerStars/players/SkaiWalkurrr?Username={{$sharkuser}}&Password={{$digest}}",
                                            cache: false,
                                            success: function(data){
                                                //console.log(data);
												$("#result").append(data);
                                            }
                                        });
                                    });
                                </script>	
                 <span id="md5"></span>	
                <span id="result"></span>	
@stop