<?php

/*
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>
 */

// Routes Website
$route = $app['controllers_factory'];

// Rotas Painel
$route->mount(sprintf('/%s', $app['security_path']), require(__DIR__.'/routes_security.php'));

// Renderizar imagens
$route->get('img/{path}/{imagem}', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request, $path, $imagem) {
    return $app['glide']->outputImage(sprintf('%s/%s', $path, $imagem), $request->query->all());
})->bind('imagem');

$route->get('/', 'Front\Home::Index')->bind('web_home');

//Exames
$route->get('exames/', 'Front\Exames::TodosExames')->bind('web_todos_exames');
$route->get('exames/{url_exame}/', 'Front\Exames::GetExame')->bind('web_interna_exame');


//Vagas
$route->get('fale-conosco/', 'Front\Vagas::FaleConosco')->bind('web_fale_conosco');
$route->post('fale-conosco-send/', 'Front\Vagas::FaleConoscoSend')->bind('web_fale_conosco_send');
$route->get('nossas-vagas/', 'Front\Vagas::NossasVagas')->bind('web_nossas_vagas');
$route->post('nossas-vagas-send/', 'Front\Vagas::NossasVagasTodasSend')->bind('web_nossas_vagas_send');
$route->post('nossas-vagas-uma-send/', 'Front\Vagas::NossasVagasUmaSend')->bind('web_nossas_vagas_uma_send');
$route->match('nossas-vagas/search/', 'Front\Vagas::NossasVagasSearch')->bind('web_procurar_vaga');
$route->get('nossas-vagas/{url_vaga}/', 'Front\Vagas::InternaVaga')->bind('web_interna_vaga');

//Unidades
$route->get('unidades/', 'Front\Unidades::TodasUnidades')->bind('web_todas_unidades');

//Convenios
$route->get('convenios/', 'Front\Home::TodosConvenios')->bind('web_todos_convenios');
//Coletas
$route->get('coletas/', 'Front\Home::TodasColetas')->bind('web_todas_coletas');
//Quem Somos
$route->get('quem-somos/', 'Front\Home::QuemSomos')->bind('web_quem_somos');
//Parceiros
$route->get('parceiro/', 'Front\Home::Parceiro')->bind('web_parceiro');

return $route;
