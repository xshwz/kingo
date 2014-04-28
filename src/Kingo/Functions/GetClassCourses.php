<?php

class Kingo_Functions_GetClassCourses extends Kingo_Functions {
  protected function getClassCode($className) {
    $classInfo = $this->kingo->GET('ZNPK/Private/List_XZBJ.aspx', array(
      'xnxq' => $this->kingo->xnxq,
      'xzbj' => iconv('utf-8', 'gb18030', $className),
    ));

    preg_match_all('/option value=(\d+)>(.*?)</', $classInfo, $matches);
    return isset($matches[0][0]) ? array(
      'code' => $matches[1],
      'name' => $matches[2]) : null;
  }

  public function __invoke($args) {
    if ($classInfo = $this->getClassCode($args[0])) { // don't panic
      $tables = $this->DOM($this->kingo->POST('ZNPK/KBFB_ClassSel_rpt.aspx',
        array(
          'Sel_XNXQ' => $this->kingo->xnxq,
          'Sel_XZBJ' => $classInfo['code'][0],
          'type'     => 2,
          'chkrxkc'  => 1,
        ), 'ZNPK/KBFB_ClassSel.aspx'))->getElementsByTagName('table');

      if ($tables && $tables->length > 3) {
        $tds = $tables->item(3)->getElementsByTagName('td');

        for ($i = 10; $i < $tds->length - 1; $i += 10) {
          for ($j = 0; $j < 10; $j++) {
            if ($tds->item($i + $j)->textContent) {
              $course[$j] = $tds->item($i + $j)->textContent;
            }
          }

          $courses[] = $course;
        }

        return $this->parseCourses($courses, array(0, 1, 2, 3, 4, 7, 8, 9));
      } else {
        return array();
      }
    } else {
      return array();
    }
  }
}
