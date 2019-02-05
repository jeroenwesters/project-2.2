<?php
// Made by Jeroen, Jarco and Emiel - © 2019

require 'message.php';
require 'settings.php';


//
// userlogin('free', 'test');
// createAccount('free', 'password', 'password', false);

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
  $stmt = $PDO->prepare('SELECT username, password, admin, api_key, userid FROM users where username = ?;');
  $stmt->execute([$username]);
  //$result = $stmt->fetchAll();
  $result = $stmt->fetch();

  if(password_verify($password, $result['password'])){
      $msg->error = false;
      $msg->data = array('username' => $result['username'], 'admin' => $result['admin'], 'apikey' => $result['api_key'], 'userid' => $result['userid']);

      return $msg;
      // echo $msg->getJson() . '<br>';
      // var_dump($msg->getArray());
      // //echo "<br>password matches<br>";
  }else{
    $msg->message = 'Invalid username or password!';
    return $msg;
  }
}

function getApiKey($userid) {
    if($userid == 0) {
        return 'asf756saf5asf75a7s6f';
    }
    else {
      $PDO = getPDO();
      $stmt = $PDO->prepare('SELECT api_key FROM users WHERE userid = :userid');
      $stmt->bindValue(':userid', $userid);
      $stmt->execute();
      return $stmt->fetchAll();
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
        $key = base64_encode(md5(uniqid(rand(), true)));
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        if($hashedPassword){
          $PDO = getPDO();
          $stmt = $PDO->prepare('INSERT into users(username, password, admin, api_key) VALUES(:username, :password, :admin, :apikey)');
          $stmt->bindValue(':username', $username);
          $stmt->bindValue(':password', $hashedPassword);
          $stmt->bindValue(':admin', $admin);
          $stmt->bindValue(':apikey', $key);
          $stmt->execute();
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
  return $msg;
  // DEBUG
  // echo $msg->message;
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
  $stmt = $PDO->prepare('SELECT userid, username, password, admin, api_key  FROM users;');
  $stmt->execute();
  $result = $stmt->fetchAll();


  if($result){
      $msg->error = false;
      $msg->data = $result;

      return $msg;
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

  if ($password == 1) {
    $password = "Welcome123*";
    $password = password_hash($password, PASSWORD_BCRYPT);

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
  }
  else {
    $stmt = $PDO->prepare(" UPDATE users
                            SET username = ?, admin= ?
                            WHERE userid = ?");
    $stmt->execute([$username, $admin, $userid]);
  }

  $result = $stmt->rowCount();

  if($result){
    $msg->error = false;
    $msg->message = 'Updated user: ' . $username;
  }else{
    $msg->message = 'Failed updating data from ' . $username;
  }
  return $msg;
}

function deleteAccount($userid){
  $msg = new Message();

  $PDO = getPDO();
  $stmt = $PDO->prepare('DELETE from users WHERE userid = ?;');
  $stmt->execute([$userid]);
  //$result = $stmt->fetchAll();
  $result = $stmt->rowCount();

  if($result){
    $msg->error = false;
    $msg->message = 'Deleted user: ' . $userid . ' successfully.';
  }else{
    $msg->message = 'Failed deleting account from ';
  }
  return $msg;
}

function changePassword($userid, $oldPass, $newPass, $repeatNewPass){
  $minLength = 8;
  $msg = new Message();

  if($newPass == $repeatNewPass){
      if(strlen($newPass) >= $minLength){
          if(validateUserID($userid)){
            $key = base64_encode(md5(uniqid(rand(), true)));
            $hashedPassword = password_hash($newPass, PASSWORD_BCRYPT);
            $hashedOldPassword = password_hash($oldPass, PASSWORD_BCRYPT);

              if($hashedPassword){
                  $PDO = getPDO();
                  $stmt = $PDO->prepare('UPDATE user SET user_password = :password WHERE userid = :userid AND user_password = :oldpass');
                  $stmt->bindValue(':password', $hashedPassword);
                  $stmt->bindValue(':userid', $userid);
                  $stmt->bindValue(':oldpass', $hashedOldPassword);
                  $stmt->execute();
                  if($stmt->rowCount() > 0) {
                      $msg->error = false;
                      $msg->message = 'Succesfully changed password!';
                  }
                  else {
                      $msg->message = 'Current password incorrect.';
                  }
              }
              else{
                $msg->message = 'Error occured! Contact the supportdesk!';
              }
          }
          else{
            $msg->message = 'User does not exist.';
          }
      }
      else{
        $msg->message = 'The password should have atleast: ' . $minLength . ' characters!';
      }
  }
  else{
    $msg->message = "Passwords didn't match!";
  }
  return $msg;
  // DEBUG
  echo $msg->message;
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

  $stmt = $PDO->prepare('SELECT api_key FROM users WHERE api_key = ?;');
  $stmt->execute([$key]);
  $res = $stmt->fetch();
  if (!$res) {
     return false;
  } else {
    return true;
  }
}


?>
