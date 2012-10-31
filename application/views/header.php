<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

<!-- Basic Page Needs
================================================== -->
<meta charset="utf-8">
<title>Football championship</title>
<meta name="description" content="">
<meta name="author" content="">

<!-- JS
================================================== -->
<script type="text/javascript" src=<?=base_url('data/js/jquery-1.8.2.min.js');?>></script>
<script type="text/javascript" src=<?=base_url('data/js/script.js');?>></script>
<script type="text/javascript" >
    var site_url = '<?=site_url()?>';
    var base_url = '<?=base_url()?>';
</script>
<!-- CSS
================================================== -->
<?=link_tag('data/stylesheets/base.css');?>
<?=link_tag('data/stylesheets/skeleton.css');?>
<?=link_tag('data/stylesheets/layout.css');?>
<?=link_tag('data/stylesheets/site_style.css');?>

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

</head>
<body>
    <div class="container">
        <div class="sixteen columns">
			<h1 class="remove-bottom" style="margin-top: 40px">Football championship</h1>
			<h5>Version 1.0</h5>
			<hr />
		</div>
        <div class = "four columns sidebar">
            <nav>
                <ul>
                    <li><?=anchor(site_url('team'), 'Список команд')?></li>
                    <li><?=anchor(site_url('championship'), 'Календарь матчей')?></li>
                    <li><?=anchor(site_url('team/view'), 'Результаты матчей')?></li>
                </ul>
            </nav>
        </div>
        <div class = "twelve columns">
        
	
