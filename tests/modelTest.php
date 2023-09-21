<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class modelTest extends TestCase
{

    protected function setUp(): void {
        $db = new \Pejman\Database\Wrapper();
        $this->config = require(__dir__.'/../config.php');
        $db->connect( $this->config );

        $this->db = $db;
    }

    public function testFind() {
       $test = \App\Model\Test::sql("where id = ? ")->find([1])->index(0);
       $this->assertSame(1,$test->id);
    }

    public function testFindFirst() {
       $test = \App\Model\Test::sql("where id = ? ")->findFirst([1]);
       $this->assertSame(1,$test->id);
    }

    public function testGetter() {
       $test = \App\Model\Test::sql("where id = ? ")->findFirst([1]);
       $this->assertSame('getter 1',$test->testGetter);
       $this->assertSame('test 1',$test->test);
    }

    public function testFindIter() {
       $tests = \App\Model\Test::sql("where id = ? ")->find([1]);
       foreach( $tests as $test ) {
        if( $test->id == 1 )
            break;
       }

       $this->assertSame(1,$test->id);
    }

}