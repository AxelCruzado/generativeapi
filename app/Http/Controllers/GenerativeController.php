<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateTextRequest;
use App\Http\Requests\GenerateMediaRequest;
use App\Services\GoogleGeminiService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GenerativeController extends Controller
{
    public function __construct(
        protected GoogleGeminiService $gemini
    ) {}

    // ============================================
    // TEXT GENERATION
    // ============================================
    
    public function generateFacebook(GenerateTextRequest $request): JsonResponse
    {
        return $this->generateTextForChannel($request, 'facebook');
    }

    public function generateInstagram(GenerateTextRequest $request): JsonResponse
    {
        return $this->generateTextForChannel($request, 'instagram');
    }

    public function generatePodcast(GenerateTextRequest $request): JsonResponse
    {
        return $this->generateTextForChannel($request, 'podcast');
    }

    // ============================================
    // IMAGE GENERATION
    // ============================================
    
    public function generateImage(GenerateMediaRequest $request): JsonResponse
    {
        $result = $this->gemini->generateImage(
            $request->input('prompt'),
            $request->only(['format', 'size', 'model', 'aspectRatio', 'numberOfImages'])
        );
        
        return response()->json($result, $result['status'] ?? 200);
    }

    public function listImages(): JsonResponse
    {
        $result = $this->gemini->listSavedImages();
        return response()->json($result, $result['status'] ?? 200);
    }

    public function downloadImage(string $id): JsonResponse|BinaryFileResponse
    {
        return $this->downloadMedia($id, 'image');
    }

    // ============================================
    // AUDIO GENERATION
    // ============================================
    
    public function generateAudio(GenerateMediaRequest $request): JsonResponse
    {
        $result = $this->gemini->generateAudio(
            $request->input('prompt'),
            $request->only(['format', 'size', 'model', 'voice'])
        );
        
        return response()->json($result, $result['status'] ?? 200);
    }

    public function listAudios(): JsonResponse
    {
        $result = $this->gemini->listSavedAudios();
        return response()->json($result, $result['status'] ?? 200);
    }

    public function downloadAudio(string $id): JsonResponse|BinaryFileResponse
    {
        return $this->downloadMedia($id, 'audio');
    }

    // ============================================
    // VIDEO GENERATION
    // ============================================
    
    public function generateVideo(GenerateMediaRequest $request): JsonResponse
    {
        $result = $this->gemini->generateVideo(
            $request->input('prompt'),
            $request->only(['format', 'size', 'model'])
        );
        
        return response()->json($result, $result['status'] ?? 200);
    }

    public function listVideos(): JsonResponse
    {
        $result = $this->gemini->listSavedVideos();
        return response()->json($result, $result['status'] ?? 200);
    }

    public function downloadVideo(string $id): JsonResponse|BinaryFileResponse
    {
        return $this->downloadMedia($id, 'video');
    }

    // ============================================
    // PRIVATE HELPERS
    // ============================================
    
    private function generateTextForChannel(GenerateTextRequest $request, string $channel): JsonResponse
    {
        $options = $request->only(['tone', 'length', 'model']);
        
        $result = $request->has('contents')
            ? $this->gemini->generateTextFromContents($request->input('contents'), $channel, $options)
            : $this->gemini->generateText($request->input('prompt'), $channel, $options);
        
        return response()->json($result, $result['status'] ?? 200);
    }

    private function downloadMedia(string $id, string $type): JsonResponse|BinaryFileResponse
    {
        if (empty($id)) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'body' => "Missing {$type} id"
            ], 400);
        }

        $getter = "getSaved" . ucfirst($type) . "ById";
        $media = $this->gemini->{$getter}($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'body' => ucfirst($type) . ' not found'
            ], 404);
        }

        $path = storage_path('app/' . $media['path']);
        
        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'body' => ucfirst($type) . ' file missing'
            ], 404);
        }

        return response()->download($path, $media['filename']);
    }
}