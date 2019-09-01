<!DOCTYPE html>
<html lang="nl">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title') | AAS 2.0</title>
	<link rel="icon" type="image/png" href="{{ url('/icon-bait.png') }}">

	<!-- Load Bootstrap theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.4/cosmo/bootstrap.min.css">

	<!-- Load Bootstrap extension for DataTables -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.0.0/css/responsive.bootstrap.min.css">
	{{ Html::style('css/custom.css') }}

	<!-- Proper text alignment in multiline tooltips -->
	<style type="text/css">
		.tooltip-inner {
			text-align: left;
		}
	</style>

	@yield('header')

</head>

<body style="padding-top:20px;">

	<div class="container">
		<!-- Navigation bar for all authenticated users -->
		@unless (Auth::guest())
		@include('partials.nav')
		@endunless

		<!-- Flash message -->
		@include('partials.flash')

		<!-- Main content -->
		@yield('content')

		<!-- Footer -->
		<div style="display: inline-flex; float: right; flex-wrap: wrap; border-top: 1px solid #ddd; padding-top: 10px;">
			<span class="btn btn-sm" disabled="true">AAS 2.0 is het Anderwijs Administratiesysteem.</span>
			<button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#infoModal">Meer informatie</button>
			<button type="button" class="btn btn-link btn-sm"><a href="{{ url('privacy') }}">Privacystatement</a></button>
			<button type="button" class="btn btn-link btn-sm"><a href="mailto:webmaster@anderwijs.nl">Mail de webmaster</a></button>
		</div>
	</div>

	<!-- Info modal -->
	@include('partials.info')

	<!-- Load jQuery and Bootstrap scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

	<!-- Load DataTables scripts -->
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.0.0/js/dataTables.responsive.min.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.0.0/js/responsive.bootstrap.min.js"></script>

	<!-- Load Modernizr and webshims scripts -->
	<script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
	<script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/webshim/1.15.7/minified/polyfiller.js"></script>

	<script type="text/javascript">
		// Set date functionality to HTML 5 in unsupported browsers
		if (!Modernizr.inputtypes.date) {
			webshim.polyfill('forms-ext');
		}

		// On DOM ready...
		($(document).ready(function() {
			// Removes unimportant alerts after 3 seconds
			$('div.alert').not('.alert-important').delay(3000).slideUp(300);

			// Set Bootstrap tooltips
			$('[data-toggle="tooltip"]').tooltip();

			// Set DataTables default language things
			$.extend($.fn.dataTable.defaults, {
					"responsive": true,
					"language": {
						"emptyTable": "Geen beschikbare data",
						"lengthMenu": "Laat  _MENU_  rijen zien",
						"zeroRecords": "Geen rijen gevonden!",
						"info": "Rij _START_ t/m _END_ van _TOTAL_",
						"infoEmpty": "Geen beschikbare rijen",
						"infoFiltered": "(gefilterd uit _MAX_ rijen totaal)",
						"search": "Zoeken:",
						"paginate": {
							"first": "Eerste",
							"last": "Laatste",
							"next": "Volgende",
							"previous": "Vorige"
						}
					}
				}
			});

		// Fix DataTables in Bootstrap tabs
		$("a[data-toggle=\"tab\"]").on("shown.bs.tab", function(e) {
			console.log('show tab');
			$($.fn.dataTable.tables(true)).DataTable()
				.columns.adjust()
				.responsive.recalc();
		});
		}));
	</script>

	<!-- Footer content (page-specific scripts etc.) -->
	@yield('footer')

</body>

</html>