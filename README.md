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
L' ensemble des exercices va nous conduire à créer dynamiquement des livres au format epub à partir de fichier écrits au format markdown. Le format markdown a été inventé conjointement par :
	- [John Gruber](http://daringfireball.net) , célèbre blogueur américain du site 

• Aaron Swartz , informaticien et militant de l'internet, luttant notamment contre les lois PIPA/SOPA
américaines, décédé en janvier 2013 par suicide. Ce projet pédagogique veut aussi lui rendre
hommage.
Votre projet devra utiliser
• un serveur internet (utilisez celui fourni par PHP)
5
• le micro-framework silex pour la redirection des URL
6
• la librairie PHP Markdown pour la transformation des fichiers au format markdown vers le format
html.
9
http://creativecommons.org/licenses/by-nc-nd/2.0/fr/
http://www.idpf.org/epub/20/spec/OPF_2.0_latest.htm
2
http://fr.wikipedia.org/wiki/Markdown
3
http://fr.wikipedia.org/wiki/John_Gruber
4
http://fr.wikipedia.org/wiki/Aaron_Swartz
5
http://silex.sensiolabs.org
6
https://github.com/michelf/php-markdown
1
1Diffusion
7
• la librairie Yaml du projet symfony pour l'analyse des méta-informations du livre électronique.
• un (des) fichier(s) xslt pour la transformation de fichiers html.
8
Pour tester les livres électroniques construits, vous pourrez utiliser le validateur de fichiers epub.
TP1, créer dynamiquement des livres au format epub à 2 partir de fichier écrits au format markdown.
