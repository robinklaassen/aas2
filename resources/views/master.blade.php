<!DOCTYPE html>
<html lang="nl">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title') | KAAS 2.0</title>
	<link rel="icon" type="image/png" href="{{ url('/icon-bait.png') }}">

	{{ Html::style('css/app.css') }}

	@yield('header')

</head>

<body style="padding-top:20px;">

	<div class="container" id="vue-root">
		<!-- Navigation bar for all authenticated users -->
		@unless (Auth::guest())
		@include('partials.nav')
		@endunless

		<!-- Flash message -->
		@include('partials.flash')

		<!-- Main content -->
		@yield('content')

		<!-- Footer -->
		<div
			style="display: inline-flex; float: right; flex-wrap: wrap; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
			<span class="btn btn-sm" disabled="true">KAAS 2.0 is het Anderwijs Administratiesysteem.</span>
			<button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#infoModal">Meer
				informatie</button>
			<button type="button" class="btn btn-link btn-sm"><a
					href="{{ url('privacy') }}">Privacystatement</a></button>
			<button type="button" class="btn btn-link btn-sm"><a href="mailto:webmaster@anderwijs.nl">Mail de
					webmaster</a></button>
		</div>
	</div>

	<!-- Info modal -->
	@include('partials.info')

	{{ Html::script('js/manifest.js') }}
	{{ Html::script('js/vendor.js') }}
	{{ Html::script('js/app.js') }}

	@if ( config('google.site_tag') !== null )
	<!-- Global site tag (gtag.js) - Google Ads -->
	<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('google.site_tag') }}"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', '{{ config("google.site_tag") }}', { 'anonymize_ip': true });
	</script>
	@endif

	<script type="text/javascript">

		// On DOM ready...
		$(document).ready(function() {
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
			});

			// Fix DataTables in Bootstrap tabs
			$("a[data-toggle=\"tab\"]").on("shown.bs.tab", function(e) {
				console.log('show tab');
				$($.fn.dataTable.tables(true)).DataTable()
					.columns.adjust()
					.responsive.recalc();
			});
		});
	</script>

	@yield('script')

	<!-- Footer content (page-specific scripts etc.) -->
	@yield('footer')

</body>

</html>
