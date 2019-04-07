@extends('master')

@section('title')
	Foto bijsnijden
@endsection

@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropper/0.9.3/cropper.min.css"
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/cropper/0.9.3/cropper.min.js"></script>
@endsection

@section('content')

<h1>Foto bijsnijden</h1>

<hr/>

<p>Let op, dit werkt nog niet volledig!</p>

<p>File name: {{$fName}}</p>

<div class="row">
	<div class="col-sm-12">
		<div class="photoContainer">
			<img src="/img/profile/full/{{$fName}}" />
		</div>

		<label class="data-url"></label>
	</div>
</div>
@endsection

@section('footer')


<script type="text/javascript">
($( document ).ready(function() {
	
	var $image = $(".photoContainer > img");
            originalData = {};


        $image.cropper({
          aspectRatio: 200/200,
          resizable: true,
          zoomable: false,
          rotatable: false,
          multiple: true,
          dragend: function(data) {        
            originalData = $image.cropper("getCroppedCanvas");
            console.log(originalData.toDataURL());
            $('.data-url').text(originalData.toDataURL());
          }
        });
}));
</script>
@endsection