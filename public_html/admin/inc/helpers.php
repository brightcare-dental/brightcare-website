<?php
/** Shared admin helpers: media upload, editor, common form bits. */
require_once __DIR__ . '/auth.php';

const UPLOAD_DIR = __DIR__ . '/../../assets/uploads';
const UPLOAD_URL = '/assets/uploads';
const MAX_IMAGE  = 8  * 1024 * 1024;   // 8 MB
const MAX_VIDEO  = 80 * 1024 * 1024;   // 80 MB

const ALLOWED = [
    'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp',
    'image/gif'  => 'gif', 'image/svg+xml' => 'svg',
    'video/mp4'  => 'mp4', 'video/webm' => 'webm',
];

/**
 * Handle one uploaded file. Returns ['url'=>..,'mime'=>..] or throws RuntimeException.
 * $kind: 'image' | 'video' | 'any'
 */
function upload_file(string $key, string $kind = 'any'): ?array {
    if (empty($_FILES[$key]) || $_FILES[$key]['error'] === UPLOAD_ERR_NO_FILE) return null;
    $f = $_FILES[$key];
    if ($f['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload failed (code ' . $f['error'] . ').');

    $fi = new finfo(FILEINFO_MIME_TYPE);
    $mime = $fi->file($f['tmp_name']);
    if (!isset(ALLOWED[$mime])) throw new RuntimeException('File type not allowed: ' . $mime);

    $isVideo = str_starts_with($mime, 'video/');
    if ($kind === 'image' && $isVideo) throw new RuntimeException('Only images allowed here.');
    if ($kind === 'video' && !$isVideo) throw new RuntimeException('Only videos allowed here.');
    if ($f['size'] > ($isVideo ? MAX_VIDEO : MAX_IMAGE))
        throw new RuntimeException('File too large (max ' . ($isVideo ? '80MB' : '8MB') . ').');

    $sub = date('Y/m');
    $dir = UPLOAD_DIR . '/' . $sub;
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $name = bin2hex(random_bytes(8)) . '.' . ALLOWED[$mime];
    $dest = "$dir/$name";
    if (!move_uploaded_file($f['tmp_name'], $dest)) throw new RuntimeException('Could not save file.');

    $url = UPLOAD_URL . "/$sub/$name";
    db()->prepare('INSERT INTO media(filename,path,mime,size) VALUES(?,?,?,?)')
        ->execute([$name, $url, $mime, $f['size']]);
    return ['url' => $url, 'mime' => $mime];
}

/** Include Quill rich-text editor on a named textarea. */
function editor_assets(): void { ?>
<link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
<?php }
function editor_init(string $textareaId): void { ?>
<div class="editor-wrap"><div id="<?= e($textareaId) ?>_ed"></div></div>
<script>
(function(){
  var ta=document.getElementById('<?= e($textareaId) ?>');
  ta.style.display='none';
  var q=new Quill('#<?= e($textareaId) ?>_ed',{theme:'snow',modules:{toolbar:[
    [{header:[2,3,false]}],['bold','italic','underline'],
    [{list:'ordered'},{list:'bullet'}],['link','blockquote'],['clean']]}});
  q.root.innerHTML=ta.value;
  ta.form.addEventListener('submit',function(){ ta.value=q.root.innerHTML; });
})();
</script>
<?php }

/** Status pill. */
function status_badge(int $on): string {
    return $on ? '<span class="badge badge--on">Active</span>'
               : '<span class="badge badge--off">Hidden</span>';
}
