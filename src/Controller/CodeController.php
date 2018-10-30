<?php

namespace App\Controller;

use App\Entity\Code;
use App\Service\CodeGenerator;
use App\Service\XLSGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class CodeController extends AbstractController
{
    /**
     * @Route("/generate", name="generate_code", methods={"POST"})
     * @param Request $request
     * @param CodeGenerator $codeGenerator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function generateAction(Request $request, CodeGenerator $codeGenerator, XLSGenerator $XLSGenerator)
    {
        $nb = $request->request->get('nb', 1);
        $export = $request->request->get('export', '');
        $codes = $codeGenerator->generateFewEntities($nb);

        if ($export === 'xls') {
            $XLSGenerator->generate($codes);
        }

        return $this->json($codes);
    }

    /**
     * @Route("/{code}")
     * @param $code
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws InternalErrorException
     */
    public function codeAction($code)
    {
        try {
            $repository = $this->getDoctrine()->getRepository(Code::class);
            $codeEntity = $repository->findOneBy(['code' => $code]);

            if (empty($codeEntity)) {
                throw new NotFoundHttpException("Code not found");
            }

            return $this->json(
                $codeEntity
            );
        } catch (\Exception $e) {
            throw new InternalErrorException();
        }
    }
}
