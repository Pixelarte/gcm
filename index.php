 <?php
 	require_once 'php/lib/Mobile_Detect.php';
    require_once 'php/lib/Token.php';

    session_start();
    $_SESSION['token']=getTokenTime();
    $num=$_SESSION['token'];

    $detect = new Mobile_Detect;
    $dispositivo = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'movil') : 'escritorio');
?>
<!doctype html>
<html lang="es">
<head>
  	<meta name="google-site-verification" content="HgNxQq4vkTNOH3bZZx8zSDOezaK5JJT7VhXZKMifMbk" />
  	
  	<link rel="manifest" href="assets/js/libs/manifest.json">

  	<script>
        var dispositivo="<?php echo $dispositivo ?>";
        var num="<?php echo $num; ?>";
    </script>
</head>
<body>
<a href="#" id="subscribe" style="display:none;"></a>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="assets/js/main.min.js"></script>

</body>