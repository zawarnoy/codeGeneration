<?php

namespace App\Controller;

use App\Entity\Code;
use App\Exception\CodeNotFoundException;
use App\Exception\MethodNotAllowedApiException;
use App\Service\CodeGenerator;
use App\Service\XLSGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class CodeController extends AbstractController
{
    /**
     * @Route("/generate", name="generate_code")
     * @param Request $request
     * @param CodeGenerator $codeGenerator
     * @param XLSGenerator $XLSGenerator
     * @return BinaryFileResponse|\Symfony\Component\HttpFoundation\JsonResponse
     * @throws MethodNotAllowedApiException
     */
    public function generateAction(Request $request, CodeGenerator $codeGenerator, XLSGenerator $XLSGenerator)
    {
        if(!$request->isMethod("POST")) {
            throw new MethodNotAllowedApiException();
        }

        $nb = $request->request->get('nb', 1);
        $export = $request->request->get('export', '');
        $codes = $codeGenerator->generateFewEntities($nb);

        if ($export === 'xls') {
            $filename = $XLSGenerator->generate($codes);
            return (new BinaryFileResponse($filename))->deleteFileAfterSend();
        }

        return $this->json($codes);
    }

    /**
     * @Route("/{code}")
     * @param Request $request
     * @param $code
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws CodeNotFoundException
     * @throws InternalErrorException
     * @throws MethodNotAllowedApiException
     */
    public function codeAction(Request $request, $code)
    {
        if(!$request->isMethod("GET")) {
            throw new MethodNotAllowedApiException();
        }

        try {
            $repository = $this->getDoctrine()->getRepository(Code::class);
            $codeEntity = $repository->findOneBy(['code' => $code]);
        } catch (\Exception $e) {
            throw new InternalErrorException();
        }

        if (empty($codeEntity)) {
            throw new CodeNotFoundException();
        }

        return $this->json($codeEntity);
    }
}
