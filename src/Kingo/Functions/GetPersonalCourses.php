<?php

class Kingo_Functions_GetPersonalCourses extends Kingo_Functions {
  public function __invoke() {
    $tables = $this->DOM(
      $this->kingo->POST('znpk/Pri_StuSel_rpt.aspx', array(
        'Sel_XNXQ' => $this->kingo->xnxq,
        'rad'      => 1,
        'px'       => 0)))->getElementsByTagName('table');

    if ($tables->item(0)->getElementsByTagName('td')->item(0)->textContent
        == '讲授/上机') {

      return $this->parseCourses(
        array_slice($this->parseTable($tables->item(1)), 2, -1),
        array(1, 2, 3, 8, 9, 10, 11, 12));
    } else {
      return array();
    }
  }
}
