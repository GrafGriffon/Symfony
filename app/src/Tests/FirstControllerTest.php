<?php

namespace App\Tests;

use phpDocumentor\Reflection\Types\True_;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function PHPUnit\Framework\assertEmpty;

class FirstControllerTest extends WebTestCase
{

    /**
     * @var KernelBrowser
     */
    private $client;
    private $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->enableProfiler();
        $kernel = $this->client->getKernel([
            'environment' => 'dev',
            'debug' => false,
        ]);
        $this->em = $kernel->getContainer()
            ->get('doctrine.orm.default_entity_manager');
        $this->em->getConnection()->beginTransaction();
        $this->em->getConnection()->setAutoCommit(false);
    }

    public function testIndex()
    {
        $token = $this->createUser('ROLE_ADMIN');
        $this->checkCode($token, '/api/newcategory', ['title' => 'newName', 'parent' => 37], "POST");
        $this->checkCode($token, '/api/udpate-category/55', ['title' => 'newName', 'parent' => 44], "PUT");
//        $this->checkCode($token, '/api/delete-category/49', [], "DELETE");
        $this->checkNull($token, '/api/products');
        $this->checkNull($token, '/api/categories?page=1');
    }

    private function checkNull($token, $url)
    {
        $result = $this->getTestResult($token, $url, 'GET');
        foreach ($result as $elements) {
            foreach ($elements as $element) {
                $this->assertNotNull($element);
            }
        }
    }

    private function getTestResult($token, $url, $method): array
    {
        $this->client->request($method, $url
            , ['title' => 'newName', 'parent' => 38], array()
            , array('HTTP_AUTHORIZATION' => 'Bearer ' . $token, 'CONTENT_TYPE' => 'application/json')
        );
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        return json_decode($this->client->getResponse()->getContent(), TRUE);
    }

    private function checkCode($token, $url, $params, $method)
    {
        $this->client->request($method, $url
            , $params, array()
            , array('HTTP_AUTHORIZATION' => 'Bearer ' . $token, 'CONTENT_TYPE' => 'application/json')
        );
        $this->assertEquals(200, json_decode($this->client->getResponse()->getContent(), TRUE)['status']);
    }

    private function createUser($role)
    {
        $data = array('username' => 'davon12@hilpert.com', 'roles' => [$role]);

        return $this->client->getContainer()
            ->get('lexik_jwt_authentication.encoder')
            ->encode($data);
    }
}