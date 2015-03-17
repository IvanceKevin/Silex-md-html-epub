<?php

require_once __DIR__. '/../vendor/michelf/php-markdown/Michelf/Markdown.inc.php';
require_once __DIR__.'/../vendor/autoload.php';

use \Michelf\Markdown;

    $app = new Silex\Application();

    # Fonction de base pour index.html
    $app->get('/', function () {
        $dossier = __DIR__. '/../data/';




        if($dossier = opendir($dossier))
        {

        }while($file = readdir($dossier)) {
        if($file != '.' && $file != '..' && !is_dir($dirname.$file))
        {
            echo '<a href="'.$dirname.$file.'">'.$file.'</a>'; # A REVOIR LE LISTING
        }
    }

closedir($dir);

    return 'Voici le catalogue';

});

#Fonction markdown
$app->get('/{book}.md', function ($book) use ($app) {

	
		$filename = '../data/'. $app->escape($book).'/README.md';
		// if (file_exists ($filename)){
  //   		$app->abort(404, "Post $book does not exist.");
  //   	}	

    	$var = file_get_contents($filename);
        createMetaInf($filename);
    	return '<pre>'.$var.'</pre>';
    //return  "<h1>{$post['title']}</h1>".
           // "<p>{$post['body']}</p>";
});

# Fonction HTML
$app->get('/{book}.html', function ($book) use ($app) {

    
        $filename = '../data/'. $app->escape($book).'/README.md';
        // if (file_exists ($filename)){
  //        $app->abort(404, "Post $book does not exist.");
  //    }   

        $var = file_get_contents($filename);
        $var = Markdown::defaultTransform($var);
        return $var;
    //return  "<h1>{$post['title']}</h1>".
           // "<p>{$post['body']}</p>";
});

$app->run();

 function createMetaInf($workDir)
{
    
    // create destination directory
    if (!mkdir("$workDir/META-INF")) {
        throw new Exception('Unable to create content META-INF directory');
    }
 return 'toto';
    // compile file
    $tpl = $this->initTemplateEngine(
        array(
            'tpl_dir'   => "{$this->params['templates_dir']}/",
            'tpl_ext'   => 'xml',
            'cache_dir' => sys_get_temp_dir() . '/'
        )
    );
    $tpl->assign('ContentDirectory', $this->params['content_dir']);
    $container = $tpl->draw('book/META-INF/container', true);
 
    // write compiled file to destination
    if (file_put_contents("$workDir/META-INF/container.xml", $container) === false) {
        throw new Exception("Unable to create content META-INF/container.xml");
    }
}


