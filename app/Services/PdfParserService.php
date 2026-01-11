<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;

class PdfParserService
{
    /**
     * Extract text from PDF file.
     *
     * @param UploadedFile $file
     * @return string
     */
    public function extract(UploadedFile $file): string
    {
        try {
            // Parse the PDF directly from the temporary path instead of storing it first
            // to avoid path mapping issues or redundant storage
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getPathname());
            $text = $pdf->getText();

            if (empty(trim($text))) {
                throw new \Exception('PDF file is empty or contains no extractable text.');
            }

            // Clean up the extracted text
            return $this->cleanText($text);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PDF extraction error: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Gagal mengekstrak teks dari PDF: ' . $e->getMessage());
        }
    }

    /**
     * Clean up extracted text.
     *
     * @param string $text
     * @return string
     */
    private function cleanText(string $text): string
    {
        // Remove extra whitespace and normalize line breaks
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/\n\s*\n/', "\n\n", $text);
        $text = trim($text);

        return $text;
    }
}
