<?php
  /**
   * Copyright 2016 SharedBooks
   *
   * Licensed under the Apache License, Version 2.0 (the "License");
   * you may not use this file except in compliance with the License.
   * You may obtain a copy of the License at
   *
   *  http://www.apache.org/licenses/LICENSE-2.0
   *
   * Unless required by applicable law or agreed to in writing, software
   * distributed under the License is distributed on an "AS IS" BASIS,
   * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   * See the License for the specific language governing permissions and
   * limitations under the License.
   */

  //Add support for utf-8 charset
  //header("content-type: text/html; charset=UTF-8");

  //Import the user model and dto
  use \Model\UserModel;
  use \Model\UserDTO;

  /**
   * The Welcome Controller.
   *
   * A basic Login and SignUp page
   *
   * @package  app
   * @extends  Controller
   */
  class Controller_Welcome extends Controller {
    /**
     * The basic welcome message
     * @access  public
     * @return  Response
     */
    public function action_index() {
      if(!$user = Session::get('userInfo')){
        $view = View::forge('welcome/index');
        $view->cities = UserModel::getCities();
        return $view;
      }

      Response::redirect('store','location');
    }

    public function post_registerUser() {
      $user = new UserDTO();
      $user->setEmail(Input::post('emailsignup'));
      $user->setName(Input::post('namesignup'));
      $user->setCity(Input::post('citysignup'));
      $user->setAddress(Input::post('addresssignup'));
      $password1 = Input::post('passwordsignup');
      $password2 = Input::post('passwordsignup_confirm');
      if(!($password1 == $password2)){
        echo '<script language="javascript">';
        echo 'alert("Sorry, passwords does not match")';
        echo '</script>';
        Response::redirect_back('/', 'refresh');
      }
      try{
        $result = UserModel::registerUser($user, $password1);
      }catch(Exception $e){
        echo '<script language="javascript">';
        echo 'alert("Sorry, email already exists")';
        echo '</script>';
        Response::redirect('/#toregister', 'refresh');
      }
      echo '<script>alert("Congratulations, you have a new account");</script>';
      Response::redirect('welcome', 'refresh');
    }

    public function post_checkUser() {
      if ($_POST['action'] == 'Err'){
        Response::redirect('pdf');
      } else {
        $email      = Input::post('emaillogin');
        $password   = Input::post('passwordlogin');
        $userResult = UserModel::getUser($email, $password);
        if ($userResult == null) {
          $view = View::forge('welcome/index');
          echo '<script language="javascript">';
          echo 'alert("Sorry, wrong user and/or password")';
          echo '</script>';
          Response::redirect('/#toregister', 'refresh');
        } else {
          Session::create();
          Session::set('userInfo', $userResult);
          Response::redirect('store', 'location');
        }
      }
    }

    /**public function action_index() {
      $view = View::forge('welcome/list');
      $booksDTO = BookModel::getAll();
      //print_r(self::test($booksDTO[0]));
      $books = array_map(function ($b){return $b->toArray();}, $booksDTO);
      $view->books = $books;
      return $view;
    }*/
















    /**
     * The 404 action for the application.
     * @access  public
     * @return  Response
     */
    public function action_404() {
      return Response::forge(Presenter::forge('welcome/404'), 404);
    }

    public function action_newbook() {
      return Response::forge(View::forge('welcome/newBook'));
    }

    /*public function action_index() {
    $user = UserModel::getUser('email2@mail.com', '321');
    $view = View::forge('welcome/list');
    $view->id = $user->getId();
    return $view;*/

    /**$cities = UserModel::getCities();
    foreach($cities as $city) {
    echo "City => " . $city['name'] . " | ";
    }*/

    /**
    $user = new UserDTO();
    $user->setEmail('email2@mail.com');
    $user->setName('Luis');
    $user->setCity('Medellín');
    $user->setAddress('Calle Falsa 123');
    $result = UserModel::registerUser($user, '123');
    echo 'exito ' . $result;*/

    //$result = UserModel::changePassword(3, '321');
    //echo 'exito ' . $result;

    /**
    $user = new UserDTO();
    $user->setId(2);
    $user->setCity('Bogotá');
    $user->setAddress('Calle Falsa 333');
    $result = UserModel::updateUser($user);
    echo 'exito ' . $result;*/
    //}
  }
?>