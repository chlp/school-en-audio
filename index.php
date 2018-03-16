<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>English Spotlight Starter</title>
    <meta content="width=device-width, initial-scale=0.6" name="viewport">
    <style>
        body {
            margin: 0;
            padding: 0;
            color: #666;
            font-family: sans-serif;
            line-height: 1.4;
        }

        h1 {
            color: #444;
            font-size: 1.2em;
            padding: 14px 2px 12px;
            margin: 0px;
        }

        h1 em {
            font-style: normal;
            color: #999;
        }

        a {
            color: #888;
            text-decoration: none;
        }

        .wrapper {
            width: 460px;
            margin: 10px auto;
        }

        .wrapper-top {
            position: fixed;
            z-index: 1;
            top: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, .8);
        }

        ul {
            padding: 0px;
            margin: 100px 0 0;
            list-style: none;
            color: #ccc;
            width: 460px;
            border-top: 1px solid #ccc;
            font-size: 0.9em;
        }

        ul li {
            position: relative;
            margin: 0px;
            padding: 9px 2px 10px;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
        }

        ul li a {
            display: block;
        }

        li.playing {
            color: #aaa;
            text-shadow: 1px 1px 0px rgba(255, 255, 255, 0.3);
        }

        li.playing a {
            color: #000;
        }

        li.playing:before {
            content: '♬';
            width: 14px;
            height: 14px;
            padding: 3px;
            line-height: 14px;
            margin: 0px;
            position: absolute;
            left: -24px;
            top: 9px;
            color: #000;
            font-size: 13px;
            text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.2);
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="audiojs/audio.js"></script>
    <script>
        $(function () {
            // Setup the player to autoplay the next track
            var a = audiojs.createAll({
                trackEnded: function () {
                    if (this.settings.loop) {
                        this.play.apply(this);
                    } else {
                        var next = $('.playing').next();
                        if (next.length) {
                            next.click();
                        }
                    }
                }
            });

            var audio = a[0];

            // Load in a track on click
            $('ul li').click(function (e) {
                e.preventDefault();
                $('.playing').removeClass('playing');
                $(this).addClass('playing');
                audio.load($('a', this).attr('data-src'));
                audio.play();
                $('.wrapper-top h1').text($('a', this).text());
            });
        });
    </script>
</head>
<body>
<div class="wrapper-top">
    <div class="wrapper">
        <h1>English Spotlight Starter</h1>
        <audio preload="auto"></audio>
    </div>
</div>
<div class="wrapper">
    <ul>
        <?php
        function getFiles($dir, $extension)
        {
            if (!function_exists('getFilesRecursive')) {
                function getFilesRecursive($dir, $extension, &$results = array())
                {
                    $files = scandir($dir);

                    foreach ($files as $key => $value) {
                        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
                        if (!is_dir($path)) {
                            if (@pathinfo($value)['extension'] === $extension) {
                                $results[] = $path;
                            }
                        } else if ($value != "." && $value != "..") {
                            getFilesRecursive($path, $extension, $results);
                        }
                    }

                    return $results;
                }
            }

            $result = getFilesRecursive($dir, $extension);
            foreach ($result as &$r) {
                $r = mb_substr($r, mb_strlen($dir) + 1);
            }

            return $result;
        }

        foreach (getFiles(__DIR__ . '/english-book', 'mp3') as $file) {
            echo "
            <li><a href=\"#\" data-src=\"english-book/$file\">$file</a></li>
            ";
        }
        ?>
    </ul>
</div>
</body>
</html>
