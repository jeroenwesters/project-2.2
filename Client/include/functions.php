<?php
require 'message.php';
require 'settings.php';


//
// userlogin('free', 'test');
// userlogin('free', 'admin');

function userlogin($username, $password){
  $msg = new Message();

  if($username == ""){
    $msg->message = 'No username given!';
    return $msg;
  }else if($password == ""){
      $msg->message = 'No password given!';
      return $msg;
  }

  $PDO = getPDO();
  $stmt = $PDO->prepare('SELECT username, password, admin FROM users where username = ?;');
  $stmt->execute([$username]);
  //$result = $stmt->fetchAll();
  $result = $stmt->fetch();

  if(password_verify($password, $result['password'])){
      $msg->error = false;
      $msg->data = array('username' => $result['username'], 'admin' => $result['admin']);

      return $msg;
      // echo $msg->getJson() . '<br>';
      // var_dump($msg->getArray());
      // //echo "<br>password matches<br>";
  }else{
    $msg->message = 'Invalid username or password!';
    return $msg;
  }
}
//
// echo "<br>";
// // By uncommenting this, its easy to make accounts locally
// createAccount('admina', 'adminaaa', 'adminaaa', 1);
// echo "<br>";

function createAccount($username, $password, $confirmpassword, $admin){
  $minLength = 8;
  $msg = new Message();

  if($password == $confirmpassword){
    if(strlen($password) >= $minLength){
      if(validateUsername($username)){
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        if($hashedPassword){
          $PDO = getPDO();
          $stmt = $PDO->prepare('INSERT into users(username, password, admin) VALUES(?, ?, ?);');
          $stmt->execute([$username, $hashedPassword, $admin]);
          $msg->error = false;
          $msg->message = 'Acount has been made!';

        }else{
          $msg->message = 'Error occured! Contact the supportdesk!';
        }
      }else{
        $msg->message = 'Username already choosen.';
      }
    }else{
      $msg->message = 'The password should have atleast: ' . $minLength . ' characters!';
    }
  }else{
    $msg->message = "Passwords didn't match!";
  }

  // DEBUG
  echo $msg->message;
}

function validateUsername($username){
  $PDO = getPDO();
  $stmt = $PDO->prepare('SELECT username FROM users where username = ?;');
  $stmt->execute([$username]);
  //$result = $stmt->fetchAll();
  $result = $stmt->fetch();

  // If no result, username is avaible
  if(!$result){
    return true;
  }else{
    return false;
  }
}


function getAccounts(){
  $msg = new Message();

  $PDO = getPDO();
  $stmt = $PDO->prepare('SELECT * FROM users;');
  $stmt->execute();
  $result = $stmt->fetchAll();


  if($result){
      $msg->error = false;
      $msg->data = $result;

      return $msg;
      // echo $msg->getJson() . '<br>';
      // var_dump($msg->getArray());
      // //echo "<br>password matches<br>";
  }else{
    $msg->message = 'No users found!';
    return $msg;
  }
}

function getAccountDetails($userid){
  $msg = new Message();

  $PDO = getPDO();
  $stmt = $PDO->prepare('SELECT * FROM users WHERE userid = ?;');
  $stmt->execute([$userid]);
  //$result = $stmt->fetchAll();
  $result = $stmt->fetchAll();

  if($result){
      $msg->error = false;
      $msg->data = $result;

      return $msg;
    }
}

function updateAccount($userid, $username, $password, $admin){
  $msg = new Message();

  $PDO = getPDO();
  $data = [
    'userid' => $userid,
    'username' => $username,
    'password' => $password,
    'admin' => $admin,
  ];

  $stmt = $PDO->prepare(" UPDATE users
                          SET username =:username, password =:password, admin=:admin
                          WHERE userid =:userid;");
  $stmt->execute($data);

  $result = $stmt->rowCount();

  if($result){
    $msg->error = false;
    $msg->message = 'Updated user: ' . $username;
  }else{
    $msg->message = 'Failed!'. $result;
  }

  return $msg;

}






//
//
//
//
// $key = '!V@TADR#@#FHT#SRGDSC@$';
// $key2 = '?V@TADR#@#FHT#SRGDSC@$';
//
// echo '<br> ApiKey ' . $key . ' is: '. testKey($key);
//
//
// echo '<br> ApiKey ' . $key2 . ' is: ' . testKey($key2);

function testKey($key){
  if(validateAPI($key)){
    return 'Valid';
  }else{
    return 'Invalid';
  }
}

// Validates the API key (USED BY THE API)
function validateAPI($key){
  $PDO = getPDO();

  $stmt = $PDO->prepare('SELECT apikey FROM apikeys WHERE apikey = ?;');
  $stmt->execute([$key]);
  $res = $stmt->fetch();
  if (!$res) {
     return false;
  } else {
    return true;
  }
}


?>
