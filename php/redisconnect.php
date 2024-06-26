<?php
require '../vendor/autoload.php'; 
class RedisConnect 
{
    private $redis;
    public function __construct() 
    {
        try 
        {
            $this->redis = new Predis\Client(array(
                'host' => ('redis-17411.c16.us-east-1-3.ec2.cloud.redislabs.com'),
                'port' => (17411),
                'password' => ('ZB7bivbf2DQXsIBmVucdDpcerEhHUEtU'),
            ));
        } 
        catch (Predis\Connection\ConnectionException $e) 
        {
            error_log("Redis Connection Error: " . $e->getMessage());
            die("Redis Connection Error");
        } 
        catch (Predis\Response\ServerException $e) 
        {
            error_log("Redis Server Error: " . $e->getMessage());
            die("Redis Server Error");
        } 
        catch (Exception $e) {
            error_log("General Error: " . $e->getMessage());
            die("General Error");
        }
    }
    public function saveProfileData($student_email, $row) 
    {
        try 
        {
            if ($this->redis->hexists('ProfileData', $student_email)) 
            {
              return; 
            } 
            else 
            {
              $this->redis->hset('ProfileData', $student_email, json_encode($row));
            }
        }
        catch (Exception $e) 
        {
            error_log("Redis Update Error: " . $e->getMessage());
            die("Redis Update Error");
        }
    }
    public function updateProfileData($student_email, $row) 
    {
        try 
        {
            $this->redis->hset('ProfileData', $student_email, json_encode($row));
        }
        catch (Exception $e) 
        {
            error_log("Redis Update Error: " . $e->getMessage());
            die("Redis Update Error");
        }
    }
    public function getProfileData($student_email) {
        try 
        {
          $data = $this->redis->hget('ProfileData', $student_email);
          if ($data) 
          {
            return json_decode($data, true); 
          } 
          else 
          {
            return null;
          }
        } 
        catch (Exception $e) 
        {
          error_log("Redis Get Error: " . $e->getMessage());
          die("Redis Get Error");
        }
      }
}
?>
