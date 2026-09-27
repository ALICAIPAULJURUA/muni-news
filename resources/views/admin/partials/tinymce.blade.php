<script src="https://cdn.tiny.cloud/1/x5hqufi1enf2ifu5yp46yk0uqiixtpbk6bzm5u1kh6pthrp0/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#content',
    license_key: 'gpl',
    height: 600,
    menubar: true,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount',
        'codesample', 'pagebreak', 'nonbreaking', 'autoresize', 'paste'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | ' +
        'link image media table | codesample | ' +
        'removeformat | fullscreen | code | help',
    toolbar_mode: 'wrap',
    image_advtab: true,
    automatic_uploads: true,
    file_picker_types: 'image',
    paste_data_images: true,

    // CRITICAL: preserve <style> and <script> tags so premium newsletter
    // HTML with inline CSS/JS (sticky TOC, scroll-spy, cards) is not stripped
    valid_elements: '[]',
    valid_children: '+body[style|script|div|span|article|section|header|footer|nav|main|button|a|link|meta]',
    extended_valid_elements: 'style[type],script[src|type|defer|async],div[],span[],article[],button[],a[],link[],meta[],figure[],figcaption[],aside[],time[]',
    verify_html: false,

    // Custom image upload handler (CSRF-protected)
    images_upload_handler: function (blobInfo, progress) {
        return new Promise(function (resolve, reject) {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route('admin.upload-image') }}');

            xhr.upload.onprogress = function (e) {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = function () {
                if (xhr.status === 403) {
                    reject({ message: 'HTTP Error: 403 Forbidden', remove: true });
                    return;
                }
                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }
                try {
                    const json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.location !== 'string') {
                        reject('Invalid JSON response');
                        return;
                    }
                    resolve(json.location);
                } catch (e) {
                    reject('Server did not return valid JSON');
                }
            };

            xhr.onerror = function () {
                reject('Image upload failed due to a network error.');
            };

            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                formData.append('_token', csrfToken.getAttribute('content'));
            }

            xhr.send(formData);
        });
    },

    content_style: 'body { font-family: "Source Sans Pro", sans-serif; font-size: 16px; line-height: 1.8; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }' +
        'h1 { font-size: 2rem; font-weight: 700; color: #8B0000; }' +
        'h2 { font-size: 1.5rem; font-weight: 700; color: #8B0000; }' +
        'h3 { font-size: 1.25rem; font-weight: 700; color: #8B0000; }' +
        'img { max-width: 100% !important; height: auto !important; }' +
        'table { width: 100%; border-collapse: collapse; }' +
        'table td, table th { border: 1px solid #ddd; padding: 8px; }' +
        'blockquote { border-left: 4px solid #8B0000; padding: 10px 20px; margin: 10px 0; background: #f8f9fa; }',

    setup: function(editor) {
        editor.on('change', function() {
            tinymce.triggerSave();
        });
    }
});
</script>