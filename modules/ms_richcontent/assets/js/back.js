$(document).ready(function(){

	$('body').on('click', '.addfile', function() {

		var link = $('#link_richcontent').val();

		event.preventDefault();
		var filename = $('#zipFile').val();

			var formData = new FormData();
			formData.append('file', $('#zipFile')[0].files[0] );

			formData.append('text', 'test');
			$.ajax({
				type: "POST",
				url: link+"controllers/admin/addfile.php",
				data: formData,
				contentType: false,
				processData: false,
				cache: false,
				enctype: 'multipart/form-data',
				success: function (result) {
					$('#zip_success').removeClass("d-none");
					$('#rich_content_field_lang_wysiwyg_1').val(result);
					setTimeout(function() { 
						$('#zip_success').addClass("d-none");
					}, 3000);
				}});
	});


});