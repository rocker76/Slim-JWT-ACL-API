<?php

namespace App\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \PDOException;
use App\Model\User;

class UserController {

	private $userService;
	private $jwt;
	private $return;
    
    public function __construct($userService) {
		$this->userService = $userService;
		$this->return = ['error' => false, 'message' => '', 'data' => []];
	}
	
	public function list(Request $request, Response $response, $args) {

		$data = $this->userService->getAll();

		$this->return['data'] = $data;
        
        return $response->withJson($this->return);
	}
	
	public function create(Request $request, Response $response, $args) {
		$post = $request->getParsedBody();
		
		$user = new User();
		$user->setName($post['name']);
		$user->setEmail($post['email']);
		$user->setPassword(md5($post['senha']));

		$data = $this->userService->insert($user);

		if (!$data) {
			$this->return['error'] = true;
			$this->return['message'] = 'User was not created!';
		} else {			
			$this->return['message'] = 'User created!';
		}
        
        return $response->withJson($this->return);
	}

}
