@extends('layouts.master')
@section('content')
<style>
 .login-wrapper {padding-top: 30px;}
</style>
 <div class="row login-wrapper">
        <div class="col-xs-10 col-sm-6 col-md-6 col-xs-offset-1 col-sm-offset-3 col-md-offset-3">
        	
            <div class="account-box">
              <h2 style="margin-bottom:40px;  font-weight:bold;">Reset Password</h2>
               @if(Session::has('success'))
                    <div class="alert alert-success alert-wrap">
                        <div>{{ Session::get('message') }}</div>
                  
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul> 
                
                  </div>
                @else
                	@if(Session::has('message'))
                        <div class="alert alert-danger alert-wrap">
                            <div>{{ Session::get('message') }}</div>
                      
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul> 
                    
                      </div>
                    @endif  
                @endif
 <div class="row">
 			{{ Form::open(array('url'=>'users/reset', 'class'=>'form-signin')) }}
                <div class="col-md-9">
                
                 
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required autofocus />
                    </div>
                     <div class="form-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required autofocus />
                    </div>
                     <div class="form-group">
                      <input type="hidden" class="form-control" id="email" name="email" value="{{ $email }}" />
                      <input type="hidden" class="form-control" id="token" name="token" value="{{ $token }}" />
                        <button class="btn btn-block btn-success" type="submit">
                        Submit
                    </button>
                    </div>
                </div>              
                </form>
                </div>
	</div>
    </div>
    </div>

@stop