var acceptedImages = ["image/svg+xml", "image/jpeg", "image/png"];
var droppedImage; // If an image was dropped, they code must append this to sent FormData

function renderNewTag(tag){
	var item = $("#tag-template").clone().removeClass("d-none").removeAttr("id");
	item.find("input").val(tag);
	item.find(".tag-content").html(tag);

	item.find(".delete-icon").on("click", function(){
		item.remove();
	});

	item.insertBefore($("#upload-image-tags"));
}

function removePreviousTag(){
	var lastTag = $("#upload-tags-container .image-tag-input-box").last();
	if (lastTag.length > 0){
		lastTag.remove();
	}
}

// Focus the input element even if they do not click in it
$(".fake-textarea").on('click', function(e){
	if (e.target === this || $(e.target).attr('id') == "upload-tags-container"){
		$("#upload-image-tags").focus();
	}
});

$("#upload-image-tags").on("keydown", function(e){
	var tag = $(this).val().trim();
	if (e.key === "," || e.keyCode == 13){
		e.preventDefault();
		if (tag.length > 0){
			$(this).val('');
			renderNewTag(tag);
		}
	}else if(e.which === 8){
		if (tag.trim() === ""){
			removePreviousTag();
		}
	}
});

function handleFileUpload(file, wasDroppedIntoWindow){
	var reader = new FileReader();
	var errCont = $("#image-error");

	errCont.addClass("d-none");

	reader.addEventListener("load", function(){
		var result = reader.result;
		var img = new Image();
		var imageType = "Unknown";
		$(".upload-image-preview").attr('src', result);

		img.onload = function(){
			var height = img.height;
			var width = img.width;
			$("#image-file-dimensions").html(width + "x" + height);

			// Calculate new scaled dimensions for the preview by keeping aspect ratio
			var keepWidth = parseInt($(".upload-image-preview").width(), 10);
			var sizedHeight = (height/width)*keepWidth;
			$(".upload-image-preview").height(sizedHeight);
		};

		img.src = result;

		switch(file.type){
			case "image/svg+xml":
				imageType = "SVG";
				break;
			case "image/jpeg":
				imageType = "JPEG";
				break;
			case "image/png":
				imageType = "PNG";
				break;
			default:
				break;
		}

		$("#image-file-type").html(imageType);
	});

	if (file){
		console.log(file);
		$(".upload-image-preview").width("200").height("200").attr('src', "/Assets/images/loader.svg");
		if (acceptedImages.indexOf(file.type) == -1){
			errCont.removeClass("d-none").html("Invalid file type. Allowed file types are " + acceptedImages.join(", "));
			$(".upload-image-preview").removeAttr('src');
		}else{
			if (file.size < $("#max-upload-bytes").val()){
				if (wasDroppedIntoWindow === true){
					droppedImage = file;
				}else{
					droppedImage = undefined;
				}
				setTimeout(function(){
					reader.readAsDataURL(file);
				}, 500);
			}else{
				errCont.removeClass("d-none").html("File size exceeds maximum upload limit.");
				$(".upload-image-preview").removeAttr('src');
			}
		}
	}
}

$("#upload-image-file").on("change", function(e){
	handleFileUpload(this.files[0], false);
});

$(document).on("dragover dragenter", function(e){
	console.log(e);
	var dt = e.originalEvent.dataTransfer;
	if (dt.types && (dt.types.indexOf ? dt.types.indexOf('Files') != -1 : dt.types.contains('Files'))) {
		$("#upload-image-modal").modal("show");
	}
});

$(window).on("drop dragover", function(){
	e.preventDefault();
	return false;
}, false);

$(document).on("drop", function(e){
	console.log(e);
	var dt = e.originalEvent.dataTransfer;
	if (dt.types && (dt.types.indexOf ? dt.types.indexOf('Files') != -1 : dt.types.contains('Files'))) {
		e.preventDefault();
		e.stopPropagation();
		var droppedFile = e.originalEvent.dataTransfer.files[0];
		handleFileUpload(droppedFile, true);
	}

	return false;
});

$("#upload-image-form").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(this);

	if (droppedImage){
		formData.set("uploaded-image", droppedImage);
	}

	$.ajax({
		type:"post",
		url:"/Controllers/uploadImage.php",
		data:formData,
		cache:false,
		contentType:false,
		processData:false,
		success:function(r){
			// Clear out the previous image data from the client's upload modal
			$("#upload-image-form .upload-image-preview").removeAttr('src');
			$("#upload-tags-container .image-tag-input-box").remove();
			$("#image-file-type").html("");
			$("#image-file-dimensions").html("");
			$("#image-success").css("opacity", 1).removeClass("d-none").animate({
				opacity:0
			}, 1000, function(){
				$("#image-success").addClass("d-none");
			});
		}
	})

	console.log(formData);
});
