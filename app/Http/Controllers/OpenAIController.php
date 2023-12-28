<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;

class OpenAIController extends Controller
{
    protected $openaiService;

    public function __construct(OpenAIService $openaiService)
    {
        $this->openaiService = $openaiService;
    }

    public function index()
    {
        return view('openai');
    }

    public function getCompletion(Request $request)
    {
        $messages = $request->input('messages', []);
        $completion = $this->openaiService->getCompletion($messages);

        return response()->json($completion);
    }
}
