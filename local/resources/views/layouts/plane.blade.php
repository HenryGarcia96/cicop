<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8" />
    <title>SICOP</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link rel="shortcut icon" type="image/png" href="{{ asset("assets/favicon.png")}}" />

    <!-- <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}" />
	<link rel="stylesheet" href="{{ asset("assets/scripts/css/ui-lightness/jquery-ui-1.10.4.custom.min.css") }}" />
	<link rel="stylesheet" href="{{ asset("assets/scripts/select2/select2.css") }}" /> -->

    <link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet'
        type='text/css' />
    <link type="text/css" rel="stylesheet" href="{{ asset("assets/css/theme-default/bootstrap.css?1422792965")}}" />
    <link type="text/css" rel="stylesheet" href="{{ asset("assets/css/theme-default/materialadmin.css?1425466319")}}" />
    <link type="text/css" rel="stylesheet"
        href="{{ asset("assets/css/theme-default/font-awesome.min.css?1422529194")}}" />
    <link type="text/css" rel="stylesheet"
        href="{{ asset("assets/css/theme-default/material-design-iconic-font.min.css?1421434286")}}" />
    <link type="text/css" rel="stylesheet"
        href="{{ asset("assets/css/theme-default/libs/rickshaw/rickshaw.css?1422792967")}}" />
    <link type="text/css" rel="stylesheet"
        href="{{ asset("assets/css/theme-default/libs/morris/morris.core.css?1420463396")}}" />
    <link rel="stylesheet" href="{{ asset("assets/css/jquery.toast.css") }}" />
    <link type="text/css" rel="stylesheet"
        href="{{ asset("assets/css/theme-default/libs/typeahead/typeahead.css?1424887863")}}" />
    <!-- <script type="text/javascript" src='{{ asset("assets/scripts/jquery-1.11.3.js") }}'></script>
	<script type="text/javascript" src='{{ asset("assets/scripts/bootstrap.js") }}'></script>

	<script type="text/javascript" src='{{ asset("assets/scripts/js/jquery-ui-1.10.4.custom.min.js") }}'></script>
	
	<script type="text/javascript" src='{{ asset("assets/scripts/jquery.formatCurrency-1.4.0.js") }}'></script>
	<script type="text/javascript" src='{{ asset("assets/scripts/select2/select2.min.js") }}'></script>
	<script type="text/javascript" src='{{ asset("assets/scripts/jquery.PrintArea.js") }}'></script>
	<script type="text/javascript" src='{{ asset("assets/scripts/picnet.table.filter.min.js")}}'></script>
	
	<script type="text/javascript" src='{{ asset("assets/scripts/time-picker.js")}}'></script>
	
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
	 -->
    <!-- BEGIN JAVASCRIPT -->

    <script src="{{ asset("assets/js/libs/jquery/jquery-1.11.2.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/jquery/jquery-migrate-1.2.1.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/bootstrap/bootstrap.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/spin.js/spin.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/autosize/jquery.autosize.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/moment/moment.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/flot/jquery.flot.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/flot/jquery.flot.time.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/flot/jquery.flot.resize.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/flot/jquery.flot.orderBars.js")}}"></script>
    <script src="{{ asset("assets/js/libs/flot/jquery.flot.pie.js")}}"></script>
    <script src="{{ asset("assets/js/libs/flot/curvedLines.js")}}"></script>
    <script src="{{ asset("assets/js/libs/jquery-knob/jquery.knob.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/sparkline/jquery.sparkline.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/nanoscroller/jquery.nanoscroller.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/d3/d3.min.js")}}"></script>
    <script src="{{ asset("assets/js/libs/d3/d3.v3.js")}}"></script>
    <script src="{{ asset("assets/js/libs/rickshaw/rickshaw.min.js")}}"></script>
    <script src="{{ asset("assets/js/core/source/App.js")}}"></script>
    <script src="{{ asset("assets/js/core/source/AppNavigation.js")}}"></script>
    <script src="{{ asset("assets/js/core/source/AppOffcanvas.js")}}"></script>
    <script src="{{ asset("assets/js/core/source/AppCard.js")}}"></script>
    <!-- <script src="{{ asset("assets/js/core/source/AppForm.js")}}"></script> -->
    <script src="{{ asset("assets/js/core/source/AppNavSearch.js")}}"></script>
    <script src="{{ asset("assets/js/core/source/AppVendor.js")}}"></script>
    <script src="{{ asset("assets/js/core/demo/Demo.js")}}"></script>
    <!-- <script src="{{ asset("assets/js/libs/typeahead/typeahead.bundle.min.js")}}"></script> -->
    <script type="text/javascript" src='{{ asset("assets/scripts/form-validator/jquery.form-validator.js") }}'></script>
    <script type="text/javascript" src='{{ asset("assets/scripts/jquery.toast.js")}}'></script>
    <script type="text/javascript" src='{{ asset("assets/scripts/bootstrap-typeahead.js")}}'></script>
    <script type="text/javascript" src='{{ asset("assets/scripts/jquery.formatCurrency-1.4.0.js")}}'></script>

    <script src='{{ asset("assets/js/libs/jquery-validation/dist/jquery.validate.min.js")}}'></script>
    <script src='{{ asset("assets/js/libs/moment/moment.min.js")}}'></script>
    <script src='{{ asset("assets/js/libs/bootstrap-datepicker/bootstrap-datepicker.js")}}'></script>
    <script src='{{ asset("assets/js/libs/raphael/raphael-min.js")}}'></script>
    <script src='{{ asset("assets/js/libs/morris.js/morris.min.js")}}'></script>
    <!-- <script src="{{ asset("assets/js/core/demo/DemoDashboard.js")}}"></script> -->
    <!-- END JAVASCRIPT -->

</head>

<body>

    @yield('body')


    @yield('scripts')
    <script type="text/javascript">
    $(document).ready(function() {
        $('input[type="text"].filter').css('width', '100%');
    });
    </script>
</body>

</html>