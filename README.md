Silex-md-html-epub
============

Silex-md-html-epub v1 23 Mars 2015

by Ivance Kevin
Professor : Demko Christophe

- [Introduction](#Introduction)
- [Nodejs](#nodejs)
  - [MongoDB](#mongodb)
  - [Server](#server)
- [Installation](#installation)
  - [Dependencies](#dependencies)
  - [ezseed](#ezseed)
- [Update](#update)
- [Configuration](#configuration)
- [SFTP](#sftp)
- [Streaming](#streaming)
- [Known Issues](#known-issues)

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

