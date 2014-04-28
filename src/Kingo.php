<?php

class Kingo {
  /**
   * @param string $url
   * @param string $xnxq semester
   * @param string $sessionId
   */
  public function __construct($url, $xnxq, $sessionId=null) {
    $this->xnxq = $xnxq;
    $this->session = new Requests_Session($url);
    $this->session->headers['Cookie'] = 'ASP.NET_SessionId=' .
      ($sessionId ? $sessionId : $this->generateSessionId());
  }

  /**
   * @param string $sid
   * @param string $pwd
   * @return bool
   */
  public function login($sid, $pwd) {
    $responseText = $this->POST(
      '_data/index_login_tfc.aspx',
      array(
        'Sel_Type' => 'STU',
        'UserID'   => $sid,
        'PassWord' => $pwd,
      )
    );

    if (strpos($responseText, '正在加载权限数据')) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * @param string $method
   * @param string $url
   * @param array $data
   * @param string $referer
   * @return string
   */
  protected function request($method, $url, $data=array(), $referer='') {
    $referer = $referer ?
      $this->session->url . $referer : $this->session->url . $url;

    return iconv('gb18030', 'utf-8//ignore',
      $this->session->request($url, array(
        'Referer' => $referer), $data, $method)->body);
  }

  /**
   * @param string $url
   * @param array $data
   * @param string $referer
   * @return string
   */
  public function GET($url, $data=array(), $referer='') {
    return $this->request('GET', $url, $data, $referer);
  }

  /**
   * @param string $url
   * @param array $data
   * @param string $referer
   * @return string
   */
  public function POST($url, $data=array(), $referer='') {
    return $this->request('POST', $url, $data, $referer);
  }

  /**
   * @return string
   */
  protected function generateSessionId() {
    return substr(
      str_shuffle('012345abcdefghijklmnopqrstuvwxyz'), 0, 24);
  }


  /**
   * methods dynamic binding
   */
  public function __call($method, $args) {
    $functionName = 'Kingo_Functions_' . ucwords($method);
    $function = new $functionName($this);

    return $function($args);
  }
}
