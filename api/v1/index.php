<?php
declare(strict_types=1);

/**
 * AHAMED DRAFTED — REST-Style PHP 8.2+ API Endpoint Router
 * Supports public read endpoints and authenticated CMS administration endpoints:
 *   GET    /api/v1/profile
 *   GET    /api/v1/posts
 *   GET    /api/v1/posts/{slug}
 *   GET    /api/v1/gallery
 *   GET    /api/v1/albums/{slug}
 *   GET    /api/v1/memories
 *   GET    /api/v1/friends
 *   GET    /api/v1/projects
 *   GET    /api/v1/timeline
 *   GET    /api/v1/events
 *   GET    /api/v1/places
 *   GET    /api/v1/guestbook
 *   POST   /api/v1/guestbook
 *   POST   /api/v1/admin/posts
 *   PUT    /api/v1/admin/posts/{id}
 *   DELETE /api/v1/admin/posts/{id}
 *   POST   /api/v1/admin/media/upload
 *   GET    /api/v1/media/private/{id}
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/archive.php';

use AhamedDrafted\Config\Database;
use AhamedDrafted\Config\Auth;

Auth::startSession();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/api/v1/profile', PHP_URL_PATH);
$routeQuery = trim((string)($_GET['route'] ?? ''), '/');
$path = $routeQuery !== '' ? $routeQuery : preg_replace('#^.*/api/v1/?#', '', trim((string)$uri, '/'));
$path = preg_replace('#^index\\.php/?#', '', $path);
$parts  = array_values(array_filter(explode('/', $path)));

$pdo  = Database::getConnection();
$data = get_ahamed_archive_data();

function json_response(array $payload, int $status = 200): never {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

$resource = $parts[0] ?? 'profile';
$param1   = $parts[1] ?? null;
$param2   = $parts[2] ?? null;

// PUBLIC ENDPOINTS
if ($method === 'GET') {
    switch ($resource) {
        case 'profile':
            json_response(['status' => 'ok', 'data' => $data['profile']]);

        case 'posts':
            $posts = array_values(array_filter($data['posts'], fn($p) => Auth::canViewRecord($p)));
            if ($param1 !== null) {
                foreach ($posts as $post) {
                    if ($post['slug'] === $param1 || (string)$post['id'] === $param1) {
                        json_response(['status' => 'ok', 'data' => $post]);
                    }
                }
                json_response(['status' => 'error', 'message' => 'Article not found.'], 404);
            }
            $category = $_GET['category'] ?? '';
            $q        = trim($_GET['q'] ?? '');
            if ($category !== '' && $category !== 'all') {
                $posts = array_values(array_filter($posts, fn($p) => strtolower($p['category']) === strtolower($category)));
            }
            if ($q !== '') {
                $posts = array_values(array_filter($posts, fn($p) =>
                    stripos($p['title'], $q) !== false || stripos($p['excerpt'], $q) !== false
                ));
            }
            json_response(['status' => 'ok', 'total' => count($posts), 'data' => $posts]);

        case 'gallery':
            $albums = array_values(array_filter($data['albums'], fn($a) => Auth::canViewRecord($a)));
            json_response([
                'status'      => 'ok',
                'albums'      => $albums,
                'photographs' => array_values(array_filter($data['photographs'], fn($ph) => ($ph['visibility'] ?? 'public') === 'public'))
            ]);

        case 'albums':
            if ($param1 === null) {
                json_response(['status' => 'ok', 'data' => $data['albums']]);
            }
            foreach ($data['albums'] as $album) {
                if (($album['slug'] === $param1 || (string)$album['id'] === $param1) && Auth::canViewRecord($album)) {
                    $photos = array_values(array_filter($data['photographs'], fn($ph) => ($ph['album_slug'] ?? '') === ($album['slug'] ?? '')));
                    $album['photographs'] = $photos;
                    json_response(['status' => 'ok', 'data' => $album]);
                }
            }
            json_response(['status' => 'error', 'message' => 'Album not found or private.'], 404);

        case 'memories':
            $memories = array_values(array_filter($data['memories'], fn($m) => Auth::canViewRecord($m)));
            json_response(['status' => 'ok', 'data' => $memories]);

        case 'friends':
            $friends = array_values(array_filter($data['friends'], fn($f) =>
                !empty($f['consent_approved']) && ($f['visibility'] ?? 'public') === 'public'
            ));
            json_response(['status' => 'ok', 'data' => $friends]);

        case 'projects':
            $projects = array_values(array_filter($data['projects'], fn($pr) => Auth::canViewRecord($pr)));
            json_response([
                'status'       => 'ok',
                'data'         => $projects,
                'education'    => $data['education'],
                'achievements' => $data['achievements'],
                'interests'    => $data['interests']
            ]);

        case 'timeline':
            json_response(['status' => 'ok', 'data' => $data['timeline']]);

        case 'events':
            json_response(['status' => 'ok', 'data' => $data['events']]);

        case 'places':
            json_response(['status' => 'ok', 'data' => $data['places']]);

        case 'guestbook':
            $approved = array_values(array_filter($data['guestbook'], fn($g) => $g['moderation_status'] === 'approved'));
            json_response(['status' => 'ok', 'data' => $approved]);

        case 'media':
            if ($param1 === 'private' && $param2 !== null) {
                Auth::requireAdminApi();
                json_response(['status' => 'ok', 'message' => 'Authorized private media stream ready.', 'media_id' => (int)$param2]);
            }
            break;
    }
}

// PUBLIC GUESTBOOK SUBMISSION
if ($method === 'POST' && $resource === 'guestbook') {
    $input = json_decode(file_get_contents('php://input') ?: '{}', true) ?: $_POST;
    $name  = trim(strip_tags((string)($input['visitor_name'] ?? '')));
    $msg   = trim(strip_tags((string)($input['message'] ?? '')));
    $link  = filter_var($input['public_profile_url'] ?? '', FILTER_VALIDATE_URL) ?: null;

    if ($name === '' || strlen($msg) < 5) {
        json_response(['status' => 'error', 'message' => 'Please provide your name and a thoughtful message (at least 5 characters).'], 422);
    }

    $slug = 'guestbook-' . bin2hex(random_bytes(8));
    $record = ['title'=>$name,'visitor_name'=>$name,'public_profile_url'=>$link,'message'=>$msg,'description'=>$msg,'moderation_status'=>'pending','created_at'=>date('Y-m-d H:i:s')];
    $stmt = $pdo->prepare("INSERT INTO content_records (section,slug,title,data_json,visibility,status,published_at) VALUES ('guestbook',?,?,?,'private','pending',NULL)");
    $stmt->execute([$slug,$name,json_encode($record, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR)]);

    json_response([
        'status'  => 'ok',
        'message' => 'Thank you. Your note has been recorded and queued for editorial moderation.',
        'entry'   => [
            'visitor_name'       => $name,
            'public_profile_url' => $link,
            'message'            => $msg,
            'moderation_status'  => 'pending',
            'created_at'         => date('Y-m-d H:i:s'),
        ]
    ], 201);
}

// ADMIN ENDPOINTS (/api/v1/admin/...)
if ($resource === 'admin') {
    Auth::requireAdminApi();
    if (in_array($method, ['POST','PUT','PATCH','DELETE'], true) && !Auth::verifyCsrfToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? null)) {
        json_response(['status'=>'error','message'=>'CSRF validation failed.'],403);
    }
    $adminModule = $param1 ?? 'posts';
    $recordId    = $param2 !== null ? (int)$param2 : null;
    $input       = json_decode(file_get_contents('php://input') ?: '{}', true) ?: $_POST;

    if ($adminModule === 'media' && $param2 === 'upload' && $method === 'POST') {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            json_response(['status' => 'error', 'message' => 'Valid image upload required.'], 400);
        }
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $mime    = mime_content_type($_FILES['file']['tmp_name']);
        if (!in_array($mime, $allowed, true)) {
            json_response(['status' => 'error', 'message' => 'Unsupported format. Use WebP, JPEG, or PNG.'], 415);
        }
        $safeName = time() . '-' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['file']['name']);
        $target   = __DIR__ . '/../../assets/images/' . $safeName;
        move_uploaded_file($_FILES['file']['tmp_name'], $target);
        json_response([
            'status'    => 'ok',
            'message'   => 'Photograph added to Media Library.',
            'file_path' => 'assets/images/' . $safeName
        ], 201);
    }

    $allowedModules = ['posts'=>'journal','albums'=>'album','photographs'=>'photograph','memories'=>'memory','events'=>'event','timeline'=>'timeline','birthdays'=>'birthday','songs'=>'song','projects'=>'project','places'=>'place','friends'=>'friend','education'=>'education','achievements'=>'achievement','interests'=>'interest'];
    $section = $allowedModules[$adminModule] ?? null;
    if ($section === null) json_response(['status'=>'error','message'=>'Unknown CMS module.'],404);
    if ($method === 'POST') {
        $title = trim((string)($input['title'] ?? ''));
        if ($title === '') json_response(['status'=>'error','message'=>'Title is required.'],422);
        $slug = trim((string)($input['slug'] ?? '')) ?: trim(preg_replace('/[^a-z0-9]+/i','-',strtolower($title)),'-');
        $visibility = ($input['visibility'] ?? 'private') === 'public' ? 'public' : 'private';
        $status = in_array(($input['status'] ?? 'draft'),['draft','published','pending','archived'],true) ? $input['status'] : 'draft';
        $stmt = $pdo->prepare('INSERT INTO content_records(section,slug,title,data_json,visibility,status,published_at) VALUES(?,?,?,?,?,?,?)');
        $stmt->execute([$section,$slug,$title,json_encode($input,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR),$visibility,$status,$status==='published'?date('Y-m-d H:i:s'):null]);
        json_response(['status'=>'ok','id'=>(int)$pdo->lastInsertId(),'message'=>'Record created.'],201);
    }
    if (($method === 'PUT' || $method === 'PATCH') && $recordId !== null) {
        $find=$pdo->prepare('SELECT data_json FROM content_records WHERE id=? AND section=?');
        $find->execute([$recordId,$section]);$existing=$find->fetchColumn();
        if($existing===false) json_response(['status'=>'error','message'=>'Record not found.'],404);
        $merged=array_replace(json_decode((string)$existing,true) ?: [],$input);
        $title=trim((string)($merged['title']??''));$slug=trim((string)($merged['slug']??''));$visibility=($merged['visibility']??'private')==='public'?'public':'private';
        $status=in_array(($merged['status']??'draft'),['draft','published','pending','archived'],true)?$merged['status']:'draft';
        $stmt=$pdo->prepare('UPDATE content_records SET title=?,slug=?,data_json=?,visibility=?,status=?,published_at=? WHERE id=? AND section=?');
        $stmt->execute([$title,$slug,json_encode($merged,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR),$visibility,$status,$status==='published'?date('Y-m-d H:i:s'):null,$recordId,$section]);
        json_response(['status'=>'ok','message'=>'Record updated.']);
    }
    if ($method === 'DELETE' && $recordId !== null) {
        $stmt=$pdo->prepare('DELETE FROM content_records WHERE id=? AND section=?');$stmt->execute([$recordId,$section]);
        if($stmt->rowCount()===0) json_response(['status'=>'error','message'=>'Record not found.'],404);
        json_response(['status'=>'ok','message'=>'Record deleted.']);
    }
    json_response(['status'=>'error','message'=>'Method not allowed.'],405);
}

json_response(['status' => 'error', 'message' => 'Endpoint not found.'], 404);
