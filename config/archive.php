<?php
declare(strict_types=1);

use AhamedDrafted\Config\Database;

require_once __DIR__ . '/database.php';

function get_ahamed_archive_data(): array {
    $empty = [
        'profile' => [
            'tagline'=>'Personal Digital Journal & Editorial Portfolio',
            'short_bio'=>'', 'display_name'=>'', 'hero_headline'=>'', 'hero_supporting'=>'',
            'profile_image'=>'', 'established_label'=>'', 'education_summary'=>'',
            'current_focus'=>'', 'philosophy_quote'=>'', 'full_biography'=>'',
            'favourite_things'=>[], 'social_links'=>[]
        ],
        'posts'=>[], 'categories'=>[], 'albums'=>[], 'photographs'=>[],
        'memories'=>[], 'friends'=>[], 'events'=>[], 'places'=>[],
        'projects'=>[], 'timeline'=>[], 'guestbook'=>[], 'education'=>[],
        'achievements'=>[], 'interests'=>[]
    ];

    $pdo = Database::getConnection();
    $settings = $pdo->query('SELECT setting_key, setting_value FROM site_settings')->fetchAll();
    foreach ($settings as $setting) {
        if ($setting['setting_key'] === 'profile') {
            $profile = json_decode((string)$setting['setting_value'], true);
            if (is_array($profile)) $empty['profile'] = array_replace($empty['profile'], $profile);
        }
    }

    $stmt = $pdo->prepare(
        "SELECT section, data_json FROM content_records
         WHERE visibility = 'public' AND status = 'published'
         ORDER BY COALESCE(published_at, created_at) DESC, id DESC"
    );
    $stmt->execute();
    $sectionMap = [
        'journal'=>'posts', 'category'=>'categories', 'album'=>'albums',
        'photograph'=>'photographs', 'memory'=>'memories', 'friend'=>'friends',
        'event'=>'events', 'place'=>'places', 'project'=>'projects',
        'timeline'=>'timeline', 'guestbook'=>'guestbook', 'education'=>'education',
        'achievement'=>'achievements', 'interest'=>'interests'
    ];
    foreach ($stmt->fetchAll() as $row) {
        $target = $sectionMap[$row['section']] ?? null;
        if ($target === null) continue;
        $record = json_decode((string)$row['data_json'], true);
        if (is_array($record)) $empty[$target][] = $record;
    }
    return $empty;
}
