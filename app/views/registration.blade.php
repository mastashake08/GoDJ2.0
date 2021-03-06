@extends('layouts.master')

@section('sidebar')
<script src="//connect.soundcloud.com/sdk-2.0.0.js"></script>
<script>
function SCLogin() {
  window.location.href = '/Soundcloud';
};
</script>

@stop

@section('content')
<div class="jumbotron how-to">
If you would like to try GoDJ for yourself, then please create an account using the form below!
</div>
<% Form::open(array('url' => 'api/v1/createuser','files' => true)) %>
                <h1>Create A New Account</h1>

                <!-- if there are login errors, show them here -->
                <p>
                        <% $errors->first('email') %>
                        <% $errors->first('password') %>
                </p>

               <p>
		               <%Form::text('username', Input::old('username'), array('class'=>'request_form_field form-control','id'=>'username','placeholder' => 'DJ name w/o the word DJ')) %>
		 <p>
                        <% Form::text('email', Input::old('email'), array('id'=>'email','class'=>'request_form_field form-control', 'placeholder' => 'Email')) %>
                </p>

	<p>
                        <% Form::password('password', array('id'=>'password','class'=>'request_form_field form-control', 'placeholder' => 'Password')) %>
                </p>

                <p>
                        <% Form::password('password', array('class'=>'request_form_field form-control','placeholder' => 'Repeat Password')) %>
                </p>

                <p>
                        <% Form::textArea('bio', Input::old('bio'),array('class'=>'request_form_field form-control','placeholder' => 'Your Bio')) %>
                </p>

                <p>
                        <% Form::label('Profile Pic','Profile Pic',array('class'=>'request_form_field form-control'))%>
                        <% Form::file('profile_pic') %>
                </p>

                <p><% Form::submit('Register!', array('class' => 'btn btn-success')) %> <img src="http://connect.soundcloud.com/2/btn-connect-sc-l.png" onClick="SCLogin()"></p>
        <% Form::close() %>
@stop
