<?php
namespace App\Servicios\IA;
use Illuminate\Support\Facades\Http;

class ClienteOpenAI {
  public function chat(array $messages): array {
    $payload = ['model'=>config('services.openai.model'),'messages'=>$messages];
    return Http::withToken(env('OPENAI_API_KEY'))
      ->post(config('services.openai.base').'/chat/completions',$payload)
      ->throw()->json();
  }
}
