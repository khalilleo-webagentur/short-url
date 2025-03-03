<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Collection;

use App\Entity\LinkCollection;
use App\Service\LinkCollectionService;
use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard/collections/9kx9l2l5h6b0h2v4')]
class IndexController extends AbstractController
{
    use FormValidationTrait;

    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService           $linkService,
        private readonly LinkCollectionService $linkCollectionService,
        private readonly LinkStatisticService  $linkStatisticService
    ) {
    }

    #[Route('/home', name: 'app_link_collection_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $collections = $this->linkCollectionService->getAllByUser($user);

        if (!$collections) {
            $this->addFlash('warning', 'No group has been found.');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        return $this->render('profile/dashboard/collections/index.html.twig', [
            'collections' => $collections
        ]);
    }

    #[Route('/new', name: 'app_links_collection_new', methods: 'POST')]
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $name = $this->validateAndReplaceSpace($request->request->get('iName'));

        if (!$name || strlen($name) < 3) {
            $this->addFlash('notice', 'Name of Group length must be greater than or equal 3 Chars.');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        if ($this->linkCollectionService->getByUserAndName($user, $name)) {
            $this->addFlash('notice', sprintf('Group [%s] is already exists.', $name));
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $collections = $this->linkCollectionService->getAllByUser($user);

        $collection = new LinkCollection();

        $this->linkCollectionService->save(
            $collection
                ->setName($name)
                ->setUser($user)
                ->setIsDefault(count($collections) <= 0)
        );

        // user has add link(s) and we just move all links (if any) into a created collection.
        if (count($collections) <= 0) {
            $this->linkService->moveLinksWithoutAnyAssociationsToCollection($user, $collection);
        }

        $this->addFlash('success', sprintf('Group [%s] has been added.', $name));

        return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
    }

    #[Route('/edit/{id}', name: 'app_links_collection_edit')]
    public function edit(?string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $collection = $this->linkCollectionService->getByUserAndId($user, $this->validateNumber($id));

        if (!$collection) {
            $this->addFlash('warning', 'Unknown Group');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        return $this->render('profile/dashboard/collections/edit.html.twig', [
            'collection' => $collection
        ]);
    }

    #[Route('/store/{id}', name: 'app_links_collection_store', methods: 'POST')]
    public function store(?string $id, Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $collection = $this->linkCollectionService->getByUserAndId($user, $this->validateNumber($id));

        if (!$collection) {
            $this->addFlash('warning', 'Unknown Group');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        if ($this->validateCheckbox($request->request->get('deleteAll'))) {

            foreach ($this->linkService->getAllByUserAndCollection($user, $collection) as $link) {
                $this->linkStatisticService->deleteAllByLink($link);
            }

            $this->linkService->deleteCollectionWithLinks($user, $collection);
            $this->addFlash('success', 'Group and all links within (if any) has been deleted.');

            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        if ($this->validateCheckbox($request->request->get('delete'))) {

            foreach ($this->linkService->getAllByUserAndCollection($user, $collection) as $link) {
                $this->linkStatisticService->deleteAllByLink($link);
            }

            $this->linkService->removeCollectionFromLinks($user, $collection);
            $this->linkCollectionService->delete($collection);

            $this->addFlash('success', 'Group has been deleted.');

            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $name = $this->validateAndReplaceSpace($request->request->get('iName'));

        if (!$name) {
            $this->addFlash('warning', 'Group name is required.');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $this->linkCollectionService->save($collection->setName(ucfirst($name)));

        $this->addFlash('success', 'Group name has been updated.');

        return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
    }
}
