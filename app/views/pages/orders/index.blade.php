@extends('layouts.profilemaster')
@section('content')  
<?php

if(isset($_SESSION["packagecart"]))
    {
	    $total = 0;
		echo '<form method="post" action="paypal-express-checkout/process.php">';
		echo '<ul>';
		$cart_items = 0;
		foreach ($_SESSION["packagecart"] as $cart_itm)
        {
           $package_id = $cart_itm["package_id"];
		   	   
		    echo '<li class="cart-itm">';
			echo '<span class="remove-itm"><a href="cart_update.php?removep='.$cart_itm["package_id"].'&return_url='.$current_url.'">&times;</a></span>';
			echo '<div class="p-price">'.$cart_itm["selling_price"].'</div>';
            echo '<div class="product-info">';
			echo '<h3>'.$cart_itm["package_name"].' (Code :'.$cart_itm["package_id"].')</h3> ';
            echo '</div>';
            echo '</li>';
			
        }
    	echo '</ul>';
		echo '<span class="check-out-txt">';
		
		echo '</span>';
		echo '</form>';
		
    }
?>
@stop