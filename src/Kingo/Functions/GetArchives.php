<?php

class Kingo_Functions_GetArchives extends Kingo_Functions {
  public function __invoke() {
    return array_merge(
      $this->parseArchives($this->DOM($this->kingo->GET(
        'xsxj/Stu_MyInfo_RPT.aspx'))->getElementsByTagName('table')->item(0)),
      $this->parseArchives($this->DOM($this->kingo->GET(
        'xscj/stu_djksbm_rpt.aspx'))->getElementsByTagName('table')->item(1)));
  }

  /**
   * @param DOMNode $table
   * @return array
   */
  protected function parseArchives($table) {
    $archives = array();

    if ($table) {
      foreach ($this->parseTable($table) as $row) {
        for ($i = 1; $i < count($row); $i += 2) {
          if ($row[$i]) {
            $archives[$row[$i - 1]] = $row[$i];
          }
        }
      }

      return $archives;
    } else {
      return array();
    }
  }
}
