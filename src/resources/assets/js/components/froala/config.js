export default {
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
    }
};