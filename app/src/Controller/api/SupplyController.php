<?php

namespace App\Controller\api;

use App\Entity\Products;
use App\Handler\AddCountHistoryHandler;
use App\Handler\TableHandler;
use App\Validation\SupplyValidator;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @Route("/api")
 */
class SupplyController extends AbstractController
{

    /**
     * @Route("/download/{file}", methods={"GET"})
     */
    public function downloadProductsApi(ProductsRepository $repository, string $file)
    {
        $response = new StreamedResponse();
        $response->setCallback(function () use ($repository) {
            $products = $repository->getListProduct($this->getUser())->getResult();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', "name");
            $sheet->setCellValue('B1', "category");
            $sheet->setCellValue('C1', "count");
            $sheet->setCellValue('D1', "price");
            $counter = 2;
            foreach ($products as $product) {
                $sheet->setCellValue('A' . $counter, $product->getName());
                $sheet->setCellValue('B' . $counter, $product->getCategory()->getTitle());
                $sheet->setCellValue('C' . $counter, $product->getStatusCount());
                $sheet->setCellValue('D' . $counter, $product->getCurrPrice());
                $counter++;
            }
            $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
            $writer->save("php://output");
        });
        $contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file . ".xlsx");
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', $contentDisposition);
        return $response;
//        }
//        } else {
//            return new JsonResponse(["result" => "File does not exist"]);
//        }
    }

//    /**
//     * @Route("/supply/download", methods={"GET"})
//     */
//    public function downloadProducts(ProductsRepository $repository, TableHandler $handler)
//    {
//        $fileName = (Uuid::uuid4())->toString();
//        $handler->createTable($repository->getListProduct($this->getUser())->getResult(), $fileName);
//        $data['link'] = "http://localhost:8081/api/download/" . $fileName;
//        return new JsonResponse($data);
//    }

    /**
     * @Route("/supply", methods={"GET"})
     */
    public function getProducts(Request $request, ProductsRepository $repository, PaginatorInterface $paginator)
    {
        $products = $paginator->paginate(
            $repository->getListProduct($this->getUser()),
            $request->query->getInt('page', 1),
            10
        );
        $data = array();
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'category' => $product->getCategory()->getTitle()
            ];
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/supply/{product}", methods={"PUT"})
     */
    public function addProducts(
        Request                $request,
        EntityManagerInterface $entityManager,
        Products               $product,
        AddCountHistoryHandler $handler
    )
    {
        $this->denyAccessUnlessGranted('supply_view', $product);
        try {
            $oldCount = $product->getStatusCount();
            $handler->addCountSupply($product, $request->get('count'));
            $request = $this->transformJsonBody($request);
            $errors = (new SupplyValidator())->validate($request->request->all());
            if (!empty($errors)) {
                throw new ValidatorException('Введённые данные некорректны: ' . implode('; ', $errors));
            }
            $product->setStatusCount($request->get('count') + $oldCount);
            $entityManager->flush();
            $data = [
                'status' => 200,
                'errors' => "Post updated successfully",
            ];
            return new JsonResponse($data);

        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return new JsonResponse($data, 422);
        }
    }

    protected function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

    /**
     * @Route("/supply", methods={"POST"})
     */
    public function saveFile(Request $request, TableHandler $handler)
    {
        $handler->updateTable($request->files->get('file'),$this->getUser());
        return new JsonResponse(["res" => "success"]);
    }
}