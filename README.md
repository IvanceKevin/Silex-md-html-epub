Silex-md-html-epub
============

Silex-md-html-epub v1 23 Mars 2015

by Ivance Kevin
Professor : Demko Christophe

- [Introduction](#introduction)
- [Affichagemd](#affichagemd)
- [Affichagehtml](#affichagehtml)
- [LivreEpub](#livreepub)
	- [LivreElectronique](#livreelectronique)
	- [AjoutMetaDonnees](#ajoutmetadonnees)

## Introduction
L' ensemble des exercices va nous conduire à créer dynamiquement des livres au format [epub](http://www.idpf.org/epub/20/spec/OPF_2.0_latest.htm) à partir de fichier écrits au format markdown. Le format [markdown](http://fr.wikipedia.org/wiki/Markdown) a été inventé conjointement par :
- [John Gruber](http://fr.wikipedia.org/wiki/John_Gruber) , célèbre blogueur américain du site [http://daringfireball.net](http://daringfireball.net).
-  [Aaron Swartz](http://fr.wikipedia.org/wiki/Aaron_Swartz) , informaticien et militant de l'internet, luttant notamment contre les lois PIPA/SOPA américaines, décédé en janvier 2013 par suicide. Ce projet pédagogique veut aussi lui rendre hommage.

Notre projet utilisera
-  un server internet qui sera PHP à l'aide de cette commande :
```
	php -S localhost:8000
```
- le micro-framework [silex](http://silex.sensiolabs.org) pour la redirection des URL
- la librairie [PHP Markdown](PHP Markdown) pour la transformation des fichiers au format markdown vers le format html.
- la librairie [Yaml](https://github.com/symfony/Yaml) du projet symfony pour l'analyse des méta-informations du livre électronique.
- un (des) fichier(s) xslt pour la transformation de fichiers html.

Pour valider nos livres électroniques construits, on a utilisé  le [validateur](http://validator.idpf.org) de fichiers epub.


## Affichagemd

Cette partie consiste à écrire un ensemble de fichiers PHP permettant d'afficher des fichiers au format markdown ayant comme extension .md

La structure du projet est la suivante : 

![structure](./images/structure.png "structure du projet")

- Le fichier **web/index.php** est le point d'entrée de votre serveur, il sera écrit en utilisant la librairie
silex et la librairie PHP markdown.
- Le dossier lib servira à stocker le code des librairies que vous utiliserez.
- Les dossiers situés dans le dossier data contiendront les fichiers sources au format markdown.

Pour affichier des URL de la forme **http://localhost:8000/{book}.md** dont le **{book}** est un dossier existant dans le dossier **data**.
L'affichage de cette URL a pour but d'afficher le contenu du fichier **README.md** situé à l'intérieur de ce dossier

Exemple : 
	```
	http://localhost:8000/index.php/book1.md
	```

Il devrait affichier le contenu du fichier **data/book1/README.md**

![structure](./images/screen1_README_md.png "Image screen README.md")

Code associé

```
	$app->get('/{book}.md', function (Silex\Application $app, $book) {  
  if (file_exists('../data/'.$book) && file_exists('../data/'.$book.'/README.md') ) {
    $text = file_get_contents('../data/'. $app->escape($book).'/README.md');
  } else {
     $app->abort(404, "Le bouquin $book est introuvable.");
  } 
  return '<pre>'.$text.'</pre>';
});
```

**$app** :  On utilise l'application Silex pour la redirection des livres par son nom pour l'afficher en MarkDown 

**$book** : On récupère le nom du fichier md taper dans l'url ({book})

On vérifie si le **nom du livre** (le nom du dossier) existe dans le dossier **data/** et si dans le dossier du livre il existe un fichier **README.md** 
Si ce n'est pas le cas l'application retournera une page 404. 

Si c'est le cas il affichera le livre en format md.

## Affichagehtml

Pour affichier des URL de la forme **http://localhost:8000/{book}.html** dont le **{book}** est un dossier existant dans le dossier **data**.
L'affichage de cette URL a pour but d'afficher le contenu du fichier **README.md** situé à l'intérieur de ce dossier
Pour l'afficher en html, nous allons le convertir en utilisant la librairie PHP Markdown sur le fichier README.md.

Exemple : 
	```
	http://localhost:8000/index.php/book1.html
	```

Il devrait affichier le contenu du fichier **data/book1/README.md**

![structure](./images/screen1_README_md_html.png "Image screen README.md en html")

Code associé

```
$app->get('/{book}.html', function (Silex\Application $app, $book) {
  if (file_exists('../data/'.$book) && file_exists('../data/'.$book.'/README.md') ) {
    $text = file_get_contents('../data/'.$book.'/README.md');
    $html = Markdown::defaultTransform($text);
  } else {
    $app->abort(404, "Le bouquin $book est introuvable.");
  }
  return  $html;
});
```

**$app** :  On utilise l'application Silex pour la redirection des livres par son nom pour l'afficher en html  

**$book** : On récupère le nom du fichier html taper dans l'url ({book})

On vérifie si le **nom du livre** (le nom du dossier) existe dans le dossier **data/** et si dans le dossier du livre il existe un fichier **README.md** 
Si ce n'est pas le cas l'application retournera une page 404. 

Si c'est le cas on utilise la librairie PHP Markdown pour le transformer en format **html** et l'afficher.

## LivreEpub
## LivreElectronique

Pour télécharger le fichier le livre en format epub il faudra taper  l'URL de la forme **http://localhost:8000/{book}.html** dont le **{book}** est un dossier existant dans le dossier **data**.

L'affichage de cette URL a pour but de vous permettre de télécharger le fichier **README.md** convertit en format epub.
Ca vous permettra de l'ajouter dans l'un de vos logiciels préférés de bibliothèque Ebooks

Exemple : 
	```
	http://localhost:8000/index.php/book1.epub
	```

Code associé

```
	$app->get('/{book}.epub', function (Silex\Application $app, $book) {
  	if (file_exists('../data/'.$book) && file_exists('../data/'.$book.'/README.md') ) {
		$text = file_get_contents('../data/'.$book.'/README.md');
	    $html = Markdown::defaultTransform($text);
	    $zip = new ZipArchive();
	    $file = './modele.zip';
	    $newfile = '../data/'.$book.'/'.$book.'.epub';
	    copy($file, $newfile);
	    $zip->addFromString('META-INF/container.xml',ContainerXML()); 
	    $zip->addFromString('content.opf', ContentOPF($zip,$html,$title, $identifier,$language));
	    $zip->addFromString('toc.ncx', ContentNCX($html,$identifier,$title));
	    $cont .= '<a href="../data/'.$book.'/'.$book.'.epub">Télécharger</a><br />';
	    $cont .= '<a href="index.php">Retour</a>';  
	    return $cont;
	}
});
```

**$app** :  On utilise l'application Silex pour la redirection des livres par son nom pour l'afficher en html  

**$book** : On récupère le nom du fichier html taper dans l'url ({book})

On vérifie si le **nom du livre** (le nom du dossier) existe dans le dossier **data/** et si dans le dossier du livre il existe un fichier **README.md** 
Si ce n'est pas le cas l'application retournera une page 404. 

Si c'est le cas on  va générer un fichier epub en associant les informations que l'on récupère en html

Création d'un epub : 
- Structure d'un fichier epub :

	![structure](./images/EpubStructure.png "Structure epub")

Nous avons utilisés une **archive** zip nommé "modele.zip" situé dans le dossier **web/** pour créer notre epub.

Notre modele zip contient :
	- le minetype.

Nous ajoutons dans dans l'epub :

	- META-INF/container.xml
	- content.opf
	- toc.ncx
contenant les informations du fichier **README.mb** transformer en html.

## AjoutMetaDonnees

Pour l'ajout de méta données, nous regardons si il existe un fichier **meta.yaml** situé dans le dossier du livre électronique qui va permette de préciser : 
	- le titre du livre électronique
	- l'indentifiant du livre électronique
	- le langage du livre électronique

Code associé

```
use \Symfony\Component\Yaml\Yaml;
	 if (file_exists('../data/'.$book.'/meta.yaml')){
         $yaml = Yaml::parse(file_get_contents('../data/'.$book.'/meta.yaml'));
         $title = $yaml['title'];
         $identifier = $yaml['identifier'];
         $language = $yaml['language'];

        $zip->addFromString('META-INF/container.xml',ContainerXML()); 

        $zip->addFromString('content.opf', ContentOPF($zip,$html,$title, $identifier,$language));
        $zip->addFromString('toc.ncx', ContentNCX($html,$identifier,$title));
        $cont .= '<a href="../data/'.$book.'/'.$book.'.epub">Télécharger</a><br />';
        $cont .= '<a href="index.php">Retour</a>';  
         

    }
        return  $cont;
});
```

On utilise la représentation de de données **YAML** pour récupérer les informations suivantes : 
	- le titre 
	- l'identifiant
	- le language



 