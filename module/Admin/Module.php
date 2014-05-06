<?php
namespace Admin;
use Zend\Authentication\AuthenticationService,
	Zend\Authentication\Storage\Session as SessionStorage;
use Admin\Service\Banner,
	Admin\Service\OndeComprar,
	Admin\Service\ProdutosCategoria,
	Admin\Service\ProdutosSubCategoria,
	Admin\Service\Tecnologia,
	Admin\Service\Wallpaper,
	Admin\Service\Vitrine,
	Admin\Service\Midia,
	Admin\Service\ProdutosCor,
	Admin\Service\Produtos;
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    
    
    public function onBootstrap($e)
    {
    	$e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
    		/*
    		 * Definições de sessoes
    		*/
    		$auth = new AuthenticationService;
    		$auth->setStorage(new SessionStorage("Usuario"));
    		$controller      = $e->getTarget();
    		$controllerClass = get_class($controller);
    		$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
    		$config          = $e->getApplication()->getServiceManager()->get('config');
    	
    		/*
    		 * Permissão de usuário
    		*/
    		$matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();
    	
    		/*
    		 * Definição de Layout de todos modulos
    		*/
    		/*if($auth->hasIdentity())
    		{
    	
    			if($auth->getIdentity()->getNivelUsuario() != 1)
    			{
    	
    				if (isset($config['module_layouts'][$moduleNamespace])) {
    					$controller->layout($config['module_layouts'][$moduleNamespace]."_logado");
    					$controller->layout()->infoUser = $auth->getIdentity();
    				}
    			}
    			else
    			{
    				if (isset($config['module_layouts'][$moduleNamespace])) {
    					$controller->layout($config['module_layouts'][$moduleNamespace]."_logado_adm");
    					$controller->layout()->infoUser = $auth->getIdentity();
    				}
    			}
    		}
    		else
    		{
    	
    			if (isset($config['module_layouts'][$moduleNamespace])) {
    				$controller->layout($config['module_layouts'][$moduleNamespace]);
    			}
    		}*/
    	
    		/*
    		 * Definições para bloqueio total de usuários no admin
    		*/
    		$adminRoute = explode("-",$matchedRoute);
    		if(!$auth->hasIdentity() && $adminRoute[0] == "admin")
    		{
    			return $controller->redirect()->toRoute("admin/logar");
    		}
    		else if($matchedRoute == "admin/logar" && $auth->hasIdentity())
    		{
    			return $controller->redirect()->toRoute("admin");
    		}
    		/*
    		if(!$auth->hasIdentity() && $adminRoute[0] == "admin")
    		{
    			return $controller->redirect()->toRoute("admin/logar");
    		}
    		else if($auth->hasIdentity())
    		{
    				return $controller->redirect()->toRoute("admin");
    		}    
    		*/	    		
    	
    	}, 100);
    }
    public function getServiceConfig() {
    	return array(
    			'factories' => array(
    					'Admin\Service\ProdutosCor' => function($service) {
    						$ProdutosCor = new ProdutosCor($service->get('Doctrine\ORM\EntityManager'));
    						return $ProdutosCor;
    					},
    					'Admin\Service\Tecnologia' => function($service) {
    						$Tecnologia = new Tecnologia($service->get('Doctrine\ORM\EntityManager'));
    						return $Tecnologia;
    					},
    					'Admin\Service\ProdutosCategoria' => function($service) {
    						$ProdutosCategoria = new ProdutosCategoria($service->get('Doctrine\ORM\EntityManager'));
    						return $ProdutosCategoria;
    					},
    					'Admin\Service\ProdutosSubCategoria' => function($service) {
    						$ProdutosSubCategoria = new ProdutosSubCategoria($service->get('Doctrine\ORM\EntityManager'));
    						return $ProdutosSubCategoria;
    					},
    					'Admin\Service\Wallpaper' => function($service) {
    						$Wallpaper = new Wallpaper($service->get('Doctrine\ORM\EntityManager'));
    						return $Wallpaper;
    					},
    					'Admin\Service\Vitrine' => function($service) {
    						$Vitrine = new Vitrine($service->get('Doctrine\ORM\EntityManager'));
    						return $Vitrine;
    					},
    					'Admin\Service\OndeComprar' => function($service) {
    						$OndeComprar = new OndeComprar($service->get('Doctrine\ORM\EntityManager'));
    						return $OndeComprar;
    					},
    					'Admin\Service\Midia' => function($service) {
    						$midia = new Midia($service->get('Doctrine\ORM\EntityManager'));
    						return $midia;
    					},
    					'Admin\Service\Banner' => function($service) {
    					    $banner = new Banner($service->get('Doctrine\ORM\EntityManager'));
    					    return $banner;
    					},
    					'Admin\Service\Produtos' => function($service) {
    						$produtos = new Produtos($service->get('Doctrine\ORM\EntityManager'));
    						return $produtos;
    					},
    			)
    	);
    }
    
}
