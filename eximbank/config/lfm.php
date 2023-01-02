 <?php

return [

    'url_prefix' => 'filemanager',

    'storage' => [
        'image' => [
            'max_file_size' => 20, //MB
            'mimetypes' => [
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/gif',
                'image/svg+xml',
                'image/webp',
            ]
        ],
        'file' => [
            'max_file_size' => 5000, //MB
            'mimetypes' => [
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/gif',
                'image/svg+xml',
                'image/webp',
                'application/pdf',
                'text/plain',
                '.zip',
                'application/zip',
                '.rar',
                'application/x-rar',
                'application/msword',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                '.mp4',
                '.mp3',
                '.mov',
                '.mkv',
                'video/mp4',
                'video/x-msvideo',
                'audio/mpeg',
            ]
        ],
        'scorm' => [
            'max_file_size' => 5000, //MB
            'mimetypes' => [
                '.zip',
                'application/zip',
            ]
            ],
        'xapi' => [
            'max_file_size' => 5000, //MB
            'mimetypes' => [
                '.zip',
                'application/zip',
            ]
        ]
    ],

    'file_type_array' => [
        'pdf'  => 'Adobe Acrobat',
        'doc'  => 'Microsoft Word',
        'docx' => 'Microsoft Word',
        'xls'  => 'Microsoft Excel',
        'xlsx' => 'Microsoft Excel',
        'rar'  => 'Rar',
        'zip'  => 'Archive',
        'gif'  => 'GIF Image',
        'jpg'  => 'JPEG Image',
        'jpeg' => 'JPEG Image',
        'png'  => 'PNG Image',
        'webp' => 'Webp Image',
        'ppt'  => 'Microsoft PowerPoint',
        'pptx' => 'Microsoft PowerPoint',
        'mp4'  => 'MP4',
        'x-msvideo' => 'MP4',
        'mp3'  => 'MP3',
    ],

    'file_icon_array' => [
        'pdf'  => 'fa-file-pdf-o',
        'doc'  => 'fa-file-word-o',
        'docx' => 'fa-file-word-o',
        'xls'  => 'fa-file-excel-o',
        'xlsx' => 'fa-file-excel-o',
        'rar'  => 'fa-file-archive-o',
        'zip'  => 'fa-file-archive-o',
        'gif'  => 'fa-file-image-o',
        'jpg'  => 'fa-file-image-o',
        'jpeg' => 'fa-file-image-o',
        'png'  => 'fa-file-image-o',
        'webp'  => 'fa-file-image-o',
        'ppt'  => 'fa-file-powerpoint-o',
        'pptx' => 'fa-file-powerpoint-o',
        'PNG'  => 'fa-file-image-o',
        'mp4'  => 'fa-file-video-o',
        'x-msvideo'  => 'fa-file-video-o',
        'mp3'  => 'fa-file-video-o',
        'jfif'  => 'fa-file-image-o',
        'txt'  => 'fa-file-text-o',
    ],

];
