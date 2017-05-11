<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $browsertitle; ?></title>
<link rel="shortcut icon" href="views/images/<?php echo $favicon ?>" type="image/x-icon">
<link rel="icon" href="views/images/<?php echo $favicon ?>" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="views/css/<?php echo $cssfile; ?>">
<script type="text/javascript" src="views/js/<?php echo $jsfile; ?>"></script>
</head>
<body>
<?php
require_once 'views/login.php';
?>
<hr>
</body>
</html>
