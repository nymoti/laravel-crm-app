@extends('layouts.auth')

@section('content')



<div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 login-form">
            <div class="panel-header">
                <h2 class="text-center">
                    <img src="/uploads/admin/logo-app.png" alt="Logo" style="height: 128px;">
                </h2>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        

                        @if (session('status'))                          
                            <div class="alert  text-center" role="alert" 
                                style="color: #ffffff;background-color: #ca0707;border-color: #edefec;" >
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{ route('login') }}" id="authentication" method="POST" class="login_validator" aria-label="{{ __('Login') }}"> 
                        
                           @csrf 
 
                            <div class="form-group">
                                <input id="login" type="text"
                                       class="form-control{{ $errors->has('fixe_tmk') || $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="login" value="{{ old('fixe_tmk') ?: old('email') }}" placeholder="Fixe TMK" required autofocus>
                         
                                @if ($errors->has('fixe_tmk') || $errors->has('email'))
                                    <span class="invalid-feedback" style="color: #ff7806;">
                                        <strong>{{ $errors->first('fixe_tmk') ?: $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="password" class="sr-only">{{ __('Password') }}</label> 
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} form-control-lg" name="password"  placeholder="***********" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert" style="color: #ff7806;">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div> 
                            <div class="form-group"> 
                                <button type="submit" class="btn btn-primary btn-block">
                                        {{ __('Login') }}
                                    </button>
                            </div> 
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>






@endsection
