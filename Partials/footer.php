<div id="upload-button-container">
	<a data-toggle="modal" data-target="#upload-image-modal" class="btn btn-dark text-white"><i class="fas fa-plus-square"></i> Upload Image</a>
</div>
<div class="modal fade" id="upload-image-modal">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<form id="upload-image-form" class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Upload an image</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input id="max-upload-bytes" type="hidden" value="<?= Internals::getMaxUploadSize(false); ?>" />
				<div class="mb-3 small">
					<strong>Max upload size: </strong><span><?= Internals::getMaxUploadSize(); ?></span>
				</div>
				<div class="row">
					<div class="col-5 text-center">
						<img class="upload-image-preview" /><br />
						<label class="hidden-upload-button btn btn-primary mt-2" for="upload-image-file">
							<span><i class="fas fa-image"></i> Upload Image</span>
							<input id="upload-image-file" name="uploaded-image" type="file" />
						</label>
					</div>
					<div class="col-7">
						<div class="form-group">
							<div id="tag-template" class="d-none image-tag-input-box">
								<div class="d-flex align-items-center">
									<input type="hidden" name="imageTags[]" />
									<div class="tag-content"></div>
									<div class="delete-icon">&times;</div>
								</div>
							</div>
							<div class="fake-textarea form-control">
								<div id="upload-tags-container" class="d-flex align-items-center flex-wrap">
									<input type="text" id="upload-image-tags" placeholder="Type a tag. Separate by commas" />
								</div>
							</div>
						</div>
						<div id="image-file-details">
							<div class="my-2">
								<strong>File type: </strong><span id="image-file-type"></span>
							</div>
							<div class="my-2">
								<strong>Dimensions: </strong><span id="image-file-dimensions"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="my-3 bg-danger p-2 text-white d-none" id="image-error"></div>
			</div>
			<div class="modal-footer">
				<button id="submit-new-image" type="submit" class="btn btn-success">Submit Entry</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<footer class="p-5">

</footer>
