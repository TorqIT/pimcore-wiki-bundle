<?php

declare(strict_types=1);

namespace Torq\PimcoreWikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Attribute\Route;

#[AsAlias('wiki_bundle')]
class DocumentationController extends AbstractController
{
    public function __construct(
        #[Autowire(param: 'torq_pimcore_wiki.documentation_path')] private readonly string $documentationPath,
    ) {
    }

    #[Route('', name: 'torq_pimcore_wiki_documentation_showindex')]
    public function showIndexAction()
    {
        return $this->render('@TorqPimcoreWiki/documentation/index.html.twig', [
            'toc' => $this->getTableOfContents(),
            'content' => null,
            'activeSlug' => null,
        ]);
    }

    #[Route('/{slug}', name: 'torq_pimcore_wiki_documentation_showpage', requirements: ['slug' => '.+'])]
    public function showPageAction(string $slug)
    {
        $slug = urlencode($slug);
        $filePath = $this->documentationPath . '/' . $slug . '.md';
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException(sprintf('Documentation page "%s" not found.', $slug));
        }
        $realDocPath = realpath($this->documentationPath);
        $realFilePath = realpath($filePath);
        if (!$realFilePath || !str_starts_with($realFilePath, $realDocPath)) {
            throw $this->createAccessDeniedException('Invalid file path.');
        }
        $content = file_get_contents($realFilePath);
        return $this->render('@TorqPimcoreWiki/documentation/index.html.twig', [
            'toc' => $this->getTableOfContents(),
            'content' => $content,
            'activeSlug' => $slug,
        ]);
    }

    #[Route('/images/{filename}', name: 'torq_pimcore_wiki_documentation_getimage', priority: 10)]
    public function getImageAction(string $filename)
    {
        $filename = urlencode($filename);
        $path = $this->documentationPath . '/images/' . $filename;
        if (!file_exists($path)) {
            throw $this->createNotFoundException(sprintf('Image "%s" not found.', $filename));
        }
        $realDocPath = realpath($this->documentationPath);
        $realFilePath = realpath($path);
        if (!$realFilePath || !str_starts_with($realFilePath, $realDocPath)) {
            throw $this->createAccessDeniedException('Invalid file path.');
        }
        return $this->file($realFilePath);
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
