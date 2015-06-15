@extends('layouts.master')
@section('content')
<style>
 .login-wrapper {padding-top: 30px;}
</style>
 <div class="row login-wrapper">
        <div class="col-xs-10 col-sm-6 col-md-6 col-xs-offset-1 col-sm-offset-3 col-md-offset-3">
        	
            <div class="account-box">
              <h2 style="margin-bottom:40px;  font-weight:bold;">Forgot Password</h2>
 <div class="row">
 			{{ Form::open(array('url'=>'users/forgotpassword', 'class'=>'form-signin')) }}
                <div class="col-md-9">
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" required autofocus />
                </div>
                 <div class="form-group">
                    <input type="password" class="form-control" id="password2" name="password2" required autofocus />
                </div>
                </div>
                <div class="col-md-3 pad0">
                <button class="btn btn-block btn-success" type="submit">
                    Submit
                </button>
                
                </div>
                </form>
                </div>
	</div>
    </div>
    </div>

@stop