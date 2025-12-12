<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Property;
use Illuminate\Support\Str;

class ImportWpProperties extends Command
{
    protected $signature = 'import:wp {url}';
    protected $description = 'Importa imóveis de um site WordPress via REST API';

    public function handle()
    {
        $url = $this->argument('url');
        $endpoint = rtrim($url, '/') . '/wp-json/wp/v2/posts?per_page=100';

        $this->info("Conectando em: $endpoint");

        $response = Http::get($endpoint);

        if ($response->failed()) {
            $this->error('Falha ao conectar na API do WP.');
            return;
        }

        $posts = $response->json();
        $bar = $this->output->createProgressBar(count($posts));

        foreach ($posts as $post) {
            $content = strip_tags($post['content']['rendered']);
            
            Property::updateOrCreate(
                ['external_id' => $post['id']], 
                [
                    'title' => $post['title']['rendered'],
                    'slug' => $post['slug'],
                    'description' => $content,
                    'price' => 0, 
                    'type' => 'venda' 
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Importação concluída com sucesso!');
    }
}