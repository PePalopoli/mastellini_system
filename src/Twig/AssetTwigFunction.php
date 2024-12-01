<?php

/*
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>
 */

namespace Audi\AudiSystem\Twig;

/**
 * Class AssetTwigFunction.
 *
 * http://twig.sensiolabs.org/doc/advanced.html#creating-an-extension
 */
class AssetTwigFunction extends TwigContainerAware
{
    public function getName()
    {
        return 'asset';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('asset', array($this, 'find')),
            new \Twig_SimpleFunction('format_date', array($this, 'format_date')),
            new \Twig_SimpleFunction('retornaUnidades', array($this, 'retornaUnidades')),
            new \Twig_SimpleFunction('formatNumberTel', array($this, 'formatNumberTel')),
            new \Twig_SimpleFunction('retornaLinkAreaRestrita', array($this, 'retornaLinkAreaRestrita')),
        );
    }

    public function find($asset)
    {
        $request = $this->get('request');
        $url = '';
        if ($request instanceof Request) {
            $url = $request->getBaseUrl();
        }

        try {
            $parameters = $this->get('composer');

            $version = $parameters['version'];
        } catch (\InvalidArgumentException $e) {
            $version = '0.0.1';
        }

        if ($request->server->get('HTTP_HOST') === 'localhost') {
            $url = substr($request->server->get('SCRIPT_NAME'), 0, -10).$url;
        }

        return sprintf('%s/%s%sv=%s', $url, $asset, strpos($asset, '?') === false ? '?' : '&', $version);
    }

    public function format_date($data){
        $date = date_create($data);
        $date_exibe = date_format($date, 'd/m/Y');        
        return $date_exibe;
    }

    public function retornaUnidades(){
        
        $unidades_type = $this->get('db')->fetchAll('SELECT * FROM `unidades_type` WHERE enabled = 1 ');
        return $unidades_type;
    }

    public function retornaLinkAreaRestrita(){
        
        // $unidades_type = $this->get('db')->fetchAll('SELECT * FROM `unidades_type` WHERE enabled = 1 ');
        return "https://mastellini.shiftcloud.com.br/shift/lis/mastellini/elis/s01.iu.web.Login.cls?config=UNICO&sigla=";
    }

    

    function formatNumberTel($string) {        
        // matriz de entrada
        $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );
    
        // matriz de saída
        $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','','','','','','','','','','','','','','','','','','','','','','','' );
    
        // devolver a string
        return str_replace($what, $by, $string);
    }
}
