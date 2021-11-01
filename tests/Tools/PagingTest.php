<?php

namespace Tests\Tools;

use App\Model\Manager\ContactManager;
use App\Tools\Paging;
use Tests\DatabaseTestCase;

class PagingTest extends DatabaseTestCase
{
    public function testPaginationWithFindAll()
    {
        $currentPage = 1;
        $contactManager = new ContactManager($this->pdo);
        $perPage = 30;
        $paging = new Paging($perPage, $contactManager->count(), $currentPage);
        $offset = $paging->getOffset();
        $contacts = $contactManager->findPaginated('id', $perPage, $offset);
        $previous = $paging->previousLink("/demo", []);
        $next = $paging->nextLink("/demo", []);

        $this->assertEquals(40, $paging->getNumberItems());
        $this->assertNull($previous);
        $this->assertStringContainsString("/demo?page=2", $next);
        $this->assertStringContainsString('action_link', $next);
        $this->assertEquals($offset, 1 *($currentPage-1));
        $this->assertTrue(!empty($contacts));
        $this->assertEquals(1, (int)$contacts[0]->getId());
    }

    public function testFluentMode()
    {
        $paging = new Paging();
        $offset = $paging->perPage(30)->total(40)->definePagination(1)->getOffset();
        $previous = $paging->previousLink("/demo", []);
        $next = $paging->nextLink("/demo", []);
        $this->assertNull($previous);
        $this->assertStringContainsString("/demo?page=2", $next);
        $this->assertEquals($offset, 1 * (1 - 1));
    }

    public function testFluentModeWithPDO()
    {
        $paging = new Paging();
        $offset = $paging->perPage(30)->countTotal('contacts', $this->pdo)->definePagination(1)->getOffset();
        $previous = $paging->previousLink("/demo", []);
        $next = $paging->nextLink("/demo", []);
        $this->assertNull($previous);
        $this->assertStringContainsString("/demo?page=2", $next);
        $this->assertEquals($offset, 1 * (1 - 1));
    }

    public function testFluentInvalid()
    {
        $this->expectException(\Exception::class);
        $paging = new Paging(5, 40);
        $offset = $paging->getOffset();
    }

    public function testFluentInvalidTotal()
    {
        $this->expectException(\Exception::class);
        $paging = new Paging();
        $offset = $paging->definePagination(1)->getOffset();
    }

    public function testInvalidDefinition()
    {
        $this->expectException(\Exception::class);
        $paging = new Paging();
        $offset = $paging->definePagination(1)->getOffset();
    }

    public function testPaginationWithSeveralPages()
    {
        $currentPage = 2;
        $contactManager = new ContactManager($this->pdo);
        $perPage = 5;
        $paging = new Paging($perPage, $contactManager->count(), $currentPage);
        $contacts = $contactManager->findPaginated('id', $perPage, $paging->getOffset());
        $previous = $paging->previousLink("/demo", []);
        $next = $paging->nextLink("/demo", []);
        $pages = $paging->getPages("/demo", []);

        $this->assertStringContainsString("/demo?page=1", $previous);
        $this->assertStringContainsString("action_link", $next);
        $this->assertEquals(6, count($pages));
        $this->assertStringContainsString("action_link", $pages[0]);
        $this->assertStringContainsString("/demo?page=1", $pages[0]);
        $this->assertStringContainsString("/demo?page=8", $pages[5]);
        $this->assertStringContainsString("/demo?page=5", $pages[4]);
        $this->assertEquals(6, (int)$contacts[0]->getId());
    }

    public function testPaginationWithGetParams()
    {
        $paging = new Paging(5, 40, 2);
        $params = ['order' => 'name'];
        $expectedGets = "order=name&page=";
        $previous = $paging->previousLink("/demo", $params);
        $next = $paging->nextLink("/demo", $params);
        $pages = $paging->getPages("/demo", $params);
        $this->assertStringContainsString("/demo?{$expectedGets}1", $previous);
        $this->assertStringContainsString("/demo?{$expectedGets}3", $next);
        $this->assertStringContainsString("/demo?{$expectedGets}1", $pages[0]);
    }

    public function testPaginationWithInvalidCurrentPage()
    {
        $paging = new Paging(30, 40, 56565656544);
        $previous = $paging->previousLink("/demo", []);
        $next = $paging->nextLink("/demo", []);
        $error = $paging->getError();
        $this->assertTrue($error !== null);
        $this->assertEquals("Numéro de page non valide", $error);
        $this->assertNull($next);
        $this->assertStringContainsString("/demo?page=1", $previous);
    }

    public function testPartialFluent()
    {
        $paging = new Paging(15);
        $offset = $paging->total(40)->definePagination(1)->getOffset();
        $previous = $paging->previousLink("/demo", []);
        $next = $paging->nextLink("/demo", []);
        $this->assertNull($previous);
        $this->assertStringContainsString("/demo?page=2", $next);
        $this->assertEquals($offset, 1 * (1 - 1));
    }
}