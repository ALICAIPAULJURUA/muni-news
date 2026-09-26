<script src="https://cdn.tiny.cloud/1/x5hqufi1enf2ifu5yp46yk0uqiixtpbk6bzm5u1kh6pthrp0/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#content',
    license_key: 'gpl',
    plugins: ['advlist','autolink','lists','link','image','charmap','preview','anchor','searchreplace','visualblocks','code','fullscreen','insertdatetime','media','table','wordcount','codesample','paste'],
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image media table | removeformat | code',
    images_upload_url: '{{ route('admin.upload-image') }}',
    images_upload_handler: function (blobInfo, progress) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route('admin.upload-image') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.upload.onprogress = (e) => { progress(e.loaded / e.total * 100); };
            xhr.onload = () => {
                if (xhr.status === 403) { reject({ message: 'HTTP Error: ' + xhr.status, remove: true }); return; }
                if (xhr.status < 200 || xhr.status >= 300) { reject('HTTP Error: ' + xhr.status); return; }
                const json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') { reject('Invalid JSON: ' + xhr.responseText); return; }
                resolve(json.location);
            };
            xhr.onerror = () => { reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status); };
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        });
    },
    extended_valid_elements: 'style[type],script[src|type|defer],div[],span[],article[*]',
    verify_html: false,
    height: 420,
    content_style: 'body { font-family: Source Sans Pro, sans-serif; line-height:1.8; max-width:75ch; }'
});
</script>