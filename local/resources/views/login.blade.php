@extends ('layouts.plane')
@section ('body')
<div class="container">
  <div class="row logo_login">

  </div>
  <div class="row">
    <div class="col-md-4 col-md-offset-4" >
      <br /><br /><br />
      @section ('login_panel_title','Ingresa tus accesos')
      @section ('login_panel_body')
      <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
        @endif
        @endforeach
      </div>
      <form id="frmlogin" role="form" method="post" action="{{ url ('/login') }}">
        <fieldset>
          <!-- {{Hash::make('123');}}-->
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <input class="form-control" placeholder="Usuario" name="email" autofocus>
          </div>
          <div class="form-group">
            <input class="form-control" placeholder="Password" name="password" type="password" value="">
          </div>
          <div class="checkbox hidden">
            <label>
              <input name="remember" type="checkbox" value="Remember Me">Recordar
            </label>
          </div>
          <!-- Change this to a button or input when using this as a form -->
          <!--<a href="{{ url ('/login') }}" class="btn btn-lg btn-success btn-block">Login</a>-->
          <input type="submit" class="btn btn-primary btnLogin" value="Login" tabindex="4">
        </fieldset>

      </form>

      @endsection
      @include('widgets.panel', array('as'=>'login', 'header'=>true))
    </div>
  </div>
</div>
@section('scripts')
<script type="text/javascript">

  var form=$('#frmlogin');
  var url=form.attr('action').replace('login','assets/fondo2.jpg');
  $(document).ready(function()
  {
    $('body').css('background','#f8f8f8 url('+url+') no-repeat center center');
    $('body').css('background-size','100%');
  });
</script>
@endsection
@stop
