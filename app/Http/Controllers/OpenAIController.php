<?php

namespace App\Http\Controllers;

use App\Models\Session;
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
        $sessions = Session::all();
        return view('openai', compact('sessions'));
    }

    public function getCompletion(Request $request)
    {
        $messages = $request->input('messages', []);
        $completion = $this->openaiService->getCompletion($messages);

        Session::create(['messages' => json_encode($messages)]);

        return response()->json($completion);
    }
}
