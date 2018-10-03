<view_head>
	<title>Stock Photos Queryable Database</title>
	<script defer src="/Assets/js/image-query.min.js"></script>
</view_head>
<view_body>
	<h1 class="text-center">Search for a photo</h1>
	<form id="search-form" class="container">
		<div class="input-group">
			<div class="input-group-append">
				<div class="input-group-text">
					<i class="fas fa-search"></i>
				</div>
			</div>
			<input id="image-query-input" placeholder="Enter keywords or a phrase" type="text" name="query" class="form-control" />
		</div>
	</form>
	<hr />
	<div id="photo-container">
		<div class="container">
			<div id="photos-loader" class="text-center">
				<img src="/Assets/images/loader.svg" width="300" />
			</div>
			<div id="no-photos-message" class="d-none text-center">
				<em>No more photos found.</em>
			</div>
			<div id="photo-template" class="d-none photo-container">
				<div>
					<img class="photo" />
				</div>
				<div class="my-2">
					<div class="px-2 pb-1 small">
						<strong>Tags: </strong><span class="tags"></span>
					</div>
					<div class="px-2 small">
						<strong>Dimensions: </strong><span class="dimensions"></span>
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div id="photos-result-container" class="d-none justify-content-center flex-wrap">
				<div class="photo-column d-flex flex-column"></div>
				<div class="photo-column d-flex flex-column"></div>
				<div class="photo-column d-flex flex-column"></div>
				<div class="photo-column d-flex flex-column"></div>
				<div class="photo-column d-flex flex-column"></div>
				<div class="photo-column d-flex flex-column"></div>
			</div>
		</div>
	</div>
</view_body>
