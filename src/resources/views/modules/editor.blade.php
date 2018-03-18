<!-- Include Editor style. -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/css/froala_editor.pkgd.min.css" />

<!-- Code Mirror CSS file. -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.32.0/codemirror.min.css">

@push('js')
	<!-- Code Mirror JS files. -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.32.0/codemirror.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.32.0/mode/xml/xml.min.js"></script>

	<!-- Include Editor JS file. -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/js/froala_editor.pkgd.min.js"></script>

	<script>
		$(".editor").froalaEditor({
			language: 'ru',
			imageUploadURL: '/scripts/upload_editor_image.php',
			imageAllowedTypes: ['jpeg', 'jpg', 'png', 'svg'],
			fileUpload: false,
			videoUpload: false,
			toolbarButtons: [
				'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontSize', 'color', '|', 'paragraphFormat', 'align', '|', 'formatOL', 'formatUL', '-', 'insertLink', 'insertImage', 'insertVideo', 'embedly', 'insertTable', '|', 'specialCharacters', 'clearFormatting', '|', 'spellChecker', 'html', '|', 'undo', 'redo', '|', 'fullscreen'
			],
			width: '100%',
			heightMin: 200,
			codeMirrorOptions: {
				indentWithTabs: true,
				lineNumbers: true,
				lineWrapping: true,
				mode: 'text/html',
				tabMode: 'indent',
				tabSize: 4
			},
			enter: $.FroalaEditor.ENTER_BR
		}).on('froalaEditor.image.error', function (e, editor, error, response) {
			if (response !== undefined)
				console.log(response);
		});
	</script>
@endpush