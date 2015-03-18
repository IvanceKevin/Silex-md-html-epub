<?php

require_once __DIR__.'/../vendor/autoload.php';
use \Michelf\Markdown;
    /**
* This is the content.opf file
*/

 $opf = array();
/**
* This is the toc.ncx file
*/
 $ncx = array();
/**
* This is the pages array
*/
// $pages = array();
/**
* This is the temp folder used to store the book's files before zip it
*/
 $temp_folder="salut";
/**
* This is the book's uuid
*/
 //$uuid;
$error;
 $title = "salut";



//Création de toute l'arborescence de l'EPUB
 function CreateEPUB($name) {
    // Creates all the folders needed
    CreateFolders();
    // If there's no error we're good to go
    /*if ( $error ) {
    return;*/
    // Open the content.opf file
        OpenOPF("book1","1","en");
        
        // Open the toc.ncx file
        OpenNCX("book1","1");
        
        // Open the css.css file
       // $this->OpenCSS();
        
        // Variables needed to put everything in the right place
        $ncx = null;
        $opf = null;
        $fill_opf_spine = null;

        // Fill the NCX and OPF
        $ncx[] = $ncx;
        
        $opf[] = $opf;
        
        // If there's a cover, we'll need an <itemref idref="cover" />
       /* if ( $this->cover_img ) {
            $this->opf[] = "<itemref idref=\"cover\" />\r\n";
        }*/
        
        // Fill the spine
       // $this->opf[] = $fill_opf_spine;
        
        // Closes the OPF and NCX
        CloseOPF();
        CloseNCX();
        
        // Create the OPF and NCX files
        CreateOPF();
        CreateNCX();
        
        $filename = '../data/'. $name.'/README.md';  

        $var = file_get_contents($filename);
        $var = Markdown::defaultTransform($var);
        // XHTML default page header
        $page_content  = "<?xml version='1.0' encoding='utf-8'?>" . "\r\n";
        $page_content .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">' . "\r\n";
        $page_content .= '<html xmlns="http://www.w3.org/1999/xhtml">' . "\r\n";
        $page_content .= '<head>' . "\r\n";
        $page_content .= '<meta content="application/xhtml+xml; charset=utf-8" http-equiv="Content-Type"/>' . "\r\n";
        $page_content .= '<link href="css.css" type="text/css" rel="stylesheet"/>' . "\r\n";
        $page_content .= '<title> Book1 </title>' . "\r\n";
        $page_content .= '</head>' . "\r\n";
        $page_content .= '<body>' . $var ."</body>\r\n";
    
}
// Creation des dossier pour l'EPUB

 function CreateFolders() {
   /* if ( ! $temp_folder ) {
        $temp_folder = preg_replace( '/[^A-Za-z0-9]/is', '', $title );
        $temp_folder = strtolower( $temp_folder );
    }*/
    // Temp folder is the book's uuid
    $temp_folder = __DIR__. '/../data/book1/salut/';
    // Check to see if there's no folder with the same name
   /* if( is_dir( $temp_folder ) ) {
        $error = 'Le dossier existe déjà.';
        return;
    }*/
    // Creates the main temp folder
        mkdir( $temp_folder, 0777 );
        // Check the folder
        if ( ! is_dir( $temp_folder ) ) {
            $error = "Cannot create EPUB folder \"{$temp_folder}\".";
            return;
        }
        // Creates the other needed folders
    mkdir( $temp_folder . '/META-INF', 0777 );
    mkdir( $temp_folder . '/OEBPS', 0777 );
    mkdir( $temp_folder . '/OEBPS/images', 0777 );
    // Creates the container.xml
       // CreateContainer();
    // Creates the needed epub files
    CreateFile( $temp_folder . '/mimetype', 'application/epub+zip');
   // CreateFile( $temp_folder . '/OEBPS/css.css', $css);
    $containers;
        $containers  = '<?xml version="1.0"?>';
        $containers .= '     <container version="1.0" xmlns="urn:oasis:names:tc:opendocument:xmlns:container" >';
        $containers .= '     <rootfiles>';
        $containers .= '         <rootfile full-path="content.opf" media-type="application/oebps-package+xml"/>';
        $containers .= '     </rootfiles>';
        $containers .= '     </container>';
    CreateFile( $temp_folder . '/META-INF/container.xml',  $containers);

}
   
/**
     * Fonction qui créait des fichiers
*/
function CreateFile( $file, $content = null ) {
        $handle = fopen( $file, 'w+' );
        $ler = fwrite( $handle, $content );
        fclose($handle);
}
    
    /**
     * Open OPF
     *
     * Fill the content.opf file ($opf property)
     */    
    function OpenOPF($title, $uuid,$lang) {
        $opf[] = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
        $opf[] = '<package xmlns="http://www.idpf.org/2007/opf" unique-identifier="BookID" version="2.0" >' . "\r\n";
        $opf[] = '<metadata xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:opf="http://www.idpf.org/2007/opf">' . "\r\n";
        $opf[] = '<dc:title>' . $title . '</dc:title>' . "\r\n";
        //$opf[] = '<dc:creator opf:file-as="' . $this->creator . '" opf:role="aut">' . $this->creator . '</dc:creator>' . "\r\n";
        //$opf[] = '<dc:rights>' . $this->rights . '</dc:rights>' . "\r\n";
        //$opf[] = '<dc:publisher>' . $this->publisher . '</dc:publisher>';
        $opf[] = '<dc:identifier id="BookID" opf:scheme="CustomID">' . $uuid . '</dc:identifier>' . "\r\n";
        //$opf[] = '<meta name="cover" content="cover" />' . "\r\n";
        $opf[] = '<dc:language>' . $lang . '</dc:language>' . "\r\n";
        $opf[] = '</metadata><manifest>' . "\r\n";
        $opf[] = '<<item id="page1" href="content.xhtml" media-type="application/xhtml+xml"/>' . "\r\n";
        $opf[] = '<item id="ncx" href="toc.ncx" media-type="application/x-dtbncx+xml" />'. "\r\n";
        $opf[] = '</manifest>'. "\r\n";
        $opf[] = '<spine toc="ncx">'. "\r\n";
        $opf[] = '<itemref idref="page1" />'. "\r\n";
        $opf[] = '</spine>'. "\r\n";

    }


    /**
     * Close OPF
     *
     * End of the content.opf file
     */    
    function CloseOPF() {
        $opf[] = '</spine></package>' . "\r\n";
    }
    
    /**
     * Create OPF
     *
     * Creates the content.opf file
     */    
    function CreateOPF() {
        $opf = null;
        
        foreach( $opf as $lines ) { 
            $opf .= "$lines\r\n";
        }
        
        CreateFile( $ttemp_folder . '/OEBPS/content.opf', $opf );
    }
    
 /**
     * Open NCX
     *
     * Fill the toc.ncx content ($ncx property)
     */    
    function OpenNCX($uuid,$title) {
        $ncx[] = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
        $ncx[] = '<ncx xmlns="http://www.daisy.org/z3986/2005/ncx/" version="2005-1">' . "\r\n";
        $ncx[] = '<meta name="dtb:uid" content="' . $uuid . '"/>' . "\r\n";
        $ncx[] = '<head>' . "\r\n";
        $ncx[] = '<meta name="dtb:depth" content="1"/>' . "\r\n";
        $ncx[] = '<meta name="dtb:totalPageCount" content="0"/>' . "\r\n";
        $ncx[] = '<meta name="dtb:maxPageNumber" content="0"/>' . "\r\n";
        $ncx[] = '</head>' . "\r\n";
        $ncx[] = '<docTitle><text>' . $title . '</text></docTitle>' . "\r\n";
        $ncx[] = '<navMap>' . "\r\n";
    }
    
    /**
     * Close NCX
     *
     * Closes the toc.ncx file content
     */    
   function CloseNCX() {
        $ncx[] = '</navMap>' . "\r\n";
        $ncx[] = '</ncx>' . "\r\n";
    }
    /**
     * Create NCX
     *
     * Creates toc.ncx file
     */    
    function CreateNCX() {
        $ncx = null;
        
        foreach( $ncx as $lines ) { 
            $ncx .= "$lines\r\n";
        }
        
        CreateFile( $temp_folder . '/OEBPS/toc.ncx', $ncx );
    }





    

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
       // createMetaInf($filename);
        CreateEPUB($app->escape($book));
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


