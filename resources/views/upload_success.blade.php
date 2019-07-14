<html>
	<body>
		@foreach($imageInfo as $detail => $path)
			<b> {{ $detail }} </b>
			<br/>
			<img src= {{ $path }} >
			<br/>
			<br/>
		@endforeach
	</body>
</html>