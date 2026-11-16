<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
use AhamedDrafted\Config\Auth;
Auth::startSession();
$pdo = \AhamedDrafted\Config\Database::getConnection();
if (empty($_SESSION['user_id']) || !in_array($_SESSION['user_role'] ?? '', ['owner','editor'], true)) { header('Location: login.php'); exit; }
$types = ['journal'=>'Journal post','album'=>'Gallery album','photograph'=>'Gallery photo','memory'=>'Memory','event'=>'Event','timeline'=>'Life timeline','birthday'=>'Birthday memory','song'=>'Favourite song','project'=>'Project','place'=>'Place','friend'=>'Friend','education'=>'Education','achievement'=>'Achievement','interest'=>'Interest','category'=>'Category'];
$csrf = htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8');
$msg=''; $err='';
$profileStmt=$pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key='profile' LIMIT 1"); $profileStmt->execute();
$profile=json_decode((string)($profileStmt->fetchColumn() ?: '{}'),true); if(!is_array($profile)) $profile=[];
$saveProfile=static function(array $data) use($pdo):void {$s=$pdo->prepare("INSERT INTO site_settings(setting_key,setting_value) VALUES('profile',?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)");$s->execute([json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR)]);};
$upload=static function(string $field,string $kind='image'):string {
 if(empty($_FILES[$field]) || $_FILES[$field]['error']===UPLOAD_ERR_NO_FILE) return '';
 $f=$_FILES[$field]; if($f['error']!==UPLOAD_ERR_OK || !is_uploaded_file($f['tmp_name']) || $f['size']<1) throw new RuntimeException('Upload failed. Select a valid file.');
 $map=$kind==='audio'?['audio/mpeg'=>'mp3','audio/mp4'=>'m4a','audio/ogg'=>'ogg','audio/wav'=>'wav','audio/x-wav'=>'wav','audio/webm'=>'webm']:['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
 $max=$kind==='audio'?15*1024*1024:8*1024*1024; if($f['size']>$max) throw new RuntimeException($kind==='audio'?'Audio limit is 15 MB.':'Image limit is 8 MB.');
 $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']); if(!isset($map[$mime])) throw new RuntimeException('Unsupported '.$kind.' format.');
 if($kind==='image' && !@getimagesize($f['tmp_name'])) throw new RuntimeException('Invalid image file.');
 $dir=dirname(__DIR__).'/uploads/'.$kind.'s'; if(!is_dir($dir) && !mkdir($dir,0755,true) && !is_dir($dir)) throw new RuntimeException('Upload folder is unavailable.');
 $name=bin2hex(random_bytes(16)).'.'.$map[$mime]; if(!move_uploaded_file($f['tmp_name'],$dir.'/'.$name)) throw new RuntimeException('Could not save uploaded file.');
 return '/uploads/'.$kind.'s/'.$name;
};
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!Auth::verifyCsrfToken($_POST['csrf_token']??null)) $err='Session validation failed. Refresh and try again.';
 else {
  $action=(string)($_POST['action']??'');
  try {
   if($action==='save_home'){
    foreach(['tagline','short_bio','hero_headline','hero_supporting','hero_image_alt','full_biography'] as $k) $profile[$k]=trim((string)($_POST[$k]??''));
    $profile['show_journal_section']=isset($_POST['show_journal_section'])?'1':'0';
    $profile['show_portfolio_section']=isset($_POST['show_portfolio_section'])?'1':'0';
    if(!empty($_POST['clear_hero'])) {$old=(string)($profile['hero_image']??''); if(preg_match('#^/uploads/hero/[A-Za-z0-9_-]+\.(?:png|webp)$#',$old)) @unlink(dirname(__DIR__).$old); $profile['hero_image']='';}
    $img=$upload('hero_image'); if($img!=='') {$profile['hero_image']=$img;$profile['hero_image_enabled']='1';}
    $profile['hero_image_enabled']=isset($_POST['hero_image_enabled'])?'1':'0';
    $saveProfile($profile); $msg='Homepage settings saved.';
   } elseif($action==='delete'){
    $id=(int)($_POST['id']??0); $s=$pdo->prepare('DELETE FROM content_records WHERE id=?');$s->execute([$id]);$msg='Content record deleted.';
   } elseif($action==='save_record'){
    $id=(int)($_POST['id']??0);$section=(string)($_POST['section']??'');$title=trim((string)($_POST['title']??''));$slug=trim((string)($_POST['slug']??''));$desc=trim((string)($_POST['description']??''));$date=trim((string)($_POST['event_date']??''));$artist=trim((string)($_POST['artist']??''));$url=trim((string)($_POST['external_url']??''));$visibility=($_POST['visibility']??'public')==='private'?'private':'public';$status=in_array($_POST['status']??'',['draft','published','pending','archived'],true)?$_POST['status']:'draft';
    if(!isset($types[$section])||$title==='') throw new RuntimeException('Choose a content type and enter a title.');
    $existingData=[]; if($id>0){$old=$pdo->prepare('SELECT data_json FROM content_records WHERE id=?');$old->execute([$id]);$oldData=json_decode((string)($old->fetchColumn()?:'{}'),true);if(is_array($oldData))$existingData=$oldData;}\n    $data=array_replace($existingData,['title'=>$title,'description'=>$desc,'excerpt'=>$desc,'caption'=>$desc,'slug'=>$slug!==''?$slug:trim(preg_replace('/[^a-z0-9]+/i','-',strtolower($title)),'-'),'event_date'=>$date,'date_label'=>$date,'year_label'=>$date!==''?substr($date,0,4):'','artist'=>$artist,'external_url'=>$url];
    if($section==='journal') {$data['excerpt']=$desc;$data['category']=trim((string)($_POST['category']??'Journal'));$data['date_formatted']=$date!==''?$date:date('F j, Y');$data['reading_time_minutes']=max(1,(int)($_POST['reading_time']??3));$data['content']=trim((string)($_POST['body']??''));}
    if($section==='album') {$data['category']=trim((string)($_POST['category']??'Personal'));$data['created_date']=$date;$data['photo_count']=0;$data['editorial_intro']=$desc;}
    if($section==='photograph') {$data['alt_text']=$desc;$data['caption']=$desc;$data['album_slug']=trim((string)($_POST['album_slug']??''));}
    if($section==='memory'||$section==='birthday') {$data['photograph_url']='';$data['location_approx']=trim((string)($_POST['location']??''));$data['category']=$section==='birthday'?'Birthday':'Memory';$data['date_label']=$date;}
    if($section==='song') {$data['artist']=$artist;$data['external_url']=$url;$data['audio_url']='';}
    $image=$upload('image');if($image!==''){$data['featured_image']=$image;$data['cover_image']=$image;$data['file_path']=$image;$data['photograph_url']=$image;}
    $audio=$section==='song'?$upload('audio','audio'):'';if($audio!=='')$data['audio_url']=$audio;
    if($section==='photograph' && $image==='') throw new RuntimeException('Choose an image for this gallery photo.');
    if($section==='song' && $url!=='' && !preg_match('#^https://#i',$url)) throw new RuntimeException('Music links must use HTTPS.');\n    if($section==='song' && $url==='' && $audio==='' && empty($existingData['audio_url'])) throw new RuntimeException('Add a music platform link or upload permitted audio.');
    if($section==='journal' && $data['content']==='') throw new RuntimeException('Add the article body.');
    $json=json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);
    $published=$status==='published'?date('Y-m-d H:i:s'):null;
    if($id>0){$s=$pdo->prepare('UPDATE content_records SET section=?,slug=?,title=?,data_json=?,visibility=?,status=?,published_at=? WHERE id=?');$s->execute([$section,$data['slug'],$title,$json,$visibility,$status,$published,$id]);}
    else {$s=$pdo->prepare('INSERT INTO content_records(section,slug,title,data_json,visibility,status,published_at) VALUES(?,?,?,?,?,?,?)');$s->execute([$section,$data['slug'],$title,$json,$visibility,$status,$published]);}
    $msg=$id>0?'Content updated.':'Content published/saved.';
   }
  } catch(Throwable $e){$err=$e instanceof RuntimeException?$e->getMessage():'Could not save changes. Check server logs.';if(!$e instanceof RuntimeException)error_log('CMS error: '.$e->getMessage());}
 }
}
$edit=null;if(isset($_GET['edit'])){$s=$pdo->prepare('SELECT * FROM content_records WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch()?:null;}
$editData=$edit?json_decode((string)$edit['data_json'],true):[];if(!is_array($editData))$editData=[];
$filter=isset($_GET['type'])&&isset($types[$_GET['type']])?$_GET['type']:'';
$sql='SELECT id,section,title,status,visibility,created_at FROM content_records'.($filter?' WHERE section=?':'').' ORDER BY id DESC LIMIT 100';$list=$pdo->prepare($sql);$list->execute($filter?[$filter]:[]);$records=$list->fetchAll();
$total=(int)$pdo->query('SELECT COUNT(*) FROM content_records')->fetchColumn();$views=(int)$pdo->query('SELECT COUNT(*) FROM page_views')->fetchColumn();
?><!doctype html><html lang="en" data-theme="light"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>CMS · Ahamed Drafted</title><link rel="stylesheet" href="../assets/css/tokens.css"><link rel="stylesheet" href="../assets/css/base.css"><link rel="stylesheet" href="../assets/css/components.css"><style>
*{box-sizing:border-box}.wrap{max-width:1280px;margin:auto;padding:24px}.top,.actions,.tabs{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}.stats,.grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.panel{background:var(--surface);border:1px solid var(--border-color);padding:20px;margin:18px 0}.stat{font-size:28px}.tabs{justify-content:flex-start;border-bottom:1px solid var(--border-color);padding-bottom:12px}.tabs a{padding:9px 12px;border:1px solid var(--border-color);text-decoration:none;font-size:12px}.form{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:13px}.form label{display:flex;flex-direction:column;gap:6px;font-size:13px}.form input,.form select,.form textarea{width:100%;padding:12px;border:1px solid var(--border-color);background:var(--background);color:var(--text-primary);font:inherit}.wide{grid-column:1/-1}.notice{padding:12px;background:var(--surface-secondary)}table{width:100%;border-collapse:collapse}td,th{text-align:left;padding:10px;border-bottom:1px solid var(--border-color);font-size:12px}.scroll{overflow-x:auto}.muted{color:var(--text-secondary);font-size:13px}.danger{color:#b42318}.preview{max-width:220px;max-height:180px;object-fit:contain}
@media(max-width:720px){.wrap{padding:14px}.stats,.grid{grid-template-columns:1fr}.form{grid-template-columns:1fr}.wide{grid-column:auto}.panel{padding:14px}.scroll table{min-width:650px}}
</style></head><body><main class="wrap">
<header class="top"><div><p class="meta-label">AHAMED DRAFTED / CONTENT MANAGEMENT</p><h1 class="h2">Admin Studio</h1><p class="muted">Signed in as <?=htmlspecialchars($_SESSION['display_name']??'Admin',ENT_QUOTES,'UTF-8')?></p></div><div class="actions"><a class="btn-editorial btn-editorial--outline" href="../">View website</a><a class="btn-editorial" href="logout.php">Log out</a></div></header>
<?php if($msg):?><p class="notice" role="status"><?=htmlspecialchars($msg)?></p><?php endif;?><?php if($err):?><p class="notice" role="alert"><?=htmlspecialchars($err)?></p><?php endif;?>
<section class="stats"><div class="panel"><p class="meta-label">CONTENT RECORDS</p><p class="stat"><?=$total?></p></div><div class="panel"><p class="meta-label">PAGE VIEWS</p><p class="stat"><?=$views?></p></div><div class="panel"><p class="meta-label">ADMIN MODULES</p><p class="stat">10+</p></div></section>
<nav class="tabs"><a href="#home">Homepage</a><a href="#editor">Create / Edit</a><a href="#library">Content Library</a><a href="#library">Gallery</a><a href="#editor">Music</a><a href="#editor">Events & Calendar</a><a href="#editor">Life Timeline</a></nav>
<section class="panel" id="home"><p class="meta-label">HOMEPAGE BUILDER</p><h2 class="h3">Homepage presentation</h2><p class="muted">Edit the hero copy, biography and portrait without changing existing profile settings.</p>
<?php if(!empty($profile['hero_image'])):?><img class="preview" src="<?=htmlspecialchars($profile['hero_image'])?>" alt="Current hero image"><?php endif;?>
<form class="form" method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?=$csrf?>"><input type="hidden" name="action" value="save_home">
<label>Site tagline<input name="tagline" value="<?=htmlspecialchars($profile['tagline']??'')?>"></label><label>Short bio<input name="short_bio" value="<?=htmlspecialchars($profile['short_bio']??'')?>"></label><label class="wide">Hero headline<input name="hero_headline" value="<?=htmlspecialchars($profile['hero_headline']??'')?>"></label><label class="wide">Hero supporting text<textarea name="hero_supporting" rows="2"><?=htmlspecialchars($profile['hero_supporting']??'')?></textarea></label><label class="wide">Full biography<textarea name="full_biography" rows="5"><?=htmlspecialchars($profile['full_biography']??'')?></textarea></label><label>Hero portrait (PNG/JPG/WebP, max 8 MB)<input type="file" name="hero_image" accept="image/jpeg,image/png,image/webp"></label><label>Image alt text<input name="hero_image_alt" value="<?=htmlspecialchars($profile['hero_image_alt']??'')?>"></label><label><span>Show hero portrait</span><input type="checkbox" name="hero_image_enabled" value="1" <?=($profile['hero_image_enabled']??'1')==='1'?'checked':''?>></label><label><span>Remove current portrait</span><input type="checkbox" name="clear_hero" value="1"></label>
<label><span>Show Journal section on homepage</span><input type="checkbox" name="show_journal_section" value="1" <?=($profile['show_journal_section']??'1')==='1'?'checked':''?>></label>
<label><span>Show Portfolio section on homepage</span><input type="checkbox" name="show_portfolio_section" value="1" <?=($profile['show_portfolio_section']??'1')==='1'?'checked':''?>></label><button class="btn-editorial wide">Save homepage settings</button></form></section>
<section class="panel" id="editor"><p class="meta-label">CONTENT STUDIO</p><h2 class="h3"><?=$edit?'Edit content':'Create content'?></h2><p class="muted">Upload images for gallery, memories, birthdays, events and posts. Music accepts an external listening link or an audio file you own or are authorised to upload.</p>
<form class="form" method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?=$csrf?>"><input type="hidden" name="action" value="save_record"><input type="hidden" name="id" value="<?=(int)($edit['id']??0)?>">
<label>Content type<select name="section" required><?php foreach($types as $k=>$v):?><option value="<?=$k?>" <?=($edit['section']??'')===$k?'selected':''?>><?=htmlspecialchars($v)?></option><?php endforeach;?></select></label>
<label>Title<input name="title" required maxlength="255" value="<?=htmlspecialchars($edit['title']??'')?>"></label>
<label>Slug (optional)<input name="slug" value="<?=htmlspecialchars($editData['slug']??'')?>"></label><label>Date<input type="date" name="event_date" value="<?=htmlspecialchars($editData['event_date']??'')?>"></label>
<label>Category / album type<input name="category" value="<?=htmlspecialchars($editData['category']??'')?>"></label><label>Artist (music)<input name="artist" value="<?=htmlspecialchars($editData['artist']??'')?>"></label>
<label class="wide">Description / caption<textarea name="description" rows="3"><?=htmlspecialchars($editData['description']??$editData['excerpt']??$editData['caption']??'')?></textarea></label>
<label>Photo upload (JPG/PNG/WebP/GIF, max 8 MB)<input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif"></label>
<label>Audio upload (MP3/M4A/OGG/WAV/WebM, max 15 MB)<input type="file" name="audio" accept="audio/*"></label>
<label class="wide">Music platform URL (YouTube / Spotify etc.)<input type="url" name="external_url" value="<?=htmlspecialchars($editData['external_url']??'')?>"></label>
<label class="wide">Article body<textarea name="body" rows="7"><?=htmlspecialchars($editData['content']??'')?></textarea></label>
<label>Album slug for photo (optional)<input name="album_slug" value="<?=htmlspecialchars($editData['album_slug']??'')?>"></label><label>Location<input name="location" value="<?=htmlspecialchars($editData['location_approx']??'')?>"></label>
<label>Reading time (minutes)<input type="number" min="1" name="reading_time" value="<?= (int)($editData['reading_time_minutes']??3)?>"></label>
<label>Visibility<select name="visibility"><option value="public" <?=($edit['visibility']??'public')==='public'?'selected':''?>>Public</option><option value="private" <?=($edit['visibility']??'')==='private'?'selected':''?>>Private</option></select></label>
<label>Status<select name="status"><?php foreach(['draft','published','pending','archived'] as $st):?><option value="<?=$st?>" <?=($edit['status']??'draft')===$st?'selected':''?>><?=ucfirst($st)?></option><?php endforeach;?></select></label>
<button class="btn-editorial wide"><?= $edit?'Update record':'Save record'?></button></form></section>
<section class="panel" id="library"><div class="top"><div><p class="meta-label">CONTENT LIBRARY / MEDIA MANAGER</p><h2 class="h3">Your content</h2></div><form method="get"><select name="type" onchange="this.form.submit()"><option value="">All content types</option><?php foreach($types as $k=>$v):?><option value="<?=$k?>" <?=$filter===$k?'selected':''?>><?=htmlspecialchars($v)?></option><?php endforeach;?></select></form></div><div class="scroll"><table><thead><tr><th>Type</th><th>Title</th><th>Status</th><th>Visibility</th><th>Created</th><th>Manage</th></tr></thead><tbody><?php foreach($records as $r):?><tr><td><?=htmlspecialchars($types[$r['section']]??$r['section'])?></td><td><?=htmlspecialchars($r['title']??'')?></td><td><?=htmlspecialchars($r['status'])?></td><td><?=htmlspecialchars($r['visibility'])?></td><td><?=htmlspecialchars($r['created_at'])?></td><td><a href="?edit=<?=(int)$r['id']?>#editor">Edit</a><form method="post" style="display:inline" onsubmit="return confirm('Delete this record?')"><input type="hidden" name="csrf_token" value="<?=$csrf?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=(int)$r['id']?>"><button class="danger" type="submit">Delete</button></form></td></tr><?php endforeach;?><?php if(!$records):?><tr><td colspan="6">No content records yet.</td></tr><?php endif;?></tbody></table></div><p class="muted">Showing up to 100 records. Existing records are preserved unless you explicitly delete them.</p></section>
</main></body></html>