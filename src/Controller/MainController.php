<?php

namespace App\Controller;

use App\Entity\Logiciel;
use App\Entity\Version;
use App\Repository\LogicielRepository;
use App\Repository\VersionRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;



class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [

        ]);
    }

    public static function cmp_versions($v1, $v2) {
        $values1 = explode(".", $v1->getNumero());
        $values2 = explode(".", $v2->getNumero());

        $c1 = count($values1);
        $c2 = count($values2);
        $m = min($c1, $c2);

        for ($i = 0; $i < $m; $i++)
        {
            $n1 = $values1[$i];
            $n2 = $values2[$i];

            $i1 = (int)$n1;
            $i2 = (int)$n2;

            if ($i1 < $i2) {
                return 1;
            }
            else if ($i1 > $i2) {
                return -1;
            }
        }

        if ($c1 == $c2) {
            return 0;
        }

        return $c1 < $c2 ? 1 : -1;
    }

    #[Route('/download', name: 'download')]
    public function dowload(
        EntityManagerInterface $entityManager,
        SessionInterface $session,
    ): Response
    {


        /** @var VersionRepository $versionRepository */
        $versionRepository = $entityManager->getRepository(Version::class);
        $versions = $versionRepository->findAll();

        $ok = usort($versions, array('self', "cmp_versions"));

            foreach ($versions as $version) {
                $deleteToken = uniqid();
                $session->set($version->getId(), $deleteToken);
            }

        return $this->render('main/download.html.twig', [

            'versions' => $versions,

        ]);
    }

    #[Route('/support', name: 'support')]
    public function support(): Response
    {
        return $this->render('main/support.html.twig', [

        ]);
    }
}
