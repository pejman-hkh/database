<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class dbTest extends TestCase
{

    protected function setUp(): void {
        $db = new \Pejman\Database\Wrapper();
        $config = require(__dir__.'/../config.php');
        $this->config = $config;
        $db->connect( $this->config );

        $this->db = $db;
    }

    public function testDisconnect() {
        $db = new \Pejman\Database\Wrapper();
        $db->connect( $this->config );
        $db->__destruct();

        $this->assertSame( null, $db->getConnection() );
    }

    public function testSelectQuery() {
        $query = $this->db->query("SELECT 'Some Test' as test ")->find()[0];
        $this->assertSame( 'Some Test', $query->test );
    }


    public function testCreateTable() {
        try {

            $query = $this->db->query("SHOW TABLES LIKE 'test'")->find()[0];
            $this->assertSame( null, $query );

            $this->db->query("CREATE TABLE `test` (
            `id` int NOT NULL,
            `test` varchar(500) COLLATE utf8mb3_persian_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_persian_ci;

            ALTER TABLE `test`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `test`
              MODIFY `id` int NOT NULL AUTO_INCREMENT;
            COMMIT;");

            $query = $this->db->query("SHOW TABLES LIKE 'test'")->find()[0];
            $this->assertSame( 'test', $query->{'Tables_in_blog (test)'} );
        } catch( Exception $e ) {
            $query = $this->db->query("SHOW TABLES LIKE 'test'")->find()[0];
            $this->assertSame( 'test', $query->{'Tables_in_blog (test)'} );
        }
    }

    public function testLastInsertId() {

        $this->db->query("insert into `test`(`test`) values(?)", ['test']);
        $id = $this->db->lastInsertId();

        $last = $this->db->query("select * from test order by id desc")->find()[0];

        $this->assertSame( (int)$id, (int)$last->id );
        
    }

}