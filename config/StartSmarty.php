<?php
use Smarty\Smarty;
class StartSmarty{
    public static function configuration(){

        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__.'/../Smarty/templates/');
        $smarty->setCompileDir(__DIR__.'/../Smarty/templates_c/');
        $smarty->setConfigDir(__DIR__.'/../Smarty/configs/test.php');
        $smarty->setCacheDir(__DIR__.'/../Smarty/cache/');

        $smarty->setEscapeHtml(true);

        // Forza la ricompilazione dei template e controlla le modifiche
        $smarty->force_compile = true;
        $smarty->compile_check = true;
        
        //$smarty->assign('base_url', '/EpTech/');
        $smarty->display('index.tpl');

        return $smarty;
   }
}
?>