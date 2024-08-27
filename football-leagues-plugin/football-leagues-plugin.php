<?php
/**
 * Plugin Name: Football Leagues Plugin
 * Description: Exibe uma lista de ligas de futebol utilizando a API Football API Sports.
 * Version: 1.0
 * Author: Anderson Souza
 */

// Função para chamar a API e obter as ligas de futebol
function get_football_leagues() {
    if (!class_exists('http\Client')) {
        return 'A extensão PECL HTTP não está instalada no servidor.';
    }

    $client = new http\Client;
    $request = new http\Client\Request;
    $request->setRequestUrl('https://v3.football.api-sports.io/leagues');
    $request->setRequestMethod('GET');
    $request->setHeaders(array(
        'x-rapidapi-host' => 'v3.football.api-sports.io',
        'x-rapidapi-key' => 'b7510a6f8dee401579afcb2ef89eba13'  // Substitua pela sua chave de API
    ));
    $client->enqueue($request)->send();
    $response = $client->getResponse();

    if ($response->getResponseCode() == 200) {
        $leagues = json_decode($response->getBody(), true);
        
        if (isset($leagues['response'])) {
            $output = '<h3>Ligas de Futebol</h3><ul>';
            foreach ($leagues['response'] as $league) {
                $output .= '<li>' . esc_html($league['league']['name']) . ' - ' . esc_html($league['country']['name']) . '</li>';
            }
            $output .= '</ul>';
            return $output;
        } else {
            return 'Nenhuma liga encontrada.';
        }
    } else {
        return 'Erro ao buscar dados da API: ' . esc_html($response->getResponseStatus());
    }
}

// Função para exibir o conteúdo na página
function display_football_leagues() {
    echo '<div class="football-leagues">';
    echo get_football_leagues();
    echo '</div>';
}

// Shortcode para exibir o conteúdo onde desejar
add_shortcode('football_leagues', 'display_football_leagues');
