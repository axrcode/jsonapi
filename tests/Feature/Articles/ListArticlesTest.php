<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_single_article()
    {
        // Deshabilitamos el manejo de Exceptions para obtener errores más concretos
        $this->withoutExceptionHandling();

        // Creamos un artículo en la bd con Factories
        $article = Article::factory()->create();

        /**
         *  Obtenemos la respuesta del artículo específico.
         *  Esta se consulta desde 'ArticleController@show' 
         */
        $response = $this->getJson( route('api.v1.articles.show', [$article]) );
        
        /**
         *  Verificamos la estructura de una respuesta JSON API
         *  'assetExactJson'   -   Retorna los atributos respetando sus tipos de datos
         */
        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', [$article])
                ]
            ]
        ]);
    }
}
