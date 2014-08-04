<html>
<head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<style>
img {padding: 3px;}
</style>

</head>
<body>
<div class="container-fluid" style="margin: 15px;">
<div class="row">
<?php
$files = scandir(".");

$open_panel = false;
$handle = @fopen("main.txt", "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        if(substr($buffer,0,1)==" ") {
            if($open_panel) echo "</div></ul>";
            echo "<h1>".substr($buffer,1,-1)."</h1>".PHP_EOL;
            $open_panel = false;
        } elseif(preg_match('/^(Level.*: )?([0-9]+)$/',substr($buffer,0,-1),$matches)) {
            $handle2 = @fopen($matches[2].".txt", "r");
            if ($handle2) {
                $buffer2 = fgets($handle2, 4096);
                echo "<li class=\"list-group-item\">".PHP_EOL;
                echo "<h3>".$matches[1].substr($buffer2,0,-1)."</h3>".PHP_EOL;
                foreach($files as $file) {
                    if(preg_match("/^".$matches[2]."-[0-9].jpg$/",$file)) echo "<img src=\"".$file."\" class=\"img-rounded\" width=\"180\" />";
                }
                while (($buffer2 = fgets($handle2, 4096)) !== false) {
                    echo "<p>".substr($buffer2,0,-1)."</p>".PHP_EOL;
                }
                if (!feof($handle2)) {
                    echo "Error: unexpected fgets() fail\n";
                }
                fclose($handle2);
                echo "</li>";
            }
        } else {
            if($open_panel) echo "</div></ul>";
            echo "<div class=\"panel panel-default\"><div class=\"panel-heading\">".substr($buffer,0,-1)."</div><ul class=\"list-group\">".PHP_EOL;
            $open_panel = true;
        }
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}
?>
</div>
</div>
</html>
</body>
