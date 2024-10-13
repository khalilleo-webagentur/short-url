<?php

declare(strict_types=1);

namespace App\Controller\Admin\Links;

use App\Entity\MaliciousUrl;
use App\Service\MaliciousUrlsService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/malicious-links/9bmvk2s1k4d4p3u0')]
class MaliciousUrlsController extends AbstractController
{
    use FormValidationTrait;

    private const ADMIN_MALICIOUS_LINKS_ROUTE = 'app_admin_links_malicious_index';

    public function __construct(
        private readonly MaliciousUrlsService $maliciousUrlsService
    ) {}

    #[Route('/home', name: 'app_admin_links_malicious_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $maliciousUrls = $this->maliciousUrlsService->getOneByCounter();

        return $this->render('admin/links/malicious-urls.html.twig', [
            'maliciousUrls' => $maliciousUrls,
        ]);
    }

    #[Route('/add', name: 'app_admin_links_malicious_new', methods: 'POST')]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $maliciousURLs = $this->validateTextarea($request->request->get('mUrls'), true);

        if (empty($maliciousURLs)) {
            $this->addFlash('warning', 'Malicious urls field is required.');
            return $this->redirectToRoute(self::ADMIN_MALICIOUS_LINKS_ROUTE);
        }

        $count = $skipped = 0;

        foreach (explode("\n", $maliciousURLs) as $maliciousURL) {

            $maliciousModel = new MaliciousUrl();

            $url = str_replace(["0.0.0.0 ", "\n", "\r", "\r\n", "<br />"], '', $maliciousURL);

            if ($this->validateURL($url) && !$this->maliciousUrlsService->getOneByUrl($url)) {
                $this->maliciousUrlsService->save(
                    $maliciousModel
                        ->setUrl($url)
                );
                $count++;
            } else {
                $skipped++;
            }
        }

        $this->addFlash('notice', sprintf('Malicious URLs [%s] has been added and skipped [%s].',  $count, $skipped));

        return $this->redirectToRoute(self::ADMIN_MALICIOUS_LINKS_ROUTE);
    }
}
