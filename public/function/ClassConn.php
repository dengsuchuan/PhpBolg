
                                      <?php  
                                    header("Content-type: text/html; charset=utf-8"); 
                                    class ClassConn{
                                        public function conn(){
                                             $dbms="mysql";
                                             $host="localhost";
                                             $dbuser="root";
                                             $dbpaw="www.geekln.cn";
                                             $dbname="message"; 
                                             $dsn="$dbms:host=$host;dbname=$dbname";
                                             
                                             try{
                                                    $pdo = new PDO($dsn,$dbuser,$dbpaw);
                                                    //echo "数据库连接成功";
                                                    return $pdo;
                                                }catch (PDOException $e){
                                                    die('发生了不可预料的错误:<br>'.$e->getMessage().'<br>');
                                             }
                                        }  
                                    }   
                                    //$conn  = new ClassConn();
                                    //$conn->conn();
                                    ?>