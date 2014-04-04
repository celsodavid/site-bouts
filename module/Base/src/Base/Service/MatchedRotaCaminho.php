<?php
namespace Base\Service;
use Doctrine\ORM\EntityManager;
class MatchedRotaCaminho
{
	/**
	 *
	 * @var EntityManager
	 */
	protected $em;
	protected $match;
	public function __construct(EntityManager $em, $RouteMatch)
	{
		$this->em = $em;
		$this->match = $RouteMatch;
	}
   public function geraCaminhoSite()
   {
   		if($this->match->getParams()['action'] == "wallpaper")
   		{
   			return array("rota" => $this->match->getMatchedRouteName(), "nome" => "Wallpaper", "subtitulo" => "Papéis de Parede - Wallpapers");
   		}
   		else if($this->match->getParams()['action'] == "midia")
   		{
   			return array("rota" => $this->match->getMatchedRouteName(), "nome" => "Mídia",  "subtitulo" => "Assista as ultimas ações produzidas pela Bout’s - Mídia");
   		}
  		else if($this->match->getParams()['action'] == "produto")
   		{
   			$categoria = $this->em->getRepository("Produto\Entity\ProdutoCategoria")->findOneByslug($this->match->getParam("categoria"));	
   			return array("rota" => array("MatchedRouteName" => $this->match->getMatchedRouteName(), array("categoria" => $categoria->getSlug())), "nome" => $categoria->getNome(),  "subtitulo" => strtoupper($categoria->getNome()));
   		}
   		else if($this->match->getParams()['action'] == "produtoSubcategoria")
   		{
   			$subcategoria = $this->em->getRepository("Produto\Entity\ProdutoSubcategoria")->findOneByslug($this->match->getParam("subcategoria"));	
   			
   			return array("rota" => array("MatchedRouteName" => "produto", array("categoria" => $subcategoria->getCategoria()->getSlug())), "nome" => $subcategoria->getCategoria()->getNome(),  "subtitulo" =>  strtoupper($subcategoria->getNome()));
   		}
   		else if($this->match->getParams()['action'] == "produtoDetalhe")
   		{
   			$produto = $this->em->getRepository("Produto\Entity\ProdutoTenis")->findOneByslug($this->match->getParam("slugProduto"));
   			return array(
   					"subtitulo" => ucwords($produto->getSubcategoriaTenis()->getNome()." - ".$produto->getModeloTenis()->getNome()),
   					"lista" => array(
   						array("rota" => array(
   								"MatchedRoute" => "produto",
   								"arrayDefines" => array("categoria" => $produto->getSubcategoriaTenis()->getCategoria()->getSlug())		
   					), "nome" => $produto->getSubcategoriaTenis()->getCategoria()->getNome()),
   						array("rota" => array(
   								"MatchedRoute" => "produto/produto-subcategoria",
   								"arrayDefines" => array("categoria" => $produto->getSubcategoriaTenis()->getCategoria()->getSlug(), "subcategoria" => $produto->getSubcategoriaTenis()->getSlug())
   						), "nome" => $produto->getSubcategoriaTenis()->getNome())
   					)
   			
   			);
   		}
   }
}

?>