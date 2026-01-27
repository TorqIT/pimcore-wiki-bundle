<?php

declare(strict_types=1);

namespace TorqIT\WikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DocumentationController extends AbstractController
{
    public function __construct(
        private readonly string $documentationPath
    ) {
    }

    #[Route('', name: 'documentation_index')]
    public function indexAction(): Response
    {
        return $this->render('documentation/default.html.twig', [
            'toc' => $this->getTableOfContents(),
            'content' => null,
            'activeSlug' => null,
        ]);
    }

    #[Route('/{slug}', name: 'documentation_page', requirements: ['slug' => '.+'])]
    public function pageAction(string $slug): Response
    {
        $filePath = $this->documentationPath . '/' . $slug . '.md';

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException(sprintf('Documentation page "%s" not found.', $slug));
        }

        // Security: Ensure the path is within the documentation directory
        $realDocPath = realpath($this->documentationPath);
        $realFilePath = realpath($filePath);

        if (!$realFilePath || !str_starts_with($realFilePath, $realDocPath)) {
            throw $this->createAccessDeniedException('Invalid file path.');
        }

        $content = file_get_contents($filePath);

        return $this->render('documentation/default.html.twig', [
            'toc' => $this->getTableOfContents(),
            'content' => $content,
            'activeSlug' => $slug,
        ]);
    }

    #[Route('/images/{filename}', name: 'documentation_image', priority: 10)]
    public function imageAction(string $filename): Response
    {
        $path = $this->documentationPath . '/images/' . $filename;

        if (!file_exists($path)) {
            throw $this->createNotFoundException(sprintf('Image "%s" not found.', $filename));
        }

        // Security: Ensure the path is within the documentation directory
        $realDocPath = realpath($this->documentationPath);
        $realFilePath = realpath($path);

        if (!$realFilePath || !str_starts_with($realFilePath, $realDocPath)) {
            throw $this->createAccessDeniedException('Invalid file path.');
        }

        return $this->file($path);
    }

    private function getTableOfContents(): array
    {
        $toc = [];

        if (!is_dir($this->documentationPath)) {
            return $toc;
        }

        $finder = new Finder();
        $finder->files()->in($this->documentationPath)->name('*.md')->sortByName();

        foreach ($finder as $file) {
            $slug = $file->getFilenameWithoutExtension();
            $content = $file->getContents();

            // Extract title from first markdown heading, fallback to humanized filename
            $title = $this->humanizeSlug($slug);
            if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
                $title = trim($matches[1]);
            }

            $toc[] = [
                'slug' => $slug,
                'title' => $title,
            ];
        }

        return $toc;
    }

    private function humanizeSlug(string $slug): string
    {
        return ucwords(str_replace('-', ' ', $slug));
    }
}
