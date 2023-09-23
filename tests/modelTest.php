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
    }

    public function testFindIter() {
       $tests = \App\Model\Test::sql("where id = ? ")->find([1]);
       foreach( $tests as $test ) {
        if( $test->id == 1 )
            break;
       }

       $this->assertSame(1,$test->id);
    }

    public function testDelete() {
        $test2 = \App\Model\Test::sql("where id = 2")->findFirst();

        if( $test2->id )
            $test2->delete();

        $test2 = \App\Model\Test::sql("where id = 2")->findFirst();
        $this->assertSame(null,$test2->id);
    }

    public function testSave() {
        $test2 = \App\Model\Test::sql("where id = 2")->findFirst();

        if( ! @$test2->id ){

            $test2 = new \App\Model\Test;
            $test2->id = 2;
            $test2->test = "insert save test";
            $test2->save();
        }

        $test2 = \App\Model\Test::sql("where id = 2")->findFirst();
        $this->assertSame("insert save test",$test2->data->test);

    }

    public function testUpdateOnSave() {
        $test2 = \App\Model\Test::sql("where id = 2")->findFirst();

        if( @$test2->id ){
            $test2->test = "update save test";
            $test2->save();
        }

        $test2 = \App\Model\Test::sql("where id = 2")->findFirst();
        $this->assertSame("update save test",$test2->data->test);

    }

    public function testMulitSelect() {
        $test1 = \App\Model\Test::sql("where id = 1")->findFirst();
        $test2 = \App\Model\Test::sql("where id = 2")->findFirst();
        if( $test2->id ){
            $test2->test = "test2 on update";
            $test2->save();
        }        

        if( $test1->id ){
            $test1->test = "test1 on update";
            $test1->save();
        }

        $test1 = \App\Model\Test::sql("where id = 1")->findFirst();
        $test2 = \App\Model\Test::sql("where id = 2")->findFirst();
        $this->assertSame("test1 on update",$test1->data->test);     
        $this->assertSame("test2 on update",$test2->data->test);  
    }

    public function testPagination() {
        $tests = \App\Model\Test::sql("where 1")->paginate(5,1)->find();
        $this->assertSame(5, $tests->pagination->end );
    }

    public function testTest1() {
        $count = \App\Model\Test1::sql("")->count();
        if( $count == 0 ) {   
            $test1 = new \App\Model\Test1();
            $test1->title = 'test';
            $test1->testid = 1;
            $test1->save();
        }

        $test1 = \App\Model\Test1::sql("where id = 1")->findFirst();
        $this->assertSame(1, $test1->test->id );
    }

}