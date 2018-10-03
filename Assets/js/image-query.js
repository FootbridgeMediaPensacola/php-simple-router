var resultsPage = 1;
var imageColumns = [];

function showLoader(){
	$("#photos-loader").removeClass("d-none");
}

function hideLoader(){
	$("#photos-loader").addClass("d-none");
}

function renderImageItem(image){
	var fullSizeSrc = image.location + "/" + image.fileName;
	var thumbnailSrc = image.location + "/thumbs/" + image.fileName;
	var item = $("#photo-template").clone().removeAttr('id').removeClass("d-none");
	item.find(".photo").attr('src', thumbnailSrc).attr("full-src", fullSizeSrc);
	item.find(".tags").html(image.tags.join(", "));
	item.find(".dimensions").html(image.width + " x " + image.height);

	getNextAppendColumn().append(item);
}

function getNextAppendColumn(){
	var lastMaxChildren = 10000;
	var column;
	$.each(imageColumns, function(i,v){
		if ($(v).children().length < lastMaxChildren){
			lastMaxChildren = $(v).children().length;
			column = $(v);
		}
	});

	return column;
}

function collectColumns(){
	imageColumns = []; // Reset
	console.log("Yes");
	$(".photo-column").each(function(i, element){
		console.log("Added");
		imageColumns.push(element);
	});
}

function clearColumnContents(){
	$.each(imageColumns, function(i, element){
		$(element).html('');
	})
}

function fetchImages(query, page){
	showLoader();
	$("#photos-result-container").addClass("d-none").removeClass("d-flex");
	$("#no-photos-message").addClass("d-none");
	$.ajax({
		type:"get",
		url:"/Controllers/queryImages.php",
		data:{query:query, page:page},
		success:function(r){
			if (typeof(r) == "object"){
				if (r.status == 1){
					var images = r.images;
					hideLoader();
					$("#photos-result-container").removeClass("d-none").addClass("d-flex");
					if (images.length > 0){
						$.each(images, function(i, data){
							renderImageItem(data);
						});
					}else{
						$("#no-photos-message").removeClass("d-none");
					}
				}else{
					alert(r.error);
				}
			}
		}
	})
}

collectColumns();
fetchImages("", resultsPage);

$("#image-query-input").on('keydown', function(e){
	if (e.keyCode == 13){
		e.preventDefault();
		e.stopPropagation();
		resultsPage = 1;
		clearColumnContents();
		fetchImages($(this).val(), resultsPage);
	}
});
