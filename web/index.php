<?php

require_once __DIR__.'/../vendor/autoload.php';

use \Michelf\Markdown;
use \Symfony\Component\Yaml\Yaml;

/**
* Création et initialisation de l'application Silex 
*/
$app = new Silex\Application();

/**
  * Utilisation du micro-framework en testant la redirection d'url apour affichier la liste des livres
  */
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

/**
    * On utilise l'application Silex pour la redirection des livres par son nom pour l'afficher en MarkDown 
    * @param Silex\Application $app 
    * @param string $book
*/
$app->get('/{book}.md', function (Silex\Application $app, $book) {  
  if (file_exists('../data/'.$book) && file_exists('../data/'.$book.'/README.md') ) {
    $text = file_get_contents('../data/'. $app->escape($book).'/README.md');
  } else {
     $app->abort(404, "Le bouquin $book est introuvable.");
  } 
  //createMetaInf($filename);
  //CreateEPUB($app->escape($book));
  return '<pre>'.$text.'</pre>';
});

/**
    * On utilise l'application Silex pour la redirection des livres par son nom pour l'afficher en html 
    * @param Silex\Application $app 
    * @param string $book
*/
$app->get('/{book}.html', function (Silex\Application $app, $book) {
  if (file_exists('../data/'.$book) && file_exists('../data/'.$book.'/README.md') ) {
    $text = file_get_contents('../data/'.$book.'/README.md');
    $html = Markdown::defaultTransform($text);
  } else {
    $app->abort(404, "Le bouquin $book est introuvable.");
  }
  return  $html;
});

/**
    * On utilise l'application Silex pour la redirection des livres par son nom pour l'afficher en epub 
    * @param Silex\Application $app 
    * @param string $book
*/
$app->get('/{book}.epub', function (Silex\Application $app, $book) {
  if (file_exists('../data/'.$book) && file_exists('../data/'.$book.'/README.md') ) {
    $text = file_get_contents('../data/'.$book.'/README.md');

    $html = Markdown::defaultTransform($text);
    $zip = new ZipArchive();
    $file = './modele.zip';
    $newfile = '../data/'.$book.'/'.$book.'.epub';
    copy($file, $newfile);
    if($zip->open('../data/'.$book.'/'.$book.'.epub') === true) {
       if (file_exists('../data/'.$book.'/meta.yaml')){
         $yaml = Yaml::parse(file_get_contents('../data/'.$book.'/meta.yaml'));
         $title = $yaml['title'];
         $identifier = $yaml['identifier'];
         $language = $yaml['language'];
         /*****************************************
        * ECRITURE DU FICHIER CONTENT.XML
             * Contient le contenu textuel de l'eBook. C'est-à-dire le titre ainsi que le tout le contenu textuel du livre.
            ******************************************/
      //  $zip->addFromString('content.xhtml', ContentXML($html,$title));
         /*****************************************
             * ecriture du fichier META-INF/container.xml
             * Le fichier container.xml contient la liste de tous les fichiers de la racine dans le fichier EPUB.
             * Si un fichier EPUB contient plus d'un livre, le fichier container.xml contiendra des références à plus d'un fichier racine.
             * La partie intéressante de ce fichier container.xml est l'élément <rootfile>.
             * Cet element rootfile pointe vers la racine du livre EPUB.
             * Le fichier racine est le fichier qui contient content.opf contenant lui-même les méta-données sur le livre EPUB.
             *
            ******************************************/
        $zip->addFromString('META-INF/container.xml',ContainerXML()); 
           /*****************************************
             * ecriture du fichier content.opf
             * Les métadonnées suivantes doivent être renseignées dans le fichier content.opf.
             * Le fichier OPF (Open Packaging Format) permet d’indiquer au système de lecture quelle
             * est la structure et le contenu d’un fichier epub. Il est principalement composé de meta-données
             * et d’un manifeste servant à référencer les fichiers qui composent le livre numérique.
             *

            ******************************************/

            

          $zip->addFromString('content.opf', ContentOPF($zip,$html,$title, $identifier,$language));
         /*****************************************
             * ECRITURE DU FICHIER TOC.XML
             * Ce fichier TOC (Table of Content) contient la table des matieres du livre epub.
             *
            ******************************************/

            

          $zip->addFromString('toc.ncx', ContentNCX($html,$identifier,$title));
          $cont .= '<a href="../data/'.$book.'/'.$book.'.epub">Télécharger</a><br />';
          $cont .= '<a href="index.php">Retour</a>';  
         

        }
        else{
         $zip->addFromString('content.xhtml', ContentXML($html,$title));
         /*****************************************
             * ecriture du fichier META-INF/container.xml
             * Le fichier container.xml contient la liste de tous les fichiers de la racine dans le fichier EPUB.
             * Si un fichier EPUB contient plus d'un livre, le fichier container.xml contiendra des références à plus d'un fichier racine.
             * La partie intéressante de ce fichier container.xml est l'élément <rootfile>.
             * Cet element rootfile pointe vers la racine du livre EPUB.
             * Le fichier racine est le fichier qui contient content.opf contenant lui-même les méta-données sur le livre EPUB.
             *
            ******************************************/
  

            

          $zip->addFromString('content.opf', ContentOPF($zip,$html,$title, $identifier,$language));
         /*****************************************
             * ECRITURE DU FICHIER TOC.XML
             * Ce fichier TOC (Table of Content) contient la table des matieres du livre epub.
             *
            ******************************************/

            

          $zip->addFromString('toc.ncx', ContentNCX($identifier,$title));
          $cont .= '<a href="../data/'.$book.'/'.$book.'.epub">Télécharger</a><br />';
          $cont .= '<a href="index.php">Retour</a>';  
        }  

      }
      else {
                $cont = "Impossible d'ouvrir &quot;Zip.zip<br/>";
            }
        } else {
            $app->abort(404, "Book $book does not exist.");
        }
          
        return  $cont;
    });

function CreateFile( $file, $content = null ) {
  $handle = fopen( $file, 'w+' );
  $ler = fwrite( $handle, $content );
  fclose($handle);
}
    
function ContentXML($h1,$title) {
  $cont=$h1;

  $ti=$title;
  $contentxml = '<?xml version="1.0" encoding="UTF-8" ?>
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
      <head>
      <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
     <title>'.$ti.'
      </title></head>
      <body>'.$cont.'</body>
      </html>';
  return $contentxml;   
}

function ContainerXML() {
   $containerxml = '<?xml version="1.0" ?>
            <container version="1.0" xmlns="urn:oasis:names:tc:opendocument:xmlns:container">
            <rootfiles>
            <rootfile full-path="content.opf" media-type="application/oebps-package+xml"
            />
            </rootfiles>
            </container>';
  return $containerxml;   
}
  
    /**
     * Open OPF
     *
     * Fill the content.opf file ($opf property)
     */    
    function ContentOPF($zip,$html,$title, $identifier,$language) {
          $dom = new DOMDocument;
          @$dom->loadHTML($html);

          $xpath = new DOMXpath($dom);


          $elementH1 = $xpath->query("/html/body/h1");
          $elementH2 = $xpath->query("/html/body/h2");
          var_dump($html);
       /*  $contentopf = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
            <package xmlns="http://www.idpf.org/2007/opf" unique-identifier="BookID" version="2.0" >
            <metadata xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:opf="http://www.idpf.org/2007/opf" >
            <dc:title>'.$title.'</dc:title>
            <dc:identifier id="BookID" opf:scheme="CustomID">'.$identifier.'</dc:identifier>
            <dc:language>'.$language.'</dc:language>
            </metadata>
            <manifest>
            <item id="page1" href="content.xhtml" media-type="application/xhtml+xml" />
            <item id="ncx" href="toc.ncx" media-type="application/x-dtbncx+xml" />
            </manifest>
            <spine toc="ncx" >
            <itemref idref="page1" />
            </spine>
            </package>';*/
          
    
           $contentopf = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
            <package xmlns="http://www.idpf.org/2007/opf" unique-identifier="BookID" version="2.0" >
            <metadata xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:opf="http://www.idpf.org/2007/opf" >
            <dc:title>'.$title.'</dc:title>
            <dc:identifier id="BookID" >'.$identifier.'</dc:identifier>
            <dc:language>'.$language.'</dc:language>
            </metadata>
            <manifest>
            <item id="ncx" href="toc.ncx" media-type="application/x-dtbncx+xml" /> 
            ';
            $key=1;
           if (!is_null($elementH2)) {
              foreach ($elementH2 as $element) {                
                  $nodes = $element->childNodes;
                  foreach ($nodes as $node) {
                    $page = 'h1_' . $key;  
                    $h1='<h1>'.$node->nodeValue.'</h1>';
                    $zip->addFromString($page.'.xhtml',ContentXML($h1,$title));
                    echo 'KEy : ' .$key1 . 'Nom : '. $node->nodeValue .'Page : ' . $page .'\n' ;
                    $contentopf .= '<item id="' .  $key . '" href="' .  $page . '.xhtml" media-type="application/xhtml+xml" />';
                     $key++;
                  }
              }
            }
            $contentopf .= '</manifest><spine toc="ncx" >';
            $key2=1;
             if (!is_null($elementH1)) {
              foreach ($elementH1 as $element) {
                 
                  $nodes = $element->childNodes;
                  foreach ($nodes as $node) {
                    $page = 'h1_' . $key2;  
                    $contentopf .= '<itemref idref="' . $key2 . '" />' . "\r\n";
                     $key2++;
                  }
              }
            }
            $contentopf .=' </spine>
            
            </package>';
           
            return $contentopf;
    }
    
 /**
     * Open NCX
     *
     * Fill the toc.ncx content ($ncx property)
     */    
    function ContentNCX($html,$identifier,$title) {
        $dom = new DOMDocument;
          @$dom->loadHTML($html);

          $xpath = new DOMXpath($dom);


         $elementH1 = $xpath->query("/html/body/h1");
        $key1=1;
         $toc = '<?xml version="1.0" encoding="UTF-8" ?>
            <!DOCTYPE html>
          <ncx xmlns="http://www.daisy.org/z3986/2005/ncx/" version="2005-1">
            <head>
            <meta name="dtb:uid" content="'.$identifier.'" />
            </head>
            <docTitle>
            <text>'.$title.'</text>
            </docTitle>
            <navMap>';
            if (!is_null($elementH1)) {
              foreach ($elementH1 as $element) {

                  $nodes = $element->childNodes;
                  foreach ($nodes as $node) {
                    $page = 'h1_';
                      $toc  .= '<navPoint id="' . $page .''.$key1 . '" playOrder="' . $key1 . '">' . "\r\n";                     
                      $toc .= '<navLabel>' . "\r\n";
                      $toc .= '<text>' . $node->nodeValue . '</text>' . "\r\n";
                      $toc .= '</navLabel>' . "\r\n";
                      $toc .= '<content src="' . $page .''.$key1 . '.xhtml"/>' . "\r\n";
                      $toc .= '</navPoint>' . "\r\n";
                      $key1++;
                  }
              }
            }
            
           $toc .=' </navMap>
            </ncx>';/*
            /*$toc = '<?xml version="1.0" encoding="UTF-8" ?>
            <ncx version="2005-1" xmlns="http://www.daisy.org/z3986/2005/ncx/" >
            <head>
            <meta name="dtb:uid" content='.$identifier.' />
            </head>
            <docTitle>
            <text>Hello World</text>
            </docTitle>
           <navMap>
        <navPoint class="h1" id="ch1">
            <navLabel>
                <text>Chapter 1</text>
            </navLabel>
            <content src="content.html#ch_1"/>
            <navPoint class="h2" id="ch_1_1">
                <navLabel>
                    <text>Chapter 1.1</text>
                </navLabel>
                <content src="content.html#ch_1_1"/>
            </navPoint>
        </navPoint>
        <navPoint class="h1" id="ncx-2">
            <navLabel>
                <text>Chapter 2</text>
            </navLabel>
            <content src="content.html#ch_2"/>            
        </navPoint>
    </navMap>
            </ncx>';*/
        return $toc;
    }
    




$app->run();


